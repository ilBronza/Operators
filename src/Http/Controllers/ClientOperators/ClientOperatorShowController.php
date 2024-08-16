<?php

namespace IlBronza\Operators\Http\Controllers\ClientOperators;

use IlBronza\CRUD\Traits\CRUDRelationshipTrait;
use IlBronza\CRUD\Traits\CRUDShowTrait;

class ClientOperatorShowController extends ClientOperatorCRUD
{
    use CRUDShowTrait;
    use CRUDRelationshipTrait;

    public $allowedMethods = ['show'];

    public function getGenericParametersFile() : ? string
    {
        return config('operators.models.clientOperator.parametersFiles.create');
    }

    public function getRelationshipsManagerClass()
    {
        return config("operators.models.{$this->configModelClassName}.relationshipsManagerClasses.show");
    }

    public function show(string $clientOperator)
    {
        $clientOperator = $this->findModel($clientOperator);

        return $this->_show($clientOperator);
    }
}
