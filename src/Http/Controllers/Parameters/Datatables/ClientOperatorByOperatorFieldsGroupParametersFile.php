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
			$result['fields']['responsibilities'] = 'relations.belongsToMany';

		$result['fields']['mySelfDelete'] = 'links.delete';

		return $result;
	}
}