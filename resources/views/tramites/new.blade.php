<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="d-flex flex-column-fluid">
        <div class="container">                
            <div>
                <span > inicio->Tramites en curso->Selección de Trámite</span>
            </div>
            <div class="card" style="padding-top: 10px; min-height: 600px;">
                <div class="card-body">
                    <div style="padding-top: 20px;">
                        <span class="card-title">
                            
                            <div style="padding-left: 1%">
                                <span class="titulo">Selección de Trámite</span>
                            </div>
                        </span>
                    </div>
                    <div class="dropdown-divider"></div>
                    <section id="tabs" >
					    <div class="container-fluid">
							<div class="row">

							    <!--Grid column-->
							    <div class="col-lg-8">

							      	<!-- Card -->
							      	<div class="mb-3">
							        	<div class="pt-4 wish-list" id="listTramites"></div>
							      	</div>
							      	<!-- Card -->


					      			<div class="mb-3" id="divMetodoPago"  style="display: none;">
										<div id="containerMetodoPago" > </div>  		
						        	</div>

							    </div>
							    <!--Grid column-->

							    <!--Grid column-->
							    <div class="col-lg-4">

							    	<!-- Card -->
							      	<div class="mb-3 shadow-sm p-3 bg-white rounded">  
							        	<div class="pt-4 ">

							          		<h5 class="mb-3">Total:</h5>

							          		<ul class="list-group list-group-flush">
							            		<li class="list-group-item d-flex justify-content-between align-items-center px-0 pb-0">
							              			Subtotal
							              			<span id="subTotalTramites">$0.00</span>
							            		</li>
							            		<li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 mb-3">
							              			<div>
							                			<strong>Total</strong>
							                			<strong>
							                  				<p class="mb-0"></p>
							                			</strong>
							              			</div>
							              			<span>
							              				<strong id="totalTramites">
							              					$0.00
							              				</strong>
							              			</span>
							            		</li>
							          		</ul>

							          		<button id="metodoPagoBtn" type="button" class="btn btn-primary btn-block" onclick="metodoPago()">
							          			Elegir método de pago
							          		</button>
							          		<button  id="addTramiteBTN" type="button" class="btn btn-default btn-block" onclick="openModalAdd()">Agregar Trámite</button>
							          		<button id="metodoPagoCancBtn" type="button" class="btn btn-danger btn-block" onclick="cancelarPago()" style="display: none;">
							          			Cancelar
							          		</button>
							        	</div>


							     	</div>
							      	<!-- Card -->
							    </div>
							    <!--Grid column-->
							</div>
							<!-- Grid row -->
					    </div>
					</section>

                </div>
            </div>
        </div>
        
    </div>
</div> 


<div id="modalDelete" class="modal fade " tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" ></button>
                <h4 class="modal-title">Confirmación</h4>
            </div>
            <div class="modal-body">
                <p>
             		¿Eliminar Registro?
                </p>
                 <input hidden="true" type="text" name="iddeleted" id="iddeleted" class="iddeleted">
            </div>
            <div class="modal-footer">
	         	<button type="button" data-dismiss="modal" class="btn default" >Cancelar</button>
	            <button type="button"  class="btn green" onclick="eliminar()" id="btnDel">
	            	<i class="fa fa-check" id="iconBtnDel"></i> 
	            	Confirmar
	            </button>
	        </div>
	   </div>
	</div>
</div>

