<?php

namespace IlBronza\Operators\Models\Sellables;

use Illuminate\Support\Collection;

trait UsesOperatorRowTrait
{
	public function initializeUsesOperatorRowTrait()
	{
		$this->addFieldsToUpdateByRowTypes('operatorRows');

		$this->addSummaryFieldsCastsByRowTypes('operatorRows');
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