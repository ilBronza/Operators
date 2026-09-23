<?php

namespace IlBronza\Operators\Http\Controllers\Contracttypes;

use IlBronza\Operators\Services\ContracttypeMerger;
use IlBronza\Operators\Models\Contracttype;
use IlBronza\Ukn\Ukn;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ContracttypeMergeStoreController extends ContracttypeCRUD
{
	public $allowedMethods = ['store'];

	public function store(Request $request, ContracttypeMerger $merger) : RedirectResponse
	{
		$values = $request->validate([
			...ContracttypeMergePreviewController::selectionRules(),
			'master_id' => ['required', 'string', 'max:255'],
			'confirm' => ['accepted'],
		]);
		$result = $merger->merge($values['ids'], $values['master_id']);

		Ukn::s(e("Accorpamento completato su {$result['master_name']}: {$result['created']} operatori aggiunti, {$result['already_associated']} già associati, {$result['archived']} mansioni archiviate."));

		return redirect(Contracttype::gpc()::make()->getIndexUrl());
	}
}
