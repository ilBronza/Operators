<?php

namespace IlBronza\Operators\Models\Sellables;

use IlBronza\Operators\Models\Operator;
use IlBronza\Operators\Models\Sellables\OperatorOrderrow;
use IlBronza\Products\Models\Sellables\SellableSupplier;
use Illuminate\Support\Collection;

trait UsesOperatorRowTrait
{
	public function initializeUsesOperatorRowTrait()
	{
		$this->setRowRelationsParameters('operatorRows');
	}

	// OperatorOrderrow or OperatorQuotationrow
	abstract public function getOperatorRowRelatedModel() : string;

	public function rowRelationByContracttype()
	{
		return $this->operatorRows();
	}

	public function operatorRows()
	{
		return $this->hasMany(
			$this->getOperatorRowRelatedModel()
		);
	}

	public function getOperatorRows()
	{
		return $this->operatorRows;
	}

	public function getAddContracttypeUrl() : string
	{
		return $this->getAddRowByTypeUrl('Contracttype');
	}

	static function getContracttypeSellableSuppliers() : Collection
	{
		return cache()->remember(
			'operatoars.getContracttypeSellableSuppliers',
			3600,
			function()
			{
				$relations = [
					'supplier.target.operator.validClientOperator.extraFields',
					'supplier.target.operator.validClientOperator.employment',
					'supplier.target.operator.address',
					'sellable.target',
					'prices',
				];

				if(method_exists(Operator::gpc(), 'extraFields'))
					if(Operator::gpc()::make()->getExtraFieldsClass())
						$relations[] = 'supplier.target.operator.extraFields';

				$result = SellableSupplier::gpc()::query()
					->whereHas('sellable', function($query)
					{
						$query->byType(
							OperatorOrderrow::gpc()::$typeName
						);
						$query->whereNull('deleted_at');
					})
					->with($relations)
					->get();

				return $result->filter(function($item)
				{
					return $item->getSupplier()?->getTarget()?->getOperator()?->active;
				});
			}
		);
	}

	public function getOperatorRowsForRelationshipManager() : Collection
	{
		$modelString = strtolower(class_basename($this->getModel()));

		if(method_exists($this->getModel(), 'getExtraFieldsClass'))
			if($this->getModel()->getExtraFieldsClass())
				$modelString .= '.extraFields';

		$relations = [
			"{$modelString}",
			'prices',
			'sellable',
			'sellable.target',
			'sellable.prices',
			'sellableSupplier.prices',
			'sellableSupplier.supplier.target',
			'sellableSupplier.sellable.target',
		];

		$operatorRowPlaceholder = $this->operatorRows()->make();

		if(method_exists($operatorRowPlaceholder, 'getExtraFieldsClass'))
			if($operatorRowPlaceholder->getExtraFieldsClass())
				$relations[] = 'extraFields';

		return $this->operatorRows()->with($relations)->get();
	}
}