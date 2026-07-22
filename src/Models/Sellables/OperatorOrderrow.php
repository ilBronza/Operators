<?php

namespace IlBronza\Operators\Models\Sellables;

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
		$target = $this->getSellableSupplier()?->getSupplier()?->getTarget();

		return $target instanceof TimelineGroupInterface ? $target : null;
	}

	public function getTimelineItemTitleForOperatorContracttype(OperatorContracttype $operatorContracttype)
	{
		return $this->getOrder()->getName() . " | " . $this->getOrder()->getDescription();
	}
}
