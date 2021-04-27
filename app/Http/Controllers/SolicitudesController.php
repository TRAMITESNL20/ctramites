<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

use Carbon\Carbon;

use App\Repositories\PortalsolicitudescatalogoRepositoryEloquent;
use App\Repositories\PortalsolicitudesticketRepositoryEloquent;
use App\Repositories\PortalTramitesRepositoryEloquent;
use App\Repositories\PortalcamporelationshipRepositoryEloquent;
use App\Repositories\EgobiernotiposerviciosRepositoryEloquent;
use App\Repositories\PortalcampotypeRepositoryEloquent;
use App\Repositories\PortalcampoRepositoryEloquent;

use App\Repositories\EgobiernopartidasRepositoryEloquent;
//use App\Repositories\PortalreglaoperativaRepositoryEloquent;
use App\Repositories\PortalcostotramitesRepositoryEloquent;
use App\Repositories\PortalcamposagrupacionesRepositoryEloquent;

use App\Repositories\PortaltramitecategoriaRepositoryEloquent;
use App\Repositories\PortaltramitecategoriarelacionRepositoryEloquent;
use App\Repositories\PortaltramitedivisasRepositoryEloquent;
use App\Repositories\DivisasRepositoryEloquent;
use App\Repositories\RolesRepositoryEloquent;

class SolicitudesController extends Controller
{
  protected $solicitudes;
  protected $relationship;
  protected $tiposer;
  protected $tipocampo;
  protected $campo;
  protected $partidas;
  protected $costo;
  protected $cat_tramite;
  protected $relcat;
  protected $reldivisas;
  protected $divisas;
  protected $roles;

// agregar catalogos
  protected $catalogo_campos;
  protected $catalogo_type;
  protected $group;

  protected $ticket;
  protected $solTramites;

  public function __construct(
    PortalsolicitudescatalogoRepositoryEloquent $solicitudes,
    PortalcamporelationshipRepositoryEloquent $relationship,
    EgobiernotiposerviciosRepositoryEloquent $tiposer,
    PortalcampotypeRepositoryEloquent $tipocampo,
    PortalcampoRepositoryEloquent $campo,
    EgobiernopartidasRepositoryEloquent $partidas,
    PortalcostotramitesRepositoryEloquent $costo,
    PortalcamposagrupacionesRepositoryEloquent $group,
    PortaltramitecategoriaRepositoryEloquent $cat_tramite,
    PortaltramitecategoriarelacionRepositoryEloquent $relcat,
    PortaltramitedivisasRepositoryEloquent $reldivisas,
    DivisasRepositoryEloquent $divisas,
    RolesRepositoryEloquent $roles,
    PortalsolicitudesticketRepositoryEloquent $ticket,
    PortalTramitesRepositoryEloquent $solTramites
    )
    {
      parent::__construct();
      // $this->middleware('auth');
      $this->solicitudes = $solicitudes;

      $this->relationship= $relationship;

      $this->tiposer = $tiposer;

      $this->tipocampo = $tipocampo;

      $this->campo = $campo;

      $this->partidas = $partidas;

      $this->costo = $costo;

      $this->group = $group;

      $this->cat_tramite = $cat_tramite;

      $this->relcat = $relcat;

      $this->reldivisas = $reldivisas;

      $this->divisas = $divisas;

      $this->roles  = $roles;
      // creamos los catalogos iniciales

      $this->ticket = $ticket;
      $this->solTramites = $solTramites;

      $this->loadInfo();


    }

  /*
   * getTramites() ESTE METODO REGRESA LOS TRAMITES CONFIGURADOS
   *
   * @params null
   *
   *
   * @returns an json object
   */

  public function loadInfo()
  {

    $c_campos = array();

    $campos = $this->campo->all();

    foreach($campos as $c)
    {
      $c_campos [$c->id]= $c->descripcion;
    }

    $this->catalogo_campos = $c_campos;

    $c_type = array();

    $type = $this->tipocampo->all();

    foreach($type as $t)
    {
      $c_type [$t->id]= $t->descripcion;
    }

    $this->catalogo_type = $c_type;

    $cost = $this->costo->all();

  }


  /*
   * getTramites() ESTE METODO REGRESA LOS TRAMITES CONFIGURADOS
   *
   * @params null
   *
   *
   * @returns an json object
   */

