<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\Cast\Object_;

class PendienteFirmaController extends Controller
{
    //
    public function index () {
        $idTramites = [];
        $tramites= '';
		set_layout_arg([
			"subtitle" => "perfil",
			"fluid_container"=> true
		]);
        $tramitesDoc = [];
		$user = session()->get("user");
        $tramites = curlSendRequest("GET", getenv("TESORERIA_HOSTNAME")."/solicitudes-info/{$user->id}/firma");

       


        // dd($tramites->Code != "400");
        (is_object( $tramites )  &&  $tramites->tramites) ? $idTramites = $tramites->tramites : $idTramites = [];
        // dd($idTramites);
        if( !empty($idTramites) ){
            for ($i=0; $i < count( $idTramites );  $i++) {                     
                    for ($k=0; $k < count($idTramites[$i]->solicitudes) ; $k++) { 
                        # code...
                        $aux = curlSendRequest("POST", getenv("TESORERIA_HOSTNAME")."/solicitudes-filtrar?required_docs=true&id_solicitud={$tramites->tramites[$i]->solicitudes[$k]->id}") ;
                        if( !empty($aux) && $aux[0]->required_docs == 0 ){
                            $tramitesDoc[] = $aux[0];
                        };
                    }                
               
            }
        }
        // dd( ($idTramites[0]->solicitudes[0]->info->campos ));
		return layout_view("pendienteFirma", ['user' => json_encode($user), 'idTramites' => json_encode($idTramites), 'tramitesDoc' => json_encode($tramitesDoc)] );
    }

}
