<?php

namespace IlBronza\Operators\Http\Controllers\Parameters\Datatables;

class OperatorContracttypeByContracttypeFieldsGroupParametersFile extends OperatorContracttypeRelatedFieldsGroupParametersFile
{
	static function getFieldsGroup() : array
	{
		$parameters = parent::getFieldsGroup();

		unset($parameters['fields']['contracttype.name']);

		return [
			'translationPrefix' => 'operators::fields',
			'fields' => [
				'mySelfPrimary' => 'primary',
				'mySelfEdit' => 'links.edit',
				'mySelfSee' => 'links.see',
				'operator.user.userdata.first_name' => 'flat',
				'operator.user.userdata.surname' => 'flat',
				'operator.contracttypes' => [
					'type' => 'iterators.each',
					'childParameters' => [
						'type' => 'links.seeName',
					],
					'width' => '14em'
				],

				'operator.address.city' => [
					'type' => 'flat',
					'width' => '20em'
				],
				'operator.address.province' => 'flat',

				'internal_approval_rating' => 'flat',
				'level' => 'flat',

				'cost_company_day' => 'flat',
				'cost_gross_day' => 'flat',
				'operator_neat_day' => 'flat',

				'mySelfDelete' => 'links.delete'
			]
		];
	}
}