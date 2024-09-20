<?php

namespace IlBronza\Operators\Helpers\WorkingDay;

use Carbon\Carbon;
use IlBronza\Datatables\Providers\FieldsGroupsMergerHelper;
use IlBronza\Operators\Models\WorkingDay;

use function substr;

class WorkingDayFieldsGroupsHelper
{
	static function getFieldsParametersByDates(Carbon $startsAt, Carbon $endsAt)
	{
		$fields = [];

		$date = $startsAt->copy();

		$list = WorkingDay::gpc()::getWorkingDaySelectArray();

		while ($date->lte($endsAt))
		{
			$day = $date->format('Y-m-d');

			$holidayClass = WorkingDayCheckerHelper::isHoliday($date)? 'holiday' : 'normal';

			$dayLabel = substr($date->translatedFormat('D'), 0,2) . $date->format('d');

			foreach([
				'real',
				        'bureau'
			        ] as $section)
				foreach(['am', 'pm'] as $partOfTheDay)
					$fields["mySelf{$day}_{$section}_{$partOfTheDay}"] = [
						'type' => 'utilities.view',
						'tDHtmlClasses' => [$section, $partOfTheDay, $holidayClass],
						'headerHtmlClasses' => ['dayheader', $section, $partOfTheDay, $holidayClass],
						'translatedName' => ($partOfTheDay == 'am') ? $date->translatedFormat('D') : $date->format('d'),
						'viewName' => 'days._typeSelect',
						'viewParameters' => [
							'day' => $day,
							'section' => $section,
							'partOfTheDay' => $partOfTheDay,
							'list' => $list,
							'fieldName' => "dayType[$section][$partOfTheDay][{$day}]"
						],
						'viewParametersGetter' => 'getWorkingDayDatatableFieldParameters'
					];


			$date->addDays(1);
		}

		return [
			'fields' => $fields
		];

	}

	static function getCalendarFieldsGroupsByDates(string $parametersFileName, Carbon $startsAt, Carbon $endsAt)
	{
		$helper = new FieldsGroupsMergerHelper();

		$helper->addFieldsGroupParameters($parametersFileName::getFieldsGroup());

		$formParameters = static::getFieldsParametersByDates($startsAt, $endsAt);

		$helper->addFieldsGroupParameters(
			$formParameters
		);

		$helper->moveFieldToEnd('mySelfDelete');

		return $helper->getMergedFieldsGroups();
	}

}