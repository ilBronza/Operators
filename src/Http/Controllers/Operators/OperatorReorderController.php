<?php

namespace IlBronza\Operators\Http\Controllers\Operators;

use IlBronza\CRUD\Traits\CRUDNestableTrait;

use Illuminate\Http\Request;

use function config;

class OperatorReorderController extends OperatorCRUD
{
	use CRUDNestableTrait;

	/**
	 * http methods allowed. remove non existing methods to get a 403
	 **/
	public $allowedMethods = [
		'reorder',
		'storeReorder'
	];

	public function reorder(Request $request, $operator = null)
	{
		if(! is_null($operator))
			$operator = $this->findModel($operator);

		return $this->_reorder($request, $operator);
	}
}

