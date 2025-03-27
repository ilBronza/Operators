<?php

namespace IlBronza\Operators\Models;

use Carbon\Carbon;
use Exception;
use IlBronza\CRUD\Models\BaseModel;
use IlBronza\CRUD\Traits\CRUDSluggableTrait;
use IlBronza\CRUD\Traits\Model\CRUDUseUuidTrait;
use IlBronza\CRUD\Traits\Model\PackagedModelsTrait;
use IlBronza\Operators\Helpers\OperatorPricesCreatorHelper;
use IlBronza\Prices\Models\Interfaces\WithPriceInterface;
use IlBronza\Prices\Models\Traits\InteractsWithPriceTrait;
use IlBronza\Prices\Providers\PriceData;
use IlBronza\Products\Models\Interfaces\SellableItemInterface;
use IlBronza\Products\Models\Interfaces\SellableSupplierPriceCreatorBaseClass;
use IlBronza\Products\Models\Sellables\Supplier;
use IlBronza\Products\Models\Traits\Sellable\InteractsWithSellableTrait;
use Illuminate\Support\Collection;

use function dd;

class Contracttype extends BaseModel implements SellableItemInterface, WithPriceInterface
{
	use PackagedModelsTrait;

	use CRUDUseUuidTrait;
	use CRUDSluggableTrait;
	use InteractsWithSellableTrait;
	use InteractsWithPriceTrait;

	static $packageConfigPrefix = 'operators';
	static $modelConfigPrefix = 'contracttype';
	public $deletingRelationships = [];
	protected $keyType = 'string';

	public function getNameForSellable(...$parameters) : string
	{
		return $this->getName();
	}

	public function getDescription()
	{
		return $this->description;
	}

	public function getIstatCode() : ?string
	{
		return $this->istat_code;
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
			Operator::getProjectClassName(), config('operators.models.operatorContracttype.table')
		)->using(OperatorContracttype::getProjectClassName());
	}

	public function operatorContracttypes()
	{
		return $this->hasMany(OperatorContracttype::getProjectClassName());
	}

	public function getRelatedFullOperatorContracttypes() : Collection
	{
		return $this->operatorContracttypes()->with(['operator.user.userdata', 'operator.address', 'operator.contracttypes', 'prices'])->get();
	}

	public function getRelatedFullOperators() : Collection
	{
		return $this->operators()->with(
			'user.extraFields', 'contracttypes', 'sellableSuppliers.directPrice', 'sellableSuppliers.sellable', 'employments'
		)->withSupplierId()->get();
	}

	public function getOperators() : Collection
	{
		return $this->operators;
	}

	public function _calculatePriceData(PriceData $priceData) : PriceData
	{
		dd('risolvere');
	}

	//must calculate the final price
	public function _manageCalculationErrors(Exception $e)
	{
		//TODO manage errors
		dd('risolvere');
	}

	//get first cost
	public function getCost()
	{
		//TODO get first cost
		dd('risolvere');
	}

	//get new price model base attributes to fill the price before its calculated
	public function getPriceBaseAttributes()
	{
		return [];
	}


	/**
	 * example
	 *
	 * public function getPriceBaseAttributes()
	 * {
	 *      return [
	 *          'own_cost' => $this->getCost(),
	 *          'sequence' => $this->getPriceSequence(),
	 *      ];
	 * }
	 **/

	/**
	 * get the classname you need to relate in price table
	 * expl your current model is App\Cardboards\PriceCalculations\Cardboard but you need to use App\Cardboards\PriceCalculations\Cardboard
	 **/
	// public function getPriceRelatedClassName();

	/**
	 * get the model key you need to relate in price table
	 * expl your current model is 172 but you need to use 34
	 **/
	// public function getPriceRelatedKey();

	public function getPriceValidityFrom() : ?Carbon
	{
		return null;
	}

	public function getPriceValidityTo() : ?Carbon
	{
		return null;
	}

	public function getCostCompany() : ?float
	{
		return $this->cost_company_day;
	}

}
