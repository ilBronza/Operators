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
				'forcedValidClientOperator' => 'links.edit',
				'name' => [
					'type' => 'flat',
					'mainHeader' => [
						'label' => 'Nome'
					],
					'order' => [
						'priority' => 100
					]
				],
				//				'valid_client_operator_contract' => [
				//					'type' => 'flat',
				//					'width' => '125px'
				//				],
				//				'holidays_reset_date' => [
				//					'type' => 'dates.date',
				//					'visible' => false,
				//				],
				//				'flexibility_reset_date' => [
				//					'type' => 'dates.date',
				//					'visible' => false,
				//				],
				//				'rol_reset_date' => [
				//					'type' => 'dates.date',
				//					'visible' => false,
				//				],
				'calculated_holiday_days' => [
					'type' => 'flat',
					'mainHeader' => [
						'label' => 'ferie',
						'colspan' => 3
					],
					'suffix' => 'h',
					'width' => '75px'
				],
				'holidays_reset_date' => [
					'type' => 'dates.date',
					'width' => '8em'
				],
				'holidays_reset' => [
					'type' => 'numbers.number2',
				],
				'calculated_flexibility_days' => [
					'type' => 'flat',
					'mainHeader' => [
						'label' => 'flex',
						'colspan' => 3
					],
					'suffix' => 'h',
					'width' => '55px'
				],
				'flexibility_reset_date' => [
					'type' => 'dates.date',
					'width' => '8em'
				],
				'flexibility_reset' => [
					'type' => 'numbers.number2',
				],
				'calculated_rol_days' => [
					'type' => 'flat',
					'mainHeader' => [
						'label' => 'rol',
						'colspan' => 3
					],
					'suffix' => 'h',
				],
				'rol_reset_date' => [
					'type' => 'dates.date',
				],
				'rol_reset' => [
					'type' => 'numbers.number2',
				],
				'calculated_bb_days' => [
					'type' => 'flat',
					'mainHeader' => [
						'label' => 'bb',
						'colspan' => 3
					],
					'suffix' => 'h',
				],
				'bb_reset_date' => [
					'type' => 'dates.date',
					'width' => '8em'
				],
				'bb_reset' => [
					'type' => 'numbers.number2',
				],
			]
		];
	}
}