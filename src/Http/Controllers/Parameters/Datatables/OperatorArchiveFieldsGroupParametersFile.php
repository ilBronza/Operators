<?php

namespace IlBronza\Operators\Http\Controllers\Parameters\Datatables;

use IlBronza\Datatables\Providers\FieldsGroupParametersFile;

class OperatorArchiveFieldsGroupParametersFile extends FieldsGroupParametersFile
{
	static function getFieldsGroup() : array
	{
		return [
            'translationPrefix' => 'operators::fields',
            'fields' => 
            [
                'mySelfPrimary' => 'primary',
                'mySelfEdit' => 'links.edit',

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

                'user.email' => 'links.email',

                'vat' => 'flat',
                'user.userdata.fiscal_code' => 'flat',
                'user.userdata.tmp_codice' => 'flat',
                'code' => 'flat',

				'address.city' => 'flat',
				'address.province' => 'flat',

                'employments' => 'relations.belongsToMany',
                'contracttypes' => 'relations.belongsToMany',

                'mySelfDelete' => 'links.delete'
            ]
        ];
	}
}