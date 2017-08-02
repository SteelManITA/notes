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
	Route::post('/register', 'UserController@register');

	Route::group(['middleware' => 'auth:api'], function() {

		Route::group(['prefix' => 'note'], function() {
			Route::get('/', 'NoteController@get');
			Route::post('/add', 'NoteController@add');
			Route::post('/{note_id}/edit', 'NoteController@edit');
			Route::delete('/{note_id}/delete', 'NoteController@delete');

			Route::group(['prefix' => '{note_id}/collaborator'], function(){
				Route::get('/', 'SharedNotesController@get');
				Route::post('/add', 'SharedNotesController@add');
				Route::delete('{collaborator_id}/delete', 'SharedNotesController@delete');
			});

		});

		
	});

});