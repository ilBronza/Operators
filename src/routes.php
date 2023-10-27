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
