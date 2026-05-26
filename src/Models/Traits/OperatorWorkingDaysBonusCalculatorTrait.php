<?php

namespace IlBronza\Operators\Models\Traits;

use Carbon\Carbon;
use IlBronza\Operators\Helpers\WorkingDay\WorkingDayCalculationsHelper;
use IlBronza\Operators\Helpers\WorkingDay\WorkingDayProviderHelper;
use IlBronza\Operators\Models\WorkingDay;
use IlBronza\Products\Models\Order;
use IlBronza\Products\Models\Orders\Orderrow;
use IlBronza\Ukn\Ukn;
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
		try
		{
			$resetDate = $this->getHolidaysResetDate();
			$resetHours = $this->getHolidaysReset();			
		}
		catch(\Exception $e)
		{
			//Ukn::w($e->getMessage());

			return 0;
		}

		$endDate = $this->getCalendarEndDate();

		$holidayWorkingDaysPortions = WorkingDayProviderHelper::getByOperatorRangeCount($this, $resetDate->copy()->addDays(1), $endDate, 'bureau', null, WorkingDay::gpc()::getHolidayStatusArray(), true);

		$months = WorkingDayCalculationsHelper::getMonthsSince($resetDate->copy(), $endDate);

		$holidayAccumulatedHours = $months * config('one.holidayHoursInAMonth');

		$total = $holidayAccumulatedHours + $resetHours;

		return round($total - ($holidayWorkingDaysPortions * 4), 2);
	}

	public function getMonthCalculatedHolidayDaysAttribute() : float
	{
		$endDate = $this->getCalendarEndDate();

		$resetDate = $endDate->copy()->startOfMonth()->subDays(1);
		$resetHours = 0;

		$holidayWorkingDaysPortions = WorkingDayProviderHelper::getByOperatorRangeCount($this, $resetDate->copy()->addDays(1), $endDate, 'bureau', null, WorkingDay::gpc()::getHolidayStatusArray(), true);

		$months = WorkingDayCalculationsHelper::getMonthsSince($resetDate->copy(), $endDate);

		$holidayAccumulatedHours = $months * config('one.holidayHoursInAMonth');

		$total = $holidayAccumulatedHours + $resetHours;

		return round($total - ($holidayWorkingDaysPortions * 4), 2);
	}

	public function getOrderrowsByDates(Carbon $start, Carbon $end)
	{
		$sellableSuppliersIdsDictionary = [];

		foreach($this->operatorContracttypes as $operatorContracttype)
			foreach ($operatorContracttype->supplier->sellableSuppliers as $sellableSupplier)
				$sellableSuppliersIdsDictionary[$sellableSupplier->getKey()] = [
					'operator' => $this,
					'orderrows' => collect()
				];

		$ordersIds = Order::gpc()::select('id')->whereHas('extraFields', function ($query) use($start, $end)
		{
			$query->whereBetween('starts_at', [$start, $end])->orWhereBetween('ends_at', [$start, $end]);
		})->pluck('id');

		$orderrows = Orderrow::gpc()::whereIn('sellable_supplier_id', array_keys($sellableSuppliersIdsDictionary))->where(function ($query) use ($ordersIds, $start, $end)
		{
			$query->whereIn('order_id', $ordersIds);
			$query->orWhere(function ($_query) use($start, $end)
			{
				$_query->whereBetween('starts_at', [$start, $end]);
				$_query->orWhereBetween('ends_at', [$start, $end]);
			});
		})->get();

		foreach ($orderrows as $orderrow)
			$sellableSuppliersIdsDictionary[$orderrow->sellable_supplier_id]['orderrows']->push($orderrow);

		$orderrowDays = collect();

		foreach($this->operatorContracttypes as $operatorContracttype)
			foreach ($operatorContracttype->supplier->sellableSuppliers as $sellableSupplier)
				$orderrowDays = $orderrowDays->merge($sellableSuppliersIdsDictionary[$sellableSupplier->getKey()]['orderrows']);

		return $orderrowDays;
	}

	//calculated_flexibility_days
	public function getCalculatedFlexibilityDaysAttribute() : float
	{
		try
		{
			$resetDate = $this->getFlexibilityResetDate();
			$resetHours = $this->getFlexibilityReset();
		}
		catch(\Exception $e)
		{
			//Ukn::w($e->getMessage());

			return 0;
		}

		$endDate = $this->getCalendarEndDate();

		$allDays = WorkingDayProviderHelper::getByOperatorRangeRaw(
			$this,
			$resetDate->copy()->addDays(1),
			$endDate,
			'bureau',
			null,
			null,
			true
		);

		$orderrows = $this->getOrderrowsByDates($resetDate, $endDate);

		$resultHours = 0;

		$startCountingDate = $resetDate->copy();

		while (($date = $startCountingDate->addDay()) < $endDate)
		{
			if (! $workingMorning = $allDays->where('date', $date)->where('type', 'bureau_am')->first())
				$morningStatus = WorkingDayCalculationsHelper::calculateStatusByDate($this, $date, 'am', $orderrows);
			else
				$morningStatus = $workingMorning->getStatus();

			if (! $workingAfternoon = $allDays->where('date', $date)->where('type', 'bureau_pm')->first())
				$afternoonStatus = WorkingDayCalculationsHelper::calculateStatusByDate($this, $date, 'pm', $orderrows);
			else
				$afternoonStatus = $workingAfternoon->getStatus();

			foreach ([$morningStatus, $afternoonStatus] as $status)
			{
				if (! is_null($status))
				{
					$placeholderWorkingDay = WorkingDay::gpc()::make();
					$placeholderWorkingDay->status = $status;
					$placeholderWorkingDay->date = $date;

					$resultHours += $placeholderWorkingDay->getFlexByDateCoefficient();
				}
			}
		}

		return $resultHours + $resetHours;
	}

	public function getMonthCalculatedFlexibilityDaysAttribute() : float
	{
		$endDate = $this->getCalendarEndDate();

		$resetDate = $endDate->copy()->startOfMonth()->subDays(1);
		$resetHours = 0;

		$allDays = WorkingDayProviderHelper::getByOperatorRangeRaw(
			$this,
			$resetDate->copy()->addDays(1),
			$endDate,
			'bureau',
			null,
			null,
			true
		);

		$orderrows = $this->getOrderrowsByDates($resetDate, $endDate);

		$resultHours = 0;

		$startCountingDate = $resetDate->copy();

		while (($date = $startCountingDate->addDay()) < $endDate)
		{
			if (! $workingMorning = $allDays->where('date', $date)->where('type', 'bureau_am')->first())
				$morningStatus = WorkingDayCalculationsHelper::calculateStatusByDate($this, $date, 'am', $orderrows);
			else
				$morningStatus = $workingMorning->getStatus();

			if (! $workingAfternoon = $allDays->where('date', $date)->where('type', 'bureau_pm')->first())
				$afternoonStatus = WorkingDayCalculationsHelper::calculateStatusByDate($this, $date, 'pm', $orderrows);
			else
				$afternoonStatus = $workingAfternoon->getStatus();

			foreach ([$morningStatus, $afternoonStatus] as $status)
			{
				if (! is_null($status))
				{
					$placeholderWorkingDay = WorkingDay::gpc()::make();
					$placeholderWorkingDay->status = $status;
					$placeholderWorkingDay->date = $date;

					$resultHours += $placeholderWorkingDay->getFlexByDateCoefficient();
				}
			}
		}

		return $resultHours + $resetHours;
	}

	public function getCalculatedRolDaysAttribute() : float
	{
		try
		{
			$resetDate = $this->getRolResetDate();
			$resetHours = $this->getRolReset();			
		}
		catch(\Exception $e)
		{
			//Ukn::w($e->getMessage());

			return 0;
		}

		$endDate = $this->getCalendarEndDate();

		$rolConsumingDaysPortions = WorkingDayProviderHelper::getByOperatorRange(
			$this,
			$resetDate->copy()->addDays(1),
			$endDate,
			'bureau',
			null,
			null,
			true
		);

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

	public function getMonthCalculatedRolDaysAttribute() : float
	{
		$endDate = $this->getCalendarEndDate();

		$resetDate = $endDate->copy()->startOfMonth()->subDays(1);
		$resetHours = 0;

		$rolConsumingDaysPortions = WorkingDayProviderHelper::getByOperatorRange(
			$this,
			$resetDate->copy()->addDays(1),
			$endDate,
			'bureau',
			null,
			null,
			true
		);

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
		try
		{
			$resetDate = $this->getBBResetDate();
			$resetHours = $this->getBBReset();
		}
		catch(\Exception $e)
		{
			//Ukn::w($e->getMessage());

			return 0;
		}



		$endDate = $this->getCalendarEndDate();

		$bpCount = WorkingDayProviderHelper::getByOperatorRangeCount($this, $resetDate->copy()->addDays(1), $endDate, 'bureau', null, ['bp'], true);
		$bmCount = WorkingDayProviderHelper::getByOperatorRangeCount($this, $resetDate->copy()->addDays(1), $endDate, 'bureau', null, ['bm'], true);

		return $resetHours + ($bpCount - $bmCount) * 0.5 * 8;
	}

	public function getMonthCalculatedBBDaysAttribute() : float
	{
		$endDate = $this->getCalendarEndDate();

		$resetDate = $endDate->copy()->startOfMonth()->subDays(1);
		$resetHours = 0;

		$bpCount = WorkingDayProviderHelper::getByOperatorRangeCount($this, $resetDate->copy()->addDays(1), $endDate, 'bureau', null, ['bp'], true);
		$bmCount = WorkingDayProviderHelper::getByOperatorRangeCount($this, $resetDate->copy()->addDays(1), $endDate, 'bureau', null, ['bm'], true);

		return $resetHours + ($bpCount - $bmCount) * 0.5 * 8;
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

		if(! $this->$fieldName)
			throw new \Exception('Inserire le date di reset conteggi ferie/rol/flex (' . $fieldName .') per ' . $this->getName() . ' e controllare l\'esistenza di un contratto valido');

		return $this->$fieldName;
	}

	public function getWorkingDayResetDate(string $datName)
	{
		$fieldName = "{$datName}_reset_date";

		if ($value = $this->$fieldName)
			return $value;

			throw new \Exception('Inserire le date di reset conteggi ferie/rol/flex (' . $fieldName .') per ' . $this->getName() . ' e controllare l\'esistenza di un contratto valido');

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