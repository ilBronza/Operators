<?php

namespace IlBronza\Operators\Http\Controllers\WorkingCounters;

use IlBronza\CRUD\Traits\CRUDIndexTrait;
use IlBronza\CRUD\Traits\CRUDPlainIndexTrait;

class WorkingCounterIndexController extends WorkingCounterCRUD
{
	use CRUDPlainIndexTrait;
	use CRUDIndexTrait;

	public $allowedMethods = ['index'];

	public function getIndexFieldsArray()
	{
		return config('operators.models.workingCounter.fieldsGroupsFiles.index')::getTracedFieldsGroup();
	}

	public function getIndexElements()
	{
		return $this->getModelClass()::with(
			'operator.user.userdata',
			'clientOperator.operator.user.userdata'
		)
			->orderByDesc('reset_date')
			->get();
	}
}
