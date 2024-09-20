<?php

namespace IlBronza\Operators\Models\Traits;

use Carbon\Carbon;
use IlBronza\Operators\Helpers\WorkingDay\WorkingDayCalculationsHelper;
use IlBronza\Operators\Helpers\WorkingDay\WorkingDayCheckerHelper;
use IlBronza\Operators\Helpers\WorkingDay\WorkingDayProviderHelper;
use IlBronza\Operators\Models\WorkingDay;

use function config;
use function dd;
use function in_array;

trait OperatorWorkingDaysBonusCalculatorTrait
{
	public function getWorkingDayResetDate(string $datName)
	{
		$fieldName = "{$datName}_reset_date";

		if ($this->$fieldName)
			return $this->$fieldName;

		return $this->created_at;
	}

	public function getHolidaysResetDate() : Carbon
	{
		return $this->getWorkingDayResetDate('holidays');
	}

	public function getFlexibilityResetDate() : Carbon
	{
		return $this->getWorkingDayResetDate('flexibility');
	}

	public function getRolResetDate() : Carbon
	{
		return $this->getWorkingDayResetDate('rol');
	}

	public function getCalculatedHolidayDaysAttribute() : float
	{
		$resetDate = $this->getHolidaysResetDate();

		$holidayWorkingDaysPortions = WorkingDayProviderHelper::getByOperatorRangeCount($this, $resetDate, Carbon::now(), 'bureau', null, WorkingDay::gpc()::getHolidayStatusArray());
		$months = WorkingDayCalculationsHelper::getMonthsSince($resetDate);

		$holidayAccumulatedDays = $months * config('one.holidayInAMonth');

		return round($holidayAccumulatedDays - ($holidayWorkingDaysPortions * 0.5), 2);
	}

	public function getCalculatedFlexibilityDaysAttribute() : float
	{
		$resetDate = $this->getFlexibilityResetDate();

		$allDays = WorkingDayProviderHelper::getByOperatorRangeRaw($this, $resetDate, Carbon::now(), 'bureau');

		$result = 0;

		foreach($allDays as $day)
		{
			if(WorkingDayCheckerHelper::isHoliday($day->getDate()))
			{
				if($day->hasBeenWorked())
					$result += 0.5;
			}
			else
			{
				if(! $day->hasBeenWorked())
					$result -= 0.5;
			}
		}

		return $result;
	}

	public function getCalculatedRolDaysAttribute() : float
	{
		$resetDate = $this->getRolResetDate();

		$holidayWorkingDaysPortions = WorkingDayProviderHelper::getByOperatorRange($this, $resetDate, Carbon::now(), 'bureau', null, WorkingDay::gpc()::getPermissionsStatusArray());

		$rolHoursEnojyed = 0;

		foreach($holidayWorkingDaysPortions as $type => $elements)
			foreach($elements as $holidayWorkingDaysPortion)
				$rolHoursEnojyed += WorkingDayCalculationsHelper::getRolHoursByWorkingDayStatus($holidayWorkingDaysPortion->getStatus());

		$months = WorkingDayCalculationsHelper::getMonthsSince($resetDate);

		return round(($months * config('one.rolInAMonth')) - ($rolHoursEnojyed / 8), 2);
	}

}