<?php

use Illuminate\Support\Facades\Route;


Route::group([
	'middleware' => ['web', 'auth'],
	'namespace' => 'ilBronza\Operator\Http\Controllers'
	],
	function()
	{
		Route::resource('operators', 'CrudOperatorController');
	});

Route::get('asdasd', 'asdcontroller@masd')->name('operators.children.create');


