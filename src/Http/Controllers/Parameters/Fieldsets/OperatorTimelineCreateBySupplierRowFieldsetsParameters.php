<?php

namespace IlBronza\Operators\Http\Controllers\Parameters\Fieldsets;

use IlBronza\Form\Helpers\FieldsetsProvider\FieldsetParametersFile;
use IlBronza\Operators\Models\Operator;
use IlBronza\Products\Models\Order;
use IlBronza\Products\Models\Sellables\Sellable;
use IlBronza\Products\Models\Sellables\Supplier;

class OperatorTimelineCreateBySupplierRowFieldsetsParameters extends FieldsetParametersFile
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
					'operator_id' => [
						'type' => 'hidden',
						'rules' => 'required|exists:' . Sellable::gpc()::make()->getTable() . ',id',
					],
					'sellable_id' => [
						'type' => 'hidden',
						'rules' => 'required|exists:' . Sellable::gpc()::make()->getTable() . ',id',
					],
					'order_id' => [
						'type' => 'select',
						'select2' => false,
						'multiple' => false,
						'label' => 'Commessa',
						'list' => $this->getPossibleOrdersList(),
						'rules' => 'required|exists:' . Order::gpc()::make()->getTable() . ',id',
					],
				],
				'width' => ['large'],
			],
		];
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
