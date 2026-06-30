<?php

namespace IlBronza\Operators\Http\Controllers\AccessGates;

use IlBronza\CRUD\Traits\CRUDShowTrait;

class AccessGateShowController extends AccessGateCRUD
{
	use CRUDShowTrait;

	public $allowedMethods = ['show'];

	public function getGenericParametersFile() : ? string
	{
		return config('operators.models.accessGate.parametersFiles.show');
	}

	public function getShowParametersFile() : ? string
	{
		return config('operators.models.accessGate.parametersFiles.show');
	}

	public function show(string $accessGate)
	{
		$accessGate = $this->findModel($accessGate);

		return $this->_show($accessGate);
	}
}
