<?php

namespace IlBronza\Operators\Http\Controllers\Parameters\Fieldsets;

use IlBronza\Form\Helpers\FieldsetsProvider\FieldsetParametersFile;

class ClientOperatorCreateStoreFieldsetsParameters extends FieldsetParametersFile
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
        return [
            'base' => [
                'translationPrefix' => 'operators::fields',
                'fields' => [
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

					//
//
//
                    'social_security_institution' => ['text' => 'string|required|max:255'],
					'social_security_code' => ['text' => 'string|nullable|max:255'],



					'status' => ['text' => 'string|nullable|max:255'],
					'sogg_iva' => ['text' => 'string|nullable|max:255'],
					'enpals' => ['text' => 'string|nullable|max:255'],
					'azienda' => ['text' => 'string|nullable|max:255'],
					'livello' => ['text' => 'string|nullable|max:255'],
					'iscr_liste' => ['text' => 'string|nullable|max:255'],


					'started_at' => ['date' => 'date|nullable'],
					'ended_at' => ['date' => 'date|nullable'],


					'level' => ['text' => 'string|nullable|max:255'],
//                    'istat_code' => ['text' => 'string|nullable|max:255'],
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
                'width' => ["1-3@l", '1-2@m']
            ]
        ];
    }
}
