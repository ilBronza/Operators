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
                ],
                'width' => ["1-3@l", '1-2@m']
            ],

        ];
    }
}
