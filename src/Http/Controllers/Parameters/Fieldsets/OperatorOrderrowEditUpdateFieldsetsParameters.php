<?php

namespace IlBronza\Operators\Http\Controllers\Parameters\Fieldsets;

use IlBronza\Form\Helpers\FieldsetsProvider\FieldsetParametersFile;

use function config;

class OperatorOrderrowEditUpdateFieldsetsParameters extends FieldsetParametersFile
{
    public function _getFieldsetsParameters() : array
    {
        $costField = ['number' => 'numeric|nullable'];
        $booleanField = ['boolean' => 'bool|nullable'];

        return [
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
                    'calculated_cost_per_km' => $costField,
                    'calculated_cost_per_movimentation' => $costField,
                    'calculated_cost_per_day' => $costField,
                    'approved_total_row_cost' => $booleanField,
                    'calculated_total_row_cost' => $costField,
                    'approved_total_row_revenue' => $booleanField,
                    'calculated_total_row_revenue' => $costField,
                ],
                'width' => ["1-3@l", '1-2@m']
            ],

        ];
    }
}
