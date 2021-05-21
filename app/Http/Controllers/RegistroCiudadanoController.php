<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RegistroCiudadanoController extends Controller
{
    //
    public function index () {
		set_layout_arg([
			"subtitle" => "Registrar usuario",
			"empty_layout" => true,
			"background_content" => "#ffffff",
			"fluid_container" => true,
			"script" => [
				// asset("js/login.js")
			]
		]);
		return layout_view("registroCiudadano", []);
	}
}
