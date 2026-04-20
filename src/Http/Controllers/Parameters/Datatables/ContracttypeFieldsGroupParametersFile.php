<?php

namespace IlBronza\Operators\Http\Controllers\Parameters\Datatables;

use IlBronza\Datatables\Providers\FieldsGroupParametersFile;
use IlBronza\Operators\Models\Sellables\Contracttype;
use IlBronza\Products\Providers\Helpers\Sellables\SellablePriceDatatableFieldsHelper;

class ContracttypeFieldsGroupParametersFile extends FieldsGroupParametersFile
{
	static function getFieldsGroup() : array
	{
		$result = [
			'translationPrefix' => 'operators::fields',
			'fields' => [
				'mySelfPrimary' => 'primary',
				'mySelfEdit' => 'links.edit',
				'mySelfSee' => 'links.see',
				'name' => [
					'type' => 'flat',
					'order' => [
						'priority' => 100
					]
				],
				//				'slug' => 'flat',
				'operators_count' => 'flat',
				'description' => 'flat',
				'istat_code' => 'editor.text',
				'hex_rgba' => 'editor.color',

			]
		];

		if (config('operators.manageCosts'))
			$result['fields'] = array_merge(
				$result['fields'], 
				SellablePriceDatatableFieldsHelper::getFieldsByModel(
				Contracttype::gpc()::make()
			)
			);

		$result['mySelfDelete'] = 'links.delete';

		return $result;
	}
}