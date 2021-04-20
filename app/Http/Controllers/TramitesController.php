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
use App\Repositories\EgobiernopartidasRepositoryEloquent;
use App\Repositories\PortalcamposagrupacionesRepositoryEloquent;

use App\Repositories\PortalcostotramitesRepositoryEloquent;
use App\Repositories\PortalsubsidiotramitesRepositoryEloquent;
use App\Repositories\PortalumaRepositoryEloquent;
use Illuminate\Support\Facades\Http;

class TramitesController extends Controller
{
    protected $tramites;
    protected $solicitudes;
    protected $relationship;
    protected $tiposer;
    protected $costotramites;
    protected $subsidiotramites;
    protected $uma;
    protected $partidas;
    protected $group;

    public function __construct(
      TramitedetalleRepositoryEloquent $tramites,
      PortalsolicitudescatalogoRepositoryEloquent $solicitudes,
      PortalcamporelationshipRepositoryEloquent $relationship,
      EgobiernotiposerviciosRepositoryEloquent $tiposer,
      PortalcostotramitesRepositoryEloquent $costotramites,
      PortalsubsidiotramitesRepositoryEloquent $subsidiotramites,
      PortalumaRepositoryEloquent $uma,
      EgobiernopartidasRepositoryEloquent $partidas,
      PortalcamposagrupacionesRepositoryEloquent $group
      )
      {
        parent::__construct();
        // $this->middleware('auth');
        $this->tramites = $tramites;
        $this->solicitudes = $solicitudes;
        $this->relationship= $relationship;
        $this->tiposer = $tiposer;
        $this->costotramites = $costotramites;
        $this->subsidiotramites = $subsidiotramites;
        $this->uma = $uma;
        $this->partidas = $partidas;
        $this->group = $group;
      }

