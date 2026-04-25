<?php

namespace IlBronza\Operators\Http\Controllers\Parameters\Datatables;

use IlBronza\Operators\Models\Contracttype;

class OperatorContracttypeByContracttypeFieldsGroupParametersFile extends OperatorContracttypeRelatedFieldsGroupParametersFile
{
	static function getFieldsGroup() : array
	{
		$result = [
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

			]
		];

		$result = static::addCostsFieldsetByModel(
			$result,
			Contracttype::gpc()::make()
		);

		$result['mySelfDelete'] = 'links.delete';

		return $result;
	}
}