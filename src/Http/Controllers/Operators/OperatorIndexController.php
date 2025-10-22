<?php

namespace IlBronza\Operators\Http\Controllers\Operators;

use IlBronza\CRUD\Traits\CRUDIndexTrait;
use IlBronza\CRUD\Traits\CRUDPlainIndexTrait;
use IlBronza\Operators\Models\ClientOperator;

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
			'clients',
            'sellableSuppliers.directPrice',
            'sellableSuppliers.sellable',
            'employments'
        )->with([
            'clientOperators.client',
            'clientOperators' => function($query)
            {
                if(method_exists(ClientOperator::gpc()::make(), 'extraFields'))
                    $query->with('extraFields');
            }
        ])
        ->withSupplierId()
        ->get();
    }

}
