<?php

namespace IlBronza\Operators\Http\Controllers\Sellables;

use IlBronza\CRUD\CRUD;
use IlBronza\Products\Models\Orders\Orderrow;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

use function compact;
use function view;

class OrderrowClientOperatorController extends CRUD
{
	public $allowedMethods = [
		'clientOperatorPopup',
		'associateClientOperator'
	];

	public function clientOperatorPopup($orderrow)
	{
		$row = $this->findModel($orderrow);

		$clientOperator = $row->getClientOperator();

		$operator = $row->getSupplier()->getTarget();

		$clientOperators = $operator->getClientOperators();

		return view(
			'operators.popup', compact(
				'row', 'clientOperator', 'operator', 'clientOperators'
			)
		);
	}

	public function findModel(string $key, array $relations = []) : ?Model
	{
		return Orderrow::gpc()::findOrFail($key);
	}

	public function associateClientOperator(Request $request, $row, $clientOperator)
	{
		$row = $this->findModel($row);

		$row->client_operator_id = $clientOperator;
		$row->save();

		return redirect()->route('iframe.close');
	}

}
