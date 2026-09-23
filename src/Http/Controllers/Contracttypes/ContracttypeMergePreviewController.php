<?php

namespace IlBronza\Operators\Http\Controllers\Contracttypes;

use IlBronza\Operators\Services\ContracttypeMerger;
use IlBronza\Operators\Models\Contracttype;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ContracttypeMergePreviewController extends ContracttypeCRUD
{
	public $allowedMethods = ['create', 'form'];

	public function create(Request $request, ContracttypeMerger $merger) : RedirectResponse
	{
		$values = $request->validate(self::selectionRules());
		$merger->selection($values['ids']);

		return redirect(app('operators')->route('contracttypes.merge.form', ['ids' => $values['ids']]));
	}

	public function form(Request $request, ContracttypeMerger $merger) : View|RedirectResponse
	{
		try
		{
			$values = $request->validate(self::selectionRules());
			return view('operators::contracttypes.merge', $merger->preview($values['ids']));
		}
		catch (ValidationException $exception)
		{
			return redirect(Contracttype::gpc()::make()->getIndexUrl())->withErrors($exception->errors());
		}
	}

	public static function selectionRules() : array
	{
		return [
			'ids' => ['required', 'array', 'min:1'],
			'ids.*' => ['required', 'string', 'distinct', 'max:255'],
		];
	}
}
