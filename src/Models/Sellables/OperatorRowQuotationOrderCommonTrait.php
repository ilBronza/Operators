<?php

namespace IlBronza\Operators\Models\Sellables;

use IlBronza\Operators\Models\Contracttype;

trait OperatorRowQuotationOrderCommonTrait
{
	public function initializeOperatorRowQuotationOrderCommonTrait()
	{
		$this->setCustomrowCasts(
			Contracttype::gpc()::make()->getPriceFieldsForSellable()
		);
	}

	public function isDayBased() : bool
	{
		return true;
	}

	public function getQuantity() : ? float
	{
		if($this->isDayBased())
			return 1;

		dd('calcolare le ore');
	}

	public function getSingleCostAttribute() : float
	{
		if(! $target = $this->getSellableSupplier())
			$target = $this->getSellable();

		if($this->isDayBased())
			return $target->cost_per_day ?? 0;

		return $target->cost_per_hour ?? 0;
	}

	public function getSingleRevenueAttribute() : float
	{
		return 777;
	}

	//total_row_cost
	public function getTotalRowCostAttribute() : float
	{
		return $this->getQuantity() * $this->getCalculatedSingleCost();
	}

	//total_row_revenue
	public function getTotalRowRevenueAttribute() : float
	{
		return round($this->getTotalRowCost() * 1.33);
	}

}