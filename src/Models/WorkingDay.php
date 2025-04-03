<?php

namespace IlBronza\Operators\Models;

use Carbon\Carbon;
use IlBronza\CRUD\Models\PackagedBaseModel;
use IlBronza\CRUD\Traits\Model\CRUDUseUuidTrait;

use function app;
use function array_pop;
use function in_array;

class WorkingDay extends PackagedBaseModel
{
	use CRUDUseUuidTrait;

	protected $casts = [
		'date' => 'date:Y-m-d',
		'deleted_at' => 'date'
	];

	public $timestamps = false;
	
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

	public function getType() : string
	{
		return $this->type;
	}

	public function getStatus() : ? string
	{
		return $this->status;
	}

	public function hasBeenWorked() : bool
	{
		return in_array($this->getStatus(), WorkingDay::gpc()::getWorkingStatusArray());
	}

	static function getUpdateByOperatorDay($operator, $day) : string
	{
		return app('operators')->route('workingDays.updateByOperatorDay', [$operator, $day]);
	}

	static function getUpdateBySellableSupplierDay($sellableSupplier, $day) : string
	{
		return app('operators')->route('workingDays.updateBySellableSupplierDay', [$sellableSupplier, $day]);
	}

	public function getWorkingDayParameters() : array
	{
		$result = array_filter(
			$this->getDateStatusArray(),
			function($subarray)
			{
				return isset($subarray['key']) && $subarray['key'] == $this->getStatus();
			});

		return array_pop($result);
	}

	public function getFlexUsedCoefficient() : ? float
	{
		$parameters = $this->getWorkingDayParameters();

		return $parameters['flexUsedCoefficient'] ?? 0;
	}

	public function getFlexByDateCoefficient()
	{
		$parameters = $this->getWorkingDayParameters();

		if($this->date->isWeekend())
			return $parameters['flexWeekEndGain'] ?? 0;

		return - ($parameters['flexUsedCoefficient'] ?? 0);
	}

	public function getRolUsedDayCoefficient() : ? float
	{
		$parameters = $this->getWorkingDayParameters();

		return $parameters['rolUsedCoefficient'] ?? 0;
	}

	public function getFlexUsedDayCoefficient()
	{
		return $this->getFlexUsedCoefficient() / 8;
	}
}