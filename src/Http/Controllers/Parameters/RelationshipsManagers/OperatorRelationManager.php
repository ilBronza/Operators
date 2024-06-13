<?php

namespace IlBronza\Operators\Http\Controllers\Parameters\RelationshipsManagers;

use IlBronza\CRUD\Providers\RelationshipsManager\RelationshipsManager;

class OperatorRelationManager Extends RelationshipsManager
{
	public  function getAllRelationsParameters() : array
	{
		$relations = [
			'contracttypes' => config('operators.models.contracttype.controllers.index'),
			'user' => config('accountmanager.models.user.controllers.show'),
		];

		if(config('filecabinet.enabled', false))
			$relations['dossiers'] = config('filecabinet.models.dossier.controllers.index');

		return [
			'show' => [
				'relations' => $relations
			]
		];
	}
}