    public function index ($type, $id) {
    	set_layout_arg(["subtitle" => "Trámites: {$type}","fluid_container"=> true]);
    	return layout_view("tramites.index", [ "type" => $type, "id" => $id ]);
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
      //dd($tramits);
      $tmts = array();

      foreach ($tramits as $t) {
        $id_tramite = $t->tramite_id;
        $created_at = $t->created_at;
        $updated_at = $t->updated_at;

        $serv = $this->tiposer->where('Tipo_Code', $id_tramite)->get();
        foreach ($serv as $s) {
          $name = $s->Tipo_Descripcion;

          $tmts []=array(
            'id_tramite' => $id_tramite,
            'nombre' => $name,
            'created_at' => $created_at,
            'updated_at' => $updated_at
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
      $data = $request->tramite_id;
      $user_id = 1;

      try{

      }catch(\Exception $e){
        Log::info('Error - crear Solicitud: '.$e->getMessage());
      }


    }

    /**
    * Funcion para traer el costo de un trámite
    *
    *	@return json costo e informacion del trámite
    */
    public function getcostoTramite(Request $request) {
      $tramite_id = $request->tramite_id;
      //$tramite_id = 100;
      $dt = date("Y");

      //datos para el tramite
      $valor_catastral = $request->valor_catastral;
      //$valor_catastral = 150050;
      //$valor_operacion = 178960;
      $valor_operacion = $request->valor_operacion;

      //Se hace la conversión en caso de mandar un valor de operacion y catastral en otra divisa
      if(!empty($request->divisa)){
        $param = $request->divisa;

        if($param != 'PESOS'){
          $costo_cambio = curlSendRequest("POST", getenv("SESSION_HOSTNAME")."/divisas/getCambioDivisa", ["parametro" => $param, "monto" => $valor_catastral]);
          $res =  $costo_cambio->response;
          $rest = $res->resultado;
          $valor_catastral = $rest->precio_final;


          $costo_cambio = curlSendRequest("POST", getenv("SESSION_HOSTNAME")."/divisas/getCambioDivisa", ["parametro" => $param, "monto" => $valor_operacion]);
          $res =  $costo_cambio->response;
          $rest = $res->resultado;
          $valor_operacion = $rest->precio_final;
        }
      }

      $lotes = $request->lote;
      $hojas =  $request->hoja;
      //$hojas = 5;
      $data_uma = $this->uma->where('year', $dt)->get();
      foreach ($data_uma as $val) {
        $actual_uma = $val->daily;
        $annual_uma = $val->yearly;
      }

      //Informacion del costo y reglas del trámite
      $data_costo = $this->costotramites->where('tramite_id', $tramite_id)->where('status', 1)->get();

      foreach($data_costo as $data){
        $tipo = $data->tipo;
        $costoX = $data->costo;
        $costo_fijo = $data->costo_fijo;
        $tipo_costo_fijo = $data->tipo_costo_fijo;
        $min = $data->minimo;
        $max = $data->maximo;
        $valor = $data->valor;
        $variable = $data->variable;
        $var_minimo = $data->var_minimo;
        $var_valor = $data->var_valor;
        $vigencia = $data->vigencia;
        $porcentaje = $data->porcentaje;
        $status = $data->status;
      }

      //$tipo_costo_obj = $request->tipo_costo_obj;

      //Valor variable
      if ( $request->tipo_costo == 1){ //cuando el costo seleccionado es variable
        if($request->tipoCostoRadio == "hoja"){ //cuando el costo en el radio seleccionado es hoja
          $costoX = "H";
          $min = $var_minimo;
          $valor = $var_valor;
          $hojas = $request->hojaInput;
        }
        elseif($request->tipoCostoRadio == "millar"){ //cuando el costo en el radio seleccionado sea millar
          $costoX = "M";
        }
        elseif ($request->tipoCostoRadio == "lote") { //cuando el costo en el radio seleccionado sea lote
          $costoX = "L";
          $min = $var_minimo;
          $valor = $var_valor;
          $lotes = $request->hojaInput;
        }
      }

      //Se calcula el costo del tramite
      try{
        if ($tipo == "F"){
          if($tipo_costo_fijo == "P"){ // costo fijo en pesos
            $costo = $costo_fijo;
          }
          else{ //C Para cuando el costo fijo es calculado en cuotas
            $costo = $actual_uma * $costo_fijo;
          }
          //Se valida si hay registro de porcentaje a cobrar
          if($porcentaje != 0 || $porcentaje != null){

            $costo = $this->porcentaje($porcentaje, $costo);
          }
          //Se aplica redondeo decimal
          $costo_final = $this->redondeo($costo);
        }
        elseif ($tipo == "V") { //Costo variable
          if($costoX == "L") { //costo x lote
            $costo_real = $actual_uma * $valor; //Se calcula el costo x 1 lote
            $primer_costo = $this->redondeo($costo_real);  //Se redondea el costo x lote

            //Se calcula el costo por la cantidad de lotes ingresados
            if (!empty($lotes)){
              $costoxlote = $primer_costo * $lotes;

              $costoMinimo = $min * $actual_uma;
              $costoMin = $this->redondeo($costoMinimo);

              $costoMaximo = $max * $actual_uma;
              $costoMax = $this->redondeo($costoMaximo);

              if($costoxlote < $costoMinimo){
                $costo_final = $costoMin;
              }elseif($costoxlote > $costoMaximo){
                $costo_final = $costoMax;
              }else{
                $costo_final = $costoxlote;
              }
            }else{
              $costo_final = $primer_costo;
            }
            //Validamos si existe un porcentaje configurado
            if($porcentaje != 0 || $porcentaje != null){

              $costo_final = $this->porcentaje($porcentaje, $costo_final);
            }
            //Se aplica redondeo al resultado final
            $costo_final = $this->redondeo($costo_final);
          }
          elseif ($costoX == "H") { // costo x hojas
            if(!empty($valor)){
              $costo_real = $actual_uma * $valor;
            }else{
              $costo_real = $actual_uma;
            }

            $primer_costo = $this->redondeo($costo_real);

            if (!empty($hojas)){
              $costoxhoja = $primer_costo * $hojas;

              $costoMinimo = $min * $actual_uma;
              $costoMin = $this->redondeo($costoMinimo);

              $costoMaximo = $max * $actual_uma;
              $costoMax = $this->redondeo($costoMaximo);
              if($costoxhoja < $costoMinimo){
                $costo_final = $costoMin;
              }elseif($costoxhoja > $costoMax){
                $costo_final = $costoMax;
              }else{
                $costo_final = $this->redondeo($costoxhoja);
              }
            }else{
              $costo_final = $primer_costo;
            }
            //Se hace calculo del costo por porcentaje en caso de tener algun porcentaje asignado
            if($porcentaje != 0 || $porcentaje != null){
              $costo_final = $this->porcentaje($porcentaje, $costo_final);
            }
            //Se aplica redondeo al resultado final
            $costo_final = $this->redondeo($costo_final);
          }
          else{ //costo x millar
            //Se calculan los valores minimos y máximos del trámite
            $costoMinimo = $min * $actual_uma;
            $costoMin = $this->redondeo($costoMinimo);

            $costoMaximo = $max * $actual_uma;
            $costoMax = $this->redondeo($costoMaximo);

            if( $valor_catastral > $valor_operacion){
              $operacion = $valor_catastral;
              $operacion = $this->redondeoalMillar($operacion);
            }else{
              $operacion = $valor_operacion;
              $operacion = $this->redondeoalMillar($operacion);
            }

            $precio = ($operacion * $valor) / 1000;
            //$precioRedondeo = $this->redondeoalMillar($precio);

            if ($precio < $costoMin ){
              $costo_final = $costoMin;
            }elseif ($precio > $costoMax) {
                $costo_final = $costoMax;
            }else{
              $costo_final = $precio;
              //Se hace calculo del costo por porcentaje en caso de tener algun porcentaje asignado
              if($porcentaje != 0 || $porcentaje != null){

                $costo_final = $this->porcentaje($porcentaje, $costo_final);
              }
            }

            $costo_final = $this->redondeo($costo_final);
          }
        }

        //Se valida si existe un subsidio aplicable
        $subsidio_data = $this->subsidiotramites->where('tramite_id', $tramite_id)->get();
        foreach ($subsidio_data as $sub) {
          $cuotas_sub = $sub->cuotas;
          $limite_cuotassub = $sub->limite_cuotas;
          $id_partida = $sub->id_partida;
          $oficio_sub = $sub->oficio;
          $tipoPersona = $sub->tipoPersona;
        }
        $descuentos = array();
        if(!empty($oficio_sub)){

          //Se aplica Validacion para ver si el numero de oficio coincide
          if($request->subsidio == $oficio_sub){
            if($request->tipoPersona == $tipoPersona){

              //Se calculan las cuotas elevadas al año configuradas
              $cuotas_anio = $limite_cuotassub * $annual_uma;
              //Validacion para cuando es un costo al millar y no aplique redondeoalMillar
              if($costoX == "M"){
                if( $valor_catastral > $valor_operacion){
                  $operacion = $valor_catastral;
                }else{
                  $operacion = $valor_operacion;
                }
              }

              //Se compara el valor de operacion utilizado para aplicar subsidio
              if($operacion <= $cuotas_anio){
                //Se calcula el valor a cobrar en $cuotas
                $costo = $cuotas_sub * $actual_uma;
                $total_subsidiado = $costo_final - $costo;
                $total_subsidiado = $this->redondeo($total_subsidiado);
                $importe_total = $costo_final;
                $costo_final = $this->redondeo($costo);

                //Obtengo la informacion de la partida correspondiente
                $data_partida = $this->partidas->where('id_partida', $id_partida)->get();
                foreach ($data_partida as $p) {
                  $id_servicio = $p->id_servicio;
                  $descripcion_part = $p->descripcion;
                }
                //Se forma el arreglo de descuentos
                $descuentos []= array(
                  'concepto_descuento' => $descripcion_part,
                  'importe_subsidio' => $total_subsidiado,
                  'partida_descuento' => $id_partida,
                  'importe_total' => $importe_total
                );

              }else{
                $descuentos []= array(
                  'concepto_descuento' => 'El valor de operación excede el monto válido para subsidio',
                );
              }

            }elseif($request->tipoPersona == "a"){
              //Se calculan las cuotas elevadas al año configuradas
              $cuotas_anio = $limite_cuotassub * $annual_uma;
              //Validacion para cuando es un costo al millar y no aplique redondeoalMillar
              if($costoX == "M"){
                if( $valor_catastral > $valor_operacion){
                  $operacion = $valor_catastral;
                }else{
                  $operacion = $valor_operacion;
                }
              }

              //Se compara el valor de operacion utilizado para aplicar subsidio
              if($operacion <= $cuotas_anio){
                //Se calcula el valor a cobrar en $cuotas
                $costo = $cuotas_sub * $actual_uma;
                $total_subsidiado = $costo_final - $costo;
                $total_subsidiado = $this->redondeo($total_subsidiado);
                $importe_total = $costo_final;
                $costo_final = $this->redondeo($costo);

                //Obtengo la informacion de la partida correspondiente
                $data_partida = $this->partidas->where('id_partida', $id_partida)->get();
                foreach ($data_partida as $p) {
                  $id_servicio = $p->id_servicio;
                  $descripcion_part = $p->descripcion;
                }
                //Se forma el arreglo de descuentos
                $descuentos []= array(
                  'concepto_descuento' => $descripcion_part,
                  'importe_subsidio' => $total_subsidiado,
                  'partida_descuento' => $id_partida,
                  'importe_total' => $importe_total
                );

              }else{
                $descuentos []= array(
                  'concepto_descuento' => 'El valor de operación excede el monto válido para subsidio',
                );
              }
            }else{
              $descuentos []= array(
                'concepto_descuento' => 'El tipo de Persona fiscal no es válido para este subsidio',
              );
            }

          }else{
            $descuentos []= array(
              'concepto_descuento' => 'El numero de oficio no coincide con el trámite',
            );
          }
        }


        //Se devuelve el arreglo con el valor del costo
        $detalle []= array(
          'tramite_id' => $tramite_id,
          'costo_final' => $costo_final,
          'descuentos' => $descuentos,
        );

        return json_encode($detalle);


      }catch(\Exception $e){
        Log::info('Error - costo Trámite: '.$e->getMessage());
      }
    }

    public function test(){
      $costo_final = '1';
      $param = 'SF46410';

      $costo_cambio = curlSendRequest("POST", getenv("SESSION_HOSTNAME")."/divisas/getCambioDivisa", ["parametro" => $param, "monto" => $costo_final]);
      $res =  $costo_cambio->response;
      $rest = $res->resultado;
      $cambio = $rest->precio_final;

      dd($costo_cambio);

    }

    public function redondeo($costo_real){
      //Redondeo
      $exacto = floor($costo_real);

      $dec = $costo_real - $exacto;

      if($dec <= "0.50"){
        $costo_real = floor($costo_real);
      }else{
        $costo_real = $exacto + 1;
      }

      return $costo_real;
    }

    public function redondeoalMillar($costo_real){
        $exacto = round ($costo_real, -3);
        if($exacto < $costo_real){
          $exacto = $exacto + 1000;
        }
        return $exacto;
    }

    /**
     *
     * porcentaje . Calcula el costo final a cobrar a partir del porcentaje asignado
     *
     * @param $porcentaje = es el porcentaje asignado a cobrar
     * @param $costo_real = es el costo calculado ya aplicando sus formulas y validaciones
     *
     * @return Value de costo final a cobrar
     *
     */
    public function porcentaje($porcentaje, $costo_real){
      //$porcentaje = 75;
      //$costo_real = 3500;

      $porcentaje = $porcentaje / 100;
      $costo_final = $costo_real * $porcentaje;

      return $costo_final;

    }

    public function detalle ( Request $request ) {
      $id_tramite = $request->idTramite;
      $clave = isset($request->clave) ? $request->clave : "";

      $detalle = array();
      $data = $this->tiposer->where('Tipo_Code', $id_tramite)->get();
      foreach ($data as $d) {
        $nombre_tramite = $d->Tipo_Descripcion;
      }

      $info = $this->getPartidasTramites($id_tramite);

      $detalle [] = array(
        'id_tramite'=>$id_tramite,
        'tramite' => $nombre_tramite,
        'partidas' => $info
      );

      set_layout_arg("subtitle", "Detalle Trámite");
      return layout_view("tramites.detalleTramite",[ "detalle" => $detalle, "clave" => $clave ] );
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

    public function carshop ( Request $request ) {
      set_layout_arg("subtitle", "CarShop");
      return layout_view("tramites.carshop" );
    }


    public function respuestaPago(Request $request ){
      $json = json_decode($request->json, true);
      $json['codigoCambioEstatus'] = $this->actualizarTransaccion($json);
      if( $json ){
        return layout_view("tramites.respuestaPago",  [ "respuestabanco" =>$json] );
      } else {
        return layout_view("tramites.respuestaPago",  [ "respuestabanco" =>[] ]);
      }

    }

    private function actualizarTransaccion($json){
      if( isset( $json['estatus'] ) ){
        $id_transaccion_motor = $json['id_transaccion_motor'];
        $urlTesoreria = getenv("TESORERIA_HOSTNAME");
        $estatus = $json['estatus'];
        $statusChange = '0';
        $url_recibo = $json['url_recibo'] ? $json['url_recibo'] : null;
        if($estatus != 1){
          $statusChange = "15";
        }

        $responseCambioEstatus = Http::post( $urlTesoreria . '/solicitudes-update-status-tramite', [
            'id_transaccion_motor' => $id_transaccion_motor,
            'status' => $statusChange,
            'url_recibo' => $url_recibo

        ]);
        return $responseCambioEstatus->json() != null && $responseCambioEstatus->json()["Code"] != null ? $responseCambioEstatus->json()["Code"] : false;
      } else {
        return false;
      }
    }

    private function cambiarEstatusTransaccion( $json ){
      if( isset( $json['response'] )  && isset($json['response']["datos"]) && isset($json['response']["datos"]["estatus"])  ){
        $id_transaccion_motor = $json['response']['datos']['id_transaccion_motor'];
        $urlTesoreria = getenv("TESORERIA_HOSTNAME");

        $estatus = $json['response']['datos']['estatus'];
        $statusChange = '0';
        $url_recibo = null;
        if($estatus != 1){
          $statusChange = "15";
        }
        if( isset( $json['response']["datos"]["url_recibo"]) ){
          $url_recibo = $json['response']["datos"]["url_recibo"];
        }
        
        $responseCambioEstatus = Http::post( $urlTesoreria . '/solicitudes-update-status-tramite', [
            'id_transaccion_motor' => $id_transaccion_motor,
            'status' => $statusChange,
            'url_recibo' => $url_recibo

        ]);
        return $responseCambioEstatus->json() != null && $responseCambioEstatus->json()["Code"] != null ? $responseCambioEstatus->json()["Code"] : false;
      } else {
        return false;
      }
    }

    public function respuestaPagoBBVA(Request $request){


      $url = getenv("PAYMENTS_HOSTNAME");
      $PAYMENTS_KEY = getenv("PAYMENTS_KEY");

      $response = Http::withHeaders([
          'Authorization' => 'Bearer ' . $PAYMENTS_KEY
      ])->post( $url . '/v1/respuestabanco', [
          's_transm' => $request->get('s_transm'),
          'mp_response' => $request->get('mp_response'),
          'mp_signature' => $request->get('mp_signature'),
          'n_autoriz' => $request->get('n_autoriz'),
      ]);

      $json = $response->json();
      $json['codigoCambioEstatus'] = $this->cambiarEstatusTransaccion($json);

      if( $json['data'] ){
        return layout_view("tramites.respuestaPagoBancomer",  [ "respuestabanco" =>$json] );
      } else {
        return layout_view("tramites.respuestaPagoBancomer",  [ "respuestabanco" =>[] ]);
      }
    }

}
