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
				'name' => 'flat',
				'slug' => 'flat',
				'operators_count' => 'flat',
				'description' => 'flat',
				'istat_code' => 'flat',

				'cost_company_day' => 'flat',
				'cost_gross_day' => 'flat',
				'operator_neat_day' => 'flat',
				'cost_charge_coefficient' => 'flat',

				'mySelfDelete' => 'links.delete'
			]
		];
	}
}