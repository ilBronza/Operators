<?php

namespace IlBronza\Operators\Http\Controllers\Parameters\RelationshipsManagers;

use IlBronza\CRUD\Providers\RelationshipsManager\RelationshipsManager;

use function config;

class ContracttypeRelationManager Extends RelationshipsManager
{
	public  function getAllRelationsParameters() : array
	{
		return [
			'show' => [
				'relations' => [
					'operatorContracttypes' => [
						'translatedTitle' => __('operators::operators.princingTable'),
						'controller' => config('operators.models.operatorContracttype.controllers.index'),
						//OperatorContracttypeByContracttypeFieldsGroupParametersFile
						'fieldsGroupsParametersFile' => config('operators.models.operatorContracttype.fieldsGroupsFiles.byContracttype'),
						'elementGetterMethod' => 'getRelatedFullOperatorContracttypes'
					],
//					'operators' => [
//						'controller' => config('operators.models.operator.controllers.index'),
//						'elementGetterMethod' => 'getRelatedFullOperators'
//					],
//					'sellables' => config('products.models.sellable.controllers.index'),
					// 'quotationrows' => [
					// 	'controller' => config('products.models.quotationrow.controllers.index'),
					// 	'elementGetterMethod' => 'getRelatedQuotationrows'
					// ]
//					'quotations' => [
//						'controller' => config('products.models.quotation.controllers.index'),
//						'elementGetterMethod' => 'getRelatedQuotations'
//					]
				]
			]
		];
	}
}