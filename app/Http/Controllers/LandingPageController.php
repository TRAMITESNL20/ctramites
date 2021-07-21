<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;


class LandingPageController extends Controller
{
    public function index () {
		set_layout_arg([
            "subtitle" => "Bienvenido",
			"empty_layout" => true,
			"background_content" => "#ffffff",
			"fluid_container" => true,	
		]);
		$tramitesAgrupados = [];
		$allTramites = curlSendRequest("GET", env("APP_URL") . "/allTramites?config_id=" .env("COMUNIDAD_CIUDADANO") );
		$tramitesAgrupados = [];

		foreach ($allTramites as $tramite) {
			$category = isset($tramite->category[0]->categorias_id) ? $tramite->category[0]->categorias_id : $tramite->category;
			$categoryName = isset($tramite->category[0]->nombre_categoria) ? $tramite->category[0]->nombre_categoria : 'Sin Asignar';
			if(!isset($tramitesAgrupados[$category]))
			$tramitesAgrupados[$category] = [
				'id' => $category,
				'name' => $categoryName,
				'tramites' => []
			];
			$tramitesAgrupados[$category]['tramites'][] = $tramite;
		}
		// dd( env("APP_URL") . "/allTramites?config_id=" .env("COMUNIDAD_CIUDADANO") );
		return layout_view("bienvenido" , [ "tramitesAgrupados" => $tramitesAgrupados ]);
    }
}