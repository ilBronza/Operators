<?php

namespace IlBronza\Operators\Models;

use IlBronza\CRUD\Models\BaseModel;
use IlBronza\CRUD\Traits\Model\CRUDUseUuidTrait;
use IlBronza\CRUD\Traits\Model\PackagedModelsTrait;

class OperatorBadge extends BaseModel
{
	use CRUDUseUuidTrait;
	use PackagedModelsTrait;

	static $packageConfigPrefix = 'operators';
	static $modelConfigPrefix = 'operatorBadge';
	static $deletingRelationships = [];

	protected $keyType = 'string';

	protected $casts = [
		'active' => 'boolean',
		'valid_from' => 'datetime',
		'valid_to' => 'datetime',
	];

	public function operator()
	{
		return $this->belongsTo(Operator::gpc());
	}

	public function getDescription() : ?string
	{
		return $this->notes;
	}
}
