<?php

namespace IlBronza\Operators\Http\Controllers\Parameters\Datatables;

use IlBronza\Datatables\Providers\FieldsGroupParametersFile;

class ClientOperatorByOperatorFieldsGroupParametersFile extends FieldsGroupParametersFile
{
	static function getFieldsGroup() : array
	{
		$result = [
			'translationPrefix' => 'operators::fields',
			'fields' => [
				'mySelfPrimary' => 'primary',
				'mySelfEdit' => 'links.edit',
				'mySelfSee' => 'links.see',

				'client' => 'relations.belongsTo',

				'employment' => 'relations.belongsTo',
				'contracttype' => 'relations.belongsTo',

				'started_at' => [
					'type' => 'dates.date',
					'order' => [
						'priority' => 100,
						'type' => 'DESC'
					]
				],
				'ended_at' => [
					'type' => 'dates.date',
					'order' => [
						'priority' => 90,
						'type' => 'DESC'
					]
				],

			]
		];

		if(app('courses'))
			$result['fields']['clientOperatorResponsibilities'] = [
				'type' => 'iterators.each',
				'childParameters' => [
					'type' => 'flat',
					'property' => 'responsibility_id',
				]
			];

		$result['fields']['mySelfDelete'] = 'links.delete';

		return $result;
	}
}