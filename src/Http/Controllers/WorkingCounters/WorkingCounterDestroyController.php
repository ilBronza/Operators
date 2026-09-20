<?php

namespace IlBronza\Operators\Http\Controllers\WorkingCounters;

use IlBronza\CRUD\Traits\CRUDDeleteTrait;

class WorkingCounterDestroyController extends WorkingCounterCRUD
{
	use CRUDDeleteTrait;

	public $allowedMethods = ['destroy'];

	public function destroy(string $workingCounter)
	{
		return $this->_destroy($this->findModel($workingCounter));
	}
}
