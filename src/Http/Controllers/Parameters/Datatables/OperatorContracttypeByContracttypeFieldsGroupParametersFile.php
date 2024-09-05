<?php

namespace IlBronza\Operators\Http\Controllers\Parameters\Datatables;

use IlBronza\Datatables\Providers\FieldsGroupParametersFile;

class OperatorContracttypeByContracttypeFieldsGroupParametersFile extends OperatorContracttypeRelatedFieldsGroupParametersFile
{
	static function getFieldsGroup() : array
	{
		$parameters = parent::getFieldsGroup();

		unset($parameters['fields']['contracttype.name']);

		return [
            'translationPrefix' => 'operators::fields',
            'fields' => 
            [
                'mySelfPrimary' => 'primary',
                'mySelfEdit' => 'links.edit',
                'mySelfSee' => 'links.see',
                'operator.user.userdata.first_name' => 'flat',
                'operator.user.userdata.surname' => 'flat',
				'operator.contracttypes' => [
					'type' => 'iterators.each',
					'childParameters' => [
						'type' => 'flat',
						'property' => 'name',
					]
				],

                'internal_approval_rating' => 'flat',
                'level' => 'flat',

                'cost_company_day' => 'flat',
                'cost_gross_day' => 'flat',
                'cost_neat_day' => 'flat',

                'mySelfDelete' => 'links.delete'
            ]
        ];
	}
}