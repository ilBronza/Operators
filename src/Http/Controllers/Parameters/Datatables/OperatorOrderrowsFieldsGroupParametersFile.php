<?php

namespace IlBronza\Operators\Http\Controllers\Parameters\Datatables;

use IlBronza\Operators\Models\Contracttype;
use IlBronza\Products\Models\ProductPackageBaseRowcontainerModel;
use IlBronza\Products\Providers\Helpers\RowsHelpers\RowsFieldsGroupParametersFile;

class OperatorOrderrowsFieldsGroupParametersFile extends RowsFieldsGroupParametersFile
{
    static function getFieldsGroup(ProductPackageBaseRowcontainerModel $parentModel) : array
    {
        $helper = static::createByContainer($parentModel);

        $fields = $helper->getRowStartingFields();

        $fields = static::addCostsFields(
            $fields,
            Contracttype::gpc()::make()
        );

		$result = [
            'translationPrefix' => 'products::fields',
            'fields' => $fields
        ];

        return $result;
	}
}