<div id="addTramite" class="modal fade " tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" ></button>
                <h4 class="modal-title">Agregar trámite</h4>
            </div>
            <div class="modal-body">

				<form class="p-5" action="#!" id="formularioDinamico">
				    <!-- Tramite -->
				    <div class="row">
					    <div class="col-lg-12" id="divSelectTramites" >
					    	<div class="form-group">
					            <label class="col-md-3 control-label">Trámite</label>
					            <div>
					                <select class="select2me form-control browser-default custom-select mb-4" name="tramitesSelect" id="tramitesSelect">
					                	<option value="limpia">------</option>
							    	</select>   
					            </div>
					        </div>
					    </div>
					</div>
					<div class="text-center" id="loadin" style="display: none; margin-bottom: 9px;" >
						<div class="spinner-grow" role="status">
						  	<span class="sr-only">Loading...</span>
						</div>
					</div>
				    <div id="camposDinamicosDiv" class="row"></div>


 					<fieldset  class="border border-secondary p-2" style="display: none;" id="fieldsetSolicitantes">
					    <legend class="w-auto"> 
					    	<span style="margin-left: 10px;"> Solicitantes </span>   
					    	<i class="fa fa-plus" title="Agregar Solicitante" style="cursor: pointer; color: blue; margin-left: 50px; margin-right: 50px;" 
					    	 onclick="openModalAddSolicitante()"></i>
					   	</legend>

						<table class="table" id="tablaSolicitantes">
						    <thead>
						      	<tr>
							        <th>#</th>
							        <th>Tipo</th>
							        <th>Nombre o Razón Social</th>
							        <th>Acciones</th>
						      	</tr>
						    </thead>
						    <tbody id="tbodySolicitantes"></tbody>
						</table>

					</fieldset>
				

				</form>

            </div>
            <div class="modal-footer">
	         	<button type="button" data-dismiss="modal" class="btn default" >Cancelar</button>
	            <button type="button"  class="btn green"  id="btnAdd">
	            	<i class="fa fa-check" id="iconBtnAdd"></i> 
	            	Aceptar
	            </button>
	        </div>
	   </div>
	</div>
</div>

<div id="modalAddSolicitante" class="modal fade " tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" ></button>
                <h4 class="modal-title">Agregar Solicitante</h4>
            </div>
            <div class="modal-body">
	            <div class="form-check-inline">
	                <label class="form-check-label">
	                 	<input type="radio" class="form-check-input" value="pf" name="tipoPersona" id="pfRadio">Persona Física
	                </label>
	            </div>
	            <div class="form-check-inline">
	                <label class="form-check-label">
	                  	<input type="radio" class="form-check-input" value="pm" name="tipoPersona" id="pmRadio">Persona Moral
	                </label>
	            </div>
				<input type="hidden" name="idSolicitante" class="form-control" id="idSolicitante">
	            <div class="row" id="divPF">
	            	<div class="col-lg-4">
	                	Nombre: <input type="text" name="nombreSolicitante" class="form-control" id="nombreSolicitante">
	                </div>
	                <div class="col-lg-4">
	               		Apellido Paterno: <input type="text" name="apMatSolicitante" class="form-control" id="apMatSolicitante">
	                </div>
	                <div class="col-lg-4">
	                	Apellido Materno: <input type="text" name="apPatSolicitante" class="form-control" id="apPatSolicitante">
	                </div>
	            </div>
	            <div class="row" id="divPM" style="display: none;">
	            	<div class="col-lg-12">
	                	RFC: <input type="text" name="rfc" class="form-control" id="rfcSolicitante">
	                </div>
	            </div>
            </div>
            <div class="modal-footer">
	         	<button type="button" data-dismiss="modal" class="btn default" >Cancelar</button>
	            <button type="button"  class="btn green" onclick="agregarSolicitante()" id="btnDel">
	            	<i class="fa fa-check" id="iconBtnDel"></i> 
	            	Aceptar
	            </button>
	        </div>
	   </div>
	</div>
</div>

<link href="{{ asset('css/newTramite.css') }}" rel="stylesheet" type="text/css" />

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript" src="{{ asset('js/nuevoTramite/TiposElements/inputClass.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/nuevoTramite/TiposElements/selectClass.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/nuevoTramite/TiposElements/checkboxClass.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/nuevoTramite/TiposElements/optionClass.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/nuevoTramite/TiposElements/TextBoxClass.js') }}"></script>

<script type="text/javascript" src="{{ asset('js/nuevoTramite/ElementFactory.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/nuevoTramite/FormularioBuilder.js') }}"></script>

<script type="text/javascript" src="{{ asset('js/nuevoTramite/JsonGeneraReferenciaBuilder.js') }}"></script>


