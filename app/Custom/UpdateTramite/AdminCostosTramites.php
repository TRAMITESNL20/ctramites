<?php 
namespace App\Custom\UpdateTramite;

use App\Custom\UpdateTramite\iTramite;
use App\Custom\UpdateTramite\TramiteRP;
use App\Custom\UpdateTramite\Tramite5ISR;
use Illuminate\Support\Facades\Log;

use App\Http\Controllers\TramitesController;
use App\Http\Controllers\CalculoimpuestosController;
use App\Repositories\PortalsolicitudesticketRepositoryEloquent;
use App\Repositories\PortalsolicitudescatalogoRepositoryEloquent;


class AdminCostosTramites 
{

    public function __construct(TramitesController $tramiteCtrl, PortalsolicitudesticketRepositoryEloquent $modelTramite, PortalsolicitudescatalogoRepositoryEloquent $catalogoSolicitud, CalculoimpuestosController $impuestoCtrl){
        $this->tramiteCtrl = $tramiteCtrl;
        $this->modelTramite = $modelTramite;
        $this->catalogoSolicitud = $catalogoSolicitud;
        $this->impuestoCtrl = $impuestoCtrl;

    }

    public function updateISR($tramite){
        $tramiteISRClass = new Tramite5ISR($this->impuestoCtrl, $this->modelTramite);
        return $this->update($tramiteISRClass, $tramite );     
    }

    public function updateTramiteRP( $tramite ){
        $tramiteRPClass = new TramiteRP($this->tramiteCtrl, $this->modelTramite, $this->catalogoSolicitud);
        return $this->update($tramiteRPClass, $tramite );
    }

    private function update( iTramite $tramiteCtrl, $tramite  ){
        Log::info('Obtener costos total actual');
        $tramiteCtrl->setTramite( $tramite );
        $totalActual =  $tramiteCtrl->getTotalActual();
        if(isset($totalActual)){
            Log::info('Importe total actual:' . $totalActual );
            Log::info('Obtener campos para calcular costo nuevo');
            $params = $tramiteCtrl->getParamsParaCosto();
            if( count($params) > 0 ){
                Log::info('**********Datos para calcular costo**********');
                Log::info( print_r ($params, true));
                Log::info('Obtener costo nuevo');
                Log::info("Importe nuevo:" . ($tramiteCtrl->getTotal()) );
                Log::info("Necesario actualizar :" . ( $tramiteCtrl->canUpdate() ? 'SI' : 'NO' )  );
                Log::info('Detalle Calculo Nuevo:');
                Log::info( print_r ($tramiteCtrl->getDetalleNuevoComoObj(), true));
                if( $tramiteCtrl->canUpdate() ){
                    return $tramiteCtrl->updateDetalle();
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

}