<?php

namespace IlBronza\Operators\Http\Controllers\Parameters\Datatables;

use IlBronza\Datatables\Providers\FieldsGroupParametersFile;

class OperatorBadgeFieldsGroupParametersFile extends FieldsGroupParametersFile
{
	static function getFieldsGroup() : array
	{
		return [
			'translationPrefix' => 'operators::fields',
			'fields' => [
				'mySelfPrimary' => 'primary',
				'mySelfEdit' => 'links.edit',
				'mySelfSee' => 'links.see',
				'name' => [
					'type' => 'flat',
					'order' => [
						'priority' => 100,
					],
				],
				'operator.user.userdata.first_name' => 'flat',
				'operator.user.userdata.surname' => 'flat',
				'active' => 'editor.toggle',
				'valid_from' => 'dates.date',
				'valid_to' => 'dates.date',
				'mySelfDelete' => 'links.delete',
			],
		];
	}
}
