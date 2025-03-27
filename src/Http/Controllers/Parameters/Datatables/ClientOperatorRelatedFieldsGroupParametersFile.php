<?php

namespace IlBronza\Operators\Http\Controllers\Parameters\Datatables;

use IlBronza\Datatables\Providers\FieldsGroupParametersFile;

class ClientOperatorRelatedFieldsGroupParametersFile extends FieldsGroupParametersFile
{
	static function getFieldsGroup() : array
	{
		return [
			'translationPrefix' => 'operators::fields',
			'fields' => [
				'mySelfPrimary' => 'primary',
				'mySelfEdit' => 'links.edit',
				'mySelfSee' => 'links.see',

				'social_security_institution' => 'flat',
				'unilav' => 'flat',

				'started_at' => 'dates.date',
				'ended_at' => 'dates.date',

				'internal_approval_rating' => 'flat',
				'level' => 'flat',

				'cost_company_hour' => 'numbers.number2',
				'cost_gross_hour' => 'numbers.number2',
				'cost_neat_hour' => 'numbers.number2',
				'cost_company_day' => 'numbers.number2',
				'cost_gross_day' => 'numbers.number2',
				'operator_neat_day' => 'numbers.number2',

				'mySelfDelete' => 'links.delete'
			]
		];
	}
}