<?php

namespace IlBronza\Operators\Http\Controllers\Parameters\Fieldsets;

use IlBronza\Form\Helpers\FieldsetsProvider\FieldsetParametersFile;

class OperatorEditUpdateFieldsetsParameters extends FieldsetParametersFile
{
	public function _getFieldsetsParameters() : array
	{
		$operator = $this->getModel();

		$result = [
			'card' => [
				'classes' => ['operator-card'],
				'showLegend' => false,
				'fields' => [],
				'fieldsets' => [
					'badge_image' => [
						'showLegend' => false,
						'fields' => [],
						'view' => [
							'name' => 'operators::operators._avatar',
							'parameters' => [
								'operator' => $this->getModel()
							]
						],
					],
					'contacts' => [
						'showLegend' => false,
						'fields' => [],
						'view' => [
							'name' => 'contacts::contacts._fetcherModelContacts',
							'parameters' => [
								'model' => $this->getModel()
							]
						],
						'width' => ['large']
					],
				],
				'width' => ['large']
			],

			'base' => [
				'translationPrefix' => 'operators::fields',
				'fields' => [
					'first_name' => ['text' => 'string|required|max:255'],
					'surname' => ['text' => 'string|required|max:255'],
					'fiscal_code' => ['text' => 'string|nullable|max:16'],
				],
				'width' => ['large']
			],
			'birth' => [
				'translationPrefix' => 'operators::fields',
				'fields' => [
					'sex' => [
						'type' => 'text',
						'readOnly' => 'true',
						'rules' => 'string|nullable|in:m,f',
					],
					'birth_date' => [
						'type' => 'date',
						'readOnly' => 'true',
						'rules' => 'date|nullable'
					],
					'birth_city' => [
						'type' => 'text',
						'readOnly' => 'true',
						'rules' => 'string|nullable|max:255',
					],
					'birth_zip' => [
						'type' => 'text',
						'readOnly' => 'true',
						'rules' => 'string|nullable|max:6',
					],
					'birth_province' => [
						'type' => 'text',
						'readOnly' => 'true',
						'rules' => 'string|nullable|max:2',
					],
					'birth_state' => [
						'type' => 'text',
						'readOnly' => 'true',
						'rules' => 'string|nullable|max:255',
					],
				],
				'width' => ['large']
			],
			'address' => [
				'translationPrefix' => 'operators::fields',
				'fields' => [
					'street' => ['text' => 'string|nullable|max:255'],
					'number' => ['text' => 'string|nullable|max:255'],
					'city' => ['text' => 'string|nullable|max:255'],
					'zip' => ['text' => 'string|nullable|max:255'],
					'province' => ['text' => 'string|nullable|max:255'],
					'town' => ['text' => 'string|nullable|max:255'],
					'region' => ['text' => 'string|nullable|max:255'],
					'state' => ['text' => 'string|nullable|max:255'],
				],
				'width' => ['large']
			],

			'documents' => [
				'fields' => [],
				'view' => [
					'name' => 'filecabinet::fetchers._modelDossiersByCategory',
					'parameters' => [
						'categorySlug' => 'documenti-anagrafici',
						'model' => $this->getModel()
					]
				],
				'width' => ['large']
			],
			'notes' => [
				'fields' => [],
				'view' => [
					'name' => 'notes::notes',
					'parameters' => [
						'modelInstance' => $this->getModel(),
					],
				],
				'width' => ['xlarge']
			],

		];

		return $result;
	}
}
