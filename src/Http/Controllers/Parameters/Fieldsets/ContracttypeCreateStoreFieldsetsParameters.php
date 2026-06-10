<?php

namespace IlBronza\Operators\Http\Controllers\Parameters\Fieldsets;

use IlBronza\Form\Helpers\FieldsetsProvider\FieldsetParametersFile;
use IlBronza\Operators\Models\Sellables\Contracttype;
use IlBronza\Products\Models\Interfaces\SellableItemInterface;
use IlBronza\Products\Providers\Helpers\Sellables\SellablePriceFormFieldsHelper;
use function config;

class ContracttypeCreateStoreFieldsetsParameters extends FieldsetParametersFile
{
	public function getRolesArray() : array
	{
		return Role::all()->pluck('name', 'id')->toArray();
	}

	public function getModelsArray() : array
	{
		return config('schedules.applicableTo');
	}

	public function _getFieldsetsParameters() : array
	{
		$result = [
			'base' => [
				'translationPrefix' => 'operators::fields',
				'fields' => [
					'name' => ['text' => 'string|required|max:255'],
					'slug' => ['text' => 'string|nullable|max:255'],
					'description' => ['text' => 'string|nullable|max:255'],
					'istat_code' => ['text' => 'string|nullable|max:255'],
					'hex_rgba' => ['color' => 'string|nullable|max:8'],
				],
				'width' => ["1-3@l", '1-2@m']
			],
		];

		$model = $this->getModel();

		if(\Auth::user()->hasRole('economics'))
			if ($model instanceof SellableItemInterface)
				$result['costs'] = [
						'translationPrefix' => 'operators::fields',
						'fields' => SellablePriceFormFieldsHelper::getFieldsByModel($model),
						'width' => ["1-3@l", '1-2@m']
					];


		return $result;
	}
}
