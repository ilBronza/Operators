<?php

namespace IlBronza\Operators\Http\Controllers\OperatorBadges;

use IlBronza\CRUD\Traits\CRUDEditUpdateTrait;
use Illuminate\Http\Request;

class OperatorBadgeEditUpdateController extends OperatorBadgeCRUD
{
	use CRUDEditUpdateTrait;

	public $allowedMethods = ['edit', 'update'];

	public function getGenericParametersFile() : ? string
	{
		return config('operators.models.operatorBadge.parametersFiles.edit');
	}

	public function getEditParametersFile() : ? string
	{
		return config('operators.models.operatorBadge.parametersFiles.edit');
	}

	public function getUpdateParametersFile() : ? string
	{
		return config('operators.models.operatorBadge.parametersFiles.update');
	}

	public function edit(string $operatorBadge)
	{
		$operatorBadge = $this->findModel($operatorBadge);

		return $this->_edit($operatorBadge);
	}

	public function update(Request $request, $operatorBadge)
	{
		$operatorBadge = $this->findModel($operatorBadge);

		return $this->_update($request, $operatorBadge);
	}
}
