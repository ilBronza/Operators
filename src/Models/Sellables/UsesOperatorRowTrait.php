<?php

namespace IlBronza\Operators\Models\Sellables;

use Illuminate\Support\Collection;

trait UsesOperatorRowTrait
{
	public function initializeUsesOperatorRowTrait()
	{
		$this->addFieldsToUpdateByRowTypes('operatorRows');
	}

	// OperatorOrderrow or OperatorQuotationrow
	abstract public function getOperatorRowRelatedModel() : string;

	public function rowRelationByOperatorType()
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

	public function getAddOperatorRowUrl() : string
	{
		return $this->getAddRowByTypeUrl('Operator');
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

	// public function getTotalOperatorRowsCostAttribute()
	// {
	// 	return $this->getTotalByCustomRowsCost('operatorRows');
	// }

	// public function getTotalOperatorRowsRevenueAttribute()
	// {
	// 	return $this->getTotalByCustomRowsRevenue('operatorRows');
	// }

	// public function getMarginOperatorRowsAttribute()
	// {
	// 	return $this->getMarginByCustomRows('operatorRows');
	// }

	// public function getPercentageMarginOperatorRowsAttribute()
	// {
	// 	return $this->getPercentageMarginByCustomRows('operatorRows');
	// }
}