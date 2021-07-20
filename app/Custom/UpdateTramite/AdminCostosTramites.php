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
        Log::channel('costos-update')->info('Obtener costos total actual');
        $tramiteCtrl->setTramite( $tramite );
        $totalActual =  $tramiteCtrl->getTotalActual();
        if(isset($totalActual)){
            Log::channel('costos-update')->info('Importe total actual:' . $totalActual );
            Log::channel('costos-update')->info('Obtener campos para calcular costo nuevo');
            $params = $tramiteCtrl->getParamsParaCosto();
            if( count($params) > 0 ){
                Log::channel('costos-update')->info('**********Datos para calcular costo**********');
                Log::channel('costos-update')->info( print_r ($params, true));
                Log::channel('costos-update')->info('Obtener costo nuevo');
                Log::channel('costos-update')->info("Importe nuevo:" . ($tramiteCtrl->getTotal()) );
                Log::channel('costos-update')->info("Necesario actualizar :" . ( $tramiteCtrl->canUpdate() ? 'SI' : 'NO' )  );
                Log::channel('costos-update')->info('Detalle Calculo Nuevo:');
                Log::channel('costos-update')->info( print_r ($tramiteCtrl->getDetalleNuevoComoObj(), true));
                if( $tramiteCtrl->canUpdate() ){
                    return $tramiteCtrl->updateDetalle();
                } else {
                    return false;
                }
                Log::channel('costos-update')->info('Fin update');
            } else {
                Log::channel('costos-update')->warning('##########No hay parametros para consultar detalle');  
                return false;
            } 
        } else {
            Log::channel('costos-update')->warning('No tiene Importe total');
            return false;
        }     

    }

}