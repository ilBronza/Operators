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
						'label' => 'ferie'
					],
					'suffix' => 'h',
					'width' => '75px'
				],
				'calculated_flexibility_days' => [
					'type' => 'flat',
					'mainHeader' => [
						'label' => 'flex'
					],
					'suffix' => 'h',
					'width' => '55px'
				],
				'calculated_rol_days' => [
					'type' => 'flat',
					'mainHeader' => [
						'label' => 'rol'
					],
					'suffix' => 'h',
					'width' => '55px'
				],
				'calculated_bb_days' => [
					'type' => 'flat',
					'mainHeader' => [
						'label' => 'bb'
					],
					'suffix' => 'h',
					'width' => '55px'
				]
			]
		];
	}
}