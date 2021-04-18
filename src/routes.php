<?php

use Illuminate\Support\Facades\Route;


Route::group([
	'middleware' => ['web', 'auth'],
	'prefix' => 'operators-manager',
	'namespace' => 'IlBronza\Operator\Http\Controllers'
	],
	function()
	{
		Route::resource('operators', 'CrudOperatorController');

		Route::prefix('parent/{parent}')->group(function () {
			Route::resource('operators', 'CrudOperatorChildrenController')->names('operators.children');
		});

		//START ROUTES PER REORDERING
		Route::get('operators-reorder/{operator?}', 'CrudOperatorController@reorder')->name('operators.reorder');
		Route::post('operators-reorder', 'CrudOperatorController@stroreReorder')->name('operators.stroreReorder');
		//STOP ROUTES PER REORDERING

	});
