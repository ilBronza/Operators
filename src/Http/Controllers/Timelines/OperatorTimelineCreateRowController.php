<?php

namespace IlBronza\Operators\Http\Controllers\Timelines;

use IlBronza\CRUD\CRUD;
use IlBronza\CRUD\Traits\CRUDCreateStoreTrait;
use IlBronza\Form\Helpers\FieldsetsProvider\FieldsetParametersFile;
use IlBronza\Form\Helpers\FieldsetsProvider\FieldsetsProvider;
use IlBronza\Operators\Helpers\OperatorOrderrows\OperatorRowAssociatorHelper;
use IlBronza\Operators\Models\OperatorContracttype;
use IlBronza\Operators\Models\Sellables\OperatorOrderrow;
use IlBronza\Products\Models\Order;
use IlBronza\Products\Models\Sellables\Sellable;
use IlBronza\Products\Providers\Helpers\RowsHelpers\RowAssociatorHelper;
use IlBronza\Products\Providers\Helpers\Sellables\SellableSupplierCreatorHelper;
use IlBronza\Products\Providers\Helpers\Sellables\SupplierCreatorHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OperatorTimelineCreateRowController extends CRUD
{
	use CRUDCreateStoreTrait;

	public $allowedMethods = [
		'createRowForm',
		'storeTimelineRow',
	];

	protected function getStoreModelAction() : string
	{
		return app('operators')->route('operators.timeline.storeRow');
	}

	public function getCreateParametersClass() : FieldsetParametersFile
	{
		$file = cconfig('operators.models.orderrow.parametersFiles.timelineCreate');

		return new $file();
	}

	public function getStoreParametersClass() : FieldsetParametersFile
	{
		return $this->getCreateParametersClass();
	}

	public function makeModel() : Model
	{
		$orderrow = OperatorOrderrow::gpc()::make();

		if ($startsAt = request()->input('starts_at'))
			$orderrow->starts_at = $startsAt;

		if ($endsAt = request()->input('ends_at'))
			$orderrow->ends_at = $endsAt;

		$orderrow->operator_id = request()->input('group_id');

		return $orderrow;
	}

	public function createRowForm(Request $request) : View
	{
		return $this->create();
	}

	public function getValidatedParameters(Request $request) : array
	{
		return FieldsetsProvider::validateRequestByParametersFile(
			$request,
			$this->getStoreParametersClass(),
			OperatorOrderrow::gpc()::make()
		);
	}

	public function storeTimelineRow(Request $request) : JsonResponse
	{
		$validated = $this->getValidatedParameters($request);

		$row = OperatorRowAssociatorHelper::gpc()::associateOrderrowByParameters(
			$validated['order_id'],
			$validated['operator_id'],
			$validated['sellable_id'],
			[
				'starts_at' => $validated['starts_at'],
				'ends_at' => $validated['ends_at']
			]
		);

		return response()->json([
			'success' => true,
			'message' => 'Riga timeline creata',
		]);
	}

	protected function getTimelineCreateRowParametersFile() : string
	{
		return config('operators.models.orderrow.parametersFiles.timelineCreate');
	}
}
