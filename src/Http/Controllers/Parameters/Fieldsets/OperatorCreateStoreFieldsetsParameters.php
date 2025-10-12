<?php

namespace IlBronza\Operators\Http\Controllers\Parameters\Fieldsets;

use IlBronza\AccountManager\Http\Parameters\FieldsetsParameters\UserCreateSlimFieldsetsParameters;
use IlBronza\Category\Models\Category;
use IlBronza\Clients\Models\Client;
use IlBronza\Operators\Models\Contracttype;
use IlBronza\Operators\Models\Employment;

use function array_keys;
use function config;
use function implode;

class OperatorCreateStoreFieldsetsParameters extends UserCreateSlimFieldsetsParameters
{
	public function _getFieldsetsParameters() : array
	{
		$result = parent::_getFieldsetsParameters();

		$owner = Client::gpc()::getOwnerCompany();

		$result['base']['fields']['client'] = [
			'type' => 'select',
			'label' => 'Azienda',
			'multiple' => false,
			'default' => $owner->getKey(),
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

		$result['base']['fields']['contracttype'] = [
			'type' => 'select',
			'label' => 'Mansione',
			'multiple' => false,
			'list' => $this->getPossibleContracttypeList(),
			'rules' => 'string|required|in:' . implode(',', array_keys($this->getPossibleContracttypeList())),
			'relation' => 'employments'
		];

		return $result;
	}

	public function getPossibleClientsList()
	{
		return config('clients.models.client.class')::asClient()->orderBy('name')->get()->pluck('name', 'id')->toArray();
	}

	public function getPossibleContracttypeList()
	{
		return Contracttype::gpc()::getSelfPossibleValuesArray();
	}

	public function getPossibleEmploymentList()
	{
		return Employment::gpc()::getSelfPossibleValuesArray();
	}
}
