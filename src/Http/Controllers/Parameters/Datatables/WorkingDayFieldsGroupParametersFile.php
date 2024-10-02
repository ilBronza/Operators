<?php

namespace IlBronza\Operators\Http\Controllers\Parameters\Datatables;

use IlBronza\Datatables\Providers\FieldsGroupParametersFile;

class WorkingDayFieldsGroupParametersFile extends FieldsGroupParametersFile
{
	static function getFieldsGroup() : array
	{
		return [
            'translationPrefix' => 'operators::fields',
            'fields' => 
            [
                'mySelfPrimary' => 'primary',
	            'name' => [
					'type' => 'flat',
	            ],
	            'clientOperators' => [
					'type' => 'iterators.each',
		            'childParameters' => [
			            'type' => 'function',
			            'function' => 'getContracttypeName'
		            ],
		            'width' => '125px'
	            ],
	            'calculated_holiday_days' => 'dates.daysToString',
	            'calculated_flexibility_days' => 'dates.daysToString',
	            'calculated_rol_days' => 'dates.daysToString',
	            'calculated_bb_days' => 'dates.daysToString'
            ]
        ];
	}
}