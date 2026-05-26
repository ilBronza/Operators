<?php

namespace IlBronza\Operators\Models\Sellables;

use IlBronza\Operators\Models\Sellables\OperatorRowQuotationOrderCommonTrait;
use IlBronza\Products\Models\Orders\CustomOrderrow;

class OperatorOrderrow extends CustomOrderrow
{
	public string $fieldsGroupParametersKey = 'operatorOrderrow';
	static ?string $typeName = 'Contracttype';
	static $designedTargetConfigPackagePrefix = 'operators';	

	use OperatorRowQuotationOrderCommonTrait;
}