<?php

namespace IlBronza\Operators\Models\Sellables;

use IlBronza\Operators\Helpers\Timelines\OperatorOrder;
use IlBronza\Operators\Models\Operator;
use IlBronza\Operators\Models\Sellables\OperatorRowQuotationOrderCommonTrait;
use IlBronza\Products\Models\Orders\CustomOrderrow;
use IlBronza\Timeline\Interfaces\TimelineGroupInterface;

class OperatorOrderrow extends CustomOrderrow
{
	public string $fieldsGroupParametersKey = 'operatorOrderrow';
	static ?string $typeName = 'Contracttype';
	static $designedTargetConfigPackagePrefix = 'operators';	

	use OperatorRowQuotationOrderCommonTrait;

	public function getSubgroupTimelineGroup() : ?TimelineGroupInterface
	{
		return $this->getSellableSupplier()?->getSupplier()?->getTarget();
	}

	public function getSubgroupByOrderTimelineGroup() : OperatorOrder
	{
		return OperatorOrder::create(
			$this->getOperator(),
			$this->getModelContainer()
		);
	}

	public function getTimelineItemTitleForOperatorContracttype(OperatorContracttype $operatorContracttype)
	{
		return $this->getOrder()->getName() . " | " . $this->getOrder()->getDescription();
	}

	public function getTimelineItemTitleForOperatorOrder(OperatorOrder $operatorOrder)
	{
		$result = [];

		if($value = $this->getSellable()?->getName())
			$result[] = $value;

		if($value = $this->getSupplier()?->getName())
			$result[] = $value;

		return implode(" | ", $result);
	}

	public function getTimelineItemTitleForOperator(Operator $operator)
	{
		return $this->getOrder()->getName() . " | " . $this->getSellable()->getName();
	}

	public function getTimelineItemTitleForSellable()
	{
		$result = [];

		if($value = $this->getOrder()?->getName())
			$result[] = $value;

		if($value = $this->getSupplier()?->getName())
			$result[] = $value;

		return implode(" | ", $result);
	}

	public function getTimelineItemTitleFor()
	{
		$result = [];

		if($value = $this->getOrder()?->getName())
			$result[] = $value;

		if($value = $this->getSellable()?->getName())
			$result[] = $value;

		if($value = $this->getSupplier()?->getName())
			$result[] = $value;

		return implode(" | ", $result);
	}


}
