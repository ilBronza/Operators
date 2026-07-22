<?php

namespace IlBronza\Operators\Models;

use IlBronza\CRUD\Models\BasePivotModel;
use IlBronza\CRUD\Traits\Model\CRUDCacheTrait;
use IlBronza\CRUD\Traits\Model\CRUDUseUuidTrait;
use IlBronza\CRUD\Traits\Model\PackagedModelsTrait;
use IlBronza\Products\Models\OrderProductPhase;
use IlBronza\Products\Models\Sellables\SellableSupplier;
use IlBronza\Products\Providers\Helpers\Sellables\SellableCreatorHelper;
use IlBronza\Products\Providers\Helpers\Sellables\SellableDeleterHelper;
use IlBronza\Products\Providers\Helpers\Sellables\SellableSupplierCreatorHelper;
use IlBronza\Products\Providers\Helpers\Sellables\SupplierCreatorHelper;
use IlBronza\Timeline\Interfaces\TimelineGroupInterface;
use IlBronza\Timeline\Traits\IsTimelineGroupTrait;
use function config;

class OperatorContracttype extends BasePivotModel implements TimelineGroupInterface
{
	use CRUDUseUuidTrait;
	use CRUDCacheTrait;
	use IsTimelineGroupTrait;

	static $deletingRelationships = [];

	static $packageConfigPrefix = 'operators';
	static $modelConfigPrefix = 'operatorContracttype';
	protected $keyType = 'string';

	use PackagedModelsTrait;

	public function contracttype()
	{
		return $this->belongsTo(
			config('operators.models.contracttype.class')
		);
	}

	public function operator()
	{
		return $this->belongsTo(
			config('operators.models.operator.class')
		);
	}

	public function getOperator() : ? Operator
	{
		return $this->operator;
	}

	public function getContracttypeName() : ?string
	{
		return $this->getContracttype()?->getName();
	}

	public function getTimelineGroupContent() : string
	{
		return $this->getContracttypeName() ?? 'N.D.';
	}

	public function getTimelineGroupName() : string
	{
		return $this->getTimelineGroupContent();
	}

	public function getCssBackgroundColorValue() : ?string
	{
		return $this->getOperator()?->getCssBackgroundColorValue()
			?? $this->getContracttype()?->getCssBackgroundColorValue();
	}

	public function getCssTextColorValue() : ?string
	{
		return $this->getOperator()?->getCssTextColorValue()
			?? $this->getContracttype()?->getCssTextColorValue();
	}

	public function getTimelineGroupGanttUrl() : string
	{
		if(method_exists($this, 'getSupplier'))
			if($supplier = $this->getSupplier())
				return $supplier->getGanttUrl();

		return app('operators')->route('operators.byContracttypesTimelineContainer', [
			'option' => 'subgroups',
		]);
	}

	public function getTimelineBindingDataArray() : array
	{
		$sellable = $this->getContracttype()->getSellable();
		$sellableSupplier = $this->getSupplier()->sellableSuppliers()->where('sellable_id', $sellable->getKey())->first();

		return [
			'operator_id' => $this->operator_id,
			'sellable_id' => $sellable->getKey(),
			'sellable_supplier_id' => $sellableSupplier->getKey(),
		];
	}

	public function getContracttype() : ?Contracttype
	{
		return $this->contracttype;
	}

	public function getSellableSupplier() : ? SellableSupplier
	{
		if (! $sellable = $this->getContracttype()?->sellables()?->first())
			return null;

		if (! $supplier = $this->getOperator()?->getSupplier())
			return null;

		return SellableCreatorHelper::getOrCreateSellableSupplier($supplier, $sellable);
	}

}
