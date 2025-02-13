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
				'unilav' => 'flat',
				'enpals' => 'flat',
				'iscr_liste' => 'flat',

				'started_at' => [
					'type' => 'dates.date',
					'order' => [
						'priority' => 100,
						'type' => 'DESC'
					]
				],
				'ended_at' => [
					'type' => 'dates.date',
					'order' => [
						'priority' => 90,
						'type' => 'DESC'
					]
				],

				'internal_approval_rating' => 'flat',
				'level' => 'flat',

				'cost_company_day' => 'editor.numeric',
				'cost_gross_day' => 'editor.numeric',
				'operator_neat_day' => 'editor.numeric',

				'mySelfDelete' => 'links.delete'
			]
		];
	}
}