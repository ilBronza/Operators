<?php

namespace IlBronza\Operators\Models;

use Carbon\Carbon;
use DB;
use IlBronza\AccountManager\Models\User;
use IlBronza\AccountManager\Models\Userdata;
use IlBronza\Addresses\Models\Address;
use IlBronza\Category\Models\Category;
use IlBronza\Category\Traits\InteractsWithCategoryTrait;
use IlBronza\Clients\Models\Client;
use IlBronza\Clients\Models\Traits\InteractsWithDestinationTrait;
use IlBronza\Contacts\Models\Traits\InteractsWithContact;
use IlBronza\CRUD\Models\BaseModel;
use IlBronza\CRUD\Models\Casts\ExtraField;
use IlBronza\CRUD\Models\Casts\ExtraFieldDate;
use IlBronza\CRUD\Traits\CRUDSluggableTrait;
use IlBronza\CRUD\Traits\IlBronzaPackages\CRUDLogoTrait;
use IlBronza\CRUD\Traits\Model\CRUDModelExtraFieldsTrait;
use IlBronza\CRUD\Traits\Model\CRUDParentingTrait;
use IlBronza\CRUD\Traits\Model\CRUDUseUuidTrait;
use IlBronza\CRUD\Traits\Model\PackagedModelsTrait;
use IlBronza\Operators\Models\Traits\OperatorWorkingDaysBonusCalculatorTrait;
use IlBronza\Products\Models\Interfaces\SupplierInterface;
use IlBronza\Products\Models\Traits\Sellable\InteractsWithSupplierTrait;
use Illuminate\Support\Facades\Log;

use function cache;
use function dd;

class Operator extends BaseModel implements SupplierInterface
{
	use InteractsWithCategoryTrait;
	use InteractsWithContact;
	use PackagedModelsTrait;
	use CRUDUseUuidTrait;
	use CRUDSluggableTrait;
	use CRUDParentingTrait;
	use InteractsWithSupplierTrait;
	use InteractsWithDestinationTrait;
	use CRUDLogoTrait;
	use CRUDModelExtraFieldsTrait;

	use OperatorWorkingDaysBonusCalculatorTrait;

	static $packageConfigPrefix = 'operators';
	static $modelConfigPrefix = 'operator';
	public $deletingRelationships = [];
	protected $with = ['user'];
	protected $keyType = 'string';

	protected $casts = [
		'first_name' => ExtraField::class . ':userdata',
		'surname' => ExtraField::class . ':userdata',
		'fiscal_code' => ExtraField::class . ':userdata',

		'street' => ExtraField::class . ':address',
		'number' => ExtraField::class . ':address',
		'zip' => ExtraField::class . ':address',
		'town' => ExtraField::class . ':address',
		'city' => ExtraField::class . ':address',
		'province' => ExtraField::class . ':address',
		'region' => ExtraField::class . ':address',
		'state' => ExtraField::class . ':address',

		'holidays_reset_date' => ExtraFieldDate::class . ':validClientOperator',
		'flexibility_reset_date' => ExtraFieldDate::class . ':validClientOperator',
		'rol_reset_date' => ExtraFieldDate::class . ':validClientOperator',

		'employment_id' => ExtraField::class . ':validClientOperator',
		'unilav' => ExtraField::class . ':validClientOperator',
		'social_security_institution' => ExtraField::class . ':validClientOperator',
		'started_at' => ExtraFieldDate::class . ':validClientOperator',
		'ended_at' => ExtraFieldDate::class . ':validClientOperator',
		//		'street' => ExtraField::class . ':address',
	];

	static function getPossibleWorkingDaysValuesArray() : array
	{
		return WorkingDay::gpc()::getWorkingDaySelectArray();
	}

