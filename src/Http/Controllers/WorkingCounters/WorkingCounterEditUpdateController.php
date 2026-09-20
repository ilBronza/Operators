<?php

namespace IlBronza\Operators\Http\Controllers\WorkingCounters;

use IlBronza\CRUD\Traits\CRUDEditUpdateTrait;
use Illuminate\Http\Request;

class WorkingCounterEditUpdateController extends WorkingCounterCRUD
{
	use CRUDEditUpdateTrait;

	public $allowedMethods = ['edit', 'update'];

	public function getEditParametersFile() : ?string
	{
		return config('operators.models.workingCounter.parametersFiles.edit');
	}

	public function edit(string $workingCounter)
	{
		return $this->_edit($this->findModel($workingCounter));
	}

	public function update(Request $request, string $workingCounter)
	{
		return $this->_update($request, $this->findModel($workingCounter));
	}
}
