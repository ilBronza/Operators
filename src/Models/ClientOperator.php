<?php

namespace IlBronza\Operators\Models;

use IlBronza\CRUD\Models\BasePivotModel;
use IlBronza\CRUD\Traits\Model\CRUDUseUuidTrait;
use IlBronza\CRUD\Traits\Model\PackagedModelsTrait;

class ClientOperator extends BasePivotModel
{
	use CRUDUseUuidTrait;

	static $packageConfigPrefix = 'operators';
	static $modelConfigPrefix = 'clientOperator';
	protected $keyType = 'string';
	protected $casts = [
		'started_at' => 'date',
		'ended_at' => 'date'
	];

	use PackagedModelsTrait;

	public function client()
	{
		return $this->belongsTo(
			config('clients.models.client.class')
		);
	}

	public function operator()
	{
		return $this->belongsTo(
			config('operators.models.operator.class')
		);
	}

	public function employment()
	{
		return $this->belongsTo(Employment::getProjectClassName());
	}

	public function contracttype()
	{
		return $this->belongsTo(Contracttype::getProjectClassName());
	}

	public function getContracttype() : ? Contracttype
	{
		return $this->contracttype;
	}

	public function remuneration()
	{
		return $this->price()->where('collection_id', 'remuneration');
	}

	public function getContracttypeName() : ? string
	{
		return $this->getContracttype()?->getName();
	}
}