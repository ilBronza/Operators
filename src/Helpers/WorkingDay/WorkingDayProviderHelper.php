<?php

namespace IlBronza\Operators\Helpers\WorkingDay;

use Carbon\Carbon;
use Exception;
use IlBronza\Operators\Models\Interfaces\HasWorkingDays;
use IlBronza\Operators\Models\Operator;
use IlBronza\Operators\Models\WorkingDay;
use IlBronza\Products\Models\ProductPackageBaseModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

use function class_basename;
use function count;
use function dd;
use function is_array;
use function is_null;
use function is_string;
use function strpos;

class WorkingDayProviderHelper
{
	static function getWorkingDaysInterval(HasWorkingDays $workingObject, ProductPackageBaseModel $row, string $section) : array
	{
		$workingDays = static::getByOperatorRange(
			$workingObject, $row->getStartsAt(), $row->getEndsAt(), $section
		);

		return static::filterWorkingDaysByHalfDays($workingDays, $row);
	}

	static function getByOperatorRange(HasWorkingDays $workingObject, Carbon $startsAt = null, Carbon $endsAt = null, string $section = null, string $partOfDay = null, array|string $status = null) : array
	{
		if ((is_null($startsAt)) || (is_null($endsAt)))
			return [];

		$elements = static::getByOperatorRangeRaw($workingObject, $startsAt, $endsAt, $section, $partOfDay, $status);

		$results = [
			'real_am' => [],
			'real_pm' => []
		];

		foreach ($elements as $workingDay)
			$results[$workingDay->getType()][$workingDay->getDate()->format('Y-m-d')] = $workingDay;

		return $results;
	}

	static function getByOperatorRangeRaw(HasWorkingDays $workingObject, Carbon $startsAt, Carbon $endsAt, string $section = null, string $partOfDay = null, array|string $status = null) : Collection
	{
		if(! $workingObject->relationLoaded('workingDays'))
			return static::getQueryByOperatorRange($workingObject, $startsAt, $endsAt, $section, $partOfDay, $status)->get();

		if(count($workingObject->workingDays))
			return $workingObject->workingDays->filter(function($workingDay) use($startsAt, $endsAt)
			{
				if($workingDay->date < $startsAt)
					return false;

				if($workingDay->date > $endsAt)
					return false;

				return true;
			});

		return collect();
	}

	static function getQueryByOperatorRange(HasWorkingDays $workingObject, Carbon $startsAt, Carbon $endsAt, string $section = null, string $partOfDay = null, array|string $status = null) : Builder
	{
		//TODO('qua usare lo scope per le section e orari');

		$query = WorkingDay::gpc()::where('operator_id', $workingObject->getKey())->whereDate('date', '>=', $startsAt)->whereDate('date', '<=', $endsAt);

		if ($section && $partOfDay)
			$query->where('type', "{$section}_{$partOfDay}");
		else if ($section)
			$query->where('type', 'LIKE', "{$section}_%");
		else if ($partOfDay)
			$query->where('type', 'LIKE', "%_{$partOfDay}");

		if (is_string($status))
			$query->where('status', $status);
		else if (is_array($status))
			$query->whereIn('status', $status);

		return $query;
	}

	static function filterWorkingDaysByHalfDays(array $workingDays, ProductPackageBaseModel $row) : array
	{
		if (! isset($workingDays['real_am']))
			throw new Exception('occhio a gestire anche bureau e real');

		if ($row->getHalfStart())
		{
			$startDate = $row->getStartsAt()->format('Y-m-d');
			unset($workingDays['real_am'][$startDate]);
		}

		if ($row->getHalfEnd())
		{
			$endDate = $row->getEndsAt()->format('Y-m-d');
			unset($workingDays['real_pm'][$endDate]);
		}

		return $workingDays;
	}

	static function provideByParameters(HasWorkingDays $workingObject, string|Carbon $date, $section, $partOfDay) : WorkingDay
	{
		if (is_string($date))
			$date = Carbon::parse($date);

		if ($workingObject->relationLoaded('workingDays'))
		{
			foreach ($workingObject->workingDays as $workingDay)
			{
				if ($workingDay->date == $date)
					if (strpos($workingDay->type, $section) !== false)
						if (strpos($workingDay->type, $partOfDay) !== false)
							return $workingDay;
			}

			$workingDay = WorkingDay::gpc()::make();

			if(class_basename($workingObject) == 'Operator')
				$workingDay->operator_id = $workingObject->getKey();
			else
				$workingDay->sellable_supplier_id = $workingObject->getKey();

			$workingDay->date = $date;
			$workingDay->type = "{$section}_{$partOfDay}";

			return $workingDay;
		}

		if ($workingDay = static::getByParameters($workingObject, $date, $section, $partOfDay))
			return $workingDay;

		$workingDay = WorkingDay::gpc()::make();

		if(class_basename($workingObject) == 'Operator')
			$workingDay->operator_id = $workingObject->getKey();
		else
			$workingDay->sellable_supplier_id = $workingObject->getKey();

		$workingDay->date = $date;
		$workingDay->type = "{$section}_{$partOfDay}";

		return $workingDay;
	}

	static function getByParameters(HasWorkingDays $workingObject, $date, $section, $partOfDay) : ?WorkingDay
	{
		$foreign = (class_basename($workingObject) == 'Operator') ? 'operator_id' : 'sellable_supplier_id';

		$workingDays = WorkingDay::gpc()::where([
			$foreign => $workingObject->getKey(),
			'date' => $date,
			'type' => "{$section}_{$partOfDay}"
		])->get();

		if (count($workingDays) == 1)
			return $workingDays->first();

		if (count($workingDays) == 0)
			return null;

		while (count($workingDays) > 1)
		{
			$workingDays->first()->forceDelete();
		}

		return $workingDays->first();
	}

	static function getByOperatorRow(HasWorkingDays$workingObject, ProductPackageBaseModel $row)
	{
		$workingDays = static::getByOperatorRange($workingObject, $row->getStartsAt(), $row->getEndsAt());

		return static::filterWorkingDaysByHalfDays($workingDays, $row);
	}

	static function getByOperatorRangeCount(HasWorkingDays$workingObject, Carbon $startsAt, Carbon $endsAt, string $section = null, string $partOfDay = null, array|string $status = null) : int
	{
		$query = static::getQueryByOperatorRange($workingObject, $startsAt, $endsAt, $section, $partOfDay, $status);

		return $query->count();
	}
}