  public function getTramites(Request $request){
    $com = $request->config_id; //Obtenemos la comunidad a la que pertenece el usuario

    $tramites = $this->relationship->all();

    $t = array();

    // obtengo los tramites configurados
    foreach ($tramites as $k) {
      # checar los que no tenemos
      if( !in_array((int)$k->tramite_id, $t) )
      {

        $t[] = (int)$k->tramite_id;

      }
    }
    //Se valida que existan los tramites dentro de la comunidad
    $tram = $this->roles->where('id', $com)->get('tramites');
    foreach ($tram as $tm) {
      $arr = json_decode($tm->tramites);
      $ids = array();
      foreach ($arr as $a) {
        $ids []= $a;
      }
    }
    // obtengo la descripcion y id de los tramites
    $servicios = $this->tiposer->findWhereIn('Tipo_Code',$t)->where('estatus',1);

    $tmts = array();

    foreach($servicios as $s)
    {
      if(in_array($s->Tipo_Code,$ids)){
      $category = $this->relcat->findWhere(['tramite_id'=> $s->Tipo_Code] );

      $tmts []=array(
          'id_tramite'=> $s->Tipo_Code,
          'tramite' => $s->Tipo_Descripcion,
          'partidas' => $this->getPartidasTramites($s->Tipo_Code), // aqui mando los datos de la partida
          'category' => $this->getCategoryTramite($s->Tipo_Code),
        );
      }
    }

    return json_encode($tmts);

  }

  function getCategoryTramite($id){
    $data = array();
    try{

      $info = $this->relcat->findWhere( ["tramite_id" =>  $id] );

      if($info->count() > 0){
        foreach($info as $i)
        {
          $cat_data = $this->cat_tramite->findWhere(["id" => $i->categorias_id]);
          foreach ($cat_data as $cats) {
            $name = $cats->descripcion;
          }
          $data []= array(
            "categorias_id"  => $i->categorias_id,
            "nombre_categoria" => $name
          );
        }
      }else{
        $data = 0;
      }

    }catch(\Exception $e){
      Log::info('Error getCategoryTramite '.$e->getMessage());
      return response()->json(
        [
          "Code" => "400",
          "Message" => "Error al getCategoryTramite",
        ]
      );
    }

    return $data;
  }

  /*
   * getCampos() ESTE METODO REGRESA LOS CAMPOS CONFIGURADOS EN EL TRAMITE
   *
   * @params id_tramite
   *
   *
   * @returns an json object
   */

  public function getCampos(Request $request){

    $id_tramite = $request->id_tramite;

    //dd($id_tramite,$this->catalogo_campos,$this->catalogo_type);

    $campos_data = array();

    try{
      $campos = $this->relationship->findWhere( ['tramite_id' => $id_tramite] );
      //$campos = $this->relationship->where('tramite_id', 100)->get();

      //revisar si el tramite tiene asignado el campo divisas

      foreach ($campos as $c) {

        $grupo = $this->group->findWhere(['id' => $c->agrupacion_id]);

        foreach ($grupo as $g) {
          $desc = $g->descripcion;
          $orden_group = $g->orden;
        }

          $campos_data []=array(
            'relationship' => $c->id,
            'tipo' => $this->catalogo_type[$c->tipo_id],
            'nombre' => $this->catalogo_campos[$c->campo_id],
            'caracteristicas' => $c->caracteristicas,
            'campo_id' => $c->campo_id,
            'agrupacion_id' => $c->agrupacion_id,
            'orden_agrupacion' => $orden_group,
            'orden'=> $c->orden,
            'nombre_agrupacion' => $desc,
          );
      }

      $div = $this->reldivisas->where('tramite_id', $id_tramite)->get();

      if($div->count() > 0 ){
        $div_list = $this->getDivisas();

        $campos_data []=array(
          'relationship' => '',
          'tipo' => 'select',
          'nombre' => 'Cambio de divisas',
          'caracteristicas' => '{"required": "true","opciones":'.$div_list.'}',
          'campo_id' => '190',
          'agrupacion_id' => '',
          'orden_agrupacion' => '100',
          'orden'=> '1',
          'nombre_agrupacion' => 'Divisas',
        );
      }



      $data = array();

      if ($id_tramite == 516 ) {

        $data [] = array(
          "campos_data" => $campos_data,
          "consulta_api" => "/getcostoImpuesto"
        );
      }elseif($id_tramite == 399){
        $data [] = array(
          "campos_data" => $campos_data,
          "consulta_api" => "/getcostoImpuesto"
        );
      }else{
        $data_costo = $this->costo->where('tramite_id', $id_tramite)->where('status', 1)->get();
        if ($data_costo) {
          foreach ($data_costo as $d) {
            $tipo_costo = $d->variable;
            $val_tipo_costo = $d->var_costo;

            if($tipo_costo == null){
              $tipo_costo = 0;
            }
          }

        }else{
          $tipo_costo = 0;
        }

        $data [] = array(
          "campos_data" => $campos_data,
          "consulta_api" => "/getcostoTramite",
          "tipo_costo" => $tipo_costo,
          "val_tipo_costo" => $val_tipo_costo
        );

      }


    }catch(\Exception $e){
      Log::info('Error Solicitud - getCampos '.$e->getMessage());
    }


    return json_encode($data);
  }

