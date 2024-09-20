<?php

namespace IlBronza\Operators\Http\Controllers\WorkingDays;

use IlBronza\Operators\Helpers\WorkingDay\WorkingDayProviderHelper;
use IlBronza\Operators\Models\Operator;
use IlBronza\Operators\Models\WorkingDay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function array_keys;
use function implode;

class WorkingDayUpdateEditController extends WorkingDayCRUDController
{
	public $allowedMethods = ['updateByOperatorDay'];

	public function updateByOperatorDay(Request $request, $operator, $day)
	{
		$params = Validator::make(array_merge($request->all(), $request->route()->parameters()), [
			'value' => 'string|required|in:' . implode(',', array_keys(WorkingDay::gpc()::getWorkingDaySelectArray())),
			'fieldData.partofday' => 'required|string|in:am,pm',
			'fieldData.section' => 'required|string|in:bureau,real',
			'day' => 'date|required|date_format:Y-m-d',
		])->validate();

		$operator = Operator::getProjectClassname()::find($operator);
		
		$workingDay = WorkingDayProviderHelper::provideByParameters(
			$operator,
			$day,
			$params['fieldData']['section'],
			$params['fieldData']['partofday']
		);

		$workingDay->status = $params['value'];

		$workingDay->save();

		$updateParameters = [];
		$updateParameters['success'] = true;

		$updateParameters['ibaction'] = true;
		$updateParameters['action'] = 'refreshRow';
		return $updateParameters;
	}
}
