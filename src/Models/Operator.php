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
use IlBronza\Clients\Models\ClientOperator;

class Operator extends BaseModel
{
    use PackagedModelsTrait;
    use CRUDUseUuidTrait;
    use CRUDSluggableTrait;
    use CRUDParentingTrait;

    static $packageConfigPrefix = 'operators';
    static $modelConfigPrefix = 'operator';

    public $deletingRelationships = [];

    public function clientOperators()
    {
        return $this->hasMany(ClientOperator::getProjectClassName());
    }

    public function clients()
    {
        return $this->belongsToMany(Client::getProjectClassName());
    }

    public function client()
    {
        return $this->hasOne(Client::getProjectClassName());
    }

    public function user()
    {
        return $this->belongsTo(User::getProjectClassName());
    }

    public function userdata()
    {
        return $this->belongsTo(Userdata::getProjectClassName());
    }

    public function getUserdata() : Userdata
    {
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