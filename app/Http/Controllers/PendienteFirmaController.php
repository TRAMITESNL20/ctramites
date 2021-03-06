<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PendienteFirmaController extends Controller
{
    //
    public function index () {
        $idTramites = [];
		set_layout_arg([
			"subtitle" => "perfil",
			"fluid_container"=> true
		]);
        $tramitesDoc = [];
		$user = session()->get("user");
		$tramites = curlSendRequest("GET", getenv("TESORERIA_HOSTNAME")."/solicitudes-info/{$user->id}/firma");

        $idTramites = $tramites->tramites;
        if( !empty($idTramites) ){
            for ($i=0; $i < count( $idTramites[0]->solicitudes );  $i++) { 
                $aux = curlSendRequest("POST", getenv("TESORERIA_HOSTNAME")."/solicitudes-filtrar?required_docs=true&id_solicitud={$tramites->tramites[0]->solicitudes[$i]->id}") ;
                if( !empty($aux) ){
                    $tramitesDoc[] = $aux[0];
                };
            }
        }
        // dd( ($tramitesDoc));
		return layout_view("pendienteFirma", ['user' => json_encode($user), 'idTramites' => json_encode($idTramites), 'tramitesDoc' => json_encode($tramitesDoc)] );
    }

}
