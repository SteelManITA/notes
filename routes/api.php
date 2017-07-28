<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['prefix' => 'v1'], function () {

	Route::post('/login', 'LoginController@login');

	Route::group(['middleware' => 'auth:api'], function() {
		Route::get('/note', 'NoteController@index');
		Route::post('/note/add', 'NoteController@add');
		Route::post('/note/edit/{id}', 'NoteController@edit');
		Route::delete('/note/delete/{id}', 'NoteController@delete');
	});

});