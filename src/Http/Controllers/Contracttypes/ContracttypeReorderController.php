<?php

namespace IlBronza\Operators\Http\Controllers\Contracttypes;

use IlBronza\CRUD\Traits\CRUDFlatSortingTrait;

class ContracttypeReorderController extends ContracttypeCRUD
{
	use CRUDFlatSortingTrait;

	public $allowedMethods = ['storeMassReorder'];
}
