<?php

namespace IlBronza\Operators\Http\Controllers\Parameters\Fieldsets;

use IlBronza\Form\Helpers\FieldsetsProvider\FieldsetParametersFile;

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
                    'name' => ['text' => 'string|required|max:255'],
                    'slug' => ['text' => 'string|nullable|max:255'],
                    'description' => ['text' => 'string|nullable|max:255'],
                    'istat_code' => ['text' => 'string|nullable|max:255'],
                ],
                'width' => ["1-3@l", '1-2@m']
            ],
            'costs' => [
                'translationPrefix' => 'operators::fields',
                'fields' => [
                    'cost_company_hour' => ['number' => 'numeric|nullable'],
                    'cost_gross_hour' => ['number' => 'numeric|nullable'],
                    'cost_neat_hour' => ['number' => 'numeric|nullable'],
                    'cost_company_day' => ['number' => 'numeric|nullable'],
                    'cost_gross_day' => ['number' => 'numeric|nullable'],
                    'cost_neat_day' => ['number' => 'numeric|nullable'],
                    'cost_charge_coefficient' => ['number' => 'numeric|nullable'],
                ],
                'width' => ["1-3@l", '1-2@m']
            ]
        ];
    }
}
