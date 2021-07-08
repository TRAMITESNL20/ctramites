<?php 
namespace App\Custom\UpdateTramite;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Http\Controllers\TramitesController;
use App\Repositories\PortalsolicitudesticketRepositoryEloquent;
use App\Repositories\PortalsolicitudescatalogoRepositoryEloquent;

use App\Custom\UpdateTramite\iTramite;

class TramiteRP implements iTramite
{


    public function __construct(TramitesController $tramiteCtrl,PortalsolicitudesticketRepositoryEloquent $modelTramite, PortalsolicitudescatalogoRepositoryEloquent $catalogoSolicitud)
    {
        $this->tramiteCtrl = $tramiteCtrl;
        $this->modelTramite = $modelTramite;
        $this->catalogoSolicitud = $catalogoSolicitud;

        $this->detalleNuevo = null;
    }

    public function setTramite( $tramite ){
        $this->tramite = $tramite;
        $this->info = $this->infoToObject( $tramite );
    }

    public function getTotalActual(){
        $info = $this->info;
        if(  (isset( $info['costo_final']))  ) {
            return $info['costo_final'];
        } else {
            return NULL;
        }
    }

    public function getDetalleActual(){
        $info = $this->info;
        if( isset( $info['detalle']) && gettype( $info['detalle']) == 'array' ) {
            $detalle = $info['detalle'];
            return $detalle;
        } else {
            return false;
        }
    }

    public function getParamsParaCosto(){   
        $info = $this->info; 

        $query = $this->catalogoSolicitud->where('id', $this->tramite->catalogo_id)->first();
        if(  $query && isset($query["tramite_id"])  ){
            $parms = [];
            $parms['tramite_id'] = $query["tramite_id"];
            $parms['id_seguimiento'] = $this->tramite->clave;

            if( isset( $info["solicitantes"] )){
                $parms['tipoPersona'] = $info["solicitantes"][0]['tipoPersona'];            
            
                if(  $info && isset($info["tipo_costo_obj"]) && isset($info["tipo_costo_obj"]['tipo_costo']) && $info["tipo_costo_obj"]['tipo_costo'] == '1' && (  $info["tipo_costo_obj"]['tipoCostoRadio'] == 'hoja' ||  $info["tipo_costo_obj"]['tipoCostoRadio'] == 'lote'  )){
                    $parms['tipo_costo'] = $info["tipo_costo_obj"]['tipo_costo'];
                    $parms['tipoCostoRadio'] = $info["tipo_costo_obj"]['tipoCostoRadio'];
                    $parms['hojaInput'] = $info["tipo_costo_obj"]['hojaInput'];
                } else {
                    $hoja = $this->getValue( $info["camposConfigurados"], "Hoja", "nombre");
                    $campoCantidadLote = $this->getValue( $info["camposConfigurados"], "Cantidad de lotes", "nombre");
                    $lote = $this->getValue( $info["camposConfigurados"], "Lote", "nombre");
                    $subsidio = $this->getValue( $info["camposConfigurados"], "Subsidio", "nombre");
                    $valor_catastral = $this->getValue( $info["camposConfigurados"], "Valor catastral", "nombre");
                    $valor_operacion = $this->getValue( $info["camposConfigurados"], "Valor de operacion", "nombre");
                    $tipoOperacion = $this->getValue( $info["camposConfigurados"], "Tipo de Operación", "nombre");
                    $divisa = $this->getValue( $info["camposConfigurados"], "Cambio de divisas", "nombre");

                    if(isset($valor_catastral) && $valor_catastral){
                        $parms['valor_catastral'] = $valor_catastral;
                    }

                    if(isset($subsidio) && $subsidio ){
                        $parms['subsidio'] = $subsidio;
                    }

                    if(isset($valor_operacion) &&  $valor_operacion){
                        $parms['valor_operacion'] = $valor_operacion;
                    }

                    if(isset($hoja) &&  $hoja){
                        $parms['hoja'] = $hoja;
                    }

                    if(isset($tipoOperacion) &&  $tipoOperacion){
                        $parms['tipoOperacion'] = $tipoOperacion;
                    }


                    if(isset($divisa) &&  $divisa){
                        $parms['divisa'] = $divisa;
                    }


                    if(isset($campoCantidadLote) &&  $campoCantidadLote) {// es con el campo o con el valor
                        $parms['lote'] = $campoCantidadLote;
                    } else if( isset($lote) &&  $lote ){
                        $parms['lote'] = $lote;
                    }

                    if( isset( $this->info['complementoDe'] ) ){
                        Log::info('complementoDe');
                        Log::info( print_r ($this->info['complementoDe'], true));
                        $parms['id_ticket'] = $this->info['complementoDe'];
                    }
                }


                return $parms;
            } else {
                Log::info("No se encontraron solicitantes");
                return [];
            }
        } else {
            Log::info("No se encontro tramite id");
            return [];
        }
    }


    public function calcularTotal($params){
        $response = [];
        $request = new Request();
        $request->initialize( $params );
        try {
            $response =  $this->tramiteCtrl->getcostoTramite( $request );
            $this->detalleNuevo = $response; 
        } catch (Exception $e) {
            Log::error('Error al obtener detalle '. $e->getMessage());
        }
        
        return $response;
        
    }

    public function updateDetalle(){
        $params =  $this->getParamsParaCosto();
        if( count($params) > 0 ){
           
                $detalleAnterior = new \stdClass;

                $detalleAnterior->fechaDeActualizacion = date("Y-m-d h:i:sa");
                $detalleAnterior->detalle = $this->getDetalleActual();
                $this->info['detalleAnterior'] = $detalleAnterior;

                $detalle = $this->calcularTotal( $params );
                $detalleObj = json_decode( $detalle, true, 512);
                $this->info['costo_final'] = $detalleObj[0]['costo_final'];
                $this->info['detalle'] = $detalleObj[0];
               
                
                $query = $this->modelTramite->where('id', $this->tramite->id)->where('status', '=', 99)->update([
                    'info' => json_encode($this->info)
                ]);   
                Log::info('Registrto actualizado');
                Log::info( print_r ($query, true));
                Log::info('-----------------------');
                return $query ? true : false;

        } else {
            Log::warning('No hay parametros para calcular costos');
            return false;
        }
    }

    private function infoToObject($tramite){
        return json_decode( $tramite->info, true, 512);
    }

    private function getValue($array, $name, $campo){
        $res = $this->findIndex($name, $array, $campo);
        if( gettype($res) != 'boolean' &&  $res >= 0 ){
            if($array[$res]['tipo'] == 'select'){
                return $array[$res]['valor']['clave'];
            } else {
                return $array[$res]['valor'];
            }
            
        } else {
            return false;
        }
    }

    private function getInNumber($dato ){
        return preg_replace('/[^0-9.-]+/i', "", $dato  );
    }


    private function findIndex($label, $array, $column){
        return array_search( strtolower($label), array_map( function ($column){
            return strtolower($column);  
        } ,array_column($array, $column) ) );
    }

    private function columnToLower($column){
        return strtolower($column);   
    }

    public function getTotal(){
        $params =  $this->getParamsParaCosto();
        if( count($params) > 0 ){
            $detalle = $this->calcularTotal( $params );
            $detalleObj = json_decode( $detalle, true, 512);            
            return $detalleObj[0]['costo_final'];
        } else {
            return false;
        }
    }

    public function getDetalleNuevoComoObj(){
        return json_decode( $this->detalleNuevo, true, 512);
    }

    public function canUpdate(){
        $totalActual = $this->getTotalActual();
        $totalNuevo = $this->getTotal();
        return $totalActual != $totalNuevo;  
    }
}