<?php

namespace IlBronza\Operators\Models\Sellables;

use IlBronza\Operators\Models\OperatorContracttype as IbOperatorContracttype;
use IlBronza\Products\Models\Interfaces\SupplierInterface;
use IlBronza\Products\Models\Traits\Sellable\InteractsWithSupplierTrait;
use IlBronza\Products\Providers\Helpers\Sellables\SellableCreatorHelper;
use IlBronza\Products\Providers\Helpers\Sellables\SellableDeleterHelper;
use IlBronza\Products\Providers\Helpers\Sellables\SellableSupplierCreatorHelper;
use IlBronza\Products\Providers\Helpers\Sellables\SupplierCreatorHelper;
use Illuminate\Support\Collection;

class OperatorContracttype extends IbOperatorContracttype implements SupplierInterface
{
	use InteractsWithSupplierTrait;

	public function getPossibleSellables() : Collection
	{
		$sellable = SellableCreatorHelper::getOrcreateSellableByTarget($this->getContracttype(), [], 'Contracttype');

		return collect([$sellable]);
	}

	public function mustAutomaticallyUpdatePrices() : ? bool
	{
		return true;
	}

	public function getNameAttribute() : ? string
	{
		return $this->getName();
	}

	public function getName() : ? string
	{
		return $this->getOperator()?->getName();
	}

	// protected static function booted()
	// {
	// 	static::saving(function ($operatorContracttype)
	// 	{
	// 		$supplier = SupplierCreatorHelper::getOrCreateSupplierFromTarget($operatorContracttype->getOperator());
	// 		$sellable = SellableCreatorHelper::getOrcreateSellableByTarget(
	// 			$operatorContracttype->getContracttype(), [], 'operator'
	// 		);

	// 		$sellableSupplier = SellableSupplierCreatorHelper::getOrCreateSellableSupplier($supplier, $sellable);
	// 	});

	// 	static::created(function ($operatorContracttype)
	// 	{
	// 		$supplier = SupplierCreatorHelper::getOrCreateSupplierFromTarget($operatorContracttype->getOperator());
	// 		$sellable = SellableCreatorHelper::getOrcreateSellableByTarget(
	// 			$operatorContracttype->getContracttype(), [], 'operator'
	// 		);

	// 		$sellableSupplier = SellableSupplierCreatorHelper::getOrCreateSellableSupplier($supplier, $sellable);

	// 			$sellableSupplier->updatePricesBySellableAndSupplier();
	// 	});

	// 	static::deleting(function ($operatorContracttype)
	// 	{
	// 		$supplier = SupplierCreatorHelper::getOrCreateSupplierFromTarget($operatorContracttype->getOperator());
	// 		$sellable = SellableCreatorHelper::getOrcreateSellableByTarget(
	// 			$operatorContracttype->getContracttype(), [], 'operator'
	// 		);

	// 		SellableDeleterHelper::deleteSellableSupplierBySellableSupplierModels($sellable, $supplier);

	// 	});
	// }
}