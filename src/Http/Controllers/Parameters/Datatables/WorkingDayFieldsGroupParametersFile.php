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
//                'mySelfEdit' => 'links.edit',
//                'mySelfSee' => 'links.see',
	            'name' => [
					'type' => 'flat',
		            'width' => '195px'
	            ],
	            'clientOperators' => [
					'type' => 'iterators.each',
		            'childParameters' => [
			            'type' => 'function',
			            'function' => 'getContracttypeName'
		            ],
		            'width' => '125px'
	            ],
	            'calculated_holiday_days' => 'flat',
	            'calculated_flexibility_days' => 'flat',
	            'calculated_rol_days' => 'flat'
            ]
        ];
	}
}