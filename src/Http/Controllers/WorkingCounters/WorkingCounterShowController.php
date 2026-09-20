<?php

namespace IlBronza\Operators\Http\Controllers\WorkingCounters;

use IlBronza\CRUD\Traits\CRUDShowTrait;

class WorkingCounterShowController extends WorkingCounterCRUD
{
	use CRUDShowTrait;

	public $allowedMethods = ['show'];

	public function getGenericParametersFile() : ?string
	{
		return config('operators.models.workingCounter.parametersFiles.show');
	}

	public function show(string $workingCounter)
	{
		return $this->_show($this->findModel($workingCounter));
	}
}
