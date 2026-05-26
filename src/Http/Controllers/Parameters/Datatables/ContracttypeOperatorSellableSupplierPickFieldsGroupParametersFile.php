<?php

namespace IlBronza\Operators\Http\Controllers\Parameters\Datatables;

use IlBronza\Operators\Models\Contracttype;
use IlBronza\Products\Providers\Helpers\RowsHelpers\CostsFieldsGroupParametersFile;
use Illuminate\Database\Eloquent\Model;

class ContracttypeOperatorSellableSupplierPickFieldsGroupParametersFile extends CostsFieldsGroupParametersFile
{
    static function getFieldsGroup(Model $model = null) : array
	{
		return [
			'translationPrefix' => 'products::fields',
			'fields' => static::addStandardCostsFlatFieldsByModelPlusDelete(
				[
                'mySelfPrimary' => 'primary',
				'supplier.target.name' => 'flat',
				'mySelfAssign' => "products::{$model->getModelConfigPrefix()}rows.addSellableSupplierRow",'sellable.name' => 'flat',
				],
				Contracttype::gpc()::make(),
			)
		];
	}
}

