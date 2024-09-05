<?php

namespace IlBronza\Operators\Http\Controllers\Parameters\RelationshipsManagers;

use IlBronza\Clients\Http\Parameters\Datatables\ClientRelatedFieldsGroupParametersFile;
use IlBronza\CRUD\Providers\RelationshipsManager\RelationshipsManager;

use function config;

class EmploymentRelationManager Extends RelationshipsManager
{
	public  function getAllRelationsParameters() : array
	{
		$relations = [];


		$relations['operators'] = [
			'controller' => config('operators.models.operator.controllers.index'),
			'fieldsGroupsParametersFile' => config('operators.models.operator.fieldsGroupsFiles.byEmployment'),
			'elementGetterMethod' => 'getRelatedOperators'
		];

		$relations['clients'] = [
			'controller' => config('clients.models.client.controller'),
			'fieldsGroupsParametersFile' => ClientRelatedFieldsGroupParametersFile::class
		];

		return [
			'show' => [
				'relations' => $relations
			]
		];
	}
}