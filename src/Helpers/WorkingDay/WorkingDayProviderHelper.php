<?php

namespace IlBronza\Operators\Helpers\WorkingDay;

use Carbon\Carbon;

use IlBronza\Operators\Models\Operator;

use IlBronza\Operators\Models\WorkingDay;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

use Illuminate\Support\Facades\Log;

use function count;
use function dd;
use function is_array;
use function is_null;
use function is_string;
use function strpos;

class WorkingDayProviderHelper
{
	static function getByParameters(Operator $operator, $date, $section, $partOfDay) : ? WorkingDay
	{
		$workingDays = WorkingDay::gpc()::where([
			'operator_id' => $operator->getKey(),
			'date' => $date,
			'type' => "{$section}_{$partOfDay}"
		])->get();

		if(count($workingDays) == 1)
			return $workingDays->first();

		if(count($workingDays) == 0)
			return null;

		while(count($workingDays) > 1)
			$workingDays->first()->forceDelete();

		return $workingDays->first();
	}

	static function provideByParameters(Operator $operator, string|Carbon $date, $section, $partOfDay) : WorkingDay
	{
		if(is_string($date))
			$date = Carbon::parse($date);

		if($operator->relationLoaded('workingDays'))
		{
			foreach($operator->workingDays as $workingDay)
			{
				if($workingDay->date == $date)
					if(strpos($workingDay->type, $section) !== false)
						if(strpos($workingDay->type, $partOfDay) !== false)
							return $workingDay;
			}

			$workingDay = WorkingDay::gpc()::make();

			$workingDay->operator_id = $operator->getKey();
			$workingDay->date = $date;
			$workingDay->type = "{$section}_{$partOfDay}";

			return $workingDay;
		}


		if ($workingDay = static::getByParameters($operator, $date, $section, $partOfDay))
			return $workingDay;

		$workingDay = WorkingDay::gpc()::make();

		$workingDay->operator_id = $operator->getKey();
		$workingDay->date = $date;
		$workingDay->type = "{$section}_{$partOfDay}";

		return $workingDay;
	}

	static function getQueryByOperatorRange(Operator $operator, Carbon $startsAt, Carbon $endsAt, string $section = null, string $partOfDay = null, array|string $status = null) : Builder
	{
		//TODO('qua usare lo scope per le section e orari');

		$query = WorkingDay::gpc()::where('operator_id', $operator->getKey())
		                   ->whereDate('date', '>=', $startsAt)
		                   ->whereDate('date', '<=', $endsAt);

		if($section && $partOfDay)
			$query->where('type', "{$section}_{$partOfDay}");
		else if($section)
			$query->where('type', 'LIKE', "{$section}_%");
		elseif($partOfDay)
			$query->where('type', 'LIKE', "%_{$partOfDay}");

		if(is_string($status))
			$query->where('status', $status);
		elseif(is_array($status))
			$query->whereIn('status', $status);

		return $query;
	}

	static function getByOperatorRange(Operator $operator, Carbon $startsAt, Carbon $endsAt, string $section = null, string $partOfDay = null, array|string $status = null) : array
	{
		$elements = static::getByOperatorRangeRaw($operator, $startsAt, $endsAt, $section, $partOfDay, $status);

		$results = [];

		foreach($elements as $workingDay)
			$results[$workingDay->getType()][$workingDay->getDate()->format('Y-m-d')] = $workingDay;

		return $results;
	}

	static function getByOperatorRangeRaw(Operator $operator, Carbon $startsAt, Carbon $endsAt, string $section = null, string $partOfDay = null, array|string $status = null) : Collection
	{
		$query = static::getQueryByOperatorRange($operator, $startsAt, $endsAt, $section, $partOfDay, $status);

		return $query->get();
	}

	static function getByOperatorRangeCount(Operator $operator, Carbon $startsAt, Carbon $endsAt, string $section = null, string $partOfDay = null, array|string $status = null) : int
	{
		$query = static::getQueryByOperatorRange($operator, $startsAt, $endsAt, $section, $partOfDay, $status);

		return $query->count();
	}
}