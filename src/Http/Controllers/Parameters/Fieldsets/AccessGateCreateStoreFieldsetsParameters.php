<?php

namespace IlBronza\Operators\Http\Controllers\Parameters\Fieldsets;

use IlBronza\Form\Helpers\FieldsetsProvider\FieldsetParametersFile;

class AccessGateCreateStoreFieldsetsParameters extends FieldsetParametersFile
{
	public function _getFieldsetsParameters() : array
	{
		return [
			'base' => [
				'translationPrefix' => 'operators::fields',
				'fields' => [
					'name' => ['text' => 'string|required|max:255'],
					'code' => ['text' => 'string|required|max:64'],
					'description' => ['text' => 'string|nullable|max:65535'],
				],
				'width' => ["1-3@l", '1-2@m']
			]
		];
	}
}
