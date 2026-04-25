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
					'operator' => config('operators.models.operator.controllers.show'),
					'contracttype' => config('operators.models.contracttype.controllers.show'),
				]
			]
		];
	}
}