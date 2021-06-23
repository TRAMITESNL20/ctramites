
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
                                    TRAMITES POR FIRMAR
								</div>
							</div>
                            
                            <aux-firmado-component 
                                                        :usuario="{{$idTramites}}" 
                                                        :user="{{$user}}" 
                                                        :tramitesdoc="{{$tramitesDoc}}"
                                                    >
                            </aux-firmado-component>    


						</div>
					</section>

                </div>
            </div>
        </div>
        
    </div>
</div>
