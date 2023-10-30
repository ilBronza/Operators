<?php

namespace IlBronza\Operators\Models;

use IlBronza\CRUD\Models\BaseModel;
use IlBronza\CRUD\Traits\CRUDSluggableTrait;
use IlBronza\CRUD\Traits\Model\CRUDUseUuidTrait;
use IlBronza\CRUD\Traits\Model\PackagedModelsTrait;

class Contracttype extends BaseModel
{
    use PackagedModelsTrait;

    use CRUDUseUuidTrait;
    use CRUDSluggableTrait;

    static $packageConfigPrefix = 'operators';
    static $modelConfigPrefix = 'contracttype';

    public $deletingRelationships = [];

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