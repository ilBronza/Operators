<?php

namespace IlBronza\Operators\Http\Controllers\WorkingDays;

use Carbon\Carbon;
use IlBronza\Operators\Helpers\WorkingDay\WorkingDayProviderHelper;
use IlBronza\Operators\Models\Operator;
use IlBronza\Operators\Models\WorkingDay;
use IlBronza\Products\Models\Sellables\SellableSupplier;
use IlBronza\Products\Providers\Helpers\RowsHelpers\RowsFinderHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function array_keys;
use function array_merge;
use function dd;
use function implode;
use function is_null;

class WorkingDayUpdateEditController extends WorkingDayCRUDController
{
	public $allowedMethods = ['updateByOperatorDay', 'updateBySellableSupplierDay', 'getDutyPopup'];

	public function updateByOperatorDay(Request $request, $operator, $day)
	{
		$params = Validator::make(array_merge($request->all(), $request->route()->parameters()), [
			'value' => 'string|nullable|in:' . implode(',', array_keys(WorkingDay::gpc()::getWorkingDaySelectArray())),
			'fieldData.partofday' => 'required|string|in:am,pm',
			'fieldData.section' => 'required|string|in:bureau,real',
			'day' => 'date|required|date_format:Y-m-d',
		])->validate();


		$operator = Operator::gpc()::find($operator);


		//DOGODO URGENTE annullare questo crea e cancella in favore di un cancella SE ESISTE
		if(is_null($params['value']))
		{
			$workingDay = WorkingDayProviderHelper::provideByParameters(
				$operator,
				$day,
				$params['fieldData']['section'],
				$params['fieldData']['partofday']
			);

			$workingDay->forceDelete();
		}
		else
		{
			$workingDay = WorkingDayProviderHelper::provideByParameters(
				$operator,
				$day,
				$params['fieldData']['section'],
				$params['fieldData']['partofday']
			);

			$workingDay->status = $params['value'];

			$workingDay->save();
		}

		$updateParameters = [];
		$updateParameters['success'] = true;

		$updateParameters['ibaction'] = true;
		$updateParameters['action'] = 'refreshRow';
		return $updateParameters;
	}

	public function updateBySellableSupplierDay(Request $request, $sellableSupplier, $day)
	{
		$params = Validator::make(array_merge($request->all(), $request->route()->parameters()), [
			'value' => 'string|required|in:' . implode(',', array_keys(WorkingDay::gpc()::getWorkingDaySelectArray())),
			'fieldData.partofday' => 'required|string|in:all',
			'fieldData.section' => 'required|string|in:real',
			'day' => 'date|required|date_format:Y-m-d',
		])->validate();

		$sellableSupplier = SellableSupplier::gpc()::find($sellableSupplier);

		$workingDay = WorkingDayProviderHelper::provideByParameters(
			$sellableSupplier,
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

	public function getDutyPopup(Request $request, $operator, $day)
	{
		$carbonDate = Carbon::createFromFormat('Y-m-d', $day);

		$operator = Operator::gpc()::with('supplier.sellableSuppliers')->find($operator);
		$sellableSuppliersIds = $operator->supplier->sellableSuppliers->pluck('id');

		if(count($rows = RowsFinderHelper::findOrderrowsByDateQuery($carbonDate)->bySellableSuppliers($sellableSuppliersIds)->with('modelContainer')->get()) == 0)
			return 'Nessuna commessa trovata';

		$result = ['Commessa:'];

		foreach($rows as $row)
			$result[] = "<a target='_blank' href='{$row->modelContainer?->getEditUrl()}'>{$row->modelContainer?->getName()}</a>";

		return implode('<br>', $result);
	}
}
