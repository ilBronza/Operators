<?php

namespace IlBronza\Operators\Http\Controllers\Parameters\Datatables;

use IlBronza\Operators\Models\Sellables\Contracttype;
use IlBronza\Products\Providers\Helpers\RowsHelpers\CostsFieldsGroupParametersFile;

class ContracttypeFieldsGroupParametersFile extends CostsFieldsGroupParametersFile
{
	static function getFieldsGroup() : array
	{
		return [
			'translationPrefix' => 'operators::fields',
			'fields' => static::addStandardCostsFieldsByModelPlusDelete(
				[
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
					'notify_when_sellable_supplier_is_overlying' => 'editor.toggle',
				],
				Contracttype::gpc()::make()
			)
		];
	}
}
