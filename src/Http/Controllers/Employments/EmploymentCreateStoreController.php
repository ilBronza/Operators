<?php

namespace IlBronza\Operators\Http\Controllers\Employments;

use IlBronza\CRUD\Traits\CRUDCreateStoreTrait;

class EmploymentCreateStoreController extends EmploymentCRUD
{
    use CRUDCreateStoreTrait;

    public $allowedMethods = ['create', 'store'];

    public function getGenericParametersFile() : ? string
    {
        return config('operators.models.employment.parametersFiles.create');
    }
}
