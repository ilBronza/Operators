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
                'user.userdata.first_name' => 'flat',
                'user.userdata.surname' => 'flat',
                'user.email' => 'flat',

                'vat' => 'flat',
                'fiscal_code' => 'flat',
                'code' => 'flat',


                'user' => 'relations.belongsTo',

                'contracttypes' => 'relations.belongsToMany',

                'mySelfDelete' => 'links.delete'
            ]
        ];
	}
}