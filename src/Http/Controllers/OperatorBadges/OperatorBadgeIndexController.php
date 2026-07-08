<?php

namespace IlBronza\Operators\Http\Controllers\OperatorBadges;

use IlBronza\CRUD\Traits\CRUDIndexTrait;
use IlBronza\CRUD\Traits\CRUDPlainIndexTrait;

class OperatorBadgeIndexController extends OperatorBadgeCRUD
{
	use CRUDPlainIndexTrait;
	use CRUDIndexTrait;

	public $allowedMethods = ['index'];

	public function getIndexFieldsArray()
	{
		return config('operators.models.operatorBadge.fieldsGroupsFiles.index')::getTracedFieldsGroup();
	}

	public function getIndexElements()
	{
		return $this->getModelClass()::with('operator.user.userdata')->orderBy('name')->get();
	}
}
