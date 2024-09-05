<?php

namespace IlBronza\Operators\Models;

use IlBronza\Clients\Models\Client;
use IlBronza\CRUD\Models\BaseModel;
use IlBronza\CRUD\Traits\CRUDSluggableTrait;
use IlBronza\CRUD\Traits\Model\CRUDGetOrCreateTrait;
use IlBronza\CRUD\Traits\Model\PackagedModelsTrait;

use function config;

class Employment extends BaseModel
{
    use CRUDGetOrCreateTrait;
    use PackagedModelsTrait;

    use CRUDSluggableTrait;

    static $packageConfigPrefix = 'operators';
    static $modelConfigPrefix = 'employment';

    public $deletingRelationships = [];

	public function getShortName() : ? string
	{
		return $this->label;
	}

	public function operators()
	{
		return $this->belongsToMany(
			Operator::getProjectClassName(),
			config('operators.models.clientOperator.table')
		)->distinct();
	}

	public function getRelatedOperators()
	{
		return $this->operators()->with(
			'user.extrafields',
			'address',
			'contracttypes',
			'employments'
		)->get();
	}

	public function clients()
	{
		return $this->belongsToMany(
			Client::getProjectClassName(),
			config('operators.models.clientOperator.table')
		)->distinct();
	}

}