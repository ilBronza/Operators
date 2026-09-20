<?php

namespace IlBronza\Operators\Http\Controllers\WorkingCounters;

use IlBronza\Operators\Models\Operator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class WorkingCountersByOperatorEditUpdateController extends WorkingCounterCRUD
{
	public $allowedMethods = ['editByOperator', 'updateByOperator'];

	public function editByOperator(Request $request, string $operator)
	{
		$operator = $this->findOperator($operator);

		$this->authorizeOperatorUpdate($operator);
		$this->rememberCallerRow($request);

		$workingCounters = $operator->workingCounters()
			->orderBy('type')
			->orderBy('reset_date')
			->get();

		return view('operators::workingCounters.editByOperator', compact('operator', 'workingCounters'));
	}

	public function updateByOperator(Request $request, string $operator)
	{
		$operator = $this->findOperator($operator);

		$this->authorizeOperatorUpdate($operator);

		$parameters = $request->validate([
			'workingCounters' => ['required', 'array'],
			'workingCounters.*.id' => [
				'required',
				'string',
				Rule::exists(config('operators.models.workingCounter.table'), 'id')
					->where('operator_id', $operator->getKey()),
			],
			'workingCounters.*.amount' => ['required', 'numeric'],
			'workingCounters.*.reset_date' => ['required', 'date'],
			'workingCounters.*.expired_at' => ['nullable', 'date', 'after_or_equal:workingCounters.*.reset_date'],
		]);

		$workingCounters = $operator->workingCounters()
			->whereKey(collect($parameters['workingCounters'])->pluck('id'))
			->get()
			->keyBy(fn ($workingCounter) => $workingCounter->getKey());

		DB::transaction(function () use ($parameters, $workingCounters)
		{
			foreach ($parameters['workingCounters'] as $parameters)
			{
				$workingCounter = $workingCounters->get($parameters['id']);

				$workingCounter->amount = $parameters['amount'];
				$workingCounter->reset_date = $parameters['reset_date'];
				$workingCounter->expired_at = $parameters['expired_at'] ?? null;
				$workingCounter->save();
			}
		});

		return redirect()->route('iframe.close');
	}

	protected function findOperator(string $operator) : Operator
	{
		return Operator::gpc()::findOrFail($operator);
	}

	protected function authorizeOperatorUpdate(Operator $operator) : void
	{
		abort_unless($operator->userCanUpdate(Auth::user()), 403, 'User can\'t update');
	}

	protected function rememberCallerRow(Request $request) : void
	{
		if ($callerTableName = $request->input('callertablename'))
			session()->put('callertablename', $callerTableName);

		if ($callerRowId = $request->input('callerrowid'))
			session()->put('callerrowid', $callerRowId);
	}
}
