<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    //
    public function index () {
		set_layout_arg([
            "subtitle" => "Bienvenido",
			"empty_layout" => true,
			"background_content" => "#ffffff",
			"fluid_container" => true,
			// "subtitle" => "perfil",
			// "fluid_container"=> true
		]);

		return layout_view("landingPage");
    }
}
