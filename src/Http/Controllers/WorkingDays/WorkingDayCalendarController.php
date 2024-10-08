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
use function request;

class WorkingDayCalendarController extends OperatorCRUD
{
	use CRUDIndexTrait;

	public $mustPrintIntestation = false;

	public $allowedMethods = ['calendar'];

	public function getIndexFieldsArray()
	{
		//WorkingDayFieldsGroupParametersFile
		return WorkingDayFieldsGroupsHelper::getCalendarFieldsGroupsByDates(
			config('operators.models.workingDay.fieldsGroupsFiles.calendar'), $this->getStartsAt(), $this->getEndsAt()
		);
	}

	public function getStartsAt()
	{
		return request()->startsAt ?? Carbon::now()->startOfMonth();
	}

	public function getEndsAt()
	{
		return request()->endsAt ?? Carbon::now()->endOfMonth();
	}

	public function getIndexElements()
	{
		$employments = Employment::gpc()::getPermanentEmployment();

		return Operator::gpc()::with([
			'clientOperators' => function ($query)
			{
				$query->where('client_id', Client::gpc()::getOwnerCompany()->getKey());
			}
		])->with([
				'workingDays' => function ($query)
				{
					$query->whereDate('date', '>=', $this->getStartsAt());
					$query->whereDate('date', '<=', $this->getEndsAt());
				}
			])->byValidEmployments([$employments->getKey()])->distinct()
//		                                        ->take(10)
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
