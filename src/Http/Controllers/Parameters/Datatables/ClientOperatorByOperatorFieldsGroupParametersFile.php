<?php

namespace IlBronza\Operators\Http\Controllers\Parameters\Datatables;

use IlBronza\Datatables\Providers\FieldsGroupParametersFile;

class ClientOperatorByOperatorFieldsGroupParametersFile extends FieldsGroupParametersFile
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
				'social_security_code' => 'flat',
				'enpals' => 'flat',
				'iscr_liste' => 'flat',

				'started_at' => 'dates.date',
				'ended_at' => 'dates.date',

				'internal_approval_rating' => 'flat',
				'level' => 'flat',

				'cost_company_day' => 'flat',
				'cost_gross_day' => 'flat',
				'operator_neat_day' => 'flat',

				'mySelfDelete' => 'links.delete'
			]
		];
	}
}