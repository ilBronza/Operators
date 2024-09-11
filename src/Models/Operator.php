<?php

namespace IlBronza\Operators\Models;

use App\Models\ExtraFields\OperatorExtraFields;
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
use IlBronza\Products\Models\Interfaces\SupplierInterface;
use IlBronza\Products\Models\Traits\Sellable\InteractsWithSupplierTrait;
use Illuminate\Support\Facades\Log;

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

		'employment_id' => ExtraField::class . ':lastClientOperator',
		'social_security_code' => ExtraField::class . ':lastClientOperator',
		'social_security_institution' => ExtraField::class . ':lastClientOperator',
		'started_at' => ExtraFieldDate::class . ':lastClientOperator',
		'ended_at' => ExtraFieldDate::class . ':lastClientOperator',
		//		'street' => ExtraField::class . ':address',
	];

	public function provideLastClientOperatorModelForExtraFields() : ? ClientOperator
	{
		if($this->lastClientOperator)
			return $this->lastClientOperator;

		dd('jere');
	}

	public function provideAddressModelForExtraFields() : Address
	{
		if ($this->address)
			return $this->address;

		return $this->getUser()->createDefaultAddress();
	}

	public function provideUserdataModelForExtraFields() : Userdata
	{
		if ($this->userdata)
			return $this->userdata;

		return $this->getUser()->createUserdata();
	}

	public function getExtraFieldsClass() : ? string
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

	public function clientOperators()
	{
		return $this->hasMany(ClientOperator::getProjectClassName());
	}

	public function getLastClientOperator()
	{
		return $this->lastClientOperator;
	}

	public function lastClientOperator()
	{
		return $this->hasOne(ClientOperator::getProjectClassName())
		            ->where('client_id', Client::getProjectClassName()::getOneCompany()->getKey())
		            ->ofMany('ended_at', 'max');
	}

	public function getNameAttribute() : ?string
	{
		return $this->getName();
	}

	public function getName() : ?string
	{
		return cache()->remember(
			$this->cacheKey('getName'),
			3600 * 24,
			function()
			{
				return $this->getUser()?->getFullName();
			}
		);
	}

	public function getUser()
	{
		return $this->user;
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
		)->using(ClientOperator::getProjectClassName());
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
		return Employment::getSelfPossibleValuesArray(null, 'label');
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