<?php

namespace IlBronza\Operators\Http\Controllers\OperatorBadges;

use IlBronza\CRUD\Traits\CRUDDeleteTrait;

class OperatorBadgeDestroyController extends OperatorBadgeCRUD
{
	use CRUDDeleteTrait;

	public $allowedMethods = ['destroy'];

	public function destroy($operatorBadge)
	{
		$operatorBadge = $this->findModel($operatorBadge);

		return $this->_destroy($operatorBadge);
	}
}
