<?php

namespace IlBronza\Operators\Http\Controllers\OperatorBadges;

use IlBronza\CRUD\Traits\CRUDShowTrait;

class OperatorBadgeShowController extends OperatorBadgeCRUD
{
	use CRUDShowTrait;

	public $allowedMethods = ['show'];

	public function getGenericParametersFile() : ? string
	{
		return config('operators.models.operatorBadge.parametersFiles.show');
	}

	public function getShowParametersFile() : ? string
	{
		return config('operators.models.operatorBadge.parametersFiles.show');
	}

	public function show(string $operatorBadge)
	{
		$operatorBadge = $this->findModel($operatorBadge);

		return $this->_show($operatorBadge);
	}
}
