<?php

namespace IlBronza\Operators\Models\Sellables;

use IlBronza\Operators\Models\Sellables\OperatorRowQuotationOrderCommonTrait;
use IlBronza\Products\Models\Orders\CustomOrderrow;

class OperatorOrderrow extends CustomOrderrow
{
	protected static ?string $typeName = 'operatorRow';
	static $designedTargetConfigPackagePrefix = 'operators';	

	use OperatorRowQuotationOrderCommonTrait;
}