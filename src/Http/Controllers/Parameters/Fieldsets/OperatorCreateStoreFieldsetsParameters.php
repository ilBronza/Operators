<?php

namespace IlBronza\Operators\Http\Controllers\Parameters\Fieldsets;

use IlBronza\AccountManager\Http\Parameters\FieldsetsParameters\UserCreateSlimFieldsetsParameters;
use IlBronza\Category\Models\Category;
use IlBronza\Clients\Models\Client;
use IlBronza\Operators\Models\Employment;

use function array_keys;
use function config;
use function implode;

class OperatorCreateStoreFieldsetsParameters extends UserCreateSlimFieldsetsParameters
{
	public function _getFieldsetsParameters() : array
	{
		$result = parent::_getFieldsetsParameters();

		$oneCompany = Client::gpc()::getOwnerCompany();

		$result['base']['fields']['client'] = [
			'type' => 'select',
			'label' => 'Azienda',
			'multiple' => false,
			'default' => $oneCompany->getKey(),
			'list' => $this->getPossibleClientsList(),
			'rules' => 'string|required|in:' . implode(',', array_keys($this->getPossibleClientsList())),
			'relation' => 'clients'
		];

		$result['base']['fields']['employment'] = [
			'type' => 'select',
			'label' => 'Impiego',
			'multiple' => false,
			'list' => $this->getPossibleEmploymentList(),
			'rules' => 'string|required|in:' . implode(',', array_keys($this->getPossibleEmploymentList())),
			'relation' => 'employments'
		];

		return $result;
	}

	public function getPossibleClientsList()
	{
		$category = Category::getProjectClassName()::where('name', 'Fornitore Videoservizi')->first();

		return config('clients.models.client.class')::byGeneralCategory($category)->orderBy('name')->get()->pluck('name', 'id')->toArray();
	}

	public function getPossibleEmploymentList()
	{
		return Employment::gpc()::getSelfPossibleValuesArray();
	}
}
