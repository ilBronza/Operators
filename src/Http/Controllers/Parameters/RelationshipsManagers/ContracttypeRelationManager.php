<?php

namespace IlBronza\Operators\Http\Controllers\Parameters\RelationshipsManagers;

use IlBronza\CRUD\Providers\RelationshipsManager\RelationshipsManager;

class ContracttypeRelationManager Extends RelationshipsManager
{
	public  function getAllRelationsParameters() : array
	{
		return [
			'show' => [
				'relations' => [
					'operatorContracttypes' => [
						'controller' => config('operators.models.operatorContracttype.controllers.index'),
						'elementGetterMethod' => 'getRelatedFullOperatorContracttypes'
					],
					'operators' => [
						'controller' => config('operators.models.operator.controllers.index'),
						'elementGetterMethod' => 'getRelatedFullOperators'
					]
				]
			]
		];
	}
}