<?php

namespace IlBronza\Operators\Http\Controllers\Timelines;

use Carbon\Carbon;
use IlBronza\Operators\Models\Operator;
use IlBronza\Products\Models\Orders\Orderrow;
use IlBronza\Products\Models\Sellables\Sellable;
use IlBronza\Products\Models\Sellables\Supplier;
use IlBronza\Products\Models\Order;
use IlBronza\Products\Providers\Helpers\RowsHelpers\RowAssociatorHelper;
use IlBronza\Products\Providers\Helpers\Sellables\SellableSupplierCreatorHelper;
use IlBronza\Timeline\Http\Controllers\BaseTimelineController;
use IlBronza\Timeline\Interfaces\TimelineGroupInterface;
use IlBronza\Timeline\Traits\GlobalTimelineTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class OperatorGlobalTimelineController extends BaseTimelineController
{
	use GlobalTimelineTrait;

	public $allowedMethods = [
		'timeline',
		'container',
		'createRowForm',
		'getPossibleSellablesArray',
		'storeTimelineRow',
	];

	public function getEndpoint() : string
	{
		return app('operators')->route('operators.timeline');
	}

	public function getPossibleSellablesEndpoint() : ?string
	{
		return app('operators')->route('operators.timeline.possibleSellables');
	}

	public function getTimelineStoreRowEndpoint() : ?string
	{
		return app('operators')->route('operators.timeline.storeRow');
	}

	public function getTimelineCreateRowFormEndpoint() : ?string
	{
		return app('operators')->route('operators.timeline.createRowForm');
	}

	public function returnGanttContainer()
	{
		$apiEndpoint = $this->getEndpoint();
		$timelineUpdateRoute = $this->getTimelineUpdateRoute();
		$possibleSellablesEndpoint = $this->getPossibleSellablesEndpoint();
		$timelineStoreRowEndpoint = $this->getTimelineStoreRowEndpoint();
		$timelineCreateRowFormEndpoint = $this->getTimelineCreateRowFormEndpoint();
		$modelInstance = $this->getModel();
		$buttons = $this->getButtons();
		$zoom = $this->zoom ?? config('timeline.zoom', 14);

		return view('operators::timelines.operators.timeline', compact(
			'apiEndpoint',
			'timelineUpdateRoute',
			'possibleSellablesEndpoint',
			'timelineStoreRowEndpoint',
			'timelineCreateRowFormEndpoint',
			'modelInstance',
			'buttons',
			'zoom'
		));
	}

	public function getRows() : Collection
	{
		return Orderrow::gpc()::with('order', 'sellable', 'sellableSupplier.supplier.target')->get();
	}

	public function getGroupModel($row) : ?TimelineGroupInterface
	{
		return $row->getSupplierTimelineGroup();
	}

	public function getGroupItems() : Collection
	{
		return Operator::gpc()::with('user')
			->get()
			->filter()
			->unique(fn(TimelineGroupInterface $group) => get_class($group) . ':' . $group->getTimelineGroupId())
			->values();
	}

	public function getMainTimelineData()
	{
		$orderrows = $this->getRows();
		$groupItems = $this->getGroupItems();

		$this->createGroupsByCollection($groupItems);
		$this->createItemsByCollectionAndGetter($orderrows, 'getSupplierTimelineGroup');

		return $this->sendResponse();
	}

	public function createRowForm(Request $request)
	{
		$possibleSellables = $this->getPossibleSellables();
		$possibleSuppliers = $this->getPossibleSuppliers();
		$possibleOrders = $this->getPossibleOrders();
		$possibleOperators = $this->getPossibleOperators();
		$groupId = $request->input('group_id');
		$selectedOperator = $possibleOperators->first(
			fn (Operator $operator) => (string) $operator->getKey() === (string) $groupId
		);
		$selectedSupplier = $possibleSuppliers->first(
			fn (Supplier $supplier) => (string) $supplier->getKey() === (string) $groupId
		);

		if (! $selectedSupplier && $selectedOperator)
			$selectedSupplier = $possibleSuppliers->first(
				fn (Supplier $supplier) => $this->supplierBelongsToOperator($supplier, $selectedOperator)
			);

		if (! $selectedOperator && $selectedSupplier)
			$selectedOperator = $this->getSupplierOperator($selectedSupplier);

		$titleSubject = $selectedOperator?->getName() ?? $selectedSupplier?->getName();

		return view('operators::timelines.operators.create-row-form', [
			'action' => $this->getTimelineCreateRowAction(),
			'startsAt' => $request->input('starts_at'),
			'endsAt' => $request->input('ends_at'),
			'possibleSellables' => $possibleSellables,
			'possibleSuppliers' => $possibleSuppliers,
			'possibleOperators' => $possibleOperators,
			'possibleOrders' => $possibleOrders,
			'selectedSupplier' => $selectedSupplier,
			'selectedOperator' => $selectedOperator,
			'title' => $titleSubject
				? 'Nuova riga timeline - '.$titleSubject
				: 'Nuova riga timeline',
		]);
	}

	protected function getTimelineCreateRowAction() : string
	{
		return app('operators')->route('operators.timeline.storeRow');
	}

	protected function getSupplierOperator(Supplier $supplier) : ?Operator
	{
		$target = $supplier->getTarget();

		if (! $target || ! method_exists($target, 'getOperator'))
			return null;

		return $target->getOperator();
	}

	protected function supplierBelongsToOperator(Supplier $supplier, Operator $operator) : bool
	{
		return $this->getSupplierOperator($supplier)?->is($operator) ?? false;
	}

	protected function getPossibleSellables() : Collection
	{
		return Sellable::gpc()::query()
			->orderBy('name')
			->get(['id', 'name']);
	}

	protected function getPossibleSuppliers() : Collection
	{
		return Supplier::gpc()::query()
			->with('target')
			->get()
			->sortBy(fn (Supplier $supplier) => $supplier->getName())
			->values();
	}

	protected function getPossibleOperators() : Collection
	{
		return Operator::gpc()::query()
			->with('user')
			->get()
			->sortBy(fn (Operator $operator) => $operator->getName())
			->values();
	}

	protected function getPossibleOrders() : Collection
	{
		return Order::gpc()::query()
			->active()
			->whereNotNull('parent_id')
			->orderBy('name')
			->get(['id', 'name']);
	}

	public function getPossibleSellablesArray(Request $request) : JsonResponse
	{
		return response()->json([
			'possibleSellables' => $this->getPossibleSellables()
				->map(fn (Sellable $sellable) => [
					'id' => $sellable->getKey(),
					'name' => $sellable->name,
				])
				->values()
				->all(),
			'possibleSuppliers' => $this->getPossibleSuppliers()
				->map(fn (Supplier $supplier) => [
					'id' => $supplier->getKey(),
					'name' => $supplier->getName(),
				])
				->values()
				->all(),
			'possibleOperators' => $this->getPossibleOperators()
				->map(fn (Operator $operator) => [
					'id' => $operator->getKey(),
					'name' => $operator->getName(),
				])
				->values()
				->all(),
			'possibleOrders' => $this->getPossibleOrders()
				->map(fn (Order $order) => [
					'id' => $order->getKey(),
					'name' => $order->getName(),
				])
				->values()
				->all(),
		]);
	}

	public function storeTimelineRow(Request $request) : JsonResponse
	{
		$validated = $request->validate([
			'starts_at' => 'required|date',
			'ends_at' => 'required|date|after:starts_at',
			'sellable_id' => 'required|exists:'.Sellable::gpc()::make()->getTable().',id',
			'supplier_id' => 'required|exists:'.Supplier::gpc()::make()->getTable().',id',
			'operator_id' => 'nullable|exists:'.Operator::gpc()::make()->getTable().',id',
			'order_id' => 'required|exists:'.Order::gpc()::make()->getTable().',id',
		]);

		$order = Order::gpc()::findOrFail($validated['order_id']);
		$sellable = Sellable::gpc()::findOrFail($validated['sellable_id']);
		$supplier = Supplier::gpc()::findOrFail($validated['supplier_id']);

		if ($operatorId = $validated['operator_id'] ?? null)
			if (! $this->supplierBelongsToOperator($supplier, Operator::gpc()::findOrFail($operatorId)))
				return response()->json([
					'success' => false,
					'message' => 'Il fornitore selezionato non appartiene all\'operatore scelto',
				], 422);

		$sellableSupplier = SellableSupplierCreatorHelper::getOrCreateSellableSupplier($supplier, $sellable);
		$result = RowAssociatorHelper::associateRowBySellableSupplier($order, $sellableSupplier);

		$result->row->starts_at = Carbon::parse($validated['starts_at'])->timezone(config('app.timezone'));
		$result->row->ends_at = Carbon::parse($validated['ends_at'])->timezone(config('app.timezone'));
		$result->row->save();

		return response()->json([
			'success' => true,
			'message' => 'Riga timeline creata',
		]);
	}
}
