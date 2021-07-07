<?php 
namespace App\Custom;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Http\Controllers\TramitesController;
use App\Repositories\PortalsolicitudesticketRepositoryEloquent;
use App\Repositories\PortalsolicitudescatalogoRepositoryEloquent;

class TramiteRP
{

    protected $namesFieldsCost = ["Cambio de divisas", "Lote", "Hoja", "Subsidio",  "Valor catastral",  "Valor de operacion", "Cantidad de lotes" ,  "Tipo de Operación" ];

    public function __construct(TramitesController $impuestoCtrl,PortalsolicitudesticketRepositoryEloquent $modelTramite, PortalsolicitudescatalogoRepositoryEloquent $catalogoSolicitud)
    {
        $this->impuestoCtrl = $impuestoCtrl;
        $this->modelTramite = $modelTramite;
        $this->catalogoSolicitud = $catalogoSolicitud;
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
        /*$info = $this->info;
        if(  (isset( $info['detalle']) && gettype( $info['detalle']) == 'array' )  ||  ( isset( $info['enajenante']['detalle']) && gettype( $info['enajenante']['detalle']) == 'array' ) ) {
            $detalle = isset( $info['detalle'] ) ? $info['detalle'] : $info['enajenante']['detalle'];
            return $detalle;
        } else {
            return false;
        }*/
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
                    $parms['valor_catastral'] = $this->getValue( $info["camposConfigurados"], "Valor catastral", "nombre");
                    $parms['subsidio'] = $this->getValue( $info["camposConfigurados"], "Subsidio", "nombre");
                }



            /*if( $info && isset($info["camposConfigurados"]) ){
                $parms['fecha_escritura'] = $this->getValue( $info["camposConfigurados"], "Fecha de escritura o minuta", "nombre");
                $parms['ganancia_obtenida'] = $this->getInNumber( $info['enajenante']["datosParaDeterminarImpuesto"]['gananciaObtenida']  ) ;
                $parms['monto_operacion'] = $this->getInNumber( $info['enajenante']["datosParaDeterminarImpuesto"]['montoOperacion']  ) ;//15600;//
                $parms['multa_correccion_fiscal'] = $this->getInNumber( $info['enajenante']["datosParaDeterminarImpuesto"]['multaCorreccion']  ) ;//16800;//
                $parms['pago_provisional_lisr'] = $this->getInNumber( $info['enajenante']["datosParaDeterminarImpuesto"]['pagoProvisional']  ) ;   
            }*/

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
            $response =  $this->impuestoCtrl->getcostoTramite( $request ); 
        } catch (Exception $e) {
            Log::error('Error al obtener detalle '. $e->getMessage());
        }
        return $response;
        
    }

    public function updateDetalle(){
        /*$params =  $this->getParamsParaCosto();
        if($this->info['tipoTramite'] == 'normal'){
            if( count($params) > 0 ){
                $detalleAnterior = new \stdClass;

                $detalleAnterior->fechaDeActualizacion = date("Y-m-d h:i:sa");
                $detalleAnterior->detalle = $this->getDetalleActual();
                $this->info['detalleAnterior'] = $detalleAnterior;

                $detalle = $this->calcularTotal( $params );
                $detalleObj = json_decode( $detalle, true, 512);
                $this->info['costo_final'] = $detalleObj['Salidas']["Importe total"];
                $this->info['detalle'] = $detalleObj;
                $this->info['enajenante']['detalle'] = $detalleObj;
                $query = $this->modelTramite->where('id', $this->tramite->id)->where('status', '=', 99)->update([
                    'info' => json_encode($this->info)
                ]);   
                Log::info('Registrto actualizado');
                Log::info( print_r ($query, true));
                Log::info('-----------------------');
                return $query ? true : false;
            } else {
                return false;
            }
        } else {
            Log::error('Es declaracionEn0');
            return false;
        }*/
    }

    private function infoToObject($tramite){
        return json_decode( $tramite->info, true, 512);
    }

    private function getValue($array, $name, $campo){
        $campo = $this->findIndex($name, $array, $campo);
        if( gettype($campo) == 'integer' &&  $campo >= 0 ){
            if($array[$campo]['tipo'] == 'select'){
                return $array[$campo]['valor']['clave'];
            } else {
                return $array[$campo]['valor'];
            }
            
        } else {
            Log::info("es no integet campo");
            return false;
        }
    }

    private function getInNumber($dato ){
        return preg_replace('/[^0-9.-]+/i', "", $dato  );
    }


    private function findIndex($label, $array, $column){
        return array_search( strtolower($label), array_column($array, strtolower($column)));
    }
}