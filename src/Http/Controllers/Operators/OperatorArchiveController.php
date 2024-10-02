<?php

namespace IlBronza\Operators\Http\Controllers\Operators;

use IlBronza\CRUD\Traits\CRUDIndexTrait;
use IlBronza\CRUD\Traits\CRUDPlainIndexTrait;
use IlBronza\Operators\Http\Controllers\Operators\VehicleCRUD;

class OperatorArchiveController extends OperatorIndexController
{
    public $allowedMethods = ['index'];

    public function getIndexFieldsArray()
    {
        return config('operators.models.operator.fieldsGroupsFiles.archive')::getFieldsGroup();
    }

    public function getIndexElements()
    {
        return $this->getModelClass()::with(
			'user.extraFields',
			'user.address',
			'address',
            'contracttypes',
            'employments'
        )
        ->get();
    }

}
