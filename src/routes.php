<?php

use Illuminate\Support\Facades\Route;


Route::group([
	'middleware' => ['web', 'auth'],
	'prefix' => 'operators-manager',
	'namespace' => 'IlBronza\Operators\Http\Controllers'
	],
	function()
	{
		Route::resource('operators', 'CrudOperatorsController');

		Route::prefix('parent/{parent}')->group(function () {
			Route::resource('operators', 'CrudOperatorsChildrenController')->names('operators.children');
		});

		//START ROUTES PER REORDERING
		Route::get('operators-reorder/{operators?}', 'CrudOperatorsController@reorder')->name('operators.reorder');
		Route::post('operators-reorder', 'CrudOperatorsController@stroreReorder')->name('operators.stroreReorder');
		//STOP ROUTES PER REORDERING

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

