<?php

namespace IlBronza\Operators\Http\Controllers\Parameters\Fieldsets;

use IlBronza\Form\Helpers\FieldsetsProvider\FieldsetParametersFile;

use function config;

class OperatorContracttypeCreateStoreFieldsetsParameters extends FieldsetParametersFile
{
	public function getRolesArray() : array
	{
		return Role::all()->pluck('name', 'id')->toArray();
	}

	public function getModelsArray() : array
	{
		return config('schedules.applicableTo');
	}

	public function _getFieldsetsParameters() : array
	{
		return [
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
					'contracttype_id' => [
						'readOnly' => true,
						'type' => 'select',
						'select2' => false,
						'multiple' => false,
						'rules' => 'string|nullable|exists:' . config('operators.models.contracttype.table') . ',id',
						'relation' => 'contracttype'
					],
					'level' => ['text' => 'string|nullable|max:255'],
					'internal_approval_rating' => ['text' => 'string|nullable|max:255'],
					'name' => [
						'type' => 'text',
						'readOnly' => true,
						'rules' => 'string|required|max:255'
					],
				],
				'width' => ["1-3@l", '1-2@m']
			],
			'costs' => [
				'translationPrefix' => 'operators::fields',
				'fields' => [
					'cost_company_day' => ['number' => 'numeric|nullable'],
					'cost_gross_day' => ['number' => 'numeric|nullable'],
					'operator_neat_day' => ['number' => 'numeric|nullable'],
					//                    'cost_charge_coefficient' => ['number' => 'numeric|nullable'],
				],
				'width' => ["1-3@l", '1-2@m']
			]
		];
	}
}
