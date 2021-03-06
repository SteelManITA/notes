<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
	public function login(Request $request) {
		$validationRules = [
		'username' => 'required',
		'password' => 'required',
		];
		$this->validate($request, $validationRules);

		$input = $request->intersect(['username', 'password']);

		$username = $input['username'];
		$password = $input['password'];

		$client = new \GuzzleHttp\Client;

		try {

			$response = $client->post( env('APP_URL') . '/oauth/token', [
				'form_params' => [
				'client_id' => env('CLIENT_ID'),
				'client_secret' => env('CLIENT_SECRET'),
				'grant_type' => 'password',
				'username' => $username,
				'password' => $password,
				'scope' => '*',
				]
				]);

			return $response->getBody();

		} catch (\GuzzleHttp\Exception\BadResponseException $e) {
			return $e->getResponse();
		}
	}

}
