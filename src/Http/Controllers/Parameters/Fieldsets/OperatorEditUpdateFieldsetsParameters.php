<?php

namespace IlBronza\Operators\Http\Controllers\Parameters\Fieldsets;

use IlBronza\AccountManager\Http\Parameters\FieldsetsParameters\UserCreateSlimFieldsetsParameters;

use IlBronza\Category\Models\Category;

use IlBronza\Form\Helpers\FieldsetsProvider\FieldsetParametersFile;

use function array_keys;
use function config;
use function implode;

class OperatorEditUpdateFieldsetsParameters extends FieldsetParametersFile
{
	public function _getFieldsetsParameters() : array
	{
		$operator = $this->getModel();

		$paymentTypes = $operator->getPossiblePaymenttypesValuesArray();

		//		$documentsFields = [];
		//
		//		$documentsCategory = Category::where('slug', 'documenti-anagrafici')->first();
		//
		//		$forms = Form::getByDirectCategory($documentsCategory);
		//		$documentsDossiers = $operator->getDossiersByForms($forms);
		//
		//		foreach ($forms as $form)
		//		{
		//			if (! $documentDossier = $documentsDossiers->firstWhere('form_id', $form->id))
		//				$documentDossier = DossierCreatorHelper::createByForm($operator, $form);
		//
		//			$documentsFields[$form->getSlug()] = [
		//				'showLabel' => false,
		//				'type' => 'filecabinet::providers.formFields.dossierStatus',
		//				'dossier' => $documentDossier,
		//				'rules' => [],
		//				'formSlug' => $form->getSlug(),
		//			];
		//		}

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
							'name' => 'operators::operator.avatar',
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
						'width' => ['medium']
					],
				],
				'width' => ['medium']
			],

			'base' => [
				'translationPrefix' => 'operators::fields',
				'fields' => [
//					'company_site_slug' => [
//						'type' => 'select',
//						'multiple' => false,
//						'rules' => 'string|nullable|in:pd,mi',
//						'list' => [
//							'mi' => 'MI',
//							'pd' => 'PD'
//						]
//					],
					'first_name' => ['text' => 'string|required|max:255'],
					'surname' => ['text' => 'string|required|max:255'],
					'fiscal_code' => ['text' => 'string|required|max:16'],
					//					'slug' => ['text' => 'string|nullable|max:255'],
					//					'description' => ['text' => 'string|nullable|max:255'],
					//					'istat_code' => ['text' => 'string|nullable|max:255'],
				],
				'width' => ['medium']
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
				'width' => ['medium']
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
				'width' => ['medium']
			],

			//			'notes' => [
			//				'fields' => [],
			//				'view' => [
			//					'name' => 'notes::notes',
			//					'parameters' => [
			//						'modelInstance' => $this->getModel(),
			//				],
			//				'width' => ['large']
			//				//				'fields' => $documentsFields,
			//				//				'width' => ['large']
			//			],

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
				//				'fields' => $documentsFields,
				//				'width' => ['large']
			],
			//			'old_documents' => [
			//				'fields' => $documentsFields,
			//				'width' => ['large']
			//			],
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

			//			'costs' => [
			//				'translationPrefix' => 'operators::fields',
			//				'fields' => [
			//					'cost_company_hour' => ['number' => 'numeric|nullable'],
			//					'cost_gross_hour' => ['number' => 'numeric|nullable'],
			//					'cost_neat_hour' => ['number' => 'numeric|nullable'],
			//					'cost_company_day' => ['number' => 'numeric|nullable'],
			//					'cost_gross_day' => ['number' => 'numeric|nullable'],
			//					'cost_neat_day' => ['number' => 'numeric|nullable'],
			//					'cost_charge_coefficient' => ['number' => 'numeric|nullable'],
			//				],
			//				'width' => ["1-3@l", '1-2@m']
			//			],
			//			'payments' => [
			//				'translationPrefix' => 'operators::fields',
			//				'fields' => [
			//					'paymenttype_id' => [
			//						'type' => 'select',
			//						'multiple' => false,
			//						'rules' => 'string|nullable|in:' . implode(",", array_keys($paymentTypes)),
			//						'list' => $paymentTypes
			//					],
			//				],
			//			]
		];

		return $result;
	}
}
