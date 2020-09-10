<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

use Carbon\Carbon;

use App\Repositories\TramitedetalleRepositoryEloquent;
use App\Repositories\PortalsolicitudescatalogoRepositoryEloquent;
use App\Repositories\PortalcamporelationshipRepositoryEloquent;
use App\Repositories\EgobiernotiposerviciosRepositoryEloquent;


class TramitesController extends Controller
{
    protected $tramites;
    protected $solicitudes;
    protected $relationship;
    protected $tiposer;

    public function __construct(
      TramitedetalleRepositoryEloquent $tramites,
      PortalsolicitudescatalogoRepositoryEloquent $solicitudes,
      PortalcamporelationshipRepositoryEloquent $relationship,
      EgobiernotiposerviciosRepositoryEloquent $tiposer
      )
      {
        // $this->middleware('auth');
        $this->tramites = $tramites;
        $this->solicitudes = $solicitudes;
        $this->relationship= $relationship;
        $this->tiposer = $tiposer;
      }

    public function index ($type) {
    	set_layout_arg(["subtitle" => "Trámites: {$type}","fluid_container"=> true]);
    	return layout_view("tramites.index", [ "type" => $type ]);
    }

    public function new () {
    	set_layout_arg("subtitle", "Nuevo Trámite");
    	return layout_view("tramites.new");
    }

   /**
   * Lista de tramites
   *
   *	@return json lista de tramites con id
   */

    public function listaTramites(){

      $tramits = $this->relationship->get();

      $tmts = array();

      foreach ($tramits as $t) {
        $id_tramite = $t->tramite_id;
        // $created_at = $t->created_at;
        // $updated_at = $t->updated_at;

        $serv = $this->tiposer->where('Tipo_Code', $id_tramite)->get();
        foreach ($serv as $s) {
          $name = $s->Tipo_Descripcion;

          $tmts []=array(
            'id_tramite' => $id_tramite,
            'nombre' => $name
          );

        }
      }

      //dd($tmts);

      return json_encode($tmts);
    }
    /**
    * Funcion para crear una nueva solicitud
    *
    *	@return json lista de tramites con id
    */
    public function crearSolicitud(Request $request) {

    }
}
