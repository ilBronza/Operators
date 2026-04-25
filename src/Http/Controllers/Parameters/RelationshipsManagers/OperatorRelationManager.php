<?php

namespace IlBronza\Operators\Http\Controllers\Parameters\RelationshipsManagers;

use IlBronza\CRUD\Providers\RelationshipsManager\RelationshipsManager;
use IlBronza\Operators\Models\Sellables\Contracttype;
use IlBronza\Products\Models\Interfaces\SellableItemInterface;
use IlBronza\Products\Providers\Helpers\Sellables\SellablePriceDatatableFieldsHelper;
use function config;

class OperatorRelationManager extends RelationshipsManager
{
	public function getAllRelationsParameters() : array
	{
		$relations = [];

		$contracttypeFields = [
			'mySelfPrimary' => 'primary',
			'mySelfEdit' => 'links.edit',
			'contracttype.name' => 'flat',

			'internal_approval_rating' => 'editor.numeric',
		];

		$contracttypeModel = Contracttype::gpc()::make();

		if ($contracttypeModel instanceof SellableItemInterface)
			$contracttypeFields = array_merge(
				$contracttypeFields, 
				SellablePriceDatatableFieldsHelper::getFieldsByModel(
					$contracttypeModel
				)
			);

		$contracttypeFields['mySelfDelete'] = 'links.delete';

		$relations['operatorContracttypes'] = [
			'controller' => config('operators.models.operatorContracttype.controllers.index'),
			'translatedTitle' => __('operators::contracttypes.otherContracttypes'),
			'hasCreateButton' => true,
			'fieldsGroups' => [
				'base' => [
					'translationPrefix' => 'operators::fields',
					'fields' => $contracttypeFields
				]
			]
		];

		$relations['clientOperators'] = [
			'controller' => config('operators.models.clientOperator.controllers.index'),
			'hasCreateButton' => true,
			'fieldsGroups' => [
				//ClientOperatorByOperatorFieldsGroupParametersFile
				'base' => config('operators.models.clientOperator.fieldsGroupsFiles.byOperator')::getTracedFieldsGroup()
			]
		];

		//		$relations['contracttypes'] = config('operators.models.contracttype.controllers.index');

		//IlBronza\Products\Http\Controllers\SellableSupplier\SellableSupplierIndexController
		// if (config('products.sellables.enabled', false))
		// 	$relations['sellableSuppliers'] = [
		// 		'controller' => config('products.models.sellableSupplier.controllers.index'),
		// 		'elementGetterMethod' => 'getSellableSuppliersBySupplier'
		// 	];
		//
		//				if(config('products.sellables.enabled', false))
		//					$relations['sellables'] = [
		//						'controller' => config('products.models.sellable.controllers.index'),
		//						'elementGetterMethod' => 'getSellablesBySupplier'
		//					];

		//		if (config('contacts.enabled', false))
		//			$relations['contacts'] = config('contacts.models.contact.controllers.index');

		if (config('addresses.enabled', false))
		{
			//			$relations['address'] = config('addresses.models.address.controllers.show');
			$relations['addresses'] = config('addresses.models.address.controllers.index');
		}

		//		if (config('products.sellables.enabled', false))
		//			$relations['sellables'] = [
		//				'controller' => config('products.models.sellable.controllers.index'),
		//				'elementGetterMethod' => 'getSellablesBySupplier'
		//			];
		//
		//		if (config('products.sellables.enabled', false))
		//			$relations['supplier'] = config('products.models.supplier.controllers.show');

		if (config('filecabinet.enabled', false))
		{
			if(method_exists($this->getModel(), 'dossiers'))
				$relations['dossiers'] = [
					'controller' => config('filecabinet.models.dossier.controllers.index'),
					'elementGetterMethod' => 'getRelatedDossiersCollection',
					'buttonsMethods' => [
						'getAssociateButton',
					]
				];
		}

		//		$relations['paymenttypes'] = config('payments.models.paymenttype.controllers.index');
		$relations['user'] = config('accountmanager.models.user.controllers.show');

		//		if (config('payments.enabled'))
		//			$relations['paymenttypes'] = config('payments.models.paymenttype.controllers.index');

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