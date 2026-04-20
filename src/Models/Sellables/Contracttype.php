<?php

namespace IlBronza\Operators\Models\Sellables;

use IlBronza\Operators\Models\Contracttype as IbContracttype;
use IlBronza\Prices\Models\Interfaces\WithPriceInterface;
use IlBronza\Prices\Models\Traits\HasCustomPricesTrait;
use IlBronza\Products\Models\Interfaces\SellableItemInterface;
use IlBronza\Products\Models\Traits\Sellable\InteractsWithSellableTrait;
use Illuminate\Support\Collection;
use IlBronza\Products\Models\Interfaces\SellableSupplierPriceCreatorBaseClass;

class Contracttype extends IbContracttype implements SellableItemInterface, WithPriceInterface
{
	use HasCustomPricesTrait;
	use InteractsWithSellableTrait;

	static $deletingRelationships = ['sellables'];

	public function getPriceFieldsForSellable() : array
	{
		return [
			'cost_per_day' => 'day',
			'cost_per_hour' => 'hour',

			'revenue_per_day' => 'day',
			'revenue_per_hour' => 'hour',
		];
	}

	public function getPossibleSuppliersElements() : Collection
	{
		return $this->operators()->with('supplier')->get()->pluck('supplier')->filter();
	}

	public function getPriceCreator() : ?SellableSupplierPriceCreatorBaseClass
	{
		$class = cconfig('operators.models.contracttype.helpers.sellableSupplierPricesCreator');

		dd($class);

		return $class ? new $class : new \IlBronza\Vehicles\Helpers\VehiclePricesCreatorHelper;
	}

	/**
	 * SE NULL ignora
	 * SE FALSE NO
	 * SE TRUE SI'
	 **/
	public function mustAutomaticallyUpdatePrices() : ? bool
	{
		return null;
	}

	public function getEditParametersFile() : string
	{
		return $this->getCompulsoryConfigByKey('parametersFiles.editSellable');
	}

	public function getSellableSupplierIndexRelations() : array
	{
		return [
			'prices',
			'supplier.target',
		];
	}}