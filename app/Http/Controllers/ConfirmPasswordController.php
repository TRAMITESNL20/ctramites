<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ConfirmPasswordController extends Controller
{
    public function index () {

		set_layout_arg([
            "subtitle" => "Confirmar la contraseña nueva",
            "empty_layout" => true,
            "background_content" => "#ffffff",
            "script" => [
              assets("js/confirmpassword.js"),
              asset("https://unpkg.com/bootstrap-show-password@1.2.1/dist/bootstrap-show-password.min.js")
            ]
		]);

    $email = $_GET["e"];
    $actual_link = str_replace('?e=', '?email=', $_SERVER['REQUEST_URI'] );
    $actual_link = explode("/", $actual_link);
    if(getenv("APP_PREFIX")) unset($actual_link[1]);
    // $result = curlSendRequest("GET", env("SESSION_HOSTNAME"). "$_SERVER[REQUEST_URI]" , [], [ "Authorization: Basic ".base64_encode("email".":".$email) ]);
    $result = curlSendRequest("GET", env("SESSION_HOSTNAME") . implode("/", $actual_link) , [], [ "Authorization: Basic ".base64_encode("email".":".$email) ]);
    $valid_token = ($result->data == 'response' ) ? true : false;
    return layout_view("confirmpassword", ["valid_token"=> $valid_token ]);
    }
}
