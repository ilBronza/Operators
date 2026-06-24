<?php

namespace IlBronza\Operators\Helpers\OperatorOrderrows;

use IlBronza\CRUD\Traits\PackagedHelpersTrait;
use IlBronza\Operators\Models\OperatorContracttype;
use IlBronza\Products\Models\Interfaces\CustomRowInterface;
use IlBronza\Products\Models\Order;
use IlBronza\Products\Models\ProductPackageBaseRowcontainerModel;
use IlBronza\Products\Models\Quotations\Quotation;
use IlBronza\Products\Models\Sellables\Sellable;
use IlBronza\Products\Providers\Helpers\RowsHelpers\RowAssociatorHelper;
use IlBronza\Products\Providers\Helpers\Sellables\SellableSupplierCreatorHelper;
use IlBronza\Products\Providers\Helpers\Sellables\SupplierCreatorHelper;

class OperatorRowAssociatorHelper extends RowAssociatorHelper
{
	use PackagedHelpersTrait;
	
	static string $packageConfigPrefix = 'operators';
	static $modelConfigPrefix = 'operatorRow';

	static function associateRowByParameters(
		ProductPackageBaseRowcontainerModel $containerModel,
		string|Operator $operator,
		string|Sellable $sellable,
		array $parameters = []
	) : CustomRowInterface
	{
		if(is_string($sellable))
			$sellable = Sellable::gpc()::findOrFail($sellable);

		$operatorContracttype = OperatorContracttype::gpc()::where(
				'operator_id', is_string($operator) ? $operator : $operator->getKey()
			)->where(
				'contracttype_id', $sellable->target_id
			)->firstOrFail();

		$supplier = SupplierCreatorHelper::getOrCreateSupplierFromTarget(
			$operatorContracttype
		);

		$sellableSupplier = SellableSupplierCreatorHelper::getOrCreateSellableSupplier(
			$supplier,
			$sellable
		);

		$row = static::associateRowBySellableSupplier(
			$containerModel,
			$sellableSupplier
		)->row;

		if(count($parameters) == 0)
			return $row;

		foreach($parameters as $key => $value)
			$row->$key = $value;

		$row->save();

		return $row;
	}

	static function associateOrderrowByParameters(
		string|Order $order,
		string|Operator $operator,
		string|Sellable $sellable,
		array $parameters = []
	)
	{
		if(is_string($order))
			$order = Order::gpc()::findOrFail($order);

		return static::associateRowByParameters(
			$order,
			$operator,
			$sellable,
			$parameters
		);
	}

	static function associateQuotationrowByParameters(
		string|Quotation $quotation,
		string|Operator $operator,
		string|Sellable $sellable,
		array $parameters = []
	)
	{
		if(is_string($quotation))
			$quotation = Quotation::gpc()::findOrFail($quotation);

		return static::associateRowByParameters(
			$quotation,
			$operator,
			$sellable,
			$parameters
		);
	}
}