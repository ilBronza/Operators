<?php

namespace IlBronza\Operators\Models;

use IlBronza\AccountManager\Models\User;
use IlBronza\AccountManager\Models\Userdata;
use IlBronza\CRUD\Models\BaseModel;
use IlBronza\CRUD\Traits\CRUDSluggableTrait;
use IlBronza\CRUD\Traits\Model\CRUDParentingTrait;
use IlBronza\CRUD\Traits\Model\CRUDUseUuidTrait;
use IlBronza\CRUD\Traits\Model\PackagedModelsTrait;
use IlBronza\Clients\Models\Client;
use IlBronza\Contacts\Models\Contact;
use IlBronza\Operators\Models\ClientOperator;
use IlBronza\Operators\Models\Contracttype;
use IlBronza\Operators\Models\Employment;
use IlBronza\Operators\Models\OperatorContracttype;
use IlBronza\Products\Models\Interfaces\SupplierInterface;
use IlBronza\Products\Models\Traits\Sellable\InteractsWithSupplierTrait;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class Operator extends BaseModel implements SupplierInterface
{
    use PackagedModelsTrait;
    use CRUDUseUuidTrait;
    use CRUDSluggableTrait;
    use CRUDParentingTrait;
    use InteractsWithSupplierTrait;

    static $packageConfigPrefix = 'operators';
    static $modelConfigPrefix = 'operator';

    protected $with = ['user'];

    public $deletingRelationships = [];

    public function getMorphClass()
    {
        return 'Operator';
    }

    public function clientOperators()
    {
        return $this->hasMany(ClientOperator::getProjectClassName());
    }

    public function getName() : ? string
    {
        return $this->getUser()->getFullName();
    }

    public function operatorContracttypes()
    {
        return $this->hasMany(OperatorContracttype::getProjectClassName());
    }

    public function contacts()
    {
        return $this->hasMany(
            Contact::getProjectClassName(),
            'contactable_id',
            'user_id'
        )->where('contactable_type', 'LIKE', "User");
    }

    public function getRelatedContacts() : Collection
    {
        if($this->relationLoaded('contacts'))
            return $this->contacts;

        return $this->contacts()->get();
    }

    public function contracttypes()
    {
        return $this->belongsToMany(
            Contracttype::getProjectClassName(),
            config('operators.models.operatorContracttype.table')
        )->using(OperatorContracttype::getProjectClassName());
    }

    public function clients()
    {
        return $this->belongsToMany(
            Client::getProjectClassName(),
            config('operators.models.clientOperator.table')
        )->using(ClientOperator::getProjectClassName());
    }

    public function employments()
    {
        return $this->belongsToMany(
            Employment::getProjectClassName(),
            config('operators.models.clientOperator.table')
        )->distinct();
    }

    public function client()
    {
        return $this->hasOne(Client::getProjectClassName());
    }

    public function user()
    {
        return $this->belongsTo(User::getProjectClassName());
    }

    public function getUserId() : string
    {
        return $this->user_id;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function userdata()
    {
        return $this->belongsTo(Userdata::getProjectClassName());
    }

    public function getUserdata() : Userdata
    {
        Log::critical('risolvere questo get userdata');

        dd('risolvere questo get userdata');

        if($this->userdata)
            return $this->userdata;

        if(! $userdata = $this->getUser()?->getUserdata())
            $userdata = Userdata::getProjectClassName()::make();

        $this->userdata()->associate($userdata);
        $this->save();

        if($user = $this->getUser())
            $userdata->user()->save($user);

        return $userdata;
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