	public function providevalidClientOperatorModelForExtraFields() : ?ClientOperator
	{
		if ($this->validClientOperator)
			return $this->validClientOperator;

		if ($possible = $this->clientOperators()->orderBy(DB::raw('ended_at, ISNULL(ended_at)'), 'DESC')->first())
		{
			$this->setRelation('validClientOperator', $possible);

			return $this->validClientOperator;
		}

		$clientOperator = ClientOperator::getProjectClassName()::make();

		$clientOperator->client_id = Client::getProjectClassName()::getOwnerCompany()->getKey();

		$this->clientOperators()->save($clientOperator);
		$this->setRelation('validClientOperator', $clientOperator);

		return $this->validClientOperator;
	}

	public function clientOperators()
	{
		return $this->hasMany(ClientOperator::getProjectClassName());
	}

	public function scopeByEmployments($query, array $employmentIds)
	{
		$query->whereHas('clientOperators', function ($query) use ($employmentIds)
		{
			$query->whereIn('employment_id', $employmentIds);
		});
	}

	public function scopeByUsernames($query, array $usernames)
	{
		$query->whereHas('user', function ($query) use ($usernames)
		{
			$query->whereIn('name', $usernames);
		});
	}

	public function scopeByClient($query, Client|string $clientId)
	{
		if ($clientId instanceof Client)
			$clientId = $clientId->getKey();

		$query->whereHas('clientOperators', function ($query) use ($clientId)
		{
			$query->where('client_id', $clientId);
		});
	}

	public function scopeValid($query)
	{
		$query->whereHas('clientOperators', function ($query)
		{
			$query->whereDate('ended_at', '<=', Carbon::now())->orWhereNull('ended_at');
		});
	}

	public function getFirstName() : ?string
	{
		if ($this->relationLoaded('userdata'))
			return $this->first_name;

		if ($this->relationLoaded('user'))
			return $this->getUser()->getFirstName();

		return $this->first_name;
	}

	public function getUser()
	{
		return $this->user;
	}

	public function getSecondName() : ?string
	{
		return $this->getSurname();
	}

	public function getSurname() : ?string
	{
		if ($this->relationLoaded('userdata'))
			return $this->surname;

		if ($this->relationLoaded('user'))
			return $this->getUser()->getSurname();

		return $this->surname;
	}

	public function scopeByValidEmployments($query, array $employmentIds)
	{
		$query->byEmployments($employmentIds)->valid();
	}

	public function scopeActive($query)
	{
		$query->whereNull('active')->orWhere('active', true);
	}

	public function validClientOperator()
	{
		return $this->hasOne(ClientOperator::getProjectClassName())->where('client_id', Client::getProjectClassName()::getOwnerCompany()->getKey())->ofMany([
			'started_at' => 'max',
		]);
	}

	public function provideAddressModelForExtraFields() : Address
	{
		if ($this->address)
			return $this->address;

		return $this->getUser()->createDefaultAddress();
	}

	public function getAddress() : ?Address
	{
		return $this->address;
	}

	public function provideUserdataModelForExtraFields() : Userdata
	{
		if ($this->userdata)
			return $this->userdata;

		return $this->getUser()->createUserdata();
	}

	public function getExtraFieldsClass() : ?string
	{
		return null;
	}

	public function getMorphClass()
	{
		return 'Operator';
	}

	public function getCategoryModel() : string
	{
		return Category::getProjectClassName();
	}

	public function getCategoriesCollection() : ?string
	{
		return null;
	}

	public function addresses()
	{
		return $this->hasMany(
			Address::getProjectClassName(), 'addressable_id', 'user_id'
		)->where('addressable_type', 'User');
	}

	public function address()
	{
		return $this->hasOne(
			Address::getProjectClassName(), 'addressable_id', 'user_id'
		)->where('addressable_type', 'User')->where('type', 'default');
	}

	public function getValidClientOperator()
	{
		return $this->validClientOperator;
	}

	public function getNameAttribute() : ?string
	{
		return $this->getName();
	}

	public function getName() : ?string
	{
		return cache()->remember(
			$this->cacheKey('getName'), 3600 * 24, function ()
		{
			return $this->getUser()?->getFullName();
		}
		);
	}

