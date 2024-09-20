<?php

namespace IlBronza\Operators\Helpers\WorkingDay;

use Carbon\Carbon;

use function is_string;

class WorkingDayCheckerHelper
{
	static function isHoliday(Carbon $date)
	{
		if($date->getDaysFromStartOfWeek() >= 5)
			return true;

		return false;
	}

}