<?php

namespace IlBronza\Operators\Http\Controllers\OperatorBadges;

use IlBronza\CRUD\Traits\CRUDCreateStoreTrait;
use IlBronza\Operators\Models\Operator;

class OperatorBadgeCreateStoreController extends OperatorBadgeCRUD
{
	use CRUDCreateStoreTrait;

	public $allowedMethods = ['create', 'createByOperator', 'store'];

	public function getGenericParametersFile() : ? string
	{
		return config('operators.models.operatorBadge.parametersFiles.create');
	}

	public function createByOperator(string $operator)
	{
		$operator = Operator::gpc()::find($operator);

		$this->setParentModel($operator);

		return $this->create();
	}
}
