<?php

namespace IlBronza\Operators\Helpers;

use IlBronza\Prices\Models\Price;
use IlBronza\Prices\Providers\PriceCalculatorHelper;
use IlBronza\Products\Models\Interfaces\SellableSupplierPriceCreatorBaseClass;
use Illuminate\Support\Collection;

class OperatorPricesCreatorHelper extends SellableSupplierPriceCreatorBaseClass
{
	private function getPriceValue(string $type) : ? float
	{
		if($this->operatorContractype->$type)
			return $this->operatorContractype->$type;

		foreach($this->clientOperators as $clientOperator)
			if($clientOperator->$type)
				return $clientOperator->$type;

		return null;
	}

	private function setDailyPrice() : ? Price
	{
		$price = $this->createPrice();

		$price->setMeasurementUnit('day');

		$price->price = $this->getPriceValue('cost_company_day');
		$price->data = [
			'cost_gross' => $this->getPriceValue('cost_gross_day'),
			'cost_neat' => $this->getPriceValue('cost_neat_day')
		];

		$price->save();

		return $price;
	}

	private function setHourlyPrice() : ? Price
	{
		$price = $this->createPrice();

		$price->setMeasurementUnit('hour');

		$price->price = $this->getPriceValue('cost_company_hour');
		$price->data = [
			'cost_gross' => $this->getPriceValue('cost_gross_hour'),
			'cost_neat' => $this->getPriceValue('cost_neat_hour')
		];

		$price->save();

		return $price;
	}

	public function createPrices() : Collection
	{
		$this->operator = $this->supplier->getTarget();
		$this->contracttype = $this->sellable->getTarget();

		$this->operatorContractype = $this->operator->operatorContracttypes()->where('operator_id', $this->operator->getKey())->first();

		$this->clientOperators = $this->operator->clientOperators()->where('contracttype_id', $this->contracttype->getKey())->orderByDesc('ended_at')->get();

		$prices = collect();

		$prices->push($this->setDailyPrice());
		$prices->push($this->setHourlyPrice());

		return $prices;
	}
}