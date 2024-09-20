<?php

namespace IlBronza\Operators\Http\Controllers\WorkingDays;

use Carbon\Carbon;
use IlBronza\Clients\Models\Client;
use IlBronza\CRUD\Traits\CRUDIndexTrait;
use IlBronza\Operators\Helpers\WorkingDay\WorkingDayFieldsGroupsHelper;
use IlBronza\Operators\Http\Controllers\Operators\OperatorCRUD;
use IlBronza\Operators\Models\Employment;
use IlBronza\Operators\Models\Operator;
use Illuminate\Http\Request;

use function config;

class WorkingDayCalendarController extends OperatorCRUD
{
	use CRUDIndexTrait;

	public $allowedMethods = ['calendar'];

	public function getIndexFieldsArray()
	{
		//WorkingDayFieldsGroupParametersFile
		return WorkingDayFieldsGroupsHelper::getCalendarFieldsGroupsByDates(
			config('operators.models.workingDay.fieldsGroupsFiles.calendar'),
			$request->startsAt ?? Carbon::now()->startOfMonth(),
			$request->endsAt ?? Carbon::now()->endOfMonth(),
		);
	}

	public function getIndexElements()
	{
		$employments = Employment::gpc()::select('id')->where('name', 'LIKE', '%dipend%')->pluck('id')->toArray();

		return Operator::gpc()::with(['clientOperators' => function ($query)
								{
									$query->where('client_id', Client::gpc()::getOneCompany()->getKey());
								}])
		                      ->byValidEmployments($employments)
		                      ->distinct()
		                      ->take(10)
		                      ->get();
	}

	public function calendar(Request $request)
	{
		return $this->_index($request);
	}


	//	static function getDossierFieldsGroupsByFormAndParametersFileName(Form $form, string $parametersFileName)
	//	{
	//		$helper = new FieldsGroupsMergerHelper();
	//
	//		$helper->addFieldsGroupParameters($parametersFileName::getFieldsGroup());
	//
	//		$formParameters = (new static($form))->getFieldsGroup();
	//
	//		$helper->addFieldsGroupParameters(
	//			$formParameters
	//		);
	//
	//		$helper->moveFieldToEnd('mySelfDelete');
	//
	//		return $helper->getMergedFieldsGroups();
	//	}
	//
	//	public function getIndexFieldsArray()
	//	{
	//		return config('operators.models.workingDay.fieldsGroupsFiles.calendar');
	//		return $this->getDossierFieldsGroupsByFormAndParametersFileName(
	//			$this->form, config('operators.models.workingDay.fieldsGroupsFiles.index')
	//		);
	//	}
	//
}
