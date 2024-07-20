<?php

namespace IlBronza\Operators\Http\Controllers\Parameters\RelationshipsManagers;

use IlBronza\CRUD\Providers\RelationshipsManager\RelationshipsManager;

class OperatorRelationManager Extends RelationshipsManager
{
	public  function getAllRelationsParameters() : array
	{
		$relations = [
			// 'contracttypes' => config('operators.models.contracttype.controllers.index'),
			// 'user' => config('accountmanager.models.user.controllers.show'),
		];

		// if(config('products.sellables.enabled', false))
		// 	$relations['sellableSuppliers'] = [
		// 		'controller' => config('products.models.sellableSupplier.controllers.index'),
		// 		'elementGetterMethod' => 'getSellableSuppliersBySupplier'
		// 	];

		if(config('products.sellables.enabled', false))
			$relations['sellables'] = [
				'controller' => config('products.models.sellable.controllers.index'),
				'elementGetterMethod' => 'getSellablesBySupplier'
			];

		// if(config('contacts.enabled', false))
		// 	$relations['contacts'] = [
		// 		'controller' => config('contacts.models.contact.controllers.index'),
		// 		'elementGetterMethod' => 'getRelatedContacts'
		// 	];

		// if(config('products.sellables.enabled', false))
		// 	$relations['sellables'] = [
		// 		'controller' => config('products.models.sellable.controllers.index'),
		// 		'elementGetterMethod' => 'getSellablesBySupplier'
		// 	];

		// if(config('products.sellables.enabled', false))
		// 	$relations['supplier'] = config('products.models.supplier.controllers.show');

		// if(config('filecabinet.enabled', false))
		// 	$relations['dossiers'] = config('filecabinet.models.dossier.controllers.index');

		// if(config('filecabinet.enabled', false))
		// 	$relations['dossiers'] = config('filecabinet.models.dossier.controllers.index');

		// if(config('payments.enabled'))
		// 	$relations['paymenttypes'] = config('payments.models.paymenttype.controllers.index');

		// if(config('products.sellables.enabled'))
		// 	$relations['projects'] = config('products.models.project.controllers.index');

		// if(config('products.sellables.enabled'))
		// 	$relations['quotations'] = [
		// 		'controller' => config('products.models.quotation.controllers.index'),
		// 		'elementGetterMethod' => 'getQuotationsBySupplier'
		// 	];


		return [
			'show' => [
				'relations' => $relations
			]
		];
	}
}