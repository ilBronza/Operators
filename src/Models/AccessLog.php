<?php

namespace IlBronza\Operators\Models;

use IlBronza\CRUD\Models\BaseModel;
use IlBronza\CRUD\Traits\Model\CRUDUseUuidTrait;
use IlBronza\CRUD\Traits\Model\PackagedModelsTrait;

class AccessLog extends BaseModel
{
	use CRUDUseUuidTrait;
	use PackagedModelsTrait;

	static $packageConfigPrefix = 'operators';
	static $modelConfigPrefix = 'accessLog';
	static $deletingRelationships = [];

	protected $keyType = 'string';

	protected $casts = [
		'accessed_at' => 'datetime',
		'processed_at' => 'datetime',
		'parsed_payload' => 'array',
	];

	public function accessGate()
	{
		return $this->belongsTo(AccessGate::gpc());
	}
}
