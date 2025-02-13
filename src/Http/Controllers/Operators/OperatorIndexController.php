<?php

namespace IlBronza\Operators\Http\Controllers\Operators;

use IlBronza\CRUD\Traits\CRUDIndexTrait;
use IlBronza\CRUD\Traits\CRUDPlainIndexTrait;

use function config;

class OperatorIndexController extends OperatorCRUD
{
    use CRUDPlainIndexTrait;
    use CRUDIndexTrait;

    public $allowedMethods = ['index'];

    public function getIndexFieldsArray()
    {
		//OperatorFieldsGroupParametersFile
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
	        'validClientOperator.client',
	        'validClientOperator.employment',
			'contacts.contacttype',
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
