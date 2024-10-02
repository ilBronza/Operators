<?php

namespace IlBronza\Operators\Http\Controllers\Operators;

use IlBronza\CRUD\Traits\CRUDIndexTrait;
use IlBronza\CRUD\Traits\CRUDPlainIndexTrait;
use IlBronza\Operators\Http\Controllers\Operators\VehicleCRUD;

class OperatorIndexController extends OperatorCRUD
{
    use CRUDPlainIndexTrait;
    use CRUDIndexTrait;

    public $allowedMethods = ['index'];

    public function getIndexFieldsArray()
    {
        return config('operators.models.operator.fieldsGroupsFiles.index')::getFieldsGroup();
    }

    public function getRelatedFieldsArray()
    {
        //OperatorFieldsGroupParametersFile
        return config('operators.models.operator.fieldsGroupsFiles.index')::getFieldsGroup();
    }

    public function getIndexElements()
    {
        return $this->getModelClass()::active()->with(
			'user.extraFields',
			'user.address',
			'address',
            'contracttypes',
	        'clientOperators.client',
	        'clientOperators.extraFields',
			'clients',
            'sellableSuppliers.directPrice',
            'sellableSuppliers.sellable',
            'employments'
        )
        ->withSupplierId()
        ->get();
    }

}
