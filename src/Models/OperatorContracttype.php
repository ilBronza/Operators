<?php

namespace IlBronza\Operators\Models;

use IlBronza\CRUD\Models\BasePivotModel;
use IlBronza\CRUD\Traits\Model\CRUDUseUuidTrait;
use IlBronza\CRUD\Traits\Model\PackagedModelsTrait;

class OperatorContracttype extends BasePivotModel
{
	use CRUDUseUuidTrait;

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

	public function getContracttypeName() : ?string
	{
		return $this->getContracttype()?->getName();
	}

	public function getContracttype() : ?Contracttype
	{
		return $this->contracttype;
	}

}