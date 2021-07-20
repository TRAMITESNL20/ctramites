<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BienvenidoController extends Controller
{
 	//
	 public function index () {
		// set_layout_arg([
		// 	"subtitle" => "Bienvenido",
		// 	"empty_layout" => true,
		// 	"background_content" => "#ffffff",
		// 	"fluid_container" => true,	
		// ]);
		// $tramitesAgrupados = [];

		// $id= 11;
    	// $allTramites = curlSendRequest("GET", env("APP_URL") . "/allTramites?config_id=" . $id);
    	// $allTramites = curlSendRequest("GET", "http://10.153.144.218/tramites-ciudadano/allTramites?config_id=11");

		// $client = new \GuzzleHttp\Client(['base_uri' => env("APP_URL") ]);
		// $response = $client->request('GET', 'allTramites?config_id=11');
		// dd($response);
		
		// dd( env("APP_URL")  . '/allTramites?config_id=' . env("COMUNIDAD_CIUDADANO"));
		
		// $allTramites = curlSendRequest("get" , 'http://10.153.144.218/tramites-ciudadano/allTramites?config_id=11');
		// dd($allTramites);
		
		// $url = "http://10.153.144.218/tramites-ciudadano/allTramites?config_id=11";
		$url = "http://localhost:8080/getCampos";
		// $url = getenv("TESORERIA_HOSTNAME")."/solicitudes-info/113/firma";
		$tramites = curlSendRequest("GET", getenv("APP_URL")."/formato-declaracion");

		// dd($tramites);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_ENCODING , "gzip"); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		curl_setopt($ch, CURLOPT_ENCODING, '');    
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10); //timeout in seconds
		$allTramites  = curl_exec($ch);
		curl_close($ch);
		
			
		dd($allTramites);
		
		// foreach ($allTramites as $tramite) {
		// 	$category = isset($tramite->category[0]->categorias_id) ? $tramite->category[0]->categorias_id : $tramite->category;
		// 	$categoryName = isset($tramite->category[0]->nombre_categoria) ? $tramite->category[0]->nombre_categoria : 'Sin Asignar';
		// 	if(!isset($tramitesAgrupados[$category]))
		// 	$tramitesAgrupados[$category] = [
		// 		'id' => $category,
		// 		'name' => $categoryName,
		// 		'tramites' => []
		// 	];
		// 	$tramitesAgrupados[$category]['tramites'][] = $tramite;
		// }
		// dd( env("APP_URL") . "/allTramites?config_id=" .env("COMUNIDAD_CIUDADANO") );
		// return layout_view("bienvenido" , [ "tramitesAgrupados" => $tramitesAgrupados ]);
    }
}
