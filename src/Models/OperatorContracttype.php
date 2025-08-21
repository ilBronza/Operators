<?php

namespace IlBronza\Operators\Models;

use IlBronza\CRUD\Models\BasePivotModel;
use IlBronza\CRUD\Traits\Model\CRUDUseUuidTrait;
use IlBronza\CRUD\Traits\Model\PackagedModelsTrait;
use IlBronza\Products\Providers\Helpers\Sellables\SellableCreatorHelper;

class OperatorContracttype extends BasePivotModel
{
	use CRUDUseUuidTrait;

	static $deletingRelationships = [];

	static $packageConfigPrefix = 'operators';
	static $modelConfigPrefix = 'operatorContracttype';
	protected $keyType = 'string';

	use PackagedModelsTrait;

	public function contracttype()
	{
		return $this->belongsTo(
			config('operators.models.contracttype.class')
		);
	}

	public function operator()
	{
		return $this->belongsTo(
			config('operators.models.operator.class')
		);
	}

	public function getOperator() : ? Operator
	{
		return $this->operator;
	}

	public function getContracttypeName() : ?string
	{
		return $this->getContracttype()?->getName();
	}

	public function getContracttype() : ?Contracttype
	{
		return $this->contracttype;
	}

}