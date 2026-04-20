<?php

namespace IlBronza\Operators\Models\Sellables;

use IlBronza\Operators\Models\Operator as IbOperator;
use IlBronza\Products\Models\Interfaces\SupplierInterface;
use IlBronza\Products\Models\Traits\Sellable\InteractsWithSupplierTrait;
use IlBronza\Products\Models\Traits\Sellable\SingleSellableSupplierTrait;
use IlBronza\Products\Providers\Helpers\Sellables\SellableCreatorHelper;
use Illuminate\Support\Collection;

class Operator extends IbOperator implements SupplierInterface
{
	use InteractsWithSupplierTrait;
	use SingleSellableSupplierTrait;

	public function getPossibleSellables() : Collection
	{
		dd($this->getVehicleType());

		$sellable = SellableCreatorHelper::getOrcreateSellableByTarget($this->getVehicleType());

		return collect([$sellable]);
	}

	public function mustAutomaticallyUpdatePrices() : ? bool
	{
		return true;
	}

	static function boot()
	{
		parent::boot();

		static::saving(function ($model)
		{
		});
	}
}