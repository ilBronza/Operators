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

	public function getQuantityAttribute($value) : ? float
	{
		if($value)
			return $value;

		return $this->getModelContainer()?->km * 2;
	}


	public function getCalculatedCostPerKm() : float
	{
		return $this->calculated_cost_per_km;
	}

	public function getCalculatedCostPerMovimentation() : float
	{
		return $this->calculated_cost_per_movimentation ?? 0;
	}

	public function getCalculatedCostPerDay() : float
	{
		return $this->calculated_cost_per_day ?? 0;
	}

	//total_row_cost
	public function getTotalRowCostAttribute() : float
	{
		$tripCost = round($this->getQuantity() * $this->getCalculatedCostPerKm(), 2) ?? 0;

		$daysCost = round($this->getDaysQuantity() * $this->getCalculatedCostPerDay(), 2) ?? 0;

		$total = $tripCost + $this->getCalculatedCostPerMovimentation() + $daysCost;

		return $total * $this->getCostCoefficient();
	}

	//total_row_revenue
	public function getTotalRowRevenueAttribute() : float
	{
		return round($this->getTotalRowCost() * 1.33);
	}

}