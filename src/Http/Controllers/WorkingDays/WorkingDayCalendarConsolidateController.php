<?php

namespace IlBronza\Operators\Http\Controllers\WorkingDays;

use Carbon\Carbon;
use IlBronza\Operators\Http\Controllers\WorkingDays\WorkingDayCalendarController;
use IlBronza\Ukn\Ukn;

class WorkingDayCalendarConsolidateController extends WorkingDayCalendarController
{
	public $allowedMethods = ['consolidate'];

	public function consolidate(string $year, string $month)
	{
		$GLOBALS['ends_at'] = $this->getEndsAt();

		$elements = $this->getIndexElements();

		$year = request()->year;
		$month = request()->month;

		if((! $year)||(! $month))
			return ;

		$date = Carbon::createFromDate($year, $month, 1, 'Europe/Rome');

		$date->lastOfMonth();

		$date->hour = 0;
		$date->minute = 0;
		$date->second = 0;

		foreach($elements as $operator)
		{
			$changed = [];

			$clientOperator = $operator->provideforcedValidClientOperatorModelForExtraFields();

			foreach([
				'holidays_reset_date' => 'calculated_holiday_days', 
				'flexibility_reset_date' => 'calculated_flexibility_days', 
				'rol_reset_date' => 'calculated_rol_days', 
				'bb_reset_date' => 'calculated_bb_days'
			] as $field => $valueField)
				if($clientOperator->$field < $date)
			{
				$changed[] = $field;
				$_field = str_replace('_date', '', $field);

				$clientOperator->$field = $date;
				$clientOperator->$_field = $operator->$valueField;
			}

			if(count($changed))
			{
				Ukn::s('consolidati: ' . implode(" - ", $changed) . ' per ' . $operator->getName());

				$clientOperator->save();
			}
		}

		return back();
	}
}
