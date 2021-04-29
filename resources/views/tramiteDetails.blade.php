<?php
    $info = isset($tramite->info->campos->Escritura) ? $tramite->info->campos->Escritura : ( isset($tramite->info->campos->Expediente) ? $tramite->info->campos->Expediente : null );
    $camposConfigurados = [];
    // dd($tramite->created_at);
    setlocale(LC_TIME, "spanish");
    foreach($tramite->info->camposConfigurados as $campo){
        $name = strtolower($campo->nombre);
        $name = preg_replace("([^A-Za-z\ ])", '', $name);
        $name = preg_replace('/\s+/', '_', $name);
        $camposConfigurados[$campo->alias] = isset($campo->documento) ? $campo->documento : (isset($campo->valor) ? $campo->valor : null);
    }
?>
<div class="content d-flex flex-column flex-column-fluid" id="app">
    <div class="d-flex flex-column-fluid">
        <div class="container">              
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent">
                    <li class="breadcrumb-item"><a href="{{ url()->route("home") }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ url()->route("tramite.nuevo") }}">Trámites</a></li>
                    <li class="breadcrumb-item active" aria-current="page" id="tramite-name">{{ $tramite->titulo }}</li>
                </ol>
            </nav>
            <div style="min-height: 600px;" class="content d-flex flex-column flex-column-fluid pt-0">
                <div class="card">
                	<div class="card-body">
                		<div class="row align-items-center">
                			<div class="col">
		                		<h3 class="m-0 text-uppercase">{{ $tramite->info->tipoTramite }}</h3>
		                		<p class="text-muted m-0">{{ $tramite->clave }}</p>
                			</div>
                			<div class="col text-right">
                				<p class="m-0">{{ strftime("%A %d, %Y %I:%M %p", strtotime($tramite->created_at)) }}</p>
                			</div>
                		</div>
                	</div>
                </div>
                <div class="card mt-5">
                    @if ($tramite->tramite_id == getenv('TRAMITE_5_ISR'))
                        @foreach($camposConfigurados['expedientes']->expedientes as $ind => $expediente)
                            <h5 class="card-header text-uppercase bg-secondary d-flex align-items-center"><strong>Expedientes Catastrales</strong><span class="btn btn-light ml-auto nowrap">{{ $ind+1 }} de {{ count($camposConfigurados['expedientes']->expedientes) }}</span></h5>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <span class="text-muted">Número de escritura pública o minuta</span>
                                        <p><strong>{{ $camposConfigurados['escritura'] }}</strong></p>
                                    </div>
                                    <div class="col-md-4">
                                        <span class="text-muted">Fecha de escritura pública o minuta</span>
                                        <p><strong>{{ $camposConfigurados['fecha_de_escritura_o_minuta'] }}</strong></p>
                                    </div>
                                    <div class="col-md-4">
                                        <span class="text-muted">Cálculo del ISR conforme al 126 LISR (Archivo)</span>
                                        <p>
                                            @if(count($camposConfigurados['calculo_del_isr_conforme_al_lisr']) > 0)
                                                @foreach ($camposConfigurados['calculo_del_isr_conforme_al_lisr'] as $key => $documento)
                                                    <a href="{{ $documento }}" target="_blank" class="btn btn-primary text-white mt-2"><i class="fas fa-download"></i> Ver Documento {{  count($camposConfigurados['calculo_del_isr_conforme_al_lisr']) > 1 ? $key+1 : '' }}</a>
                                                @endforeach
                                            @else
                                            -
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <span class="text-muted">Expediente</span>
                                        <p><strong>{{ $expediente->expediente }}</strong></p>
                                    </div>
                                    <div class="col-md-4">
                                        <span class="text-muted">Estado</span>
                                        <p><strong>{{ $expediente->estado->nombre }}</strong></p>
                                    </div>
                                    <div class="col-md-4">
                                        <span class="text-muted">Municipio</span>
                                        <p><strong>{{ $expediente->municipio->nombre }}</strong></p>
                                    </div>
                                </div>
                            </div>
                            <h5 class="card-header border-0 text-uppercase bg-secondary"><strong>Dirección</strong></h5>
                            <div class="card-body">
                                <div class="row">
                                    <?php
                                        foreach($expediente->direccion as $key => $val){
                                            if(gettype($val) == 'array'){
                                                echo "</div>
                                                </div>
                                                <h6 class=\"card-header\"><strong>".strtoupper(str_replace('_', ' ', $key))."</strong></h6>
                                                <div class=\"card-body\">
                                                    <div class=\"row\">";

                                                foreach($val[0] as $key => $val){
                                                    echo "
                                                        <div class=\"col-md-4\">
                                                            <span class=\"text-muted text-capitalize\">".str_replace('_', ' ', $key)."</span>
                                                            <p><strong>".( !empty($val) ? $val : '-' )."</strong></p>
                                                        </div>
                                                    ";
                                                }
                                            }else{
                                                echo "
                                                    <div class=\"col-md-4\">
                                                        <span class=\"text-muted text-capitalize\">".str_replace('_', ' ', $key)."</span>
                                                        <p><strong>".( !empty($val) ? $val : '-' )."</strong></p>
                                                    </div>
                                                ";
                                            }
                                        }
                                    ?>
                                </div>
                            </div>
                        @endforeach
                        </div>
                        <div class="card mt-5">
                            @foreach($camposConfigurados['listado_de_enajenantes']->enajenantes as $ind => $enajenante)
                                <h5 class="card-header text-uppercase bg-secondary d-flex align-items-center"><strong>DATOS DEL ENAJENANTE</strong><span class="btn btn-light ml-auto">{{ $ind+1 }} de {{ count($camposConfigurados['listado_de_enajenantes']->enajenantes) }}</span></h5>
                                <div class="card-body mb-3">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <span class="text-muted">Registro Federal del Contribuyente</span>
                                            <p><strong>{{ $enajenante->datosPersonales->curp }}</strong></p>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="text-muted">Clave Única de Registro de Población</span>
                                            <p><strong>{{ $enajenante->datosPersonales->rfc }}</strong></p>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="text-muted">Clave de Elector (INE)</span>
                                            <p><strong>{{ $enajenante->datosPersonales->claveIne }}</strong></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <span class="text-muted">Nombre</span>
                                            <p><strong>{{ $enajenante->datosPersonales->nombre }}</strong></p>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="text-muted">Apellido Paterno</span>
                                            <p><strong>{{ $enajenante->datosPersonales->apPat }}</strong></p>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="text-muted">Apellido Materno</span>
                                            <p><strong>{{ $enajenante->datosPersonales->apMat }}</strong></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <span class="text-muted">% de Co-propiedad</span>
                                            <p><strong>{{ $enajenante->porcentajeCompra }}</strong></p>
                                        </div>
                                    </div>
                                </div>
                                <h6 class="card-header"><strong>DATOS PARA DETERMINAR EL IMPUESTO DE LA ENTIDAD FEDERATIVA</strong></h6>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <span class="text-muted">Tipo de declaración</span>
                                            <p><strong>{{ $tramite->info->tipoTramite }}</strong></p>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="text-muted">Ganancia obtenida</span>
                                            <p><strong>MX$ {{ number_format($enajenante->detalle->Salidas->{'Ganancia Obtenida'}, 2) }}</strong></p>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="text-muted">Monto obtenido conforme al ART 127 BIS de la ISR</span>
                                            <p><strong>MX$ {{ number_format($enajenante->detalle->Salidas->{'Monto obtenido conforme al art 127 LISR'}, 2) }}</strong></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <span class="text-muted">Pago provisional conforme al ART 126 BIS de la ISR</span>
                                            <p><strong>MX$ {{ number_format($enajenante->detalle->Salidas->{'Pago provisional conforme al art 126 LISR'}, 2) }}</strong></p>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="text-muted">Impuesto correspondiente a la entidad federativa</span>
                                            <p><strong>MX$ {{ number_format($enajenante->detalle->Salidas->{'Impuesto correspondiente a la entidad federativa'}, 2) }}</strong></p>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="text-muted">Parte actualizada del impuesto</span>
                                            <p><strong>MX$ {{ number_format($enajenante->detalle->Salidas->{'Parte actualizada del impuesto'}, 2) }}</strong></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <span class="text-muted">Recargos</span>
                                            <p><strong>MX$ {{ number_format($enajenante->detalle->Salidas->{'Recargos'}, 2) }}</strong></p>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="text-muted">Multa por correción fiscal</span>
                                            <p><strong>MX$ {{ number_format($enajenante->detalle->Salidas->{'Multa corrección fiscal'}, 2) }}</strong></p>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="text-muted">Importe total</span>
                                            <p><strong>MX$ {{ number_format($enajenante->detalle->Salidas->{'Importe total'}, 2) }}</strong></p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="card-header text-uppercase bg-secondary"><strong>Información</strong></div>
                        <div class="card-body">
                            <div class="row">
                                <?php
                                    foreach($camposConfigurados as $campo => $value){
                                        while(gettype($value) == "array"){
                                            if(isset($value[0])) $value = $value[0];
                                            if(!isset($value[0])) $value[0] = '-';

                                            $value = $value[0];
                                        }

                                        if(gettype($value) == "object"){
                                            if(isset($value->nombre)) $value = $value->nombre;
                                            else continue;
                                        }
                                        echo "
                                            <div class=\"col-md-6\">
                                                <span class=\"text-muted\">$campo</span>
                                                <p><strong>$value</strong></p>
                                            </div>
                                        ";
                                    }
                                ?>
                            </div>
                        </div>                        
                    @endif
                </div>
                <div class="card mt-5 <?= count($tramite->mensajes) == 0 ? "d-none" : ""?>">
                	<div class="card-header d-flex align-items-center">
                		<p class="m-0">
                			<strong>Mensajes</strong> <span class="badge badge-secondary"><?=count($tramite->mensajes)?></span>
                		</p>
                		<button class="btn btn-secondary ml-auto d-none">Nuevo Mensaje</button>
                	</div>
                	<div class="card-body">
                		<?php
                			foreach($tramite->mensajes as $ind => $msg){
                				$format = [];
                				$updated = new DateTime($msg->updated_at);
                				$current = new DateTime();
								$interval = $updated->diff($current);
								if($interval->format('%d') > 0)
									array_push($format, $interval->format('%d') . " días");
								array_push($format, $interval->format('%h') . " hrs");
								array_push($format, $interval->format('%i') . " mins");
                                
                				echo "
                					<div data-ind=".$ind." class=\"mensage ".($ind == (count($tramite->mensajes)-1) ? "pb-4 pt-0 border-bottom " : ($ind == 0 ? "pt-4 pb-0 " : "py-4 border-bottom "))."px-1\">
                						<div class=\"row\">
                							<div class=\"col-md-10\">
		                						<p class=\"card-text mb-0\">{$msg->mensaje}</p>
		                						<p class=\"card-text mt-2\"><small class=\"text-muted\">Ultima actualización hace ".implode(" ", $format)."</small></p>
                							</div>
                							<div class=\"col-md-2 text-right ".(empty($msg->attach) ? "d-none" : "")."\">
                								<a href=\"".(!empty($msg->attach) ? $msg->attach : "")."\" class=\"btn btn-secondary\" target=\"_blank\"><i class=\"fas fa-download\"></i> Ver Archivo</a>
                							</div>
                						</div>
                					</div>
                				";
                			}
                		?>
                	</div>
                </div>
            </div>
        </div>
    </div>
</div> 