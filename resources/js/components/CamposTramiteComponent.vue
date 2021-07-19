<template>
	<div id="contenedorCampos" class="container-fluid">
		<div class="text-center" id="loadin" style=" margin-bottom: 9px;" v-if="!mostrar" >
			<div class="spinner-grow" role="status">
			  	<span class="sr-only">Loading...</span>
			</div>
		</div>
		<div class="" v-if="mostrar">
			<form id="formularioDinamico">
				<div class="panel panel-default" >
 					<div class="panel-heading">
 						<div class="row">	
							<v-expansion-panels accordion multiple hover style="z-index: inherit" v-model="panel">
							    <v-expansion-panel v-for="(agrupacion, i) in agrupaciones" :key="i" close :disabled=" disabled.includes(i) ">
							      	<v-expansion-panel-header >
							        	{{ agrupacion.nombre_agrupacion }}
							        	 <template v-slot:actions >
								        	<i class="fa fa-angle-down" />
								    	</template>
							      	</v-expansion-panel-header>
							      	<v-expansion-panel-content>
										<div class="row align-items-center">
											<div v-if="agrupacion.tipo === 'agrupacion'" class="col-lg-12">
												<div class="col-md-12 col-lg-12">
												    <div >
													    <div class="custom-control custom-radio custom-control-inline">
													      	<input type="radio" value="pf"  name="radioInline" class="custom-control-input" id="defaultInline2" v-model="tipoPersona" key="tipoPersona">
													      	<!--<input  name="tipoPersona"  >-->
													      	<label class="custom-control-label" for="defaultInline2">
													      		Persona fisica	
													      	</label>
													    </div>

													    <!-- Default inline 3-->
													    <div class="custom-control custom-radio custom-control-inline">
													      	<input type="radio" value="pm" name="radioInline" class="custom-control-input" id="defaultInline3" v-model="tipoPersona" key="tipoPersona">

													      	<label class="custom-control-label" for="defaultInline3">
													      		Persona Moral	
													      	</label>
													    </div>

													    <div :set= 'agrupacion.campos = agrupacion.grupos[tipoPersona].campos'> </div>
													</div>
												</div>
											</div>
			 								<div v-for="(campo, j) in agrupacion.campos" :key="j" class="col-md-6 col-sm-6 col-xs-6"
			 								:class="campo.nombre == '¿Cuenta con avalúo?' || ['file', 'results', 'question','enajenante','expedientes', 'valuador', 'table'].includes(campo.tipo) ? 'col-md-12 col-sm-12 col-xs-12' : 'col-md-6 col-sm-6 col-xs-6'">
			 									<input-currency-component 
			 										v-if="campo.tipo === 'input' && JSON.parse(campo.caracteristicas).formato === 'moneda'" 
													:campo="campo" 
													:showMensajes="showMensajes" 
													:estadoFormulario="comprobarEstadoFormularioCount"
													@updateForm="updateForm" :divisa="divisa">
			 										
			 									</input-currency-component>
												<input-component
													v-else-if="campo.tipo === 'input'" 
													:campo="campo" 
													:showMensajes="showMensajes" 
													:estadoFormulario="comprobarEstadoFormularioCount"
													@updateForm="updateForm">
												</input-component>
												<divisa-component v-else-if="campo.tipo === 'select' && campo.nombre == 'Cambio de divisas'" 
													:campo="campo" 
													:showMensajes="showMensajes" 
													:estadoFormulario="comprobarEstadoFormularioCount"
													@updateForm="updateForm"></divisa-component>	
												<select-component
													v-else-if="campo.tipo === 'select' || campo.tipo === 'multiple'" 
													:campo="campo" 
													:showMensajes="showMensajes" 
													:estadoFormulario="comprobarEstadoFormularioCount"
													@updateForm="updateForm"
													v-on:estadoSelected="estadoSelected($event)"
													:estado="estado"
													:distrito="distrito"
													>
												</select-component>
												<option-component 
													v-else-if="campo.tipo === 'option'"
													:campo="campo" 
													:showMensajes="showMensajes" 
													:estadoFormulario="comprobarEstadoFormularioCount"
													@updateForm="updateForm">
												</option-component>
												<textbox-component
													v-else-if="campo.tipo === 'textbox' && (!campo.condition || campo.condition.view(agrupaciones))"
													:campo="campo" 
													:showMensajes="showMensajes" 
													:estadoFormulario="comprobarEstadoFormularioCount"
													@updateForm="updateForm">
												</textbox-component>

												<checkbox-component 
													v-else-if="campo.tipo === 'checkbox'"
													:campo="campo" 
													:showMensajes="showMensajes" 
													:estadoFormulario="comprobarEstadoFormularioCount"
													@updateForm="updateForm">
												</checkbox-component>
												<file-component
													v-else-if="campo.tipo == 'file' && JSON.parse(campo.caracteristicas).tipo != 'expediente_validacion_excel'"
													:campo="campo" 
													:showMensajes="showMensajes" 
													:estadoFormulario="comprobarEstadoFormularioCount"
													@updateForm="updateForm" :files="files"
													@validarFormulario="validarFormulario"
												></file-component>
												<results-component 
													v-else-if="campo.tipo === 'results'"
													:campo="campo" 
													:showMensajes="showMensajes" 
													:estadoFormulario="comprobarEstadoFormularioCount"
													@updateForm="updateForm"
													:info="response"
													:fields="fields"
													:rows="rows"
													:loading="loading"
													:infoExtra="infoExtra"
													v-on:expedienteSeleccionado="updateExpedienteSeleccionado($event)"
													:response="response"
													>
												</results-component>
												<expediente-excel-component  
													v-else-if="JSON.parse(campo.caracteristicas).tipo == 'expediente_validacion_excel'"
													:campo="campo" 
													:showMensajes="showMensajes" 
													:estadoFormulario="comprobarEstadoFormularioCount"
													@updateForm="updateForm" :files="files"
													@validarFormulario="validarFormulario"
													@processGrupal="processGrupal"
													:disabled="disabled"
													>
												</expediente-excel-component>
												<enajenantes-component v-else-if="campo.tipo == 'enajenante'" 
												:campo="campo" 
													:showMensajes="showMensajes" 
													:estadoFormulario="comprobarEstadoFormularioCount"
													@updateForm="updateForm" :configCostos="configCostos">
														
													</enajenantes-component>
												<aviso-enajenacion-component
													:campo="campo"
													:expediente="expediente"
													@updateForm="updateForm"
													v-else-if="campo.tipo == 'table'">
												</aviso-enajenacion-component>
												<fecha-component v-if="campo.tipo === 'date'" 
													:campo="campo" 
													:showMensajes="showMensajes" 
													:estadoFormulario="comprobarEstadoFormularioCount"
													@updateForm="updateForm"></fecha-component>

												<listado-expedientes-5-i-s-r 
													v-else-if="campo.tipo === 'expedientes'"
													:campo="campo" 
													:showMensajes="showMensajes" 
													:estadoFormulario="comprobarEstadoFormularioCount"
													@updateForm="updateForm" :usuario="usuario"></listado-expedientes-5-i-s-r>
												<div v-else-if="campo.tipo == 'question'">
													¿Consigna valor?
													<div class="col-md-12 col-lg-12">
													    <div >
														    <div class="custom-control custom-radio custom-control-inline">
														      	<input type="radio" value="millar"  name="radioInline" class="custom-control-input" id="millar1" v-model="tipo_costo_obj.tipoCostoRadio" key="millar" @change="cambioModelo">
														      	<label class="custom-control-label" for="millar1">
														      		Si<!-- (millar)-->	
														      	</label>
														    </div>

														    <!-- Default inline 3-->
														    <div class="custom-control custom-radio custom-control-inline" v-if="tipo_costo_obj.val_tipo_costo=== 'H'">
														      	<input type="radio" value="hoja" name="radioInline" class="custom-control-input" id="hoja1" v-model="tipo_costo_obj.tipoCostoRadio" key="millar" @change="cambioModelo">

														      	<label class="custom-control-label" for="hoja1">
														      		No <!-- (hoja)-->	
														      	</label>
														    </div>
														    <div class="custom-control custom-radio custom-control-inline" v-if="tipo_costo_obj.val_tipo_costo === 'L'">
														      	<input type="radio" value="lote" name="radioInline" class="custom-control-input" id="lote1" v-model="tipo_costo_obj.tipoCostoRadio" key="lote" @change="cambioModelo">

														      	<label class="custom-control-label" for="lote1">
														      		No <!--(lote)-->	
														      	</label>
														    </div>
														    <div class=" fv-plugins-icon-container" v-if="tipo_costo_obj.tipoCostoRadio=== 'hoja'"  >
															    <label>
															        Hoja
															    </label>
															    <span class="currencyinput">
															      <input type="text" class="form-control  form-control-lg " style="background-color: #e5f2f5 !important" placeholder="Hoja" id="hojaInput" v-model="tipo_costo_obj.hojaInput"  @change="cambioModelo"/>
															    </span>
															</div>
														    <div class=" fv-plugins-icon-container" v-if="tipo_costo_obj.tipoCostoRadio=== 'lote'">
															    <label>
															        Lote
															    </label>
															    <span class="currencyinput">
															      <input type="text" class="form-control  form-control-lg " style="background-color: #e5f2f5 !important" placeholder="Lote" id="lojaInput" v-model="tipo_costo_obj.hojaInput"  @change="cambioModelo"/>
															    </span>
															</div>
														</div>
													</div>
												</div>
			 									<valuador-component v-if="campo.tipo === 'valuador'" 
			 										:campo="campo" 
													:showMensajes="showMensajes" 
													:estadoFormulario="comprobarEstadoFormularioCount"
													@updateForm="updateForm">
			 									</valuador-component>
			 								</div>
		 									<div v-if="agrupacion.tieneSeccionDocumentos" class="col-md-12 col-lg-12">
		 										<div class="text-left">
													<strong>Nota:</strong>
													<small class="">
														De no anexar el documento obligatorio, no podrá Imprimir su declaración fiscal, mismo que podra ser requerido por la Autoridad.
													</small>
												</div>
											</div>
										</div>
							      	</v-expansion-panel-content>
							    </v-expansion-panel>
							</v-expansion-panels>
 						</div>
 					</div>
 				</div>
			</form>
		</div>
    </div>
</template>
<script>
	import Vue from 'vue';
	import divisaCtrl from '../services/DivisasCtrl.js';
    export default {
        computed:{
            configCostos(){
                return {
                	campos:this.campos, 
                	tramite:this.tramite, 
                	tipoPersona:this.tipoPersona,
                	declararEn0:this.declararEn0
                };
            },
        },
        props: ['tramite','formularioValido', 'comprobarEstadoFormularioCount', 'infoGuardada', 'declararEn0', 'notary', 'usuario'],
        data() {
            return {
				progress: '',
				expediente: '',
				selectedId: [],
				campos: [], 
				agrupaciones:[], 
				estado: {clave:19, nombre: "NUEVO LEÓN"},
				distrito: {clave:0, nombre: "Distrito 0"},
                mostrar:false,
                errors: {},
                showMensajes:false,
                files:[], file:null,
                tipoPersona:'pf',
				consulta_api:'',
				panel : [0,1,2,3,4],
				//motivoDeclaracion0:'',
				disabled : [],
				ajax : null,
				response : [],
				fields : [],
				rows :[],
				loading : false,
				infoExtra : {},
				tipo_costo_obj: { tipo_costo:0 ,tipoCostoRadio:'millar',hojaInput:'', val_tipo_costo:'' },
				tieneSeccionDocumentos: false,
				divisa:this.$store.state.DEFAULT_DIVISA
			}
        },
        created() {
			if (localStorage.getItem('datosFormulario')) {
              	try {
                	let datosFormulario = JSON.parse(localStorage.getItem('datosFormulario'));
                	if(datosFormulario.tramite.tramite === 'INFORMATIVO VALOR CATASTRAL'){
                		this.panel = [0];
                		this.disabled = [1,2,3,4,5];
                	}

                	if( datosFormulario.tramite.id_tramite  == this.tramite.id_tramite){
		                this.campos = datosFormulario.campos;
		                this.consulta_api = datosFormulario.consulta_api;
		                this.tipo_costo_obj = datosFormulario.tipo_costo_obj;
						this.agruparCampos();
						this.showMensajes = true;
						this.mostrar = true;
						if (datosFormulario.tipoPersona) {
							this.tipoPersona = datosFormulario.tipoPersona;
						}
                	} else {
                		localStorage.removeItem('datosFormulario');
                		this.obtenerCampos();
                	}
                	
              	} catch(e) {
                	localStorage.removeItem('datosFormulario');
                	this.obtenerCampos();
              	}
	        } else {
				this.obtenerCampos();
			}
        },
        methods: {
			updatePorcentaje(porcentaje){	
				this.progress = porcentaje;
			},
			updateExpedienteSeleccionado(ex){
				this.expediente = ex;
			},
			estadoSelected(estado){
				this.estado = estado;
			},

        	gestionarCambioEstado(estado){
        		this.estado = estado;
        	},

			distritoSelected(distrito){
				this.distrito = distrito;
			},

        	gestionarCambioDistrito(distrito){
        		this.distrito = distrito;
        	},

        	async updateForm(campo){
				const tramite = localStorage.getItem('tramite') && JSON.parse(localStorage.getItem('tramite')) ;
				if (tramite && tramite.id_tramite === process.env.TRAMITE_AVISO) {
				
					if(campo.tipo == 'results' && campo.valido){
						this.expediente = campo.valor
        				this.updateExpedienteSeleccionado(this.expediente);
        			}

					
				}

				const datosFormulario = localStorage.getItem('datosFormulario') && JSON.parse(localStorage.getItem('datosFormulario')) ;
				if(campo.nombre.search(/region/i) >= 0){
					const value = campo.valor && campo.valor.toString();
					if(value && value.length == 3 && value[0] === '0')
						campo.valor = value.slice(1)
				}
				if(tramite && tramite.tramite === 'INFORMATIVO VALOR CATASTRAL'){
					const datosFormulario = localStorage.getItem('datosFormulario') && JSON.parse(localStorage.getItem('datosFormulario')) ;
					if(campo.nombre.search(/region/i) >= 0){
						const value = campo.valor && campo.valor.toString();
						if(value && value.length == 3 && value[0] === '0')
							campo.valor = value.slice(1)
					}

					if(campo.nombre.search(/tipo de busqueda/i) >= 0){
						this.fields = [ 'Expediente Catastral', 'Municipio', 'Tipo de predio', 'Tipo de Construcción', 'Propietarios' ];
						if(campo.valor){
							switch(campo.valor.toString()){
								case 'individual':
									this.panel = [0, 1];
									this.disabled = [2,3];
								break;
								case 'rango':
									this.panel = [0, 2];
									this.disabled = [1,3];
								break;
								case 'grupal':
									this.panel = [0, 3];
									this.disabled = [1,2];
								break;
								default:
									this.panel = [0];
								break;
							}
						}
					}

					switch(campo.nombre_agrupacion){
						case 'Individual':
							await this.processIndividual({campo, tramite, datosFormulario})
						break;
						case 'Rango':
							await this.processRango({campo, tramite, datosFormulario})
						break;
						case 'Grupal':
							this.panel = [0, 3];
						break;
					}

					this.processCampo(campo);
				}

        		if(campo.tipo == 'file' && campo.valido){
        			let nuevoFile = {valor:campo.valor, nombre:campo.nombre, id:campo.campo_id, nombrreFile:campo.valor ? campo.valor.name : ''};
        			let indexArchivoEnLista = this.files.findIndex( file => file.id == campo.campo_id );
        			if(indexArchivoEnLista>=0){
        				this.files[indexArchivoEnLista] = nuevoFile;
        			} else {
        				this.files.push( nuevoFile);
        			}
		    		this.$emit('updatingFiles', this.files);
        		}

        		if(campo.nombre == 'Estado' && campo.valido){
        			this.gestionarCambioEstado(campo.valor);
        		}

        		if(campo.nombre == 'Distrito' && campo.valido){
        			this.gestionarCambioDistrito(campo.valor);
        		}

        		this.listenCampos( campo );				

        		this.cambioModelo();
        	},
		    cambioModelo(){
				let formvALID = this.validarFormulario();
            	let datosFormulario = {
            		tramite: this.tramite,
            		campos: this.campos,
            		tipoPersona:this.tipoPersona,//quitar?
            		consulta_api: this.consulta_api,
            		formularioValido:formvALID,
            		tipo_costo_obj:this.tipo_costo_obj
            	}
            	localStorage.setItem('datosFormulario', JSON.stringify(datosFormulario)); 
        	},

        	validarFormulario( ){
        		let formularioValido = true;

        		let camposValidables = [];
        		this.agrupaciones.forEach( agrupacion =>{
					camposValidables = camposValidables.concat( agrupacion.campos );
        			return agrupacion;
        		});
                camposValidables.forEach( (campo, indice) => {
					formularioValido = formularioValido && !!campo.valido;
                });

                if(!!this.tipo_costo_obj && (this.tipo_costo_obj.tipoCostoRadio == 'hoja' || this.tipo_costo_obj.tipoCostoRadio == 'lote' )){
                	let hojaInputValid = !!this.tipo_costo_obj.hojaInput;
                	formularioValido = formularioValido && hojaInputValid;
				}

                this.$emit('updatingScore', formularioValido);
                return formularioValido;
		    },

		    async obtenerCampos(){
		    	let url = process.env.APP_URL + "/getCampos";
				let promises = [];
		    	let linksArchivos = [];
		    	try {
				  	let response = await axios.get(url,  { params: { id_tramite: this.tramite.id_tramite } });
				  	this.consulta_api = response.data && response.data.length > 0 ? response.data[0].consulta_api : '';
					this.campos = response.data && response.data.length > 0 ? response.data[0].campos_data : [];
					this.tipo_costo_obj.tipo_costo = response.data && response.data.length > 0 ? response.data[0].tipo_costo : '';
					this.tipo_costo_obj.val_tipo_costo = response.data && response.data.length > 0 ? response.data[0].val_tipo_costo : '';


					if( this.infoGuardada && this.infoGuardada.campos ){
						this.tipoPersona = this.infoGuardada.tipoPersona;
						this.tipo_costo_obj = this.infoGuardada.tipo_costo_obj;

						this.campos = this.infoGuardada.camposConfigurados;

						this.campos.forEach( (campo, index) =>{	
							//campo.valor = this.infoGuardada.campos[ campo.campo_id ];
							if( campo.tipo == 'file' && this.infoGuardada.archivosGuardados){
								let infoArchivoGuardado = this.infoGuardada.archivosGuardados.find( archivo => archivo.mensaje == campo.nombre );
								campo.archivoGuardado = true;
								if(infoArchivoGuardado && infoArchivoGuardado.attach){
									campo.nombreArchivoGuardado = infoArchivoGuardado.attach;
								}
							}
							if (campo.tipo == 'results') {
								this.campos[index].valido = true;
							}

						});
					}
				} catch (error) {
				  	console.log(error);
				}

				this.agruparCampos();
				
				this.mostrar = true;
		    },

		    agruparCampos(){
		    		let agrupaciones = this.campos.map( (campo, index) => {  
		    			return {
			    			agrupacion_id:campo.agrupacion_id, 
			    			nombre_agrupacion: campo.nombre_agrupacion, 
			    			campos:[],
			    			orden_agrupacion: campo.orden_agrupacion 
		    			} 
		    		}).filter( this.onlyUnique );
				  	agrupaciones = agrupaciones.map( agrupacion => {
				  		agrupacion.campos = this.campos.filter(  campo => campo.agrupacion_id == agrupacion.agrupacion_id );
				  		agrupacion.campos.sort(function(a,b) { return parseFloat(a.orden) - parseFloat(b.orden) } );
				  		return agrupacion;
				  	});

				  	let agrupacionDatosCostos = agrupaciones.find( agrupacion => agrupacion.nombre_agrupacion == "Costos"); 
				  	
				  	if( agrupacionDatosCostos  && this.tipo_costo_obj.tipo_costo == '1'){
				  		let campo = {
				  			idElemento:'tipoCostoElement',
							agrupacion_id: agrupacionDatosCostos.agrupacion_id,
							caracteristicas: '{"required":"true"}',
							nombre: "",
							nombre_agrupacion: agrupacionDatosCostos.nombre_agrupacion,
							orden: agrupacionDatosCostos.campos[ agrupacionDatosCostos.campos.length - 1 ].orden + 1,
							tipo: "question",
							valido:true			  			
				  		}
				  		agrupacionDatosCostos.campos.push( campo );
				  	}

				  	let agrupacionDocumentacon = agrupaciones.find( agrupacion => agrupacion.nombre_agrupacion == "Documentación");
				  	if(agrupacionDocumentacon){
				  		agrupacionDocumentacon.tieneSeccionDocumentos =  !!agrupacionDocumentacon;
				  	}


				  	this.datosPersonales = agrupaciones.find( agrupacion => agrupacion.nombre_agrupacion == 'Datos Personales' );
					this.razonSocial = agrupaciones.find( agrupacion => agrupacion.nombre_agrupacion == 'Razón Social' );

				  	if(  this.datosPersonales && this.razonSocial  ){
				  		agrupaciones = agrupaciones.filter( agrupacion => agrupacion.nombre_agrupacion != 'Datos Personales' && agrupacion.nombre_agrupacion != 'Razón Social'  );
				  		agrupaciones.unshift( {nombre_agrupacion:'Tipo Persona',  tipo: 'agrupacion', grupos: { 'pf': this.datosPersonales, 'pm': this.razonSocial } } );
				  	}

				  	this.agrupaciones = agrupaciones.sort(function(a,b) { return parseFloat(a.orden_agrupacion) - parseFloat(b.orden_agrupacion) } );
				  	
				  	
				  	let segg= this;
					setTimeout(function(){ 
						segg.cambioModelo();
					}, 1000);
		    },

		    onlyUnique(value, index, self) { 
			    return self.findIndex (dato => dato.agrupacion_id == value.agrupacion_id) === index;
			},
			async processIndividual({campo, tramite, datosFormulario}){
				let empty = [];
				let all = {}
				datosFormulario.campos.map(ele => {
					if(['Municipio', 'Region', 'Manzana', 'Lote'].includes(ele.nombre)){
						if(ele.nombre == campo.nombre)
							ele.valor = campo.valor
						all[ele.nombre] = ele
					}
				})

				empty = Object.entries(all).map(ele => {
					const valor = ele[1].valor ? typeof ele[1].valor === 'string' ? ele[1].valor : ele[1].valor.toString() : null;
					return valor == '' || !ele[1].valido ? ele[1] : null;
				}).filter(ele => ele)

				if(empty.length == 0){
					this.panel = [0, 1, 4];
					const exp = `${all['Municipio'].valor && all['Municipio'].valor.clave.toString()}${all['Region'] && all['Region'].valor}${all['Manzana'] && all['Manzana'].valor}${all['Lote'] && all['Lote'].valor}`;
					const url = `${process.env.TESORERIA_HOSTNAME}/insumos-catastro-consulta/${exp}`;
					if(this.ajax !== url){
						this.ajax = url;
						this.loading = true;
						const response = await axios.get(url);
						const rows = [];
						this.response = [response.data];
						if(response.data.resultado) rows.push([exp, response.data.resultado])
						else{
							const propietarios = response.data.datos_propietarios.length > 1 ? { label : response.data.datos_propietarios[0].nombrePro, tooltip : { title : 'Propietarios', listItems : response.data.datos_propietarios.map(e => e.nombrePro) } } : response.data.datos_propietarios[0].nombrePro;
							rows.push([
								response.data.datos_catastrales[0].expediente_catastral,
								response.data.nombre_municipio,
								response.data.tipo_predio,
								response.data.uso_suelo,
								propietarios
							])
						}


						const noValido = this.response.filter(ele => ele.cta_valida === '0');
						const bloqueados = this.response.filter(ele => ele.bloqueado && ele.bloqueado !== '0');
						const fallidos = this.response.filter(ele => ele.resultado === 'NO ENCONTRADO');
						const autorizados = this.response.filter(ele => ele.datos_propietarios);

						const infoExtra = [
							{
								label : 'Registros Consultados',
								value : rows.length
							},
							{
								label : 'No Validos',
								value : noValido ? noValido.length : 0
							},
							{
								label : 'Bloqueados',
								value : bloqueados ? bloqueados.length : 0
							},
							{
								label : 'Fallidos',
								value : fallidos ? fallidos.length : 0
							},
							{
								label : 'Duplicados',
								value : 0
							},
							{
								label : 'Autorizados',
								value : autorizados ? autorizados.length : 0
							}
						];

						this.infoExtra = {
							title : 'Resultados de la búsqueda',
							listItems : infoExtra
						};

						this.rows = rows;
						this.loading = false;
					}
				}else{
					this.panel = [0, 1];
				}
			},
			async processRango({ campo, tramite, datosFormulario }){
				this.panel = [0, 2];
				let final = null;
				let inicial = null;
				let unico = null;

				let empty = [];
				let all = {}

				switch(campo.nombre){
					case 'Manzana Unica':
					case 'Manzana Inicial':
						final = this.campos.map((ele, ind) => ele.nombre === 'Manzana Final' ? ind : null).filter(ele => ele).toString()
						inicial = this.campos.map((ele, ind) => ele.nombre === 'Manzana Inicial' ? ind : null).filter(ele => ele).toString()
						unico = this.campos.map((ele, ind) => ele.nombre === 'Manzana Unica' ? ele.valor : null).filter(ele => ele).toString()
						if(unico === 'true'){
							this.campos[final].valor = this.campos[inicial].valor || 0;
						}else{
							this.campos[final].valor = '';
						}
						this.cambioModelo();
					break;

					case 'Lote Unico':
					case 'Lote Inicial':
						final = this.campos.map((ele, ind) => ele.nombre === 'Lote Final' ? ind : null).filter(ele => ele).toString()
						inicial = this.campos.map((ele, ind) => ele.nombre === 'Lote Inicial' ? ind : null).filter(ele => ele).toString()
						unico = this.campos.map((ele, ind) => ele.nombre === 'Lote Unico' ? ele.valor : null).filter(ele => ele).toString()
						if(unico === 'true'){
							this.campos[final].valor = this.campos[inicial].valor || 0;
						}else{
							this.campos[final].valor = '';
						}
						this.cambioModelo();
					break;
				}

				datosFormulario.campos.map(ele => {
					if(['*Municipios', '*Región', 'Manzana Inicial', 'Manzana Final', 'Lote Inicial', 'Lote Final'].includes(ele.nombre)){
						if(ele.nombre == campo.nombre)
							ele.valor = campo.valor
						all[ele.nombre] = ele
					}
				})

				empty = Object.entries(all).map(ele => {
					const valor = ele[1].valor ? typeof ele[1].valor === 'string' ? ele[1].valor : ele[1].valor.toString() : null;
					return valor == '' || valor == null || !ele[1].valido ? ele[1] : null;
				}).filter(ele => ele)

				if(empty.length == 0){
					this.panel = [0, 2, 4];
					const exp = [];

				}else{
					this.panel = [0, 2];
				}
			},
			async processGrupal({response, exp}){
				let rows = [];
				if(!response.data.expediente_catastral) response.data.expediente_catastral = exp;
				this.response.push(response.data);
				if(response.data.resultado) rows = [exp, response.data.resultado]
				else if(response.data.datos_catastrales){
					const propietarios = response.data.datos_propietarios.length > 1 ? { label : response.data.datos_propietarios[0].nombrePro, tooltip : { title : 'Propietarios', listItems : response.data.datos_propietarios.map(e => e.nombrePro) } } : response.data.datos_propietarios[0].nombrePro;
					rows = [
						response.data.datos_catastrales[0].expediente_catastral,
						response.data.nombre_municipio,
						response.data.tipo_predio,
						response.data.uso_suelo,
						propietarios
					]
				}else{
					rows = [exp, 'Error al consultar WS. Por favor, intenta de nuevo.']
				}

				const noValido = this.response.filter(ele => ele.bloqueado && ele.bloqueado !== '0');
				const bloqueados = this.response.filter(ele => ele.bloqueado && ele.bloqueado !== '0');
				const fallidos = this.response.filter(ele => ele.resultado && ele.resultado === 'NO ENCONTRADO');
				const autorizados = this.response.filter(ele => ele.bloqueado && ele.bloqueado === '0');

				const infoExtra = [
					{
						label : 'Registros Consultados',
						value : this.response.length
					},
					// {
					// 	label : 'No Validos',
					// 	value : noValido ? noValido.length : 0
					// },
					{
						label : 'Bloqueados',
						value : bloqueados ? bloqueados.length : 0
					},
					{
						label : 'Fallidos',
						value : fallidos ? fallidos.length : 0
					},
					{
						label : 'Autorizados',
						value : autorizados ? autorizados.length : 0
					}
				];

				this.infoExtra = {
					title : 'Resultados de la búsqueda',
					listItems : infoExtra
				};

				this.rows.push(rows);
				this.loading = false;
				this.panel = [0, 3, 4];
				this.rows = this.rows.sort((a,b) => a[0]-b[0]);
			},

			listenCampos(campo){
				if(campo.nombre == this.$const.NOMBRES_CAMPOS.CAMPO_DIVISAS && campo.valido){
					let divisaValue = divisaCtrl.getSymbol(campo.valor); 
	        		this.$store.commit('change', divisaValue);
	        		this.divisa = this.$store.state.DEFAULT_DIVISA
	        	}   else if( campo.nombre == 'Número de Escritura Pública' ) {
	        		this.$root.$emit('notify', { 
	        			of:'Número de Escritura Pública', 
	        			for:'Acta Fuera Protocolo', 
	        			campoOf:campo,
	        			validation: (self, campoOf, caracteristicas) => {
	        				self.campo.valido = campoOf.valido && !!campoOf.valor && campoOf.valor.length > 0;  
	        				caracteristicas.required = !!campoOf.valor && campoOf.valor.length == 0;
	        				self.campo.disabled = !!campoOf.valor && campoOf.valor.length > 0; 
                            self.campo.caracteristicas = JSON.stringify(caracteristicas);
	        				return self;  
	        			},
	        		});
	        	}	else if( campo.nombre == 'Acta Fuera Protocolo') {
	        		this.$root.$emit('notify', { 
	        			for:'Número de Escritura Pública', 
	        			of:'Acta Fuera Protocolo', 
	        			campoOf:campo,
	        			validation: (self, campoOf, caracteristicas) => {
	        				self.campo.valido = campoOf.valido && !!campoOf.valor && campoOf.valor.length > 0;  
	        				caracteristicas.required = !!campoOf.valor && campoOf.valor.length == 0;
	        				self.campo.disabled = !!campoOf.valor && campoOf.valor.length > 0; 
                            self.campo.caracteristicas = JSON.stringify(caracteristicas);
	        				return self;  
	        			},
	        		});
	        	} else if( campo.nombre == "Fecha de protocolización"){
	        		
	        		this.$root.$emit('notifyDate', { 
	        			for:'Fecha de escritura', 
	        			of:'Fecha de protocolización', 
	        			campoOf:campo,
	        			validation: (self, campoOf, caracteristicas) => {
	        				let fechaProtocolizacion = moment(campoOf.valor);
	        				let fechaEscritura = moment(self.campo.valor)
	        				let fechaProtocolizacionMayor = fechaProtocolizacion > fechaEscritura;
	        			    if(fechaProtocolizacionMayor){
	        				    let mensaje = { 
				                	tipo:'validation',
				                	mensajeStr: "La fecha de protocolo no puede ser mayor a la fecha de la escritura."
				              	}
				              	campoOf.valido = false;	
				              	campoOf.mensajes.push( mensaje );


				            } else {
				            	self.campo.valido = campoOf.valido;
				            	self.campo.mensajes = self.campo.mensajes.filter( mensaje => mensaje.tipo != 'validation')
				            }
	        				return self;  
	        			},
	        		});
	        	} else if( campo.nombre == "Fecha de escritura"){
	        		
	        		this.$root.$emit('notifyDate', { 
	        			of:'Fecha de escritura', 
	        			for:'Fecha de protocolización', 
	        			campoOf:campo,
	        			validation: (self, campoOf, caracteristicas) => {
	        				let fechaProtocolizacion = moment(self.campo.valor);
	        				let fechaEscritura = moment(campoOf.valor);
	        				let fechaProtocolizacionMayor = fechaProtocolizacion > fechaEscritura;
	        			    if(fechaProtocolizacionMayor){
	        				    let mensaje = { 
				                	tipo:'validation',
				                	mensajeStr: "La fecha de protocolo no puede ser mayor a la fecha de la escritura."
				              	}
				              	campoOf.valido = false;	
				              	campoOf.mensajes.push( mensaje );
				            } else {
				            	self.campo.valido = campoOf.valido;
				            	self.campo.mensajes = self.campo.mensajes.filter( mensaje => mensaje.tipo != 'validation')
				            }
	        				return self;  
	        			},
	        		});
	        	}
			},

			processCampo (campo) {
				const disabled = this.agrupaciones.map((agrupacion, ind) => this.disabled.includes(ind) ? agrupacion.agrupacion_id : null).filter(ele => ele);
				const actived = this.agrupaciones.map((agrupacion, ind) => this.panel.includes(ind) ? agrupacion.agrupacion_id : null).filter(ele => ele);

				this.campos.map(campo => {
					if(disabled.find(ele => ele === campo.agrupacion_id)) campo.valido = true;
					if(actived.find(ele => ele === campo.agrupacion_id)){
						let object = false;
						if(typeof campo.caracteristicas == 'object') object = true;
						if(typeof campo.caracteristicas == 'string') campo.caracteristicas = JSON.parse(campo.caracteristicas);
						campo.caracteristicas.required = "true";
						if(!object) campo.caracteristicas = JSON.stringify(campo.caracteristicas);
					}
				})
			}
		},
		mounted(){
		},

		watch: {
			tipo_costo_obj:  {
		        handler: function (val, oldVal) {
		        	if(val){
		         		this.$root.$emit('tipo_costo_obj_change', { activo: val.tipoCostoRadio != 'millar' });
		         	}
		        },
		        deep: true				
			},

		},
	}


</script>
