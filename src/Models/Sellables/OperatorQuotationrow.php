<?php

namespace IlBronza\Operators\Models\Sellables;

use IlBronza\Operators\Models\Sellables\OperatorRowQuotationOrderCommonTrait;
use IlBronza\Products\Models\Quotations\CustomQuotationrow;

class OperatorQuotationrow extends CustomQuotationrow
{
	protected static ?string $typeName = 'Contracttype';
	static $designedTargetConfigPackagePrefix = 'operators';	

	use OperatorRowQuotationOrderCommonTrait;
}