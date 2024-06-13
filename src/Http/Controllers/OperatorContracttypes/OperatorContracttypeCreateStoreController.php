<?php

namespace IlBronza\Operators\Http\Controllers\OperatorContracttypes;

use IlBronza\CRUD\Traits\CRUDCreateStoreTrait;
use IlBronza\CRUD\Traits\CRUDRelationshipTrait;
use IlBronza\CRUD\Traits\CRUDShowTrait;

class OperatorContracttypeCreateStoreController extends OperatorContracttypeCRUD
{
    use CRUDCreateStoreTrait;
    use CRUDShowTrait;
    use CRUDRelationshipTrait;

    public $allowedMethods = ['create', 'store', 'edit', 'update', 'show'];

    public function getGenericParametersFile() : ? string
    {
        return config('operators.models.operatorContracttype.parametersFiles.create');
    }

    public function getRelationshipsManagerClass()
    {
        return config("products.models.{$this->configModelClassName}.relationshipsManagerClasses.show");
    }

    public function show(string $operatorContracttype)
    {
        $operatorContracttype = $this->findModel($operatorContracttype);

        return $this->_show($operatorContracttype);
    }
}
