<?php
    $user = session()->get("user");
?>
<div class="content d-flex flex-column flex-column-fluid" id="app">
    <div class="d-flex flex-column-fluid">
	    <div class="container">              
            <div>
                <span> inicio->Tramites en curso-> Pago</span>
            </div>
            @if(isset($respuestabanco['error']) && $respuestabanco['error']['code'] == 500)
	            <div  style="padding-top: 10px; min-height: 600px;" class="content d-flex flex-column flex-column-fluid">
	            	<div >
		                <div class="card" style="width: 100%;">
		              		<div class="card-body text-center">
		                		<h5 class="card-title" >Ocurrió un error al porcesar el trámite</h5>
		                		{{$respuestabanco['error']['message']}}
		              		</div>
		            	</div>
		            </div>
	            	
	            </div>
            @else
	            <div  style="padding-top: 10px; min-height: 600px;" class="content d-flex flex-column flex-column-fluid">
	                <div>
	                    <div class="dropdown-divider"></div>
	                    <section id="invoicePanel" >
							<div class="container">
	  							<div class="card">
									<div class="card-header">
										Estado:
										<strong>
											{{ $respuestabanco['mensaje'] }}
											@if(isset($user->email))
											<a href="mailto:{{$user->email}}?subject=Recibo de pago&body={{$respuestabanco['response']['datos']['url_recibo']}}" style="display: none;">
												Enviar por correo <i class="fa fa-envelope-open-text "></i>
											</a>
											@endif
										</strong>
										@if($respuestabanco['mensaje'] == 'Aprobada')
		  									<span class="float-right"> 
		  										<strong>Total:</strong> 
		  										{{$respuestabanco['importe_transaccion']}}
		  									</span>
										@endif
									</div>

								
									<div class="card-body">
										<div class="row" >
											<div class="col-lg-12 col-sm-12">
												<div class="container">
													@if($respuestabanco['mensaje'] == 'Aprobada')
										        		<iframe class="responsive-iframe" src="{{$respuestabanco['url_recibo']}}" frameborder="0"  width="100%" height="600px"></iframe>
													@else
											           	<div class="text-center">
											           		<h1>  Lo sentimos, No se ha podido procesar el pago</h1>
											           		<hr>
											           		<a class="btn btn-danger" href="{{ url()->route('tramite.cart') }}" >
											           			Ir al carrito
											           		</a>
											           	</div>
											         
											        @endif
										    	</div> 
											</div>
										</div>
									</div>
								</div>
							</div>
						</section>

	                </div>
	            </div>
	        @endif
        </div>
        
    </div>
</div>
