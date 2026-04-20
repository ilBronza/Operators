<?php

namespace IlBronza\Operators\Models;

use Carbon\Carbon;
use Exception;
use IlBronza\CRUD\Models\BaseModel;
use IlBronza\CRUD\Traits\CRUDSluggableTrait;
use IlBronza\CRUD\Traits\Model\CRUDUseUuidTrait;
use IlBronza\CRUD\Traits\Model\HasColorTrait;
use IlBronza\CRUD\Traits\Model\PackagedModelsTrait;
use Illuminate\Support\Collection;
use function class_basename;
use function dd;

class Contracttype extends BaseModel
{
	use PackagedModelsTrait;

	use CRUDUseUuidTrait;
	use CRUDSluggableTrait;

	use HasColorTrait;

	static $packageConfigPrefix = 'operators';
	static $modelConfigPrefix = 'contracttype';
	static $deletingRelationships = [];
	protected $keyType = 'string';

	public function getDescription()
	{
		return $this->description;
	}

	public function getIstatCode() : ?string
	{
		return $this->istat_code;
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
		return $this->operatorContracttypes()->with(['operator.user.userdata', 'operator.address', 'operator.contracttypes'])->get();
	}

	public function getOperators() : Collection
	{
		return $this->operators;
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
}
