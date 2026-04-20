<?php

namespace IlBronza\Operators\Models\Sellables;

use IlBronza\Operators\Models\OperatorContracttype as IbOperatorContracttype;

class OperatorContracttype extends IbOperatorContracttype
{
	protected static function booted()
	{
		static::saving(function ($operatorContracttype)
		{
			$supplier = SupplierCreatorHelper::getOrCreateSupplierFromTarget($operatorContracttype->getOperator());
			$sellable = SellableCreatorHelper::getOrcreateSellableByTarget(
				$operatorContracttype->getContracttype(), [], 'operator'
			);

			$sellableSupplier = SellableSupplierCreatorHelper::getOrCreateSellableSupplier($supplier, $sellable);

			if(config('operators.manageCosts') == true)
			{
				$sellableSupplier->cost_company_day = $operatorContracttype->cost_company_day;
				$sellableSupplier->save();
			}
		});

		static::deleting(function ($operatorContracttype)
		{
			$supplier = SupplierCreatorHelper::getOrCreateSupplierFromTarget($operatorContracttype->getOperator());
			$sellable = SellableCreatorHelper::getOrcreateSellableByTarget(
				$operatorContracttype->getContracttype(), [], 'operator'
			);

			SellableDeleterHelper::deleteSellableSupplierBySellableSupplierModels($sellable, $supplier);
		});
	}
}