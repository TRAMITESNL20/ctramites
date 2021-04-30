
<div class="content d-flex flex-column flex-column-fluid pt-0" id="app">
    <div class="d-flex flex-column-fluid">
	    <div class="container">              
            <div>
                <span> inicio->Tramites por firmar-> Firmar </span>
            </div>
            <div  style="padding-top: 10px; min-height: 600px;" class="content d-flex flex-column flex-column-fluid">
                <div>
                    <div class="dropdown-divider"></div>
                    <section id="invoicePanel" >
						<div class="container">
  							<div class="card">
								<div class="card-header">
                                    TRAMITES POR FIRMAR:
								</div>
								<div class="card-body">
									<div class="row" >
										<div class="col-lg-12 col-sm-12">
											<div class="container">
                                            <div class="pt-10 ">
                                                    <!-- <div class="pt-10 pl-10 pr-10"  v-if=" {{ strlen($tramitesDoc) }} != 2">
                                                        <div >
                                                            <p> El tramite seleccionado no cuenta con los documentos de CALCULO DEL ISR CONFORME AL 126 LISR y SAT </p>
                                                            <modal-document-component :tramitesdoc="{{$tramitesDoc}}" ></modal-document-component>
                                                        </div>
                                                    </div> -->

                                                    <div class="pt-10 "  v-for="idTramite in {{$idTramites}}">
                                                        <firma-electronica-component :usuario="idTramite"   :user="{{$user}}" ></firma-electronica-component>
                                                    </div>

                                                </div>
									    	</div> 
										</div>
									</div>
								</div>
							
							</div>
						</div>
					</section>

                </div>
            </div>
        </div>
        
    </div>
</div>
