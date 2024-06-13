<?php

namespace IlBronza\Operators\Http\Controllers\Parameters\RelationshipsManagers;

use IlBronza\CRUD\Providers\RelationshipsManager\RelationshipsManager;

class OperatorContracttypeRelationManager Extends RelationshipsManager
{
	public  function getAllRelationsParameters() : array
	{
		return [
			'show' => [
				'relations' => [
					'operatorContracttypes' => config('operators.models.operatorContracttype.controllers.index'),
					'operators' => config('operators.models.operator.controllers.index')
				]
			]
		];
	}
}