<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\PortalsolicitudesticketRepositoryEloquent;
use Illuminate\Support\Facades\Log;
use App\Custom\UpdateTramite\AdminCostosTramites;

class updateDetalle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tramites:updateDetalle';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualiza el costo de los tramites';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(PortalsolicitudesticketRepositoryEloquent $modelTramite, AdminCostosTramites $adminCostos)
    {
        $this->modelTramite = $modelTramite;
        $this->adminCostos = $adminCostos;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::info('*******************************************INICIO DEL PROCESO*******************************************');
        Log::info('Obtener tramites');
        $totalActualizados = 0;
        $listadoTramites = $this->getTramites();
        Log::info('###############################################Total Tramites: ' . (count($listadoTramites)) . '###############################################');
        if($listadoTramites && count($listadoTramites)){
            Log::info('Procesar tramites');
            foreach ($listadoTramites as $tramite) {
                Log::info('###############################################Tramite, ticket: ' . ($tramite->id). ' ###############################################'); 
                if( $tramite->catalogo_id == 10 ){
                    Log::info('update 5% ISR') ;
                    if($this->adminCostos->updateISR($tramite)){
                        $totalActualizados = $totalActualizados+1;
                    }
                } else if( $tramite->catalogo_id == 71 ){
                    Log::info('Actualizacion no disponible Catalogo ID:' . $tramite->catalogo_id);
                } else if( $tramite->catalogo_id == 3 ){
                    Log::info('Actualizacion no soportada Catalogo ID:' . $tramite->catalogo_id);
                } else {
                   Log::info('update Tramite RP');
                    if($this->adminCostos->updateTramiteRP($tramite)){
                        $totalActualizados = $totalActualizados+1;
                    }
                }
                
                Log::info('-----------------------------------------------FIN ticket ' . $tramite->id . '-----------------------------------------------');
            }
            Log::info('Actualizados: ' . $totalActualizados);
            Log::info('*******************************************FIN DEL PROCESO*******************************************');
        } else {
            Log::warning('Error al obtener los tramites'); 
        }
    }

    public function getTramites(){  
        try {
            return $this->modelTramite->where('status','=','99')->get();
        } catch (Exception $e) {
            return false;
        }
        
    }

}
