<?php

namespace IlBronza\Operators\Models\Traits;

use Carbon\Carbon;
use IlBronza\Operators\Helpers\WorkingDay\WorkingDayCalculationsHelper;
use IlBronza\Operators\Helpers\WorkingDay\WorkingDayProviderHelper;
use IlBronza\Operators\Models\WorkingDay;

use function config;
use function round;

trait OperatorWorkingDaysBonusCalculatorTrait
{
	public function getCalendarEndDate() : Carbon
	{
		return $GLOBALS['ends_at'] ?? Carbon::now()->endOfMonth();
	}

	public function getCalculatedHolidayDaysAttribute() : float
	{
		$resetDate = $this->getHolidaysResetDate();
		$resetHours = $this->getHolidaysReset();



		$endDate = $this->getCalendarEndDate();

		$holidayWorkingDaysPortions = WorkingDayProviderHelper::getByOperatorRangeCount($this, $resetDate, $endDate, 'bureau', null, WorkingDay::gpc()::getHolidayStatusArray());

		$months = WorkingDayCalculationsHelper::getMonthsSince($resetDate->copy(), $endDate);

		$holidayAccumulatedHours = $months * config('one.holidayHoursInAMonth');

		$total = $holidayAccumulatedHours + $resetHours;

		return round($total - ($holidayWorkingDaysPortions * 4), 2);
	}

	//calculated_flexibility_days
	public function getCalculatedFlexibilityDaysAttribute() : float
	{
		$resetDate = $this->getFlexibilityResetDate();
		$resetHours = $this->getFlexibilityReset();



		$endDate = $this->getCalendarEndDate();

		$allDays = WorkingDayProviderHelper::getByOperatorRangeRaw($this, $resetDate, $endDate, 'bureau');

		$resultHours = 0;

		$startCountingDate = $resetDate->copy();

		while (($date = $startCountingDate->addDay()) < $endDate)
		{
			if (! $workingMorning = $allDays->where('date', $date)->where('type', 'bureau_am')->first())
				$morningStatus = WorkingDayCalculationsHelper::calculateStatusByDate($this, $date, 'am', $this->orderrows);
			else
				$morningStatus = $workingMorning->getStatus();

			if (! $workingAfternoon = $allDays->where('date', $date)->where('type', 'bureau_pm')->first())
				$afternoonStatus = WorkingDayCalculationsHelper::calculateStatusByDate($this, $date, 'pm', $this->orderrows);
			else
				$afternoonStatus = $workingAfternoon->getStatus();

			foreach ([$morningStatus, $afternoonStatus] as $status)
			{
				if (is_null($status))
					continue;

				$placeholderWorkingDay = WorkingDay::gpc()::make();
				$placeholderWorkingDay->status = $status;
				$placeholderWorkingDay->date = $date;

				$resultHours += $placeholderWorkingDay->getFlexByDateCoefficient();

				//				if(in_array($status, WorkingDay::gpc()::getDoctorStoppageArray()))
				//					continue;
				//
				//				if (WorkingDayCheckerHelper::isWeekendOrHoliday($date))
				//				{
				//					if(WorkingDayCheckerHelper::statusIsWorked($status))
				//						$resultHours += 4;
				//				}
				//				else
				//					if(! WorkingDayCheckerHelper::statusIsWorked($status))
				//						if(! in_array($status, WorkingDay::gpc()::getPermissionsStatusArray()))
				//							$resultHours -= 4;
			}
		}

		return $resultHours + $resetHours;
	}

	public function getCalculatedRolDaysAttribute() : float
	{
		$resetDate = $this->getRolResetDate();
		$resetHours = $this->getRolReset();



		$endDate = $this->getCalendarEndDate();

		$rolConsumingDaysPortions = WorkingDayProviderHelper::getByOperatorRange($this, $resetDate, $endDate, 'bureau', null, WorkingDay::gpc()::getPermissionsStatusArray());

		unset($rolConsumingDaysPortions['real_am']);
		unset($rolConsumingDaysPortions['real_pm']);

		$rolHoursEnojyed = 0;

		foreach ($rolConsumingDaysPortions as $type => $elements)
			foreach ($elements as $day)
				$rolHoursEnojyed += $day->getRolUsedDayCoefficient();

		$months = WorkingDayCalculationsHelper::getMonthsSince($resetDate, $endDate);

		$resultHours = ($months * config('one.rolHoursInAMonth'));

		$result = $resultHours - $rolHoursEnojyed + $resetHours;

		return round($result, 2);
	}

	public function getCalculatedBBDaysAttribute() : float
	{
		$resetDate = $this->getBBResetDate();
		$resetHours = $this->getBBReset();



		$endDate = $this->getCalendarEndDate();

		$bpCount = WorkingDayProviderHelper::getByOperatorRangeCount($this, $resetDate, $endDate, 'bureau', null, ['bp']);
		$bmCount = WorkingDayProviderHelper::getByOperatorRangeCount($this, $resetDate, $endDate, 'bureau', null, ['bm']);

		return $resetHours + ($bpCount - $bmCount) * 0.5 * 8;

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

	public function getHolidaysReset()
	{
		return $this->getWorkingDayReset('holidays');
	}

	public function getRolReset()
	{
		return $this->getWorkingDayReset('rol');
	}

	public function getBBReset()
	{
		return $this->getWorkingDayReset('bb');
	}

	public function getHolidaysResetDate() : Carbon
	{
		return $this->getWorkingDayResetDate('holidays');
	}

	public function getWorkingDayReset(string $datName)
	{
		$fieldName = "{$datName}_reset";

		return $this->getInUseClientOperator()->$fieldName;
	}

	public function getWorkingDayResetDate(string $datName)
	{
		$fieldName = "{$datName}_reset_date";

		if ($value = $this->getInUseClientOperator()->$fieldName)
			return $value;

		if ($first = WorkingDay::gpc()::orderBy('date')->first())
			return $first->date;

		return Carbon::now()->startOfMonth();
	}

	public function getFlexibilityResetDate() : Carbon
	{
		return $this->getWorkingDayResetDate('flexibility');
	}

	public function getFlexibilityReset()
	{
		return $this->getWorkingDayReset('flexibility');
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