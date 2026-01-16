<?php

namespace IlBronza\Operators\Http\Controllers\Parameters\Fieldsets;

class EmploymentEditUpdateFieldsetsParameters extends EmploymentCreateStoreFieldsetsParameters
{
	public function _getFieldsetsParameters() : array
	{
		return [
			'base' => [
				'translationPrefix' => 'operators::fields',
				'fields' => [
					'name' => ['text' => 'string|required|max:255'],
					'slug' => ['text' => 'string|nullable|max:255'],
					'label_text' => ['text' => 'string|nullable|max:16'],
					'hex_rgba' => ['color' => 'string|nullable|max:8'],
				],
				'width' => ["1-3@l", '1-2@m']
			]
		];
	}

}
