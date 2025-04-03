<?php

namespace IlBronza\Operators\Helpers\WorkingDay;

use Carbon\Carbon;
use IlBronza\Operators\Models\Operator;
use Illuminate\Support\Collection;

use function dd;

class WorkingDayCalculationsHelper
{
	static function getMonthsSince(Carbon $startsAt, Carbon $endsAt = null) : float
	{
		if (! $endsAt)
			$endsAt = Carbon::now();

		$startsAt = $startsAt->copy();
		$endsAt = $endsAt->copy();

		$daysStartToEndOfMonth = $startsAt->daysInMonth - $startsAt->day;
		$daysEndFromStartOfMonth = $endsAt->day;

		$startsAt->startOfMonth()->addMonths(1);
		$endsAt->startOfMonth();

		$monthsDiff = $startsAt->diffInMonths($endsAt);

		$totalDaysDiff = $daysStartToEndOfMonth + $daysEndFromStartOfMonth;

		$fractionalMonth = $totalDaysDiff / $endsAt->daysInMonth;

		$total = $monthsDiff + $fractionalMonth;

		return round($total, 2); // Es. 1.13 mesi
	}

	static function calculateStatusByDate(Operator $operator, Carbon $date, string $part, Collection $rows = null) : ?string
	{
		if (! $rows)
			dd('poblema, caricare le righe');

		if (! is_string($date))
			$date = $date->format('Y-m-d');

		$row = $rows->first(function ($item) use ($date)
		{
			if (($date >= $item->starts_at->format('Y-m-d')) && ($date <= $item->ends_at->format('Y-m-d')))
				return $item;
		});

		//		if (in_array($operator->getName(), ['Calzarotto Monica', 'Mandolaro Stella', 'Scattolin Valentina']))
		if (! $row)
		{
			if ($operator->getUser()?->hasRole('headquarterWorker'))
				if ((($_date = Carbon::createFromFormat('Y-m-d', $date))->isWeekday()) && ($_date <= Carbon::now()))
					return 's';

			if ((! ($_date = Carbon::createFromFormat('Y-m-d', $date))->isWeekday()) && ($_date <= Carbon::now()))
				return 'rs';

			return null;
		}

		if ($row->getHalfStart())
			if ($field->viewParameters['partOfTheDay'] == 'am')
				if ($row->starts_at->format('Y-m-d') == $date)
					return null;

		if ($row->getHalfEnd())
			if ($field->viewParameters['partOfTheDay'] == 'pm')
				if ($row->ends_at->format('Y-m-d') == $date)
					return null;

		return 'c';
	}
}