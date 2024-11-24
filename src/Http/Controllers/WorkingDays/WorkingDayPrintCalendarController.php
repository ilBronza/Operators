<?php

namespace IlBronza\Operators\Http\Controllers\WorkingDays;

use App\Models\ProjectSpecific\WorkingDay;
use Carbon\Carbon;
use IlBronza\Clients\Models\Client;
use IlBronza\Operators\Helpers\WorkingDay\WorkingDayFieldsGroupsHelper;
use IlBronza\Operators\Helpers\WorkingDay\WorkingDayProviderHelper;
use IlBronza\Operators\Models\Employment;
use IlBronza\Operators\Models\Operator;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use function dd;
use function header;
use function strtoupper;
use function urlencode;

class WorkingDayPrintCalendarController extends WorkingDayCalendarController
{
	public $allowedMethods = ['printCalendarExcel'];


	public function printCalendarExcel(Request $request)
	{
		$this->spreadsheet = new Spreadsheet();
		$this->sheet = $this->spreadsheet->getActiveSheet();

		$rowIndex = 1;

		foreach(['Nome', 'Mansione', 'Ferie', 'Flex', 'Rol', 'BB'] as $index => $text)
			$this->sheet->getCell([$index + 1, $rowIndex])->setValue($text);

		$colIndex = $index + 2;

		$startsAt = $this->getStartsAt();

		$days = WorkingDayFieldsGroupsHelper::getDaysArrayResultByDates($startsAt, $this->getEndsAt());

		foreach($days as $dayString)
		{
			$this->sheet->getCell([$colIndex ++, $rowIndex])->setValue("{$dayString} M");
			$this->sheet->getCell([$colIndex ++, $rowIndex])->setValue("{$dayString} P");
		}


		$elements = $this->getIndexElements()->sortBy(function($a, $b)
		{
			return $a->getName();
		})->values();

		foreach($elements as $element)
		{
			foreach(['bureau', 'real'] as $workingDayType)
			{
				$rowIndex ++;

				$this->sheet->getCell([1, $rowIndex])->setValue($element->getName());
				$this->sheet->getCell([2, $rowIndex])->setValue($element->clientOperators->first()?->getContracttypeName());
				$this->sheet->getCell([3, $rowIndex])->setValue($element->calculated_holiday_days);
				$this->sheet->getCell([4, $rowIndex])->setValue($element->calculated_flexibility_days);
				$this->sheet->getCell([5, $rowIndex])->setValue($element->calculated_rol_days);
				$this->sheet->getCell([6, $rowIndex])->setValue($element->calculated_bb_days);

				$colIndex = 7;

				foreach($days as $dayString)
				{
					$completeDayString = $startsAt->format('Y-m-') . $dayString;

					foreach(['am', 'pm'] as $partOfTheDay)
					{
						$workingDay = WorkingDayProviderHelper::provideByParameters(
							$element, $completeDayString, $workingDayType, $partOfTheDay
						);

						$this->sheet->getCell([$colIndex ++, $rowIndex])->setValue(strtoupper($workingDay->status));
					}
				}
			}
		}


		$writer = new Xlsx($this->spreadsheet);
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="' . $startsAt->translatedFormat('F') . '.xlsx"');
		$writer->save('php://output');
		die();
	}
}
