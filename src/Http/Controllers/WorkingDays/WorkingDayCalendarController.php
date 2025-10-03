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

	public $rowSelectCheckboxes = true;
	public $mustPrintIntestation = false;

	public $allowedMethods = ['calendar'];

	public function getIndexFieldsArray()
	{
		//WorkingDayFieldsGroupParametersFile

		/**
		 * i dati vengono caricati dal metodo getWorkingDaysDatatableFieldParameters
		 *
		 *
		 */
		return WorkingDayFieldsGroupsHelper::getCalendarFieldsGroupsByDates(
			config('operators.models.workingDay.fieldsGroupsFiles.calendar'), $this->getStartsAt(), $this->getEndsAt()
		);
	}

	public function addIndexButtons()
	{
		$this->getTable()->setCaption(trans('operators::workingDays.calendarFromTo', [
			'from' => $this->getStartsAt()->format('d/m/Y'),
			'to' => $this->getEndsAt()->format('d/m/Y')
		]));

		$this->getTable()->createPostButton([
			'href' => app('operators')->route('workingDays.printCalendarExcel', [
				'year' => request()->year,
				'month' => request()->month
			]),
			'translatedText' => trans('crud::crud.excel'),
			'icon' => 'file-excel'
		]);

		$this->getTable()->createPostButton([
			'href' => route('project.printPresencesBook', ['year' => request()->year, 'month' => request()->month]),
			'text' => 'buttons.printPresencesBook',
			'icon' => 'calendar'
		]);

		$year = request()->year;
		$month = request()->month;

		if((! $year)||(! $month))
			return ;

		$date = Carbon::createFromDate($year, $month, 1, 'Europe/Rome');

		$date->hour = 0;
		$date->minute = 0;
		$date->second = 0;

		$date->lastOfMonth();

		foreach($this->getIndexElements() as $element)
			foreach([
				'holidays_reset_date', 
				'flexibility_reset_date', 
				'rol_reset_date', 
				'bb_reset_date'] as $field)
				if($element->provideforcedValidClientOperatorModelForExtraFields()->$field < $date)
					$printButton = true;

		if(! ($printButton ?? false))
			return;

		$this->getTable()->addButton(
			Button::create([
			'href' => app('operators')->route('workingDays.consolidateCoefficients', 
				[
					'year' => $year,
					'month' => $month
				]),
			'text' => 'buttons.consolidateWorkingDaysCoefficients',
			'icon' => 'database'
		]));
	}

	public function getStartsAt()
	{
		if(($year = request()->year)&&($month = request()->month))
			return Carbon::createFromDate($year, $month)->startOfMonth();

		return request()->startsAt ?? Carbon::now()->startOfMonth();
	}

	public function getEndsAt()
	{
		if(($year = request()->year)&&($month = request()->month))
			return Carbon::createFromDate($year, $month)->endOfMonth();

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
		])
//			->where('id', '2613b0a9-5801-460a-9013-7ebfec03c963')
		                                      ->with([
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
		$GLOBALS['ends_at'] = $this->getEndsAt();

		return $this->_index($request);
	}
}
