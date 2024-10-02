<?php

namespace IlBronza\Operators\Helpers\WorkingDay;

use Carbon\Carbon;

class WorkingDayCalculationsHelper
{
	static function getMonthsSince(Carbon $startsAt, Carbon $endsAt = null) : float
	{
		if (! $endsAt)
			$endsAt = Carbon::now();

		$months = $startsAt->diffInMonths($endsAt);

		$daysToToday = (int) $endsAt->format('d');
		$daysOfTheMonth = (int) $endsAt->endOfMonth()->format('d');

		return $months + ($daysToToday / $daysOfTheMonth);
	}

	//	static function getRolHoursByWorkingDayStatus(string $workingstatus)
	//	{
	//		if($workingstatus == 'p')
	//			return 4;
	//
	//		if($workingstatus == 'p1')
	//			return 1;
	//
	//		if($workingstatus == 'p2')
	//			return 2;
	//
	//		if($workingstatus == 'p3')
	//			return 3;
	//
	//		if($workingstatus == 'p4')
	//			return 4;
	//
	//		if($workingstatus == 'ps')
	//			return 1;
	//
	//		dd('questo qua cossa xe?'  . $workingstatus);
	//	}
}