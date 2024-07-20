<?php

namespace IlBronza\Operators\Models;

use IlBronza\CRUD\Models\BaseModel;
use IlBronza\CRUD\Traits\CRUDSluggableTrait;
use IlBronza\CRUD\Traits\Model\CRUDUseUuidTrait;
use IlBronza\CRUD\Traits\Model\PackagedModelsTrait;
use IlBronza\Operators\Helpers\OperatorPricesCreatorHelper;
use IlBronza\Operators\Models\Operator;
use IlBronza\Operators\Models\OperatorContracttype;
use IlBronza\Products\Models\Interfaces\SellableItemInterface;
use IlBronza\Products\Models\Interfaces\SellableSupplierPriceCreatorBaseClass;
use IlBronza\Products\Models\Quotations\Quotation;
use IlBronza\Products\Models\Sellables\Supplier;
use IlBronza\Products\Models\Traits\Sellable\InteractsWithSellableTrait;
use Illuminate\Support\Collection;

class Contracttype extends BaseModel implements SellableItemInterface
{
    use PackagedModelsTrait;

    use CRUDUseUuidTrait;
    use CRUDSluggableTrait;
    use InteractsWithSellableTrait;


    static $packageConfigPrefix = 'operators';
    static $modelConfigPrefix = 'contracttype';

    public $deletingRelationships = [];


    public function getNameForSellable(... $parameters) : string
    {
        return "{$this->getName()} - {$this->getIstatCode()}";
    }

    public function getPossibleSuppliersElements() : Collection
    {
        return $this->operators()->with('supplier')->get()->pluck('supplier')->filter();
    }

    public function getPriceCreator() : SellableSupplierPriceCreatorBaseClass
    {
        return new OperatorPricesCreatorHelper;
    }

    public function getSellablePricesBySupplier(Supplier $supplier, ...$parameters) : array
    {
        dd($this->operatorContracttypes()->where('operator_id', $supplier->getTarget()->getKey())->first());

        dd($supplier->getTarget());
        dd('qua');
        return null;
    }

    public function operators()
    {
        return $this->belongsToMany(
            Operator::getProjectClassName(),
            config('operators.models.operatorContracttype.table')
        )->using(OperatorContracttype::getProjectClassName());
    }

    public function operatorContracttypes()
    {
        return $this->hasMany(OperatorContracttype::getProjectClassName());
    }

    public function getRelatedFullOperatorContracttypes() : Collection
    {
        return $this->operatorContracttypes()->with(
            'operator.user.userdata'
        )->get();
    }

    public function getRelatedFullOperators() : Collection
    {
        return $this->operators()->with(
            'user.extraFields',
            'contracttypes',
            'sellableSuppliers.directPrice',
            'sellableSuppliers.sellable',
            'employments'
        )
        ->withSupplierId()
        ->get();
    }

    public function getOperators() : Collection
    {
        return $this->operators;
    }


    public function getIstatCode() : ? string
    {
        return $this->istat_code;
    }
}

























// class Operator extends BaseModel
// {

//     use ClientsPackageBaseModelTrait;
//     use CRUDUseUuidTrait;
//     use CRUDSluggableTrait;

//     public function clientOperators()
//     {
//         return $this->hasMany(ClientOperator::getProjectClassName());
//     }

//     public function clients()
//     {
//         return $this->belongsToMany(Client::getProjectClassName());
//     }

//     public function client()
//     {
//         return $this->hasOne(Client::getProjectClassName());
//     }


// }