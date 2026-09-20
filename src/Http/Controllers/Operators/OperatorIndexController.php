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
        return config('operators.models.operator.fieldsGroupsFiles.index')::getTracedFieldsGroup();
    }

    public function getRelatedFieldsArray()
    {
        //OperatorFieldsGroupParametersFile
        return config('operators.models.operator.fieldsGroupsFiles.index')::getTracedFieldsGroup();
    }

	public function getIndexElements()
	{
        ini_set('memory_limit', "-1");

		return $this->getModelClass()::active()
			->without('user')
			->withUserdataNames()
			->withAddressCity()
			->with([
				'contacts.contacttype',
				'clients',
				'employments',
				'contracttypes',
			])
			->get();
	}

}
