<?php

namespace IlBronza\Operators\Http\Controllers\Parameters\Fieldsets;

use IlBronza\AccountManager\Http\Parameters\FieldsetsParameters\UserCreateSlimFieldsetsParameters;

use IlBronza\Category\Models\Category;

use function array_keys;
use function config;
use function implode;

class OperatorCreateStoreFieldsetsParameters extends UserCreateSlimFieldsetsParameters
{
	public function getPossibleClientsList()
	{
		$category = Category::getProjectClassName()::where('name', 'Fornitore Videoservizi')->first();

		return config('clients.models.client.class')::byGeneralCategory($category)->orderBy('name')->get()->pluck('name', 'id')->toArray();
	}

	public function _getFieldsetsParameters() : array
	{
		$result = parent::_getFieldsetsParameters();

		$result['base']['fields']['client'] = [
			'type' => 'select',
			'multiple' => false,
			'list' => $this->getPossibleClientsList(),
			'rules' => 'string|nullable|in:' . implode(",", array_keys($this->getPossibleClientsList())),
			'relation' => 'clients'
		];

		return $result;
	}
}
