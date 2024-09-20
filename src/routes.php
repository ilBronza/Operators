<?php

use IlBronza\Operators\Http\Controllers\CrudOperatorsController;
use Illuminate\Support\Facades\Route;


Route::group([
	'middleware' => ['web', 'auth'],
	'prefix' => 'operators-manager',
	'as' => config('operators.routePrefix')
	],
	function()
	{
		Route::prefix('parent/{parent}')->group(function () {
			Route::resource('operators', 'CrudOperatorsChildrenController')->names('operators.children');
		});

		//START ROUTES PER REORDERING
		Route::get('operators-reorder/{operators?}', 'CrudOperatorsController@reorder')->name('operators.reorder');
		Route::post('operators-reorder', 'CrudOperatorsController@stroreReorder')->name('operators.stroreReorder');
		//STOP ROUTES PER REORDERING


		Route::group(['prefix' => 'operators'], function()
		{

			Route::get('{operator}/avatar-fetcher', [Operators::getController('operator', 'avatar'), 'avatarFetcher'])->name('operators.logoFetcher');

			Route::get('', [Operators::getController('operator', 'index'), 'index'])->name('operators.index');
			Route::get('create', [Operators::getController('operator', 'create'), 'create'])->name('operators.create');
			Route::post('', [Operators::getController('operator', 'store'), 'store'])->name('operators.store');
			Route::get('{operator}', [Operators::getController('operator', 'show'), 'show'])->name('operators.show');
			Route::get('{operator}/edit', [Operators::getController('operator', 'edit'), 'edit'])->name('operators.edit');
			Route::put('{operator}', [Operators::getController('operator', 'edit'), 'update'])->name('operators.update');

			Route::delete('{operator}/delete', [Operators::getController('operator', 'destroy'), 'destroy'])->name('operators.destroy');
		});



		Route::group(['prefix' => 'contracttypes'], function()
		{
			Route::get('', [Operators::getController('contracttype', 'index'), 'index'])->name('contracttypes.index');
			Route::get('create', [Operators::getController('contracttype', 'create'), 'create'])->name('contracttypes.create');
			Route::post('', [Operators::getController('contracttype', 'store'), 'store'])->name('contracttypes.store');
			Route::get('{contracttype}', [Operators::getController('contracttype', 'show'), 'show'])->name('contracttypes.show');
			Route::get('{contracttype}/edit', [Operators::getController('contracttype', 'edit'), 'edit'])->name('contracttypes.edit');
			Route::put('{contracttype}', [Operators::getController('contracttype', 'edit'), 'update'])->name('contracttypes.update');

			Route::delete('{contracttype}/delete', [Operators::getController('contracttype', 'destroy'), 'destroy'])->name('contracttypes.destroy');
		});

		Route::group(['prefix' => 'working-days'], function()
		{
			Route::put('by-operator/{operator}/day/{day}', [Operators::getController('workingDay', 'update'), 'updateByOperatorDay'])->name('workingDays.updateByOperatorDay');
			Route::get('calendar', [Operators::getController('workingDay', 'calendar'), 'calendar'])->name('workingDays.calendar');
		});


		Route::group(['prefix' => 'client-operators'], function()
		{
			Route::get('', [Operators::getController('clientOperator', 'index'), 'index'])->name('clientOperators.index');
			Route::get('create', [Operators::getController('clientOperator', 'create'), 'create'])->name('clientOperators.create');
			Route::post('', [Operators::getController('clientOperator', 'store'), 'store'])->name('clientOperators.store');
			Route::get('{clientOperator}', [Operators::getController('clientOperator', 'show'), 'show'])->name('clientOperators.show');
			Route::get('{clientOperator}/edit', [Operators::getController('clientOperator', 'edit'), 'edit'])->name('clientOperators.edit');
			Route::put('{clientOperator}', [Operators::getController('clientOperator', 'edit'), 'update'])->name('clientOperators.update');

			Route::delete('{clientOperator}/delete', [Operators::getController('clientOperator', 'destroy'), 'destroy'])->name('clientOperators.destroy');
		});

		Route::group(['prefix' => 'operator-contracttypes'], function()
		{
			Route::get('', [Operators::getController('operatorContracttype', 'index'), 'index'])->name('operatorContracttypes.index');
			Route::get('create', [Operators::getController('operatorContracttype', 'create'), 'create'])->name('operatorContracttypes.create');
			Route::post('', [Operators::getController('operatorContracttype', 'store'), 'store'])->name('operatorContracttypes.store');
			Route::get('{operatorContracttype}', [Operators::getController('operatorContracttype', 'show'), 'show'])->name('operatorContracttypes.show');
			Route::get('{operatorContracttype}/edit', [Operators::getController('operatorContracttype', 'edit'), 'edit'])->name('operatorContracttypes.edit');
			Route::put('{operatorContracttype}', [Operators::getController('operatorContracttype', 'edit'), 'update'])->name('operatorContracttypes.update');

			Route::delete('{operatorContracttype}/delete', [Operators::getController('operatorContracttype', 'destroy'), 'destroy'])->name('operatorContracttypes.destroy');
		});



		Route::group(['prefix' => 'employments'], function()
		{
			Route::get('', [Operators::getController('employment', 'index'), 'index'])->name('employments.index');
			Route::get('create', [Operators::getController('employment', 'create'), 'create'])->name('employments.create');
			Route::post('', [Operators::getController('employment', 'store'), 'store'])->name('employments.store');
			Route::get('{employment}', [Operators::getController('employment', 'show'), 'show'])->name('employments.show');
			Route::get('{employment}/edit', [Operators::getController('employment', 'edit'), 'edit'])->name('employments.edit');
			Route::put('{employment}', [Operators::getController('employment', 'edit'), 'update'])->name('employments.update');

			Route::delete('{employment}/delete', [Operators::getController('employment', 'destroy'), 'destroy'])->name('employments.destroy');
		});



	});



		// Route::group([
		// 	'prefix' => 'operators',
		// ], function()
		// {
		// 	Route::get('', [config('clients.models.operator.controllers.index'), 'index'])->name('operators.index');

		// 	Route::get('create', [Clients::getController('operator', 'create'), 'create'])->name('operators.create');
		// 	Route::post('', [Clients::getController('operator', 'store'), 'create'])->name('operators.store');

		// 	Route::get('{operator}', [Clients::getController('operator', 'show'), 'show'])->name('operators.show');
		// 	Route::delete('{operator}/delete', [Clients::getController('operator', 'destroy'), 'destroy'])->name('operators.destroy');

		// 	Route::get('{operator}/edit', [Clients::getController('operator', 'edit'), 'edit'])->name('operators.edit');
		// 	Route::put('{operator}', [Clients::getController('operator', 'edit'), 'update'])->name('orders.update');



		// });

