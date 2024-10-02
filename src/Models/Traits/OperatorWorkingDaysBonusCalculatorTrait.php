<?php

namespace IlBronza\Operators\Models\Traits;

use Carbon\Carbon;
use IlBronza\Operators\Helpers\WorkingDay\WorkingDayCalculationsHelper;
use IlBronza\Operators\Helpers\WorkingDay\WorkingDayCheckerHelper;
use IlBronza\Operators\Helpers\WorkingDay\WorkingDayProviderHelper;
use IlBronza\Operators\Models\WorkingDay;

use function config;
use function round;

trait OperatorWorkingDaysBonusCalculatorTrait
{
	public function getCalculatedHolidayDaysAttribute() : float
	{
		$resetDate = $this->getHolidaysResetDate();

		$holidayWorkingDaysPortions = WorkingDayProviderHelper::getByOperatorRangeCount($this, $resetDate, Carbon::now(), 'bureau', null, WorkingDay::gpc()::getHolidayStatusArray());
		$months = WorkingDayCalculationsHelper::getMonthsSince($resetDate);

		$holidayAccumulatedDays = $months * config('one.holidayInAMonth');

		return round($holidayAccumulatedDays - ($holidayWorkingDaysPortions * 0.5), 2);
	}

	public function getHolidaysResetDate() : Carbon
	{
		return $this->getWorkingDayResetDate('holidays');
	}

	public function getWorkingDayResetDate(string $datName)
	{
		$fieldName = "{$datName}_reset_date";

		if ($this->$fieldName)
			return $this->$fieldName;

		if ($first = WorkingDay::gpc()::orderBy('date')->first())
			return $first->date;

		return Carbon::now()->startOfMonth();
	}

	public function getCalculatedFlexibilityDaysAttribute() : float
	{
		$resetDate = $this->getFlexibilityResetDate();

		$allDays = WorkingDayProviderHelper::getByOperatorRangeRaw($this, $resetDate, Carbon::now(), 'bureau');

		$result = 0;

		foreach ($allDays as $day)
		{
			if (WorkingDayCheckerHelper::isWeekendOrHoliday($day->getDate()))
			{
				if ($day->hasBeenWorked())
					$result += 0.5;
			}
			else
				$result -= $day->getFlexUsedDayCoefficient();
		}

		return $result;
	}

	public function getFlexibilityResetDate() : Carbon
	{
		return $this->getWorkingDayResetDate('flexibility');
	}

	public function getCalculatedRolDaysAttribute() : float
	{
		$resetDate = $this->getRolResetDate();

		$rolConsumingDaysPortions = WorkingDayProviderHelper::getByOperatorRange($this, $resetDate, Carbon::now(), 'bureau', null, WorkingDay::gpc()::getPermissionsStatusArray());

		$rolHoursEnojyed = 0;

		foreach ($rolConsumingDaysPortions as $type => $elements)
			foreach($elements as $day)
				$rolHoursEnojyed += $day->getRolUsedDayCoefficient();

		$months = WorkingDayCalculationsHelper::getMonthsSince($resetDate);

		return round(($months * config('one.rolInAMonth')) - ($rolHoursEnojyed / 8), 2);
	}

	public function getCalculatedBBDaysAttribute() : float
	{
		$resetDate = $this->getBBResetDate();

		$bpCount = WorkingDayProviderHelper::getByOperatorRangeCount($this, $resetDate, Carbon::now(), 'bureau', null, ['bp']);
		$bmCount = WorkingDayProviderHelper::getByOperatorRangeCount($this, $resetDate, Carbon::now(), 'bureau', null, ['bm']);

		return ($bpCount - $bmCount) * 0.5;

//		$rolHoursEnojyed = 0;
//
//		foreach ($rolConsumingDaysPortions as $type => $elements)
//			foreach($elements as $day)
//				$rolHoursEnojyed += $day->getRolUsedDayCoefficient();
//
//		$months = WorkingDayCalculationsHelper::getMonthsSince($resetDate);
//
//		return round(($months * config('one.rolInAMonth')) - ($rolHoursEnojyed / 8), 2);
	}

	public function getRolResetDate() : Carbon
	{
		return $this->getWorkingDayResetDate('rol');
	}

	public function getBBResetDate() : Carbon
	{
		return $this->getWorkingDayResetDate('bb');
	}

}