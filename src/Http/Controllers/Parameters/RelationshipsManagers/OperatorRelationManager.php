<?php

namespace IlBronza\Operators\Http\Controllers\Parameters\RelationshipsManagers;

use IlBronza\CRUD\Providers\RelationshipsManager\RelationshipsManager;

use function config;

class OperatorRelationManager Extends RelationshipsManager
{
	public  function getAllRelationsParameters() : array
	{
		$relations = [];

		$relations['operatorContracttypes'] = [
			'controller' => config('operators.models.operatorContracttype.controllers.index'),
			'fieldsGroups' => [
				'base' => [
					'translationPrefix' => 'operators::fields',
					'fields' =>
						[
							'mySelfPrimary' => 'primary',
							'contracttype.name' => 'flat',

							'internal_approval_rating' => 'flat',
							'level' => 'flat',

							'cost_company_day' => 'flat',
							'cost_gross_day' => 'flat',
							'cost_neat_day' => 'flat',

							'mySelfDelete' => 'links.delete'
						]
				]
			]
		];
		$relations['clientOperators'] = config('operators.models.clientOperator.controllers.index');

//		$relations['contracttypes'] = config('operators.models.contracttype.controllers.index');

		if(config('products.sellables.enabled', false))
			$relations['sellableSuppliers'] = [
				'controller' => config('products.models.sellableSupplier.controllers.index'),
				'elementGetterMethod' => 'getSellableSuppliersBySupplier'
			];

		if(config('filecabinet.enabled', false))
			$relations['dossiers'] = config('filecabinet.models.dossier.controllers.index');


		//		if(config('products.sellables.enabled', false))
//			$relations['sellables'] = [
//				'controller' => config('products.models.sellable.controllers.index'),
//				'elementGetterMethod' => 'getSellablesBySupplier'
//			];

		if(config('contacts.enabled', false))
			$relations['contacts'] = config('contacts.models.contact.controllers.index');

		if(config('addresses.enabled', false))
		{
//			$relations['address'] = config('addresses.models.address.controllers.show');
			$relations['addresses'] = config('addresses.models.address.controllers.index');
		}

		if(config('products.sellables.enabled', false))
			$relations['sellables'] = [
				'controller' => config('products.models.sellable.controllers.index'),
				'elementGetterMethod' => 'getSellablesBySupplier'
			];

		if(config('products.sellables.enabled', false))
			$relations['supplier'] = config('products.models.supplier.controllers.show');

		if(config('filecabinet.enabled', false))
			$relations['dossiers'] = [
				'controller' => config('filecabinet.models.dossier.controllers.index'),
				'elementGetterMethod' => 'getRelatedDossiersCollection'
			];

		$relations['paymenttypes'] = config('payments.models.paymenttype.controllers.index');
		$relations['user'] = config('accountmanager.models.user.controllers.show');

		if(config('payments.enabled'))
			$relations['paymenttypes'] = config('payments.models.paymenttype.controllers.index');

		//		 if(config('products.sellables.enabled'))
		//		 	$relations['projects'] = config('products.models.project.controllers.index');

		//		 if(config('products.sellables.enabled'))
		//		 	$relations['quotations'] = [
		//		 		'controller' => config('products.models.quotation.controllers.index'),
		//		 		'elementGetterMethod' => 'getQuotationsBySupplier'
		//		 	];


		return [
			'show' => [
				'relations' => $relations
			]
		];
	}
}