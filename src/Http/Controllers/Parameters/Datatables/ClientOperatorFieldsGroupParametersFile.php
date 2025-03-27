<?php

namespace IlBronza\Operators\Http\Controllers\Parameters\Datatables;

use IlBronza\Datatables\Providers\FieldsGroupParametersFile;

class ClientOperatorFieldsGroupParametersFile extends FieldsGroupParametersFile
{
	static function getFieldsGroup() : array
	{
		return [
			'translationPrefix' => 'operators::fields',
			'fields' => [
				'mySelfPrimary' => 'primary',
				'mySelfEdit' => 'links.edit',
				'mySelfSee' => 'links.see',
				'operator.user.userdata.first_name' => 'flat',
				'operator.user.userdata.surname' => 'flat',
				'contracttypes.name' => 'flat',

				'internal_approval_rating' => 'flat',
				'level' => 'flat',

				'cost_company_day' => 'numbers.number2',
				'cost_gross_day' => 'numbers.number2',
				'operator_neat_day' => 'numbers.number2',

				'mySelfDelete' => 'links.delete'
			]
		];
	}
}