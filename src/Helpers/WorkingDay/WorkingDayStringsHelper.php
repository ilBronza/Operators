<?php

namespace IlBronza\Operators\Helpers\WorkingDay;

use Carbon\Carbon;

use function is_string;

class WorkingDayStringsHelper
{
	static function getHtmlClassByDate(string|Carbon $date)
	{
		if (is_string($date))
			$date = Carbon::createFromFormat('Y-m-d', $date);

		$holiday = WorkingDayCheckerHelper::isWeekendOrHoliday($date);

		return ($holiday) ? 'holiday' : 'workingday';
	}

	static function getLabelByDate(string|Carbon $date)
	{
		if (is_string($date))
			$date = Carbon::createFromFormat('Y-m-d', $date);

		$holiday = WorkingDayCheckerHelper::isWeekendOrHoliday($date);

		return "<span class='" . (($holiday) ? 'uk-text-danger' : '') . "' >{$date->format('d m Y')} <span class=\"uk-align-right uk-text-bold\">{$date->translatedFormat('D')}</span></span>";
	}
}