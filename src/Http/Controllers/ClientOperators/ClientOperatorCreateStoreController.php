<?php

namespace IlBronza\Operators\Http\Controllers\ClientOperators;

use IlBronza\CRUD\Traits\CRUDCreateStoreTrait;
use IlBronza\CRUD\Traits\CRUDRelationshipTrait;
use IlBronza\CRUD\Traits\CRUDShowTrait;
use IlBronza\Operators\Models\ClientOperator;
use IlBronza\Operators\Models\Operator;

use function config;

class ClientOperatorCreateStoreController extends ClientOperatorCRUD
{
    use CRUDCreateStoreTrait;
    use CRUDShowTrait;
    use CRUDRelationshipTrait;

    public $allowedMethods = ['create', 'createByOperator', 'store'];

    public function getGenericParametersFile() : ? string
    {
        return config('operators.models.clientOperator.parametersFiles.create');
    }

    public function getRelationshipsManagerClass()
    {
        return config("products.models.{$this->configModelClassName}.relationshipsManagerClasses.show");
    }

    public function show(string $clientOperator)
    {
        $clientOperator = $this->findModel($clientOperator);

        return $this->_show($clientOperator);
    }

	public function createByOperator(string $operator)
	{
		$operator = Operator::gpc()::find($operator);

		$this->setParentModel($operator);

		return $this->create();
	}

	public function getAfterStoredRedirectUrl()
	{
		return $this->getModel()->getEditUrl();
	}
}
