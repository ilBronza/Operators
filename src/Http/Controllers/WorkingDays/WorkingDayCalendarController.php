<?php

namespace IlBronza\Operators\Http\Controllers\WorkingDays;

use Carbon\Carbon;
use IlBronza\Buttons\Button;
use IlBronza\Clients\Models\Client;
use IlBronza\CRUD\Traits\CRUDIndexTrait;
use IlBronza\Operators\Helpers\WorkingDay\WorkingDayFieldsGroupsHelper;
use IlBronza\Operators\Http\Controllers\Operators\OperatorCRUD;
use IlBronza\Operators\Models\Employment;
use IlBronza\Operators\Models\Operator;
use Illuminate\Http\Request;

use function app;
use function config;
use function request;
use function route;
use function trans;

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

	public function addIndexButtons()
	{
		$this->getTable()->createPostButton([
			'href' => app('operators')->route('workingDays.printCalendarExcel'),
			'translatedText' => trans('crud::crud.excel'),
			'icon' => 'file-excel'
		]);

		$this->getTable()->createPostButton([
			'href' => route('project.printPresencesBook'),
			'text' => 'buttons.printPresencesBook',
			'icon' => 'calendar'
		]);
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

		$operators = Operator::gpc()::active()->with([
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
		                                        ->get();

		return $operators;
	}

	public function calendar(Request $request)
	{
		return $this->_index($request);
	}
}
