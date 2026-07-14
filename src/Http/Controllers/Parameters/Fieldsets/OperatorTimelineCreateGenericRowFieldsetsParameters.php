<?php

namespace IlBronza\Operators\Http\Controllers\Parameters\Fieldsets;

use IlBronza\Form\Helpers\FieldsetsProvider\FieldsetParametersFile;
use IlBronza\Operators\Models\Operator;
use IlBronza\Products\Models\Order;
use IlBronza\Products\Models\Sellables\Sellable;
use Illuminate\Support\Str;

class OperatorTimelineCreateGenericRowFieldsetsParameters extends FieldsetParametersFile
{
	public function getPossibleOperatorsList()
	{
		if(! $sellableId = $this->getModel()->sellable_id)
		{
			return Operator::gpc()::active()->get()->pluck('name', 'id');
		}

		$contracttype = Sellable::gpc()::find($sellableId)?->getTarget();

		return $contracttype->getOperators()->pluck('name', 'id')->toArray();
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

	public function getPossibleSellablesList() : array
	{
		return Sellable::gpc()::query()
			->orderBy('name')
			->pluck('name', 'id')
			->toArray();
	}

	public function returnHiddenField(string $class)
	{
		return [
			'type' => 'hidden',
			'rules' => 'required|exists:' . $class::make()->getTable() . ',id',
		];		
	}

	public function returnSelectField(string $class)
	{
		$basename = class_basename($class);
		$plural = Str::plural($basename);

		$method = "getPossible{$plural}List";

		return [
			'type' => 'select',
			'select2' => false,
			'multiple' => false,
			'list' => $this->$method(),
			'rules' => 'required|exists:' . $class::gpc()::make()->getTable() . ',id',
		];
	}

	public function getOperatorIdField()
	{
		if($this->getModel()->operator_id)
			return $this->returnHiddenField(Operator::gpc());

		return $this->returnSelectField(Operator::gpc());
	}

	public function getSellableIdField()
	{
		if($this->getModel()->sellable_id)
			return $this->returnHiddenField(Sellable::gpc());

		return $this->returnSelectField(Sellable::gpc());
	}

	public function getOrderIdField()
	{
		if($this->getModel()->order_id)
			return $this->returnHiddenField(Order::gpc());

		return $this->returnSelectField(Order::gpc());
	}

	public function _getFieldsetsParameters() : array
	{
		return [
			'base' => [
				'translationPrefix' => 'products::fields',
				'fields' => [
					'starts_at' => [
						'type' => 'datetime',
						'rules' => 'required|date',
					],
					'ends_at' => [
						'type' => 'datetime',
						'rules' => 'required|date|after:starts_at',
					],
					'operator_id' => $this->getOperatorIdField(),
					'sellable_id' => $this->getSellableIdField(),
					'order_id' => $this->getOrderIdField(),
				],
				'width' => ['large'],
			],
		];
	}
}
