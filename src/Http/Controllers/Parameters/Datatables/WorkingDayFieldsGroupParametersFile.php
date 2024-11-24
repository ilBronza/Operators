<?php

namespace IlBronza\Operators\Http\Controllers\Parameters\Datatables;

use IlBronza\Datatables\Providers\FieldsGroupParametersFile;

class WorkingDayFieldsGroupParametersFile extends FieldsGroupParametersFile
{
	static function getFieldsGroup() : array
	{
		return [
			'translationPrefix' => 'operators::fields',
			'fields' => [
				'mySelfPrimary' => 'primary',
				'name' => [
					'type' => 'flat',
					'order' => [
						'priority' => 100
					]
				],
				'clientOperators' => [
					'type' => 'iterators.each',
					'childParameters' => [
						'type' => 'function',
						'function' => 'getContracttypeName'
					],
					'width' => '125px'
				],
				'holidays_reset_date' => [
					'type' => 'editor.dates.date',
					'visible' => false
				],
				'flexibility_reset_date' => [
					'type' => 'editor.dates.date',
					'visible' => false
				],
				'rol_reset_date' => [
					'type' => 'editor.dates.date',
					'visible' => false
				],
				'calculated_holiday_days' => 'dates.daysToString',
				'calculated_flexibility_days' => 'dates.daysToString',
				'calculated_rol_days' => 'dates.daysToString',
				'calculated_bb_days' => 'dates.daysToString'
			]
		];
	}
}