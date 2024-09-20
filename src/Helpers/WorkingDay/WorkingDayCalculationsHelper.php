<?php

namespace IlBronza\Operators\Helpers\WorkingDay;

use Carbon\Carbon;

use IlBronza\Operators\Models\Operator;

use IlBronza\Operators\Models\WorkingDay;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

use function config;
use function dd;
use function is_array;
use function is_null;
use function is_string;

class WorkingDayCalculationsHelper
{
	static function getMonthsSince(Carbon $startsAt, Carbon $endsAt = null) : float
	{
		if(! $endsAt)
			$endsAt = Carbon::now();

		$months = $startsAt->diffInMonths($endsAt);

		$daysToToday = (int) $endsAt->format('d');
		$daysOfTheMonth = (int) $endsAt->endOfMonth()->format('d');

		return $months + ($daysToToday / $daysOfTheMonth);
	}

	static function getRolHoursByWorkingDayStatus(string $workingstatus)
	{
		if($workingstatus == 'p')
			return 4;

		if($workingstatus == 'p1')
			return 1;

		if($workingstatus == 'p2')
			return 2;

		if($workingstatus == 'p3')
			return 3;

		if($workingstatus == 'p4')
			return 4;

		if($workingstatus == 'ps')
			return 1;

		dd('questo qua cossa xe?'  . $workingstatus);
	}
}