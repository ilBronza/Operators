<?php

namespace IlBronza\Operators\Http\Controllers\Parameters\Datatables;

use IlBronza\Operators\Models\Contracttype;
use IlBronza\Products\Models\Interfaces\SellableItemInterface;
use IlBronza\Products\Providers\Helpers\RowsHelpers\CostsFieldsGroupParametersFile;
use IlBronza\Products\Providers\Helpers\Sellables\SellablePriceDatatableFieldsHelper;

class OperatorContracttypeFieldsGroupParametersFile extends CostsFieldsGroupParametersFile
{
	static function getFieldsGroup() : array
	{
        return [
            'translationPrefix' => 'products::fields',
            'fields' => static::addStandardCostsFieldsByModelPlusDelete(
                [
					'mySelfPrimary' => 'primary',
					'mySelfEdit' => 'links.edit',
					'mySelfSee' => 'links.see',
					'operator.user.userdata.first_name' => 'flat',
					'operator.user.userdata.surname' => 'flat',
					'contracttype.name' => 'flat',

					'internal_approval_rating' => 'flat',
					'level' => 'flat',
                ],
                Contracttype::gpc()::make()
            )
        ];
	}
}