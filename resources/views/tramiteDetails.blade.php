<?php
    $info = isset($tramite->info->campos->Escritura) ? $tramite->info->campos->Escritura : ( isset($tramite->info->campos->Expediente) ? $tramite->info->campos->Expediente : null );
    // function getFields ($campos) {
        // $fields = "";
        // foreach($campos as $key => $val){
            // if(gettype($val) == 'array'){
                // for($item in $val){
                    // $fields .= getFields($val);
                // }
                // return true;
            // }
            // if(gettype($value) == "object"){
                // if(isset($value->nombre)) $val = $val->nombre;
                // else{
                    // $fields .= getFields($val);
                    // return true;
                // }
            // }
            // $fields .= "
                // <div class=\"col-md-6\">
                    // <span class=\"text-muted\">{$key}</span>
                    // <p><strong>{$val}</strong></p>
                // </div>
            // ";

        // }

        // return $fields;
    // }

     // echo getFields($tramite->info->campos);
// die();
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
		                		<h3 class="m-0">{{ $tramite->titulo }}</h3>
		                		<p class="text-muted m-0">{{ $tramite->clave }}</p>
                			</div>
                			<div class="col text-right">
                				<p class="m-0">{{ strftime("%A %d, %Y %I:%M %p", strtotime($tramite->created_at)) }}</p>
                			</div>
                		</div>
                	</div>
                </div>
                <div class="card mt-5">
                	<div class="card-header"><strong>Información</strong></div>
                	<div class="card-body">
                		<div class="row">
                			<?php
                				foreach($tramite->info->campos as $campo => $value){
                					while(gettype($value) == "array"){ $value = $value[0]; }
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
                    @if ($tramite->tramite_id == 399)
                        <hr>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <span class="text-muted">Expediente</span>
                                    <p><strong>{{ $info->expedientes[0]->expediente }}</strong></p>
                                </div>
                                <div class="col-md-6">
                                    <span class="text-muted">Estado</span>
                                    <p><strong>{{ $info->expedientes[0]->estado->nombre }}</strong></p>
                                </div>
                                <div class="col-md-6">
                                    <span class="text-muted">Municipio</span>
                                    <p><strong>{{ $info->expedientes[0]->municipio->nombre }}</strong></p>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="card-header"><strong>Dirección</strong></div>
                        <div class="card-body">
                            <div class="row">
                                <?php
                                    foreach($info->expedientes[0]->direccion as $key => $val){
                                        if(gettype($val) == 'array' && count($val) == 1){
                                            echo "</div>
                                            </div>
                                            <div class=\"card-header\"><strong>".str_replace('_', ' ', $key)."v</strong></div>
                                            <div class=\"card-body\">
                                                <div class=\"row\">";

                                            foreach($val[0] as $key => $val){
                                                echo "
                                                    <div class=\"col-md-6\">
                                                        <span class=\"text-muted text-capitalize\">".str_replace('_', ' ', $key)."v</span>
                                                        <p><strong>{$val}</strong></p>
                                                    </div>
                                                ";
                                            }
                                        }else{
                                            echo "
                                                <div class=\"col-md-6\">
                                                    <span class=\"text-muted text-capitalize\">".str_replace('_', ' ', $key)."v</span>
                                                    <p><strong>{$val}</strong></p>
                                                </div>
                                            ";
                                        }
                                    }
                                ?>
                            </div>
                        </div>
                        <hr>
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
                					<div class=\"mensage ".($ind == 0 ? "pb-0 pt-4" : "py-4 border-bottom ").($ind == (count($tramite->mensajes)-1) ? "pt-0 pb-4" : "py-4")." px-1\">
                						<div class=\"row\">
                							<div class=\"col-md-10\">
		                						<p class=\"card-text mb-0\">{$msg->mensaje}</p>
		                						<p class=\"card-text mt-2\"><small class=\"text-muted\">Ultima actualización hace ".implode(" ", $format)."</small></p>
                							</div>
                							<div class=\"col-md-2 text-right ".(empty($msg->attach) ? "d-none" : "")."\">
                								<a href=\"".(!empty($msg->attach) ? getenv("TESORERIA_HOSTNAME")."/storage/app/{$msg->attach}" : "")."\" class=\"btn btn-secondary\" target=\"_blank\"><i class=\"fas fa-download\"></i> Ver Archivo</a>
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