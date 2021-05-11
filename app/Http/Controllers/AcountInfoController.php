<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AcountInfoController extends Controller
{
    public function index () {
		set_layout_arg([
			"subtitle" => "Informacion de la cuenta",
			"fluid_container"=> true
		]);
		$user = session()->get("user");

		return layout_view("accountinfo", ["user" => $user]);
    }
}
