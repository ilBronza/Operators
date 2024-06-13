<?php

namespace IlBronza\Operators\Http\Controllers\Operators;

use IlBronza\CRUD\Traits\CRUDRelationshipTrait;
use IlBronza\CRUD\Traits\CRUDShowTrait;

class OperatorShowController extends OperatorCRUD
{
    use CRUDShowTrait;
    use CRUDRelationshipTrait;

    public $allowedMethods = ['show'];

    public function getGenericParametersFile() : ? string
    {
        return config('operators.models.operator.parametersFiles.create');
    }

    public function getRelationshipsManagerClass()
    {
        return config("operators.models.{$this->configModelClassName}.relationshipsManagerClasses.show");
    }

    public function show(string $operator)
    {
        $operator = $this->findModel($operator);

        return $this->_show($operator);
    }
}
