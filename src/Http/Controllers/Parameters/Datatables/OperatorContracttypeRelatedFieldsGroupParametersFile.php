<?php

namespace IlBronza\Operators\Http\Controllers\Parameters\Datatables;

use IlBronza\Datatables\Providers\FieldsGroupParametersFile;

class OperatorContracttypeRelatedFieldsGroupParametersFile extends FieldsGroupParametersFile
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
				'contracttype.name' => 'flat',

				'internal_approval_rating' => 'flat',
				'level' => 'flat',

				'cost_company_hour' => 'flat',
				'cost_gross_hour' => 'flat',
				'cost_neat_hour' => 'flat',
				'cost_company_day' => 'flat',
				'cost_gross_day' => 'flat',
				'operator_neat_day' => 'flat',

				'mySelfDelete' => 'links.delete'
			]
		];
	}
}