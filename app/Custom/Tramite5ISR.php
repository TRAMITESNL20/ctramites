<?php 
namespace App\Custom;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Http\Controllers\CalculoimpuestosController;
use App\Repositories\PortalsolicitudesticketRepositoryEloquent;

class Tramite5ISR 
{

    public function __construct(CalculoimpuestosController $impuestoCtrl,PortalsolicitudesticketRepositoryEloquent $modelTramite)
    {
        $this->impuestoCtrl = $impuestoCtrl;
        $this->modelTramite = $modelTramite;
    }

    public function setTramite( $tramite ){
        $this->tramite = $tramite;
        $this->info = $this->infoToObject( $tramite );
    }

    public function getTotalActualISR(){
        $info = $this->info;
        if(  (isset( $info['detalle']) && gettype( $info['detalle']) == 'array' )  ||  ( isset( $info['enajenante']['detalle']) && gettype( $info['enajenante']['detalle']) == 'array' ) ) {
            $detalle = isset( $info['detalle'] ) ? $info['detalle'] : $info['enajenante']['detalle'];
            if( isset($detalle['Salidas'] ) && gettype( $detalle['Salidas'] ) == 'array' && isset($detalle['Salidas']["Importe total"] ) ) {
                return $detalle['Salidas']["Importe total"];
            } else {
                return NULL;
            }
        }
    }

    public function getDetalleActual(){
        $info = $this->info;
        if(  (isset( $info['detalle']) && gettype( $info['detalle']) == 'array' )  ||  ( isset( $info['enajenante']['detalle']) && gettype( $info['enajenante']['detalle']) == 'array' ) ) {
            $detalle = isset( $info['detalle'] ) ? $info['detalle'] : $info['enajenante']['detalle'];
            return $detalle;
        } else {
            return false;
        }
    }

    public function getParamsParaCosto(){   
        $info = $this->info;
        Log::info('Tipo tramite: ' . ( $this->info['tipoTramite']) ); 
        $parms = [];
        if( $info && isset($info["camposConfigurados"]) ){
            $parms['fecha_escritura'] = $this->getValue( $info["camposConfigurados"], "Fecha de escritura o minuta", "nombre");
            $parms['ganancia_obtenida'] = $this->getInNumber( $info['enajenante']["datosParaDeterminarImpuesto"]['gananciaObtenida']  ) ;
            $parms['monto_operacion'] = $this->getInNumber( $info['enajenante']["datosParaDeterminarImpuesto"]['montoOperacion']  ) ;//15600;//
            $parms['multa_correccion_fiscal'] = $this->getInNumber( $info['enajenante']["datosParaDeterminarImpuesto"]['multaCorreccion']  ) ;//16800;//
            $parms['pago_provisional_lisr'] = $this->getInNumber( $info['enajenante']["datosParaDeterminarImpuesto"]['pagoProvisional']  ) ;   
        }

        return $parms; 
    }


    public function calcularTotal($params){
        $response = [];
        $request = new Request();
        $request->initialize( $params );
        try {
            $response =  $this->impuestoCtrl->index( $request ); 
        } catch (Exception $e) {
            Log::error('Error al obtener detalle '. $e->getMessage());
        }
        return $response;
        
    }

    public function updateDetalle(){
        $params =  $this->getParamsParaCosto();
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
        }
    }

    private function infoToObject($tramite){
        return json_decode( $tramite->info, true, 512);
    }

    private function getValue($array, $name, $campo){
        $campoFechaEscrituraIndex = $this->findIndex($name, $array, $campo);
        if( $campoFechaEscrituraIndex >= 0 ){
            return $array[$campoFechaEscrituraIndex]['valor'];
        } else {
            return false;
        }
    }

    private function getInNumber($dato ){
        return preg_replace('/[^0-9.-]+/i', "", $dato  );
    }


    private function findIndex($label, $array, $column){
        return array_search($label, array_column($array, $column));
    }
}