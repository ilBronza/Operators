<?php

namespace IlBronza\Operators\Http\Controllers\Timelines;

use Carbon\Carbon;
use IlBronza\CRUD\CRUD;
use IlBronza\CRUD\Helpers\ModelManagers\CrudModelCreator;
use IlBronza\Operators\Http\Controllers\Parameters\Fieldsets\OperatorTimelineCreateRowFieldsetsParameters;
use IlBronza\Operators\Models\Operator;
use IlBronza\Products\Models\Order;
use IlBronza\Products\Models\Orders\Orderrow;
use IlBronza\Products\Models\Sellables\Sellable;
use IlBronza\Products\Models\Sellables\Supplier;
use IlBronza\Products\Providers\Helpers\RowsHelpers\RowAssociatorHelper;
use IlBronza\Products\Providers\Helpers\Sellables\SellableSupplierCreatorHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class OperatorTimelineCreateRowController extends CRUD
{
	public $allowedMethods = [
		'createRowForm',
		'storeTimelineRow',
	];

	public function createRowForm(Request $request) : View
	{
		[
			'selectedOperator' => $selectedOperator,
			'selectedSupplier' => $selectedSupplier,
			'title' => $title,
		] = $this->resolveTimelineCreateRowContext($request);

		$orderrow = Orderrow::gpc()::make();

		if ($startsAt = $request->input('starts_at'))
			$orderrow->starts_at = Carbon::parse($startsAt)->timezone(config('app.timezone'));

		if ($endsAt = $request->input('ends_at'))
			$orderrow->ends_at = Carbon::parse($endsAt)->timezone(config('app.timezone'));

		$formHelper = CrudModelCreator::buildForm(
			$orderrow,
			$this->makeTimelineCreateRowFieldsetsParameters($request, $selectedOperator, $selectedSupplier),
			$this->getTimelineCreateRowAction(),
			[
				'hasCard' => false,
				'showTitle' => false,
				'submitButtonText' => 'Salva',
			]
		);

		$form = $formHelper->getForm();
		$form->addHtmlClass('timeline-create-row-form');

		return view('operators::timelines.operators.create-row-form', [
			'form' => $form,
			'title' => $title,
		]);
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
		$validated = $request->validate($this->getTimelineCreateRowValidationRules());

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

	protected function getTimelineCreateRowParametersFile() : string
	{
		return config('operators.models.orderrow.parametersFiles.timelineCreate');
	}

	protected function makeTimelineCreateRowFieldsetsParameters(
		Request $request,
		?Operator $selectedOperator,
		?Supplier $selectedSupplier,
	) : OperatorTimelineCreateRowFieldsetsParameters
	{
		return new OperatorTimelineCreateRowFieldsetsParameters(
			startsAt: $request->input('starts_at'),
			endsAt: $request->input('ends_at'),
			supplierId: $selectedSupplier?->getKey(),
			operatorId: $selectedOperator?->getKey(),
		);
	}

	protected function resolveTimelineCreateRowContext(Request $request) : array
	{
		$possibleOperators = $this->getPossibleOperators();
		$possibleSuppliers = $this->getPossibleSuppliers();
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

		return [
			'selectedOperator' => $selectedOperator,
			'selectedSupplier' => $selectedSupplier,
			'title' => $titleSubject
				? 'Nuova riga timeline - '.$titleSubject
				: 'Nuova riga timeline',
		];
	}

	protected function getTimelineCreateRowValidationRules() : array
	{
		$parametersFile = $this->getTimelineCreateRowParametersFile();

		return $this->getValidationRulesByFieldsetsParameters(
			(new $parametersFile())->getFieldsetsParameters()
		);
	}

	protected function getValidationRulesByFieldsetsParameters(array $fieldsets) : array
	{
		$fields = [];

		foreach ($fieldsets as $fieldset)
			foreach ($fieldset['fields'] as $fieldName => $parameters)
				$fields[$fieldName] = $parameters['rules'];

		return $fields;
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
}
