<?php

namespace IlBronza\Operators\Http\Controllers\Parameters\Fieldsets;

use IlBronza\Form\Helpers\FieldsetsProvider\FieldsetParametersFile;
use IlBronza\Operators\Models\Operator;
use IlBronza\Products\Models\Order;
use IlBronza\Products\Models\Sellables\Sellable;
use IlBronza\Products\Models\Sellables\Supplier;

class OperatorTimelineCreateRowFieldsetsParameters extends FieldsetParametersFile
{
	public function __construct(
		protected ?string $startsAt = null,
		protected ?string $endsAt = null,
		protected ?string $supplierId = null,
		protected ?string $operatorId = null,
	) {}

	public function _getFieldsetsParameters() : array
	{
		return [
			'base' => [
				'translationPrefix' => 'products::fields',
				'fields' => [
					'starts_at' => [
						'type' => 'datetime',
						'default' => $this->startsAt,
						'rules' => 'required|date',
					],
					'ends_at' => [
						'type' => 'datetime',
						'default' => $this->endsAt,
						'rules' => 'required|date|after:starts_at',
					],
					'order_id' => [
						'type' => 'select',
						'select2' => false,
						'multiple' => false,
						'label' => 'Commessa',
						'list' => $this->getPossibleOrdersList(),
						'rules' => 'required|exists:' . Order::gpc()::make()->getTable() . ',id',
					],
					'sellable_id' => [
						'type' => 'select',
						'select2' => false,
						'multiple' => false,
						'label' => 'Sellable',
						'list' => $this->getPossibleSellablesList(),
						'rules' => 'required|exists:' . Sellable::gpc()::make()->getTable() . ',id',
					],
					'supplier_id' => [
						'type' => 'select',
						'select2' => false,
						'multiple' => false,
						'label' => 'Fornitore',
						'default' => $this->supplierId,
						'list' => $this->getPossibleSuppliersList(),
						'rules' => 'required|exists:' . Supplier::gpc()::make()->getTable() . ',id',
					],
					'operator_id' => [
						'type' => 'select',
						'select2' => false,
						'multiple' => false,
						'label' => 'Operatore',
						'default' => $this->operatorId,
						'list' => $this->getPossibleOperatorsList(),
						'rules' => 'nullable|exists:' . Operator::gpc()::make()->getTable() . ',id',
					],
				],
				'width' => ['large'],
			],
		];
	}

	public function getPossibleSellablesList() : array
	{
		return Sellable::gpc()::query()
			->orderBy('name')
			->pluck('name', 'id')
			->toArray();
	}

	public function getPossibleSuppliersList() : array
	{
		return Supplier::gpc()::query()
			->with('target')
			->get()
			->sortBy(fn (Supplier $supplier) => $supplier->getName())
			->mapWithKeys(fn (Supplier $supplier) => [(string) $supplier->getKey() => $supplier->getName()])
			->all();
	}

	public function getPossibleOperatorsList() : array
	{
		return Operator::gpc()::query()
			->with('user')
			->get()
			->sortBy(fn (Operator $operator) => $operator->getName())
			->mapWithKeys(fn (Operator $operator) => [(string) $operator->getKey() => $operator->getName()])
			->all();
	}

	public function getPossibleOrdersList() : array
	{
		return Order::gpc()::query()
			->active()
			->whereNotNull('parent_id')
			->orderBy('name')
			->pluck('name', 'id')
			->toArray();
	}
}
