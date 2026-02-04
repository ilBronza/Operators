<?php

namespace IlBronza\Operators\Http\Controllers\Employments;

use IlBronza\CRUD\Traits\CRUDIndexTrait;
use IlBronza\CRUD\Traits\CRUDPlainIndexTrait;
use IlBronza\Operators\Http\Controllers\Operators\VehicleCRUD;

class EmploymentIndexController extends EmploymentCRUD
{
    use CRUDPlainIndexTrait;
    use CRUDIndexTrait;

    public $allowedMethods = ['index'];

    public function getIndexFieldsArray()
    {
        return config('operators.models.employment.fieldsGroupsFiles.index')::getTracedFieldsGroup();
    }

    public function getRelatedFieldsArray()
    {
        return config('operators.models.employment.fieldsGroupsFiles.related')::getTracedFieldsGroup();
    }

    public function getIndexElements()
    {
        return $this->getModelClass()::withCount('operators')->get();
    }

}
