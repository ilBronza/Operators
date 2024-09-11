<?php

namespace IlBronza\Operators\Models;

use IlBronza\CRUD\Models\PackagedBaseModel;
use IlBronza\CRUD\Traits\Model\CRUDUseUuidTrait;

class WorkingDay extends PackagedBaseModel
{
	use CRUDUseUuidTrait;

	protected $casts = [
		'date' => 'date',
		'deleted_at' => 'date'
	];

	static function getWorkingDaySelectArray() : array
	{
		//TODO
		dd('jere wuah sistmatoh');
	}

	static $packageConfigPrefix = 'operators';
	static $modelConfigPrefix = 'workingDay';
	protected $keyType = 'string';

	public function operator()
	{
		return $this->belongsTo(Operator::getProjectClassName());
	}

	public function getOperator() : ? Operator
	{
		return $this->operator;
	}

	public function getDate() : Carbon
	{
		return $this->date;
	}
}