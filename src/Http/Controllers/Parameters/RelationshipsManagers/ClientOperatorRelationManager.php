<?php

namespace IlBronza\Operators\Http\Controllers\Parameters\RelationshipsManagers;

use IlBronza\CRUD\Providers\RelationshipsManager\RelationshipsManager;

use function config;

class ClientOperatorRelationManager extends RelationshipsManager
{
	public function getAllRelationsParameters() : array
	{
		return [
			'show' => [
				'relations' => [
					'workingCounters' => [
						'controller' => config('operators.models.workingCounter.controllers.index'),
						'hasCreateButton' => true,
						'fieldsGroups' => [
							'base' => config('operators.models.workingCounter.fieldsGroupsFiles.byClientOperator')::getTracedFieldsGroup(),
						],
					],
				],
			],
		];
	}
}
