<?php

namespace IlBronza\Operators\Http\Controllers\Parameters\Datatables;

use IlBronza\Datatables\Providers\FieldsGroupParametersFile;

class OperatorFieldsGroupParametersFile extends FieldsGroupParametersFile
{
	static function getFieldsGroup() : array
	{
		return [
			'translationPrefix' => 'operators::fields',
			'fields' => [
				'mySelfPrimary' => 'primary',
				'mySelfEdit' => 'links.edit',

				'user.userdata.surname' => [
					'type' => 'flat',
					'order' => [
						'priority' => 10
					],
				],

				'user.userdata.first_name' => [
					'type' => 'flat',
					'order' => [
						'priority' => 100
					],
				],

				'active' => [
					'type' => 'editor.toggle',
					'valueAsRowClass' => true,
					'width' => '5em'
				],

				'mySelfContacts.contacts' => [
					'type' => 'iterators.each',
					'childParameters' => [
						'type' => 'function',
						'function' => 'getFullString'
					],
					'width' => '250px'
				],

				'internal_approval_rating' => [
					'type' => 'flat',
					'visible' => false,
					'valueAsRowClassPrefix' => true,
					'valueAsRowClass' => true
				],

				'address.city' => 'flat',
				'address.province' => 'flat',

				'clients' => 'relations.belongsToMany',

				'employments' => 'relations.belongsToMany',
				'contracttypes' => 'relations.belongsToMany',

				'mySelfDelete' => 'links.delete'
			]
		];
	}
}