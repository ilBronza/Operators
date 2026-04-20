<?php

namespace IlBronza\Operators\Models\Sellables;

use IlBronza\CRUD\Models\Casts\CastFieldPrice;
use IlBronza\Operators\Models\Contracttype;

trait IsOperatorSellableSupplierTrait
{
	public function initializeIsOperatorSellableSupplierTrait() : void
	{
		$prices = Contracttype::gpc()::make()->getPriceFieldsForSellable();

		$casts = [];

		foreach ($prices as $field => $measurementUnit) {
			$casts[$field] = CastFieldPrice::class . ":{$field},$measurementUnit";
		}

		$this->casts = array_merge($this->casts, $casts);
	}
}