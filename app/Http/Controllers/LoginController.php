<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class LoginController extends Controller
{
	public function index () {
		set_layout_arg([
			"subtitle" => "Inicia Sesión",
			"empty_layout" => true,
			"background_content" => "#ffffff",
			"fluid_container" => true,
			"script" => [
				// asset("js/login.js")
			]
		]);
		return layout_view("login", []);
	}

	public function validation (Request $request) {
		$login = curlSendRequest("GET", env("SESSION_HOSTNAME") . "/login", [], [ "Authorization: Basic ".base64_encode($request->username.":".$request->password) ]);
		if($login->data == "response"){
			$user = curlSendRequest("GET",  env("SESSION_HOSTNAME") . "/users/me", [], [ "Authorization: Bearer {$login->response->token}" ]);

			if($user->data == "response"){
				$session = [
					"authenticated" => [
						"created_at" => date("Y-m-d H:i:s"),
						"until_at" => $login->response->expires_in
					],
					"session" => $login->response,
					"user" => $user->response->user
				];
			}else{
				return abort(401);
			}
		}else{
			return abort(401);
		}

		if(session($session))
			return response(200);
	}

	public function logout () {
		session()->forget(["user", "authenticated"]);
		return redirect()->route("login");
	}
}
