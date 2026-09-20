<?php

namespace IlBronza\Operators\Http\Controllers\Parameters\Fieldsets;

use IlBronza\Form\Helpers\FieldsetsProvider\FieldsetParametersFile;
use IlBronza\Operators\Models\WorkingCounter;

class WorkingCounterCreateStoreFieldsetsParameters extends FieldsetParametersFile
{
	public function _getFieldsetsParameters() : array
	{
		$possibleTypes = WorkingCounter::getPossibleTypesArray();

		return [
			'base' => [
				'translationPrefix' => 'operators::fields',
				'fields' => [
					'operator_id' => [
						'type' => 'select',
						'multiple' => false,
						'rules' => 'string|required|exists:' . config('operators.models.operator.table') . ',id',
						'relation' => 'operator',
					],
					'client_operator_id' => [
						'type' => 'select',
						'multiple' => false,
						'rules' => 'string|nullable|exists:' . config('operators.models.clientOperator.table') . ',id',
						'relation' => 'clientOperator',
					],
					'type' => [
						'type' => 'select',
						'possibleValuesArray' => $possibleTypes,
						'multiple' => false,
						'rules' => 'string|required|max:16|in:' . implode(',', array_keys($possibleTypes)),
					],
					'amount' => ['number' => 'numeric|required'],
					'reset_date' => ['date' => 'date|required'],
					'expired_at' => ['datetime' => 'date|nullable|after_or_equal:reset_date'],
				],
				'width' => ['1-3@l', '1-2@m'],
			],
		];
	}
}
