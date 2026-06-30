<?php

namespace IlBronza\Operators\Models;

use IlBronza\CRUD\Models\BaseModel;
use IlBronza\CRUD\Traits\Model\CRUDUseUuidTrait;
use IlBronza\CRUD\Traits\Model\PackagedModelsTrait;

class AccessGate extends BaseModel
{
	use CRUDUseUuidTrait;
	use PackagedModelsTrait;

	static $packageConfigPrefix = 'operators';
	static $modelConfigPrefix = 'accessGate';
	static $deletingRelationships = [];

	protected $keyType = 'string';

	public function getDescription() : ?string
	{
		return $this->description;
	}
}
