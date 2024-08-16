<?php

namespace IlBronza\Operators\Http\Controllers\Parameters\Datatables;

use IlBronza\Datatables\Providers\FieldsGroupParametersFile;

class OperatorFieldsGroupParametersFile extends FieldsGroupParametersFile
{
	static function getFieldsGroup() : array
	{
		return [
            'translationPrefix' => 'operators::fields',
            'fields' => 
            [
                'mySelfPrimary' => 'primary',
                'mySelfEdit' => 'links.edit',
                'mySelfSee' => 'links.see',

				'user.userdata.surname' => [
					'type' => 'flat',
					'order' => [
						'priority' => 10
					],
				],
				'user.userdata.first_name' => [
					'type' => 'flat',
					'order' => [
						'priority' => 100
					],
				],
                'user.email' => 'flat',

                'vat' => 'flat',
                'user.userdata.fiscal_code' => 'flat',
                'user.userdata.tmp_codice' => 'flat',
                'code' => 'flat',

                'mySelfPrices.sellableSuppliers' => [
                    'type' => 'iterators.each',
                    'childParameters' => [
                        'type' => 'function',
                        'function' => 'getDirectPriceString'
                    ],
                    'width' => '60px'
                ],

                'mySelfSellables.sellableSuppliers' => [
                    'type' => 'iterators.each',
                    'childParameters' => [
                        'type' => 'function',
                        'function' => 'getSellableName'
                    ],
                    'width' => '280px'
                ],

                'employments' => 'relations.belongsToMany',
                'contracttypes' => 'relations.belongsToMany',

                'mySelfDelete' => 'links.delete'
            ]
        ];
	}
}