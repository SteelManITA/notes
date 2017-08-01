<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Client;

class UserController extends Controller
{
    public function register(Request $request) {
		$valid = validator($request->only('email', 'name', 'password'), [
			'name' => 'required|string|max:255',
			'email' => 'required|string|email|max:255|unique:users',
			'password' => 'required|string|min:6',
			]);

		if ($valid->fails()) {
			$jsonError=response()->json($valid->errors()->all(), 400);
			return \Response::json($jsonError);
		}

		$data = request()->only('email','name','password');

		$user = User::create([
			'name' => $data['name'],
			'email' => $data['email'],
			'password' => bcrypt($data['password']),
			]);
		/*
    	// And created user until here.
		$client = Client::where('password_client', 1)->first();

    	// Is this $request the same request? I mean Request $request? Then wouldn't it mess the other $request stuff? Also how did you pass it on the $request in $proxy? Wouldn't Request::create() just create a new thing?
		$request->request->add([
			'grant_type'    => 'password',
			'client_id'     => $client->id,
			'client_secret' => $client->secret,
			'username'      => $data['email'],
			'password'      => $data['password'],
			'scope'         => null,
			]);

    	// Fire off the internal request. 
		$token = Request::create(
			'oauth/token',
			'POST'
			);
		return \Route::dispatch($token);
		*/
	}
}
