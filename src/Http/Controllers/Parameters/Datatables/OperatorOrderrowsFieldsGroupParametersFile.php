<?php

namespace IlBronza\Operators\Http\Controllers\Parameters\Datatables;

use IlBronza\Datatables\Providers\FieldsGroupParametersFile;

class OperatorOrderrowsFieldsGroupParametersFile extends FieldsGroupParametersFile
{
	static function getFieldsGroup() : array
	{
		return [
			'translationPrefix' => 'products::fields',
			'fields' => 
				[
					'mySelfPrimary' => 'primary',
					'mySelfEdit' => 'links.edit',

					'sellable.name' => 'flat',

					'description' => [
						'type' => 'flat',
						'width' => '20em'
					],

				'starts_at' => 'dates.date',
				'ends_at' => 'dates.date',
				'quantity' => [
					'type' => 'editor.numeric',
					'refreshRow' => true,
				],
			]
		];
	}
}