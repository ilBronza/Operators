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
				'calculated_cost_per_km' => 'editor.price',
				'calculated_cost_per_movimentation' => 'editor.price',
				'calculated_cost_per_day' => 'editor.price',

				'calculated_total_row_cost' => 'editor.price',
				'approved_total_row_cost' => 'editor.toggle',

				'calculated_total_row_revenue' => 'editor.price',
				'approved_total_row_revenue' => 'editor.toggle',
			]
		];
	}
}