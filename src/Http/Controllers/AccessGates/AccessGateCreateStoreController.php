<?php

namespace IlBronza\Operators\Http\Controllers\AccessGates;

use IlBronza\CRUD\Traits\CRUDCreateStoreTrait;

class AccessGateCreateStoreController extends AccessGateCRUD
{
	use CRUDCreateStoreTrait;

	public $allowedMethods = ['create', 'store'];

	public function getGenericParametersFile() : ? string
	{
		return config('operators.models.accessGate.parametersFiles.create');
	}
}
