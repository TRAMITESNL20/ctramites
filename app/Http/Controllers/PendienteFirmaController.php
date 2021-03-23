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
        
        
        // for ($i=0; $i < count($tramites->tramites[0]->solicitudes)  ; $i++) { 
            //     # code...
            //     array_push($idTramites , $tramites->tramites[0]->solicitudes[$i]->id);
            // }
            // $idProcesso = $tramites->tramites[0]->tramite_id;
        $idTramites = $tramites->tramites;
        for ($i=0; $i < count( $idTramites[0]->solicitudes ) ; $i++) { 
            $aux = curlSendRequest("POST", "http://10.153.144.228/solicitudes-filtrar?required_docs=true&id_solicitud={$tramites->tramites[0]->solicitudes[$i]->id}") ;
            if( !empty($aux) ){
                $tramitesDoc[] = $aux[0];
            };
        }
        //  dd( empty($tramitesDoc));   
		return layout_view("pendienteFirma", ['user' => json_encode($user), 'idTramites' => json_encode($idTramites), 'tramitesDoc' => json_encode($tramitesDoc)] );
    }

}
