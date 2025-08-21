<?php

namespace IlBronza\Operators\Helpers;

use Exception;
use IlBronza\Prices\Models\Price;
use IlBronza\Prices\Providers\PriceCalculatorHelper;
use IlBronza\Products\Models\Interfaces\SellableSupplierPriceCreatorBaseClass;
use IlBronza\Ukn\Ukn;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class OperatorPricesCreatorHelper extends SellableSupplierPriceCreatorBaseClass
{
	private function getPriceValue(string $type) : ? float
	{
		$type = Str::snake($type);

		if($this->operatorContractype->$type)
			return $this->operatorContractype->$type;

		foreach($this->clientOperators as $clientOperator)
			if($clientOperator->$type)
				return $clientOperator->$type;

		return null;
	}

	private function setDayPrice(string $collectionId) : ? Price
	{
		$price = $this->getOrCreatePriceByCollectionId($collectionId);

		$price->setMeasurementUnit('day');

		$price->price = $this->getPriceValue($collectionId);

		$price->save();

		return $price;
	}

	public function createPrices() : Collection
	{
		$this->operator = $this->supplier->getTarget();
		$this->contracttype = $this->sellable->getTarget();

		$this->operatorContractype = $this->operator->operatorContracttypes()->where('contracttype_id', $this->contracttype->getKey())->first();

		$this->clientOperators = $this->operator->clientOperators()->where('contracttype_id', $this->contracttype->getKey())->orderByDesc('ended_at')->get();

		$prices = collect();

		$prices->push($this->setDayPrice('costCompanyDay'));
		$prices->push($this->setDayPrice('costGrossDay'));
		$prices->push($this->setDayPrice('operatorNeatDay'));

		return $prices;
	}
}