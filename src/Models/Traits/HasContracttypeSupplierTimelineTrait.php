<?php

namespace IlBronza\Operators\Models\Traits;

use IlBronza\Products\Models\Sellables\Supplier;

/**
 * Fornisce la url della timeline dedicata del package operators
 * ai target di tipo contracttype (Contracttype, OperatorContracttype).
 *
 * Implementa SupplierTimelineTargetInterface del package products.
 */
trait HasContracttypeSupplierTimelineTrait
{
	public function getSupplierTimelineContainerUrl(Supplier $supplier, ?string $option = null) : ?string
	{
		return app('operators')->route('contracttypes.suppliers.timelineContainer', array_filter([
			'supplier' => $supplier->getKey(),
			'option' => $option,
		]));
	}
}
