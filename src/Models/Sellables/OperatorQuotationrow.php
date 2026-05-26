<?php

namespace IlBronza\Operators\Models\Sellables;

use IlBronza\Operators\Models\Sellables\OperatorRowQuotationOrderCommonTrait;
use IlBronza\Products\Models\Quotations\CustomQuotationrow;

class OperatorQuotationrow extends CustomQuotationrow
{
	public string $fieldsGroupParametersKey = 'operatorQuotationrow';
	protected static ?string $typeName = 'Contracttype';
	static $designedTargetConfigPackagePrefix = 'operators';	

	use OperatorRowQuotationOrderCommonTrait;
}