<script type="text/javascript" src="{{ asset('js/nuevoTramite/shoppingCarModule/shoppingCarBuilder.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/nuevoTramite/tramiteModulo/templateMetodoPagoBuilder.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/nuevoTramite/tramiteModulo/tramiteBuilder.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfobject/2.1.1/pdfobject.min.js"></script><divid="conteudo">
<script type="text/javascript">
	let tramites = [];

	let tramitesGuardar = [];

	let nuevoTramiteModal;

	$(document).ready(() => {
    	getTramites();
	    

 let datosTramite1 = {
          "nombre": "BEBIDAS MUNDIALES S DE RL DE CV",
          "apellido_paterno": "",
          "apellido_materno": "",
          "razon_social": "BEBIDAS MUNDIALES S DE RL DE CV",
          "rfc": "BMU8605134I8 ",
          "curp": "",
          "email": "",
          "calle": "AV LA JUVENTUD",
          "colonia": "BOSQUES DEL NOGALAR",
          "numexterior": "",
          "numinterior": "120",
          "municipio": "SAN NICOLAS DE LOS GARZA",
          "codigopostal": 66480
    }

      let tramite1 = new  TramiteClass().setIdSeguimiento(4254).setIdTipoServicio(3).setIdTramite("BMU8605134I81FM5K7D80EGA56944")
              .setImporteTramite(3041).setAuxiliar_1("GRUPOS ICV BMU8605134I8 ").setAuxiliar_2("").setAuxiliar_3("")
              .setDatosSolicitante(datosTramite1).setDatosFactura(datosTramite1).setDetalle([
                    {
                      "concepto": "REFRENDO PTE.AÑO",
                      "importe_concepto": "3041",
                      "partida": 95101
                      
                    },
                    {
                      "concepto": "SANCION REFRENDO PTE.AÑO",
                      "importe_concepto": "130",
                      "partida": 95123,
                      "descuentos": [
                        {
                          "concepto_descuento": "SUBSIDIO PAPV REFRENDO PA",
                          "importe_descuento": "130",
                          "partida_descuento": 95136
                        }
                      ]
                    }
                  ]).build();
      let tramite3 = new TramiteClass().setIdSeguimiento(43).setIdTipoServicio(3).setIdTramite("BMU8605134I81GBKC34JXWJ103325")
              .setImporteTramite(826).setAuxiliar_1("GRUPOS ICV BMU8605134I8 ").setAuxiliar_2("").setAuxiliar_3("")
              .setDatosSolicitante(datosTramite1).setDatosFactura(datosTramite1).setDetalle([{
                    "concepto": "REFRENDO PTE.AÑO",
                    "importe_concepto": "3041",
                    "partida": 95101,
                    "descuentos": [
                      {
                        "concepto_descuento": "SUBSIDIO ANTIGUEDAD 15 AÑOS",
                        "importe_descuento": "2215",
                        "partida_descuento": 95106
                      }
                    ]
                  },
                  {
                    "concepto": "SANCION REFRENDO PTE.AÑO",
                    "importe_concepto": "130",
                    "partida": 95123,
                    "descuentos": [
                      {
                        "concepto_descuento": "SUBSIDIO PAPV REFRENDO PA",
                        "importe_descuento": "130",
                        "partida_descuento": 95136
                      }
                    ]
                  }
                ]).build();
console.log( tramite3 )
      let tramite2 = new TramiteClass().setIdSeguimiento(334).setIdTipoServicio(3).setIdTramite("BMU8605134I81GBKC34J9WJ108483")
              .setImporteTramite(820).setAuxiliar_1("GRUPOS ICV BMU8605134I8").setAuxiliar_2("").setAuxiliar_3("")
              .setDatosSolicitante(datosTramite1).setDatosFactura(datosTramite1).setDetalle(
                [
                    {
                      "concepto": "REFRENDO PTE.AÑO",
                      "importe_concepto": "3035",
                      "partida": 95101,
                      "descuentos": [
                        {
                          "concepto_descuento": "SUBSIDIO ANTIGUEDAD 15 AÑOS",
                          "importe_descuento": "2215",
                          "partida_descuento": 95106
                        }
                      ]
                    },
                    {
                      "concepto": "SANCION REFRENDO PTE.AÑO",
                      "importe_concepto": "130",
                      "partida": 95123,
                      "descuentos": [
                        {
                          "concepto_descuento": "SUBSIDIO PAPV REFRENDO PA",
                          "importe_descuento": "130",
                          "partida_descuento": 95136
                        }
                      ]
                    }
                  ]).build();


      let tramitesSeleccionados = [];
      tramitesSeleccionados.push( tramite1 );
      tramitesSeleccionados.push( tramite2 );
      tramitesSeleccionados.push( tramite3 );

		JSONGeneraReferenciaBuilder.setToken("DD0FDED2FE302392164520BF7090E1B3BEB7")
									.setReferencia("")
									.setUrlRetorno("url")
									.setImporteTransaccion(4687)
									.setIdTransaccion("BMU8605134I82915082020")
									.setEntidad("3")
									.setUrlConfirmaPago("url")
									.setEsReferencia("1")
									.setTramites(tramitesSeleccionados);

    	
    });

    function openModalAdd(){

    	nuevoTramiteModal = new  TramiteClass();
    	nuevoTramiteModal.setIdTramite( generarUUIDTramite() );

		$("#fieldsetSolicitantes").hide();
    	$("#tramitesSelect").val("limpia").trigger('change');
    	$("#camposDinamicosDiv").empty();
    	$("#addTramite").modal({show: true}); 

    }

	function getTramites(){
		let url = "{{ url()->route('allTramites') }}";
		getApi( url,false , ((response) => {  
        	tramites = JSON.parse(response);
        	setTramites( $("#tramitesSelect") );
        }), (( msg ) => {
         	Command: toastr.warning("No Success", "Notifications") ;
        }) ,  () => $("#spin-animate").hide())
	}

	function getApi( url, data , fnDone, fnFail, fnAlways ){
		let obkTokn =  {_token:'{{ csrf_token() }}'};
		if( data ){
			obkTokn = Object.assign(obkTokn, data)
		}
        $.ajax({method: "get", url,data:obkTokn }).done(fnDone).fail(fnFail).always(fnAlways);
	}

	function setTramites(element){
    	tramites.forEach(tramite => addOptionToSelect( element, tramite.tramite, tramite.id_tramite ));
	}

	function addOptionToSelect( element, name, key ){
		element.append( new Option(name , key) );
	}


	$("#tramitesSelect").on(  "change" , () =>{
		$("#loadin").fadeIn();
		$("#tramitesSelect").attr("disabled", true);
		$("#camposDinamicosDiv").empty();
		getCampos();
	});

	function getCampos(){
		$("#btnAdd").attr("disabled", true);
		let url = "{{ url()->route('getCampos') }}";
		let data =  { id_tramite:$("#tramitesSelect").val()  } 
		getApi( url, data , ((response) => {  
        	buildForm( response );
        }), (( msg ) => {
         	Command: toastr.warning("No Success", "Notifications") ;
        }) ,  () => { 
        	$("#loadin").hide();
        	$("#tramitesSelect").attr("disabled", false);
        });
	}

	function buildForm( campos ){
		let arrToDOm = FormularioBuilder.build( campos );
		$("#camposDinamicosDiv").hide().empty().append( arrToDOm ).fadeIn(100);  
		$("#btnAdd").attr("disabled", false);   

		$("#fieldsetSolicitantes").fadeIn( "slow", () =>{
			construirTablaSolicitantes();
		});
    	                                         
	}

	$("#btnAdd").on("click", () => {
		validarForm( FormularioBuilder.getElements(), $("#tramitesSelect").val() );
	});

	function validarForm( campos, id_tramite ){

		let isValid = true;		
		let tramite = FormularioBuilder.isValid( campos );

		for (var campo in tramite) { 
			if( !tramite[campo].isValid) {
				isValid = false;
				Command: toastr.warning(campo.split("_").join(" ") + " es requerido", "Notifications") ;
				break;
			} 
		}

		if( isValid ) {
			let nuevoTramite = { id_tramite };
			for (var campo in tramite) { 
				nuevoTramite[campo] = tramite[campo].valor;
			}
			
			let elTtramite = tramites.find( tramite => tramite.id_tramite == nuevoTramite.id_tramite ) ;
			nuevoTramite = Object.assign( nuevoTramite,  elTtramite);
			tramitesGuardar.push( nuevoTramite );
			Command: toastr.success("Se agrego el trámite a su lista", "Notifications") ;
			$("#camposDinamicosDiv").fadeOut(60).empty();
			$("#tramitesSelect").val("limpia").trigger('change');

			$("#addTramite").modal("hide"); 
			buildTablaDetalles();
		}
	}

	function buildTablaDetalles(){
		ShoppingCarBuilder.build( tramitesGuardar );
		$("#totalTramites, #subTotalTramites").text( "$" +  tramitesGuardar.length *  17.99)
	}

	function deleteTramite( tramite ){
		$("#modalDelete").modal({show: true}); 
		$( "#modalDelete" ).data( "tramite", tramite );
	}

	function eliminar(){
		let tramiteDel = $( "#modalDelete" ).data( "tramite");
		tramitesGuardar =  tramitesGuardar.filter( tramite => tramite.id_tramite != tramiteDel.id_tramite );
		$("#modalDelete").modal('hide');
		buildTablaDetalles();
	}


	function metodoPago(){
		$("#metodoPagoBtn").attr("disabled", true);
		$("#addTramiteBTN").slideUp();

		$("#metodoPagoBtn").append('<div id="spinner-pago" class="spinner-border spinner-border-sm float-right" role="status"><span class="sr-only">Loading...</span></div>');
		
		let url = "https://payments-api-stage.herokuapp.com/v1/pay";
		$.ajax({
		  	type: "POST",
		  	url,
		  	data: JSON.stringify(JSONGeneraReferenciaBuilder.build()),
		  	dataType:"json",
		   	headers: {
		        "Authorization":"Bearer B6C8XvbNouJj!ds@.NXjfeswtzehVN",
		        "Content-type":"application/json"
		    }
		}).done((response) => {
			
			let folio = response.response.folio;

			let cuentas = response.response.cuentas;
			if(cuentas.length > 0 ){
				templateMetodoPagoBulder.build(cuentas, folio);

				$(".btn-metodopago").on("click", (e) => {
					console.log(e.currentTarget.id)
					$("#" + e.currentTarget.id)
				});

				$("#metodoPagoBtn").attr("disabled", false).slideUp( "slow", () => {
					$("#listTramites").slideUp("slow", ()=> {
						$("#divMetodoPago").slideDown( "slow");
						$("#metodoPagoCancBtn").slideDown("slow");
					});
					
				});
			}
			
		}).fail((rror)=> {
			console.log( rror)
		}).always(() => {
			$("#spinner-pago").remove();
		});
	}

	function cancelarPago(){
		$("#divMetodoPago").slideUp("slow", () => {
			$("#metodoPagoBtn").slideDown( "slow");
			$("#addTramiteBTN").slideDown( "slow");	
			$("#listTramites").slideDown("slow");	

			$("#metodoPagoCancBtn").hide("slow");	
		});	

	}

	function openModalAddSolicitante(){
		$('#pfRadio').click();
		$("#nombreSolicitante, #apMatSolicitante, #apPatSolicitante, #rfcSolicitante, #idSolicitante").val("");

		$("#modalAddSolicitante").modal("show");

		$('#pfRadio').change( () => { 
			if( $('#pfRadio').is(":checked") ){
				$("#divPF").show();
				$("#divPM").hide()
			}
		});

		$('#pmRadio').change( () => { 
			if( $('#pmRadio').is(":checked") ){
				$("#divPM").show();
				$("#divPF").hide()
			}
		});

	}

	function agregarSolicitante(){

		let idSolicitante = $("#idSolicitante").val();

		let solicitante = { tipoPersona: $('input[name=tipoPersona]:checked').val()  };
		solicitante.id = !!$("#idSolicitante").val() ? $("#idSolicitante").val() : generarUUIDTramite();

		if( solicitante.tipoPersona == "pf" ){ 
			solicitante.nombreSolicitante = $("#nombreSolicitante").val();
			solicitante.apPatSolicitante = $("#apPatSolicitante").val();
			solicitante.apMatSolicitante = $("#apMatSolicitante").val();
		} else if ( solicitante.tipoPersona == "pm" ){
			solicitante.rfc = $("#rfcSolicitante").val();
		}

		if( !idSolicitante ){
			nuevoTramiteModal.setSolicitanteToList( solicitante );
		} else {
			nuevoTramiteModal.editSolicitanteToList( solicitante, idSolicitante );
		}
		

		$("#modalAddSolicitante").modal("hide");

		construirTablaSolicitantes();
	}


	function generarUUIDTramite(){
		return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
		    var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
		    return v.toString(16);
		  });
	}

	function construirTablaSolicitantes(){
		$("#tbodySolicitantes").empty();

		let listaSolicitantes = nuevoTramiteModal.getSolicitanteToList();
		if( listaSolicitantes.length > 0 ) {
			listaSolicitantes.forEach( (solicitante, index) => {
				let tr = $("<tr>");

				let tdN = $("<td>").append(index + 1);

				let txtTipoPersona = solicitante.tipoPersona == "pf" ? "Persona Física" : "Persona Moral";
				let tdTipo = $("<td>").append( txtTipoPersona );
				let txtNombreORazonSocial = solicitante.tipoPersona == "pf" ? solicitante.nombreSolicitante + " " + solicitante.apPatSolicitante + " " + solicitante.apMatSolicitante : solicitante.rfc;
				let tdNombreORazonSocial = $("<td>").append( txtNombreORazonSocial ); 
				let tdAccions = $("<td>").append( '<a type="button" onclick=editarSolicitante('  +  "'"+ solicitante.id + "'"   +   ')><i class="fas fa-edit"></i></a><a type="button" onclick=quitarSolicitante('  +  "'"+ solicitante.id + "'"   +   ')><i class="far fa-trash-alt"></i></a>');

				tr.append( tdN );
				tr.append( tdTipo );
				tr.append( tdNombreORazonSocial  );
				tr.append( tdAccions );

				$("#tbodySolicitantes").append( tr );

			});


		} else  {
			let tr = $("<tr>");
			let tdN = $("<td colspan='4' class='text-center'>").append("No se han agregado solicitantes");
			tr.append( tdN );
			$("#tbodySolicitantes").append( tr );
		}
	}


	function quitarSolicitante( id  ){
		nuevoTramiteModal.quitarSolicitante(id);
		construirTablaSolicitantes();
	}


	function editarSolicitante( id ){
	  	let solicitante = nuevoTramiteModal.obtenerSolicitante(id);

	  	$("#idSolicitante").val( solicitante.id );
	  	if( solicitante.tipoPersona == "pf"){
	  		$('#pfRadio').click();
	  		$("#nombreSolicitante").val( solicitante.nombreSolicitante );
	  		$("#apMatSolicitante").val( solicitante.apMatSolicitante);
	  		$("#apPatSolicitante").val( solicitante.apPatSolicitante );
	  		$("#rfcSolicitante").val("");
	  	}

	  	if( solicitante.tipoPersona == "pm"){
	  		$('#pmRadio').click();
	  		$("#nombreSolicitante").val( "" );
	  		$("#apMatSolicitante").val( "");
	  		$("#apPatSolicitante").val( "");
	  		$("#rfcSolicitante").val( solicitante.rfc );
	  	}

	  	

		$("#modalAddSolicitante").modal("show");

		$('#pfRadio').change( () => { 
			if( $('#pfRadio').is(":checked") ){
				$("#divPF").show();
				$("#divPM").hide()
			}
		});

		$('#pmRadio').change( () => { 
			if( $('#pmRadio').is(":checked") ){
				$("#divPM").show();
				$("#divPF").hide()
			}
		});

	}




</script>

							        		

							       


