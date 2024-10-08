<?php

namespace IlBronza\Operators\Http\Controllers\Contracttypes;

use IlBronza\CRUD\Traits\CRUDCreateStoreTrait;

class ContracttypeCreateStoreController extends ContracttypeCRUD
{
    use CRUDCreateStoreTrait;

    public $allowedMethods = ['create', 'store'];

    public function getGenericParametersFile() : ? string
    {
        return config('operators.models.contracttype.parametersFiles.create');
    }
}