	static function getSelfPossibleList() : array
	{
		return cache()->remember(
			static::staticCacheKey('getSelfPossibleList'), 3600, function ()
		{
			$elements = static::with('user.userdata')->get();

			$result = [];

			foreach ($elements as $operator)
				$result[$operator->getKey()] = $operator->getName();

			asort($result);

			return $result;
		}
		);
	}

	public function getSignatureName() : string
	{
		return cache()->remember(
			$this->cacheKey('getSignatureName'), 3600 * 24, function ()
		{
			return $this->getUser()?->getSignatureFullName();
		}
		);
	}

	public function operatorContracttypes()
	{
		return $this->hasMany(OperatorContracttype::getProjectClassName());
	}

	public function contracttypes()
	{
		return $this->belongsToMany(
			Contracttype::getProjectClassName(), config('operators.models.operatorContracttype.table')
		)->using(OperatorContracttype::getProjectClassName());
	}

	public function clients()
	{
		return $this->belongsToMany(
			Client::getProjectClassName(), config('operators.models.clientOperator.table')
		)->using(ClientOperator::getProjectClassName())->withTimestamps();
	}

	public function workingDays()
	{
		return $this->hasMany(WorkingDay::gpc());
	}

	public function getClients()
	{
		return $this->clients;
	}

	public function employments()
	{
		return $this->belongsToMany(
			Employment::getProjectClassName(), config('operators.models.clientOperator.table')
		)->distinct();
	}

	public function client()
	{
		return $this->hasOne(Client::getProjectClassName());
	}

	public function getUserId() : string
	{
		return $this->user_id;
	}

	public function getCurrentOperatorClientClientIdAttribute() : ?string
	{
		if (! $clientOperator = $this->getModel()->lastClientOperator()->first())
			return null;

		return $clientOperator->getClientId();
	}

	public function lastClientOperator()
	{
		return $this->hasOne(ClientOperator::getProjectClassName())->ofMany([
			'created_at' => 'max',
		]);
	}

	public function getUserdata() : Userdata
	{
		Log::critical('risolvere questo get userdata');

		dd('risolvere questo get userdata');

		if ($this->userdata)
			return $this->userdata;

		if (! $userdata = $this->getUser()?->getUserdata())
			$userdata = Userdata::getProjectClassName()::make();

		$this->userdata()->associate($userdata);
		$this->save();

		if ($user = $this->getUser())
			$userdata->user()->save($user);

		return $userdata;
	}

	public function userdata()
	{
		return $this->hasOne(Userdata::getProjectClassName(), 'user_id', 'user_id');
	}

	public function user()
	{
		return $this->belongsTo(User::getProjectClassName());
	}

	public function getLogoImageUrl() : ?string
	{
		return $this->getUser()?->getAvatarImageUrl();
	}

	public function getPossibleEmploymentValuesArray() : array
	{
		return Employment::getSelfPossibleValuesArray(null, 'label_text');
	}

	public function getPossibleContracttypeValuesArray() : array
	{
		return Contracttype::getSelfPossibleValuesArray(null, 'name');
	}

	public function getPossibleClientsValuesArray() : array
	{
		$category = Category::gpc()::findCachedByName('Fornitore Videoservizi');

		return Client::gpc()::byGeneralCategory($category)->select('name', 'id')->pluck('name', 'id')->toArray();
	}

	public function hasPermanentJob()
	{
		dd($this);
	}

}





















// class Operator extends BaseModel
// {

//     use ClientsPackageBaseModelTrait;
//     use CRUDUseUuidTrait;
//     use CRUDSluggableTrait;

//     public function clientOperators()
//     {
//         return $this->hasMany(ClientOperator::getProjectClassName());
//     }

//     public function clients()
//     {
//         return $this->belongsToMany(Client::getProjectClassName());
//     }

//     public function client()
//     {
//         return $this->hasOne(Client::getProjectClassName());
//     }

// }