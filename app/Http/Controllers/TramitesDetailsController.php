<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TramitesDetailsController extends Controller
{
    public function index ($id) {
		set_layout_arg([
			"subtitle" => "Informacion del trámite",
			"fluid_container"=> true
		]);
		$tramite = curlSendRequest("POST", getenv("TESORERIA_HOSTNAME")."/solicitudes-filtrar", ["id_solicitud" => (int)$id], ["Content-Type" => "application/json"]);
		$mensajes = $tramite[0]->mensajes;
		if(count($tramite[0]->mensajes) > 0) krsort($tramite[0]->mensajes);
		return layout_view("tramiteDetails", ["tramite" => $tramite[0]]);
    }
}
