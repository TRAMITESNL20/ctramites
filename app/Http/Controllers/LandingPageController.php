<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;


class LandingPageController extends Controller
{
    //
    public function index () {
		set_layout_arg([
            "subtitle" => "Bienvenido",
			"empty_layout" => true,
			"background_content" => "#ffffff", 
			"fluid_container" => true,
		]);
		$user = session()->get("user")->config_id;
			// $tramites = curlSendRequest("GET", "http://10.153.144.218/tramites-ciudadano/allTramites?config_id=4");
		
		// dd($user);
		// $link = env("APP_URL")."/allTramites?config_id=". $user;
		// 		$ch = curl_init();
		// 		curl_setopt($ch, CURLOPT_URL, $link);
		// 		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// 		$notary = curl_exec($ch);
		// 		curl_close($ch);

		dd();

		return layout_view("landingPage");
    }
}
