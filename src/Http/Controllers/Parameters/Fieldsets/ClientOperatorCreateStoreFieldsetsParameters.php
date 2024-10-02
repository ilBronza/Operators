<?php

namespace IlBronza\Operators\Http\Controllers\Parameters\Fieldsets;

use IlBronza\Form\Helpers\FieldsetsProvider\FieldsetParametersFile;

use function config;

class ClientOperatorCreateStoreFieldsetsParameters extends FieldsetParametersFile
{
	public function _getFieldsetsParameters() : array
	{
		return [
			'base' => [
				'translationPrefix' => 'operators::fields',
				'fields' => [
					'operator_id' => [
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

				],
				'width' => ["1-3@l", '1-2@m']
			]
		];
	}
}
