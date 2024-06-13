<?php

namespace IlBronza\Operators\Http\Controllers\Contracttypes;

use IlBronza\CRUD\Traits\CRUDCreateStoreTrait;
use IlBronza\CRUD\Traits\CRUDRelationshipTrait;
use IlBronza\CRUD\Traits\CRUDShowTrait;

class ContracttypeCreateStoreController extends ContracttypeCRUD
{
    use CRUDCreateStoreTrait;
    use CRUDShowTrait;
    use CRUDRelationshipTrait;

    public $allowedMethods = ['create', 'store', 'edit', 'update', 'show'];

    public function getGenericParametersFile() : ? string
    {
        return config('operators.models.contracttype.parametersFiles.create');
    }

    public function getRelationshipsManagerClass()
    {
        return config("products.models.{$this->configModelClassName}.relationshipsManagerClasses.show");
    }

    public function show(string $contracttype)
    {
        $contracttype = $this->findModel($contracttype);

        return $this->_show($contracttype);
    }
}
