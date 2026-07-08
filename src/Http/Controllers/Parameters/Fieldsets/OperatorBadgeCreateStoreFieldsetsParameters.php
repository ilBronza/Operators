<?php

namespace IlBronza\Operators\Http\Controllers\Parameters\Fieldsets;

use IlBronza\Form\Helpers\FieldsetsProvider\FieldsetParametersFile;

class OperatorBadgeCreateStoreFieldsetsParameters extends FieldsetParametersFile
{
	public function _getFieldsetsParameters() : array
	{
		return [
			'base' => [
				'translationPrefix' => 'operators::fields',
				'fields' => [
					'name' => [
						'type' => 'text',
						'rules' => 'string|required|max:64',
						'label' => 'Codice badge',
					],
					'operator_id' => [
						'type' => 'select',
						'multiple' => false,
						'rules' => 'string|nullable|exists:' . config('operators.models.operator.table') . ',id',
						'relation' => 'operator',
					],
					'active' => ['boolean' => 'boolean|nullable'],
					'valid_from' => ['date' => 'date|nullable'],
					'valid_to' => ['date' => 'date|nullable|after_or_equal:valid_from'],
					'notes' => ['textarea' => 'string|nullable'],
				],
				'width' => ["1-3@l", '1-2@m'],
			],
		];
	}
}
