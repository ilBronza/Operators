<?php

namespace IlBronza\Operators\Http\Controllers\Contracttypes;

use Carbon\Carbon;
use IlBronza\Operators\Models\Contracttype;
use IlBronza\Operators\Models\OperatorContracttype;
use IlBronza\Products\Http\Controllers\Supplier\SupplierTimelineController;
use IlBronza\Products\Models\Order;
use IlBronza\Products\Models\Orders\Orderrow;
use IlBronza\Products\Models\Sellables\Sellable;
use IlBronza\Products\Models\Sellables\Supplier;
use IlBronza\Products\Providers\Helpers\RowsHelpers\RowAssociatorHelper;
use IlBronza\Products\Providers\Helpers\Sellables\SellableSupplierCreatorHelper;
use IlBronza\Timeline\Helpers\TimelineGroupCreatorHelper;
use IlBronza\Timeline\Helpers\TimelineItemCreatorHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Timeline dedicata ai supplier il cui target è un Contracttype.
 * La url viene fornita da Contracttype::getSupplierTimelineContainerUrl()
 * (SupplierTimelineTargetInterface del package products).
 */
class ContracttypeSupplierTimelineController extends SupplierTimelineController
{
	public $allowedMethods = [
		'timeline',
		'container',
		'createRowForm',
		'getPossibleSellablesArray',
		'storeTimelineRow',
	];

	public function getEndpoint() : string
	{
		return app('operators')->route('contracttypes.suppliers.timeline', [
			'supplier' => $this->getModel()->getKey(),
		]);
	}

	public function getTimelineCreateRowFormEndpoint() : ?string
	{
		return app('operators')->route('contracttypes.suppliers.timeline.createRowForm', [
			'supplier' => $this->getModel()->getKey(),
			'iframed' => true,
		]);
	}

	protected function findContracttypeSupplier(string $key) : Supplier
	{
		$supplier = $this->findModel($key);

		return $supplier;
	}

	public function container($supplier)
	{
		$this->setModel(
			$this->findContracttypeSupplier($supplier)
		);

		return $this->returnGanttContainer();
	}

	public function timeline($supplier)
	{
		$this->findContracttypeSupplier($supplier);

		$supplier = $this->findModel($supplier);

		$operator = $supplier->getTarget()->getOperator();

		$sellableSupplierIds = [];

		foreach($operator->operatorContracttypes()->with('supplier.sellableSuppliers')->get() as $operatorContracttype)
			foreach($operatorContracttype->getSupplier()->getSellableSuppliers() as $sellableSupplier)
				$sellableSupplierIds[] = $sellableSupplier->getKey();

		$orderrows = Orderrow::gpc()::with('order', 'sellable.target')->whereIn('sellable_supplier_id', $sellableSupplierIds)->get();

		$sellables = Sellable::gpc()::all();

		foreach($sellables as $sellable)
			$this->groups[] = TimelineGroupCreatorHelper::createGroupByModel($sellable);

		foreach($orderrows as $row)
			$this->items[] = TimelineItemCreatorHelper::createItemByModel($row, $row->getSellable());

		return $this->sendResponse();
	}



	public function createRowForm(Request $request, string $supplier) : View
	{
		$supplierModel = $this->findContracttypeSupplier($supplier);

		$possibleOrders = Order::gpc()::query()
			->active()
			->whereNotNull('parent_id')
			->orderBy('name')
			->get();

		$possibleSellables = Sellable::gpc()::query()
			->orderBy('name')
			->get();

		return view('operators::contracttypes.supplierTimelineCreateRowForm', [
			'action' => app('operators')->route('contracttypes.suppliers.timeline.storeRow', [
				'supplier' => $supplierModel->getKey(),
			]),
			'title' => trans('operators::contracttypes.timelineCreateRowTitle'),
			'startsAt' => $request->input('starts_at'),
			'endsAt' => $request->input('ends_at'),
			'selectedSellableId' => $request->input('group_id'),
			'possibleOrders' => $possibleOrders,
			'possibleSellables' => $possibleSellables,
		]);
	}

	public function getPossibleSellablesArray(Request $request, string $supplier) : JsonResponse
	{
		$this->findContracttypeSupplier($supplier);

		$possibleSellables = Sellable::gpc()::query()
			->orderBy('name')
			->get(['id', 'name'])
			->map(static function (Sellable $sellable) : array {
				return [
					'id' => $sellable->getKey(),
					'name' => $sellable->name,
				];
			})
			->values()
			->all();

		$possibleOrders = Order::gpc()::query()
			->active()
			->whereNotNull('parent_id')
			->orderBy('name')
			->get(['id', 'name'])
			->map(static function (Order $order) : array {
				return [
					'id' => $order->getKey(),
					'name' => $order->getName(),
				];
			})
			->values()
			->all();

		return response()->json([
			'possibleSellables' => $possibleSellables,
			'possibleOrders' => $possibleOrders,
		]);
	}

	public function storeTimelineRow(Request $request, string $supplier) : JsonResponse
	{
		$supplierModel = $this->findContracttypeSupplier($supplier);

		$validated = $request->validate([
			'starts_at' => 'required|date',
			'ends_at' => 'required|date|after:starts_at',
			'sellable_id' => 'required|exists:' . Sellable::gpc()::make()->getTable() . ',id',
			'order_id' => 'required|exists:' . Order::gpc()::make()->getTable() . ',id',
		]);

		$order = Order::gpc()::findOrFail($validated['order_id']);
		$sellable = Sellable::gpc()::findOrFail($validated['sellable_id']);
		$startsAt = Carbon::parse($validated['starts_at'])->timezone(config('app.timezone'));
		$endsAt = Carbon::parse($validated['ends_at'])->timezone(config('app.timezone'));

		$sellableSupplier = SellableSupplierCreatorHelper::getOrCreateSellableSupplier($supplierModel, $sellable);

		$result = RowAssociatorHelper::associateRowBySellableSupplier($order, $sellableSupplier);

		$result->row->starts_at = $startsAt;
		$result->row->ends_at = $endsAt;
		$result->row->save();

		return response()->json([
			'success' => true,
			'message' => trans('operators::contracttypes.timelineRowCreated'),
		]);
	}
}
