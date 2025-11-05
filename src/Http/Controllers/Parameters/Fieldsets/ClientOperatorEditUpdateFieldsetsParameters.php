<?php

namespace IlBronza\Operators\Http\Controllers\Parameters\Fieldsets;

use IlBronza\Form\Helpers\FieldsetsProvider\FieldsetParametersFile;

use function config;

class ClientOperatorEditUpdateFieldsetsParameters extends FieldsetParametersFile
{
	//    public function getRolesArray() : array
	//    {
	//        return Role::all()->pluck('name', 'id')->toArray();
	//    }
	//
	//    public function getModelsArray() : array
	//    {
	//        return config('schedules.applicableTo');
	//    }

	public function _getFieldsetsParameters() : array
	{
		$result = [
			'base' => [
				'translationPrefix' => 'operators::fields',
				'fields' => [
					'operator_id' => [
						'readOnly' => true,
						'type' => 'select',
						'select2' => false,
						'multiple' => false,
						'rules' => 'string|nullable|exists:' . config('operators.models.operator.table') . ',id',
						'relation' => 'operator'
					],
					'employment_id' => [
						'type' => 'select',
						'multiple' => false,
						'rules' => 'string|nullable',
						'relation' => 'employment'
					],

					'client_id' => [
						'type' => 'select',
						'multiple' => false,
						'rules' => 'string|nullable',
						'relation' => 'client'
					],

					'contracttype_id' => [
						'type' => 'select',
						'multiple' => false,
						'rules' => 'string|nullable',
						'relation' => 'contracttype'
					],

					'started_at' => ['date' => 'date|nullable'],
					'ended_at' => ['date' => 'date|nullable'],

				],
				'width' => ["1-3@l", '1-2@m']
			],
			'costs' => [
				'translationPrefix' => 'operators::fields',
				'fields' => [
					'cost_company_day' => ['number' => 'numeric|nullable'],
					'cost_gross_day' => ['number' => 'numeric|nullable'],
					'operator_neat_day' => ['number' => 'numeric|nullable'],
					'cost_charge_coefficient' => ['number' => 'numeric|nullable'],
				],
				'width' => ['1-3@l', '1-2@m']
			],
			'datesReset' => [
				'translationPrefix' => 'operators::fields',
				'fields' => [
					'holidays_reset_date' => ['date' => 'date|nullable'],
					'flexibility_reset_date' => ['date' => 'date|nullable'],
					'rol_reset_date' => ['date' => 'date|nullable'],

				],
				'width' => ['1-3@l', '1-2@m']
			]
		];

		if(config('operators.manageCosts') !== true)
			unset($result['costs']);

		if(config('operators.manageDaysCalendar') !== true)
			unset($result['datesReset']);

		return $result;
	}
}
