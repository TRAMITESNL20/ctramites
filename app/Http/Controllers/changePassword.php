<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChangePassword extends Controller {
    public function index () {
		set_layout_arg([
			"subtitle" => "cambiar contraseña",
			"fluid_container"=> true,
			"script" => [
				asset("js/changepassword.js"),
				asset("https://unpkg.com/bootstrap-show-password@1.2.1/dist/bootstrap-show-password.min.js")
			]
		]);
		$user = session()->get("user");
		$session = session()->get("session")->token;
	
		return layout_view("changePassword", ['user' => $user , "sesion" => $session]);

	}
}
