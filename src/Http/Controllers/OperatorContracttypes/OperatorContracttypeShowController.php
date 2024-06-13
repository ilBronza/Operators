<?php

namespace IlBronza\Operators\Http\Controllers\OperatorContracttypes;

use IlBronza\CRUD\Traits\CRUDRelationshipTrait;
use IlBronza\CRUD\Traits\CRUDShowTrait;

class OperatorContracttypeShowController extends OperatorContracttypeCRUD
{
    use CRUDShowTrait;
    use CRUDRelationshipTrait;

    public $allowedMethods = ['show'];

    public function getGenericParametersFile() : ? string
    {
        return config('operators.models.operatorContracttype.parametersFiles.create');
    }

    public function getRelationshipsManagerClass()
    {
        return config("operators.models.{$this->configModelClassName}.relationshipsManagerClasses.show");
    }

    public function show(string $operatorContracttype)
    {
        $operatorContracttype = $this->findModel($operatorContracttype);

        return $this->_show($operatorContracttype);
    }
}
