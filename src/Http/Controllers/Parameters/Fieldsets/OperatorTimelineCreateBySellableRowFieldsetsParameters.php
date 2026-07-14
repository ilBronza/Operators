<?php

namespace IlBronza\Operators\Http\Controllers\Parameters\Fieldsets;

use IlBronza\Form\Helpers\FieldsetsProvider\FieldsetParametersFile;
use IlBronza\Operators\Models\Operator;
use IlBronza\Products\Models\Order;
use IlBronza\Products\Models\Sellables\Sellable;
use IlBronza\Products\Models\Sellables\Supplier;

class OperatorTimelineCreateBySellableRowFieldsetsParameters extends FieldsetParametersFile
{
	public function __construct(
		protected ?string $startsAt = null,
		protected ?string $endsAt = null,
		protected ?string $supplierId = null,
		protected ?string $operatorId = null,
	) {}

	public function _getFieldsetsParameters() : array
	{
		$sellableId = $this->getModel()? $this->getModel()->sellable_id : request()->sellable_id;
		$contracttype = Sellable::gpc()::find($sellableId)?->getTarget();
		$operatorsList = $contracttype->getOperators()->pluck('name', 'id')->toArray();

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
						'type' => 'select',
						'select2' => false,
						'multiple' => false,
						'label' => 'Operatore',
						'list' => $operatorsList,
						'rules' => 'required|exists:' . Operator::gpc()::make()->getTable() . ',id',
					],
					'sellable_id' => [
						'type' => 'hidden',
						'rules' => 'required|exists:' . Sellable::gpc()::make()->getTable() . ',id',
					],
					'order_id' => [
						'type' => 'hidden',
						'rules' => 'required|exists:' . Order::gpc()::make()->getTable() . ',id',
					],
				],
				'width' => ['large'],
			],
		];
	}
}
