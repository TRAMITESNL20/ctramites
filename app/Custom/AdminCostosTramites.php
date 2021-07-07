<?php 
namespace App\Custom;

use App\Custom\ramiteRP;
use App\Custom\Tramite5ISR;
use Illuminate\Support\Facades\Log;

class AdminCostosTramites 
{

    public function __construct(Tramite5ISR $tramite5ISR, TramiteRP $tramiteRP){
		$this->tramite5ISR = $tramite5ISR;
        $this->tramiteRP = $tramiteRP;
    }


    public function updateISR($tramite){
        Log::info('Obtener costos total actual');
        $this->tramite5ISR->setTramite( $tramite );
        $totalActual =  $this->tramite5ISR->getTotalActualISR();
        if(isset($totalActual)){
            Log::info('Importe total actual:' . $totalActual );
            Log::info('Obtener campos para calcular costo nuevo ');
            $params = $this->tramite5ISR->getParamsParaCosto();
            if( count($params) > 0 ){
                Log::info('**********Datos para deteterminar el impuesto**********');
                Log::info( print_r ($params, true));
                Log::info('Obtener costo nuevo');
                $detalle =  $this->tramite5ISR->calcularTotal($params);
                $detalleObj = json_decode( $detalle, true, 512);
                Log::info("Importe nuevo:" . $detalleObj['Salidas']["Importe total"] );
                Log::info("Necesario actualizar :" . ($totalActual != $detalleObj['Salidas']["Importe total"] ? 'SI' : 'NO' )  );
                Log::info('Detalle:');
                Log::info( print_r ($detalleObj, true));
                if( $totalActual != $detalleObj['Salidas']["Importe total"] ){
                	return $this->tramite5ISR->updateDetalle();
                } else {
                	return false;
                }
                Log::info('Fin update');
            } else {
                Log::error('##########No hay parametros para consultar detalle');  
                return false;
            }
            
        } else {
            Log::error('No tiene Importe total');
            return false;
        }        
    }

    public function updateTramiteRP( $tramite ){
        Log::info('Obtener costos total actual');
        $this->tramiteRP->setTramite( $tramite );
        $totalActual =  $this->tramiteRP->getTotalActual();
        if(isset($totalActual)){
            Log::info('Importe total actual:' . $totalActual );
            Log::info('Obtener campos para calcular costo nuevo');
            $params = $this->tramiteRP->getParamsParaCosto();

            if( count($params) > 0 ){
                Log::info('**********Datos para calcular costo**********');
                Log::info( print_r ($params, true));
                Log::info('Obtener costo nuevo');
            } else {
                Log::error('##########No hay parametros para consultar detalle');  
                return false;
            }            
        } else {
            Log::error('No tiene Importe total');
            return false;
        } 
    }

}