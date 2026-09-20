<?php

namespace IlBronza\Operators\Http\Controllers\WorkingCounters;

use IlBronza\CRUD\Traits\CRUDCreateStoreTrait;
use IlBronza\Operators\Models\ClientOperator;
use IlBronza\Operators\Models\Operator;

class WorkingCounterCreateStoreController extends WorkingCounterCRUD
{
	use CRUDCreateStoreTrait;

	public $allowedMethods = ['create', 'createByOperator', 'createByClientOperator', 'store'];

	public function getGenericParametersFile() : ?string
	{
		return config('operators.models.workingCounter.parametersFiles.create');
	}

	public function getModelDefaultParameters() : array
	{
		if (! isset($this->parentModel))
			return [];

		$parentModel = $this->getParentModel();

		if ($parentModel instanceof ClientOperator)
			return ['operator_id' => $parentModel->operator_id];

		return [];
	}

	public function createByOperator(string $operator)
	{
		$this->setParentModel(Operator::gpc()::findOrFail($operator));

		return $this->create();
	}

	public function createByClientOperator(string $clientOperator)
	{
		$this->setParentModel(ClientOperator::gpc()::findOrFail($clientOperator));

		return $this->create();
	}
}
