<?php

namespace IlBronza\Operators\Http\Controllers\WorkingDays;

use App\Http\Controllers\Traits\CalendarOperatorsTrait;
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
	use CalendarOperatorsTrait;

	public $allowedMethods = ['printCalendarExcel'];


	public function getDateStart()
	{
		return $this->getStartsAt();
	}

	public function getDateEnd()
	{
		return $this->getEndsAt();
	}

	public function printCalendarExcel(Request $request)
	{
		$this->spreadsheet = new Spreadsheet();
		$this->sheet = $this->spreadsheet->getActiveSheet();

		$rowIndex = 1;

		foreach(['Tipologia', 'Nome', 'Mansione', 'Ferie', 'Flex', 'Rol', 'BB'] as $index => $text)
			$this->sheet->getCell([$index + 1, $rowIndex])->setValue($text);

		$colIndex = $index + 2;

		$startsAt = $this->getStartsAt();

		$days = WorkingDayFieldsGroupsHelper::getDaysArrayResultByDates($startsAt, $this->getEndsAt());

		foreach($days as $dayString)
		{
			$completeDayString = $dayString . " " . $startsAt->format('M');

			$this->sheet->getCell([$colIndex ++, $rowIndex])->setValue("{$completeDayString} M");
			$this->sheet->getCell([$colIndex ++, $rowIndex])->setValue("{$completeDayString} P");
		}

		$elements = $this->getOperators();

		foreach($elements as $element)
		{
			foreach(['real', 'bureau'] as $workingDayType)
			{
				$rowIndex ++;
				$colIndex = 1;

				$this->sheet->getCell([$colIndex ++, $rowIndex])->setValue($workingDayType);
				$this->sheet->getCell([$colIndex ++, $rowIndex])->setValue($element->getName());
				$this->sheet->getCell([$colIndex ++, $rowIndex])->setValue($element->clientOperators->first()?->getContracttypeName());
				$this->sheet->getCell([$colIndex ++, $rowIndex])->setValue($element->calculated_holiday_days);
				$this->sheet->getCell([$colIndex ++, $rowIndex])->setValue(
					$element->calculated_flexibility_days
				);
				$this->sheet->getCell([$colIndex ++, $rowIndex])->setValue($element->calculated_rol_days);
				$this->sheet->getCell([$colIndex ++, $rowIndex])->setValue($element->calculated_bb_days);

				foreach($days as $dayString)
				{
					$completeDayString = $startsAt->format('Y-m-') . $dayString;

					foreach(['am', 'pm'] as $partOfTheDay)
					{
						$workingDay = WorkingDayProviderHelper::provideByParameters(
							$element, $completeDayString, $workingDayType, $partOfTheDay
						);

						$status = $workingDay->status ?? $element->getWorkingDayStatusByDayAndPart($completeDayString, $partOfTheDay);

						$this->sheet->getCell([$colIndex ++, $rowIndex])->setValue(strtoupper($status));
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
