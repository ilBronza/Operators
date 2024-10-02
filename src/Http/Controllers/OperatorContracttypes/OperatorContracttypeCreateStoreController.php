<?php

namespace IlBronza\Operators\Http\Controllers\OperatorContracttypes;

use IlBronza\CRUD\Traits\CRUDCreateStoreTrait;
use IlBronza\CRUD\Traits\CRUDRelationshipTrait;
use IlBronza\CRUD\Traits\CRUDShowTrait;
use IlBronza\Operators\Models\Operator;
use IlBronza\Products\Providers\Helpers\Sellables\SellableCreatorHelper;
use IlBronza\Products\Providers\Helpers\Sellables\SupplierCreatorHelper;

class OperatorContracttypeCreateStoreController extends OperatorContracttypeCRUD
{
    use CRUDCreateStoreTrait;
    use CRUDShowTrait;
    use CRUDRelationshipTrait;

	public $returnBack = true;

    public $allowedMethods = ['create', 'createByOperator', 'store', 'edit', 'update', 'show'];

    public function getGenericParametersFile() : ? string
    {
        return config('operators.models.operatorContracttype.parametersFiles.create');
    }

    public function getRelationshipsManagerClass()
    {
        return config("products.models.{$this->configModelClassName}.relationshipsManagerClasses.show");
    }

    public function show(string $operatorContracttype)
    {
        $operatorContracttype = $this->findModel($operatorContracttype);

        return $this->_show($operatorContracttype);
    }

	public function createByOperator(string $operator)
	{
		$operator = Operator::gpc()::find($operator);

		$this->setParentModel($operator);

		return $this->create();
	}

	public function performAdditionalOperations()
	{
		$operatorSupplier = SupplierCreatorHelper::getOrCreateSupplierFromTarget($this->getModel()->getOperator());
		$contracttypeSellable = SellableCreatorHelper::getOrcreateSellableByTarget(
			$this->getModel()->getContracttype(), [], 'service'
		);

		$sellableSupplier = SellableCreatorHelper::createSellableSupplier($operatorSupplier, $contracttypeSellable);

		$sellableSupplier->cost_company_day = $this->getModel()->cost_company_day;
		$sellableSupplier->save();
	}
}
