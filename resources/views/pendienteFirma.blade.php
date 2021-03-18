
<div class="content d-flex flex-column flex-column-fluid" id="app">
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
                                            <div class="pt-10 pl-10 pr-10">
                                                    <div v-for="idTramite in {{$idTramites}}">
                                                        <firma-electronica-component :usuario="idTramite" :user="{{$user}}" ></firma-electronica-component>
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