  /**
   *
   * getPartidasTramites . Busca en la tabla de partidas los valores
   *
   * @param $id es el valor del tramite
   *
   * @return Array con los valores de la partida
   *
   */

  public function getPartidasTramites($id)
  {
    $data = array();
    try{

      $info = $this->partidas->findWhere( ["id_servicio" =>  $id] );

      if($info->count() > 0){
        foreach($info as $i)
        {
          $data []= array(
            "id_partida"  => $i->id_partida,
            "descripcion" => $i->descripcion
          );
        }
      }else{
        $data = 0;
      }

    }catch(\Exception $e){
      Log::info('Error Eliminar Solicitud '.$e->getMessage());
      return response()->json(
        [
          "Code" => "400",
          "Message" => "Error al getPartidasTramites",
        ]
      );
    }

    return $data;

  }

  public function allCategories (){
    try{
      $cats = $this->cat_tramite->get();

      return json_encode($cats);

    }catch(\Exception $e){
      Log::info('Error allCategories '.$e->getMessage());
      return response()->json(
        [
          "Code" => "400",
          "Message" => "Error al listar categorias",
        ]
      );
    }
  }

  public function getDivisas(){
    try{
      $divisas_opt = $this->divisas->get();
      $divisas_options = array();

      foreach ($divisas_opt as $do) {
        $descripcion = $do->descripcion;
        $value = $do->parametro;

        $divisas_options []= array(
          $value => $descripcion
        );
      }

      return json_encode($divisas_options);

    }catch(\Exception $e){
      Log::info('Error getDivisas '.$e->getMessage());
      return response()->json(
        [
          "Code" => "400",
          "Message" => "Error al listar divisas",
        ]
      );
    }
  }

  public function getNormales($folio){
    try {

      $id_tramite = env("TRAMITE_5_ISR");

      //Se busca el tramite dentro de la tabla catalogo
      $cat_data = $this->solicitudes->where("tramite_id", $id_tramite)->get();
      foreach ($cat_data as $cat) {
        $id_cat = $cat->id;
        $titulo = $cat->titulo;
        $catalogo [] = array(
          "id" => $id_cat,
          "titulo" =>$titulo
        );
      }
      //Buscamos el folio dentro de la tabla solicitudes_tramite
      $solicitud = $this->solTramites->where("id_transaccion_motor", $folio)->get();

      foreach ($solicitud as $s) {
        $id_transaccion = $s->id;
        $catalogo_id = $s->catalogo_id;
      }
      $sol["folio"] = $id_transaccion;

      //if(in_array())
      //Con el id_transaccion se buscan los registros existentes dentro de solicitudes_ticket

      $tickets = $this->ticket->where("id_transaccion", $id_transaccion)->get();

      //$tmts = array();
      foreach ($tickets as $t) {
        $info = json_decode($t->info);
        $cat = $t->catalogo_id;
        $tmts["solcitudes"] = array(
          "info"=>$info
        );
      }
      //$tmts["tramites"] = $tmts;
      //dd($tmts);
      return $tmts;


    } catch (\Exception $e) {
      Log::info('Get Normales :'.$e->getMessage());
      return response()->json(
        [
          "Code" => "400",
          "Message" => "Error al obtener información",
        ]
      );
    }
  }
}
