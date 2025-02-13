<?php

namespace IlBronza\Operators\Http\Controllers\Parameters\Datatables;

use IlBronza\Datatables\Providers\FieldsGroupParametersFile;

class ContracttypeFieldsGroupParametersFile extends FieldsGroupParametersFile
{
	static function getFieldsGroup() : array
	{
		return [
			'translationPrefix' => 'operators::fields',
			'fields' => [
				'mySelfPrimary' => 'primary',
				'mySelfEdit' => 'links.edit',
				'mySelfSee' => 'links.see',
				'name' => [
					'type' => 'flat',
					'order' => [
						'priority' => 100
					]
				],
				//				'slug' => 'flat',
				//				'operators_count' => 'flat',
				'description' => 'flat',
				'istat_code' => 'editor.text',

				'operator_neat_day' => 'editor.numeric',
				'cost_gross_day' => 'editor.numeric',
				'cost_company_day' => 'editor.numeric',
				//				'cost_charge_coefficient' => 'flat',

				'mySelfDelete' => 'links.delete'
			]
		];
	}
}