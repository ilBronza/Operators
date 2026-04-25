<?php

namespace IlBronza\Operators\Http\Controllers\Parameters\Fieldsets;

use IlBronza\Operators\Models\Contracttype;
use IlBronza\Products\Providers\Helpers\RowsHelpers\RowFieldsetParametersFile;

class OperatorOrderrowEditUpdateFieldsetsParameters extends RowFieldsetParametersFile
{
    public function _getFieldsetsParameters() : array
    {
        $result = [
            'main' => [
                'translationPrefix' => 'products::fields',
                'fields' => [
                    'quantity' => ['number' => 'numeric|nullable'],
                ],
                'width' => ["1-3@l", '1-2@m']
            ],
            'costs' => [
                'translationPrefix' => 'products::fields',
                'fields' => [
                ],
                'width' => ["1-3@l", '1-2@m']
            ]
        ];

        return static::addRowCostsFieldset(
            $result,
            Contracttype::gpc()::make()
        );
    }
}
