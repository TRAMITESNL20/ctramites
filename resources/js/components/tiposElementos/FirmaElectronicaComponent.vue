<template>
        <div>
			<div class="card" v-if="tramiteFirmado == false">

				<div class="col-lg-12 col-sm-12">
					<div class="container">
						<div class="card-body">
							<div class="row" >
								<iframe  id="the_frame" :src="firma" style="width:100%; height: 430px;" frameborder="0"> </iframe>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div v-if="loader" class="pt-5 pb-5">
				<div  class="pt-4 pb-4 text-center order-tramites">
					<div class="spinner-border" role="status">
						<span class="sr-only">Loading...</span>
					</div>

				</div>
				<div class="text-center">
					<span class="gray" >Cargando Informativos de valor catastral, {{countInformativo}} de {{TotalInformativos}}</span>
				</div>
			</div>
		</div>
		
</template>

<script>
	const md5 = require('md5');
	export default {
		props: ['datosComplementaria', 'tipoTramite','usuario', 'pago', 'id', 'user', 'tramitesdoc'],
		data(){
			return{
				tramite : {},
				tramiteFirmado : false,
				tramiteInfo: '',
				firma: '',
				access_token: '',
				resultId: '',
				perfil: '',
				multiple: '',
				doc: '',
				rfc: '',
				folio:'',
				llave:'',
				idFirmado: [],
				urlFirmado: [],
				guardado: false,
				coutnLoad : 0,
				responseCatastroDocument: '',
				docFirmadosListos: [],
				docFirmadosPendientes: [],
				urlConcatenadas: '',
				loader: null,
				TotalInformativos: 0,
				countInformativo: 0,

			}
		},
		async mounted() {
			window.addEventListener("message", this.messageEvt, false);
			if( this.usuario.tramite_id == process.env.TRAMITE_AVISO ){
				this.usuario.solicitudes.map((solicitud, ind) => {
				this.multiple = this.usuario.solicitudes.length > 1;
				var auxEnv = process.env.AAPP_URL;
				if ( auxEnv == "https://tramites.nl.gob.mx") auxEnv = "http://tramites.nl.gob.mx";
					var userEncoded =btoa(this.user.role.description + ' - ' +  this.user.name + ' ' +  this.user.fathers_surname + ' RFC: ' +  this.user.rfc ) ;

					if(this.multiple){
						if(typeof this.doc === 'string'){
							if(this.usuario.tramite_id == process.env.TRAMITE_5_ISR){ 
								let doc = `${auxEnv}/formato-declaracion/${solicitud.id}?data=${userEncoded}`;
								this.doc = [];
								this.doc.push(doc)
							}else if(this.usuario.tramite_id == process.env.TRAMITE_AVISO ){
								this.doc = [];
								//se tiene que mandar el ws con el  paquete completo para los tramites  y ademas  y despues se busca el id que te regrese a buscar
								this.getDocumentCatastro(solicitud, this.usuario);
								//aqui sustituir por la repsuesta del servicio
								// this.doc.push(this.responseCatastroDocument);
								this.doc.push('http://www.africau.edu/images/default/sample.pdf');
							}	
							
						} 
	
						if(typeof this.llave === 'string') this.llave = [];
						this.llave.push(`${solicitud.id}`)
	
						if(typeof this.folio === 'string') this.folio = [];
						this.folio.push( md5( (Date.now() % 1000) / 1000  ) + `${ind}`);
	
					}else{
						if(this.usuario.tramite_id == process.env.TRAMITE_5_ISR){
							let doc = `${auxEnv}/formato-declaracion/${solicitud.id}?data=${userEncoded}`;
							this.doc = doc;
						}else if(this.usuario.tramite_id == process.env.TRAMITE_AVISO  ){
							this.getDocumentCatastro(solicitud);
							//aqui sustituir por la repsuesta del servicio
							// this.doc = this.responseCatastroDocument;
							this.doc = 'http://www.africau.edu/images/default/sample.pdf';
						}
						this.llave = `${solicitud.id}`;
						this.folio = md5( (Date.now() % 1000) / 1000  ) + `${ind}`;
					}

				this.idFirmado.push(solicitud.id);
				this.urlFirmado.push( `${process.env.INSUMOS_DOCS_HOSTNAME}/firmas//${this.usuario.tramite_id + "_" +  this.usuario.solicitudes[0].id}/${solicitud.id}_${this.usuario.tramite_id}_${this.usuario.solicitudes[0].id}_firmado.pdf` );
				});
			}else if(this.usuario.tramite_id == 8 /*process.env.TRAMITE_INFORMATIVO*/){
				this.loader = true;
				this.tramiteFirmado = true;
				var self = this;
				this.getTotalInformativos();
				for (let i = 0; i < this.usuario.solicitudes.length; i++) {
					this.idFirmado.push(this.usuario.solicitudes[i].id);
					for (let indSolicitud = 0; indSolicitud < this.usuario.solicitudes[i].info.campos['Resultados Informativo Valor Catastral'].length; indSolicitud++) {
						await this.getDocumentCatastro(this.usuario.solicitudes[i].info.campos['Resultados Informativo Valor Catastral'][indSolicitud], indSolicitud, i);
					}
					this.urlFirmado.push(this.urlConcatenadas);			
				}
				this.loader = false;
				console.log( JSON.stringify({ ids : self.idFirmado, status : 1, type : 'firmado', urls : self.urlFirmado, user_id: user.id })  );
				console.log('aqui se hace la firma? esta todo en orden?');
				// fetch(`${process.env.TESORERIA_HOSTNAME}/solicitudes-guardar-carrito`, {
				// 			method : 'POST',
				// 			body: JSON.stringify({ ids : self.idFirmado, status : 1, type : 'firmado', urls : self.urlFirmado, user_id: user.id })
				// 		})
				// 		.then(res => res.json())
				// 		.then(res => {
				// 			if(res.code === 200){
				// 				console.log('Informativo Guardado');    

				// 				self.$emit('docFirmadosListos', self.docFirmadosListos);
				// 				self.$emit('docFirmado', 1, this.usuario.tramite_id );
				// 				self.$emit('urlFirmado', self.urlFirmado);
				// 			}
				// 			else console.log('Algo salio mal al guardar en el sistema!',  res);
				// 		});
				

			}	
			this.rfc = this.user.rfc;
			this.accesToken();
			this.encodeData();
		},
		methods: {
			encodeData(ind){
				this.perfil = this.usuario.solicitudes[0].perfil;
				var urlDataGeneric =  process.env.INSUMOS_API_HOSTNAME + '/data_generic';
				var url =  process.env.INSUMOS_API_HOSTNAME + "/v2/signature/iframe?id=";
				var data = {
					'perfil' : this.perfil ,
					'multiple' : this.multiple,
					'tramite' :  this.usuario.tramite_id + "_" +  this.usuario.solicitudes[0].id,
					'llave' : this.llave,
					'doc' : this.doc,
					'folio' : this.folio,
					'rfc' : window.rfc || this.rfc,
					// 'pagado' : 1,
					'descargable': false,

				};
				const headers = { 
					"Authorization": "Bearer my-token",
					'Content-Type': 'application/json',
				};

				$.ajax({
					type: "POST",
					data: {
						tramite_id : this.usuario.tramite_id,
						value: JSON.stringify(data),
						access_token : this.access_token
					},
					dataType: 'json', 
					url: urlDataGeneric,
					async: false,
					success:function(data){
						self.resultId = data.data.id;
					},
					error:function(error){
						console.log('error: ', error);
					},
					complete:function(){
						// console.log('id a consultar en insumos: ', self.resultId);
					}
				});

				var encodedId = btoa(self.resultId); 
				var urlFinal = url+encodedId;
				this.firma = urlFinal;
			},
			accesToken(){
				var self = this;
				let url =  process.env.INSUMOS_API_HOSTNAME + "/auth" ;  
				var data = { 'username' : process.env.INSUMOS_USERNAME , 'password':  process.env.INSUMOS_PASSWORD };

				$.ajax({
					type: "POST",
					data: data,
					dataType: 'json', 
					url,
					async: false,
					success:function(data){
						self.access_token = data.token;
					},
					error:function(error){
						console.log(error);
					},
					complete:function(){
						// console.log('accestoken generado', self.access_token);
					}
				});
			},
			messageEvt (evt) {
				var self = this;
				console.log('messageEvt', evt.data);
				if( evt.data.length >= 1  ){

					if( evt.data[0].includes(this.usuario.solicitudes[0].id)  ){

						console.log("el id es: " + this.usuario.solicitudes[0].id );
					
						fetch(`${process.env.TESORERIA_HOSTNAME}/solicitudes-guardar-carrito`, {
							method : 'POST',
							body: JSON.stringify({ ids : self.idFirmado, status : 1, type : 'firmado', urls : self.urlFirmado, user_id: user.id })
						})
						.then(res => res.json())
						.then(res => {
							if(res.code === 200){
								console.log('Firmado');    
								self.tramiteFirmado = true;
								self.$emit('docFirmadosListos', self.docFirmadosListos);
								self.$emit('docFirmado', 1, this.usuario.tramite_id );
								self.$emit('urlFirmado', self.urlFirmado);
							}
							else console.log('Something goes wrong!',  res);
						});
					}

					
				}
			},
			async getDocumentCatastro(solicitud , tramiteInd, indTramite ){
				var adquirientes = [];
				var vendedores =[];
				var tipoTramite= '';
				this.usuario.tramite_id == process.env.TRAMITE_AVISO ? tipoTramite = "9" : tipoTramite = "15"
				//se debe de recorrer el vendedor 
				if(tipoTramite == "9"){
					for (let i = 0; i < solicitud.info.campos['Datos de Propietarios'].seccionVendedores.vendedores.length; i++) {
						vendedores.push({
										"curprfc": solicitud.info.campos['Datos de Propietarios'].seccionVendedores.vendedores[i].datosPersonales.curp ?? '',
										"clasepro": solicitud.info.campos['Datos de Propietarios'].seccionVendedores.vendedores[i].tipoPersona == 'pf' ? 1 : 2 ,
										"tipopro":1,
										"nombrerazon": solicitud.info.campos['Datos de Propietarios'].seccionVendedores.vendedores[i].datosPersonales.razonSocial ?? '',
										"apepat":solicitud.info.campos['Datos de Propietarios'].seccionVendedores.vendedores[i].datosPersonales.apPat ?? '',
										"apemat":solicitud.info.campos['Datos de Propietarios'].seccionVendedores.vendedores[i].datosPersonales.apMat ?? '',
										"fecha_nac":solicitud.info.campos['Datos de Propietarios'].seccionVendedores.vendedores[i].datosPersonales.fechaNacimiento ?? '',
										"telefono": '',
										"celular": '',
										"correo": '',
										"nuda":20,
										"usufructo":10,
										"direccion":{
											"asentamiento": solicitud.info.campos['Datos de Propietarios'].seccionVendedores.vendedores[i].datosDireccion ?  solicitud.info.campos['Datos de Propietarios'].seccionVendedores.vendedores[i].datosDireccion.colonia ?? '' : '',
											"cp": solicitud.info.campos['Datos de Propietarios'].seccionVendedores.vendedores[i].datosDireccion ? solicitud.info.campos['Datos de Propietarios'].seccionVendedores.vendedores[i].datosDireccion.cp ?? '' : '',
											"pktipovialidad": solicitud.info.campos['Datos de Propietarios'].seccionVendedores.vendedores[i].datosDireccion ?  solicitud.info.campos['Datos de Propietarios'].seccionVendedores.vendedores[i].datosDireccion.tipoVialidad ?? '' : '',
											"calle": solicitud.info.campos['Datos de Propietarios'].seccionVendedores.vendedores[i].datosDireccion ?  solicitud.info.campos['Datos de Propietarios'].seccionVendedores.vendedores[i].datosDireccion.nombreVialidad ?? '' : '',
											"cruza1":"",
											"cruza2":"",
											"numext": solicitud.info.campos['Datos de Propietarios'].seccionVendedores.vendedores[i].datosDireccion ?  solicitud.info.campos['Datos de Propietarios'].seccionVendedores.vendedores[i].datosDireccion.nExt ?? '' : '',
											"numint": solicitud.info.campos['Datos de Propietarios'].seccionVendedores.vendedores[i].datosDireccion ?  solicitud.info.campos['Datos de Propietarios'].seccionVendedores.vendedores[i].datosDireccion.nInt ?? '' : '',
											"numextant":"",
											"referencia": solicitud.info.campos['Datos de Propietarios'].seccionVendedores.vendedores[i].datosDireccion ? solicitud.info.campos['Datos de Propietarios'].seccionVendedores.vendedores[i].datosDireccion.referencia ?? '' : '',
											"municipio":solicitud.info.campos['Datos de Propietarios'].seccionVendedores.vendedores[i].datosDireccion ? solicitud.info.campos['Datos de Propietarios'].seccionVendedores.vendedores[i].datosDireccion.municipio ?? '' : '',
										}
									});
					}

					//se debe de recorrer el comprador / adquiriente
					for (let k = 0; k < solicitud.info.campos['Datos de Propietarios'].seccionCompradores.compradores.length; k++) {
						adquirientes.push({
										"curprfc": solicitud.info.campos['Datos de Propietarios'].seccionCompradores.compradores[k].datosPersonales.curp ?? '',
										"clasepro": solicitud.info.campos['Datos de Propietarios'].seccionCompradores.compradores[k].tipoPersona == 'pf' ? 1 : 2 ,
										"tipopro":1,
										"nombrerazon": solicitud.info.campos['Datos de Propietarios'].seccionCompradores.compradores[k].datosPersonales.razonSocial ?? '',
										"apepat":solicitud.info.campos['Datos de Propietarios'].seccionCompradores.compradores[k].datosPersonales.apPat ?? '',
										"apemat":solicitud.info.campos['Datos de Propietarios'].seccionCompradores.compradores[k].datosPersonales.apMat ?? '',
										"fecha_nac":solicitud.info.campos['Datos de Propietarios'].seccionCompradores.compradores[k].datosPersonales.fechaNacimiento ?? '',
										"telefono": solicitud.info.campos['Datos de Propietarios'].seccionCompradores.compradores[k].datosDireccion.telefonoCasa ?? '',
										"celular": solicitud.info.campos['Datos de Propietarios'].seccionCompradores.compradores[k].datosDireccion.telefonoFijo ?? '',
										"correo": solicitud.info.campos['Datos de Propietarios'].seccionCompradores.compradores[k].datosDireccion.correo ?? '',
										"nuda":20,
										"usufructo":10,
										"direccion":{
											"asentamiento": solicitud.info.campos['Datos de Propietarios'].seccionCompradores.compradores[k].datosDireccion.colonia ?? '',
											"cp": solicitud.info.campos['Datos de Propietarios'].seccionCompradores.compradores[k].datosDireccion.cp ?? '',
											"pktipovialidad": solicitud.info.campos['Datos de Propietarios'].seccionCompradores.compradores[k].datosDireccion.tipoVialidad ?? '',
											"calle": solicitud.info.campos['Datos de Propietarios'].seccionCompradores.compradores[k].datosDireccion.nombreVialidad ?? '',
											"cruza1":"",
											"cruza2":"",
											"numext":solicitud.info.campos['Datos de Propietarios'].seccionCompradores.compradores[k].datosDireccion.nExt ?? '',
											"numint": solicitud.info.campos['Datos de Propietarios'].seccionCompradores.compradores[k].datosDireccion.nInt ?? '',
											"numextant":"",
											"referencia":solicitud.info.campos['Datos de Propietarios'].seccionCompradores.compradores[k].datosDireccion.referencia ?? '',
											"municipio": solicitud.info.campos['Datos de Propietarios'].seccionCompradores.compradores[k].datosDireccion.municipio ?? '',
										}
									});
					}
				}
				var dataCatastro = [
					{
						"json":{
							"expedientecatastral": this.usuario.tramite_id == process.env.TRAMITE_AVISO ? solicitud.info.campos['No. EXP. CATASTRAL'] : ''  ? solicitud.info.campos['Resultados Informativo Valor Catastral'][tramiteInd].expediente_catastral : solicitud.expediente_catastral ,
							"pktramite": tipoTramite ,
							"pknotaria": this.user.notary.id ?? '',
							"estadonotaria": "19",
							"foliopago":123456,
							"fechapago": this.usuario.tramite_id == process.env.TRAMITE_AVISO ? new Date().toISOString().slice(0, 10) : new Date().toISOString().slice(0, 10) ,
							"montopago": this.usuario.tramite_id == process.env.TRAMITE_AVISO ? solicitud.info.costo_final : '1231' ? this.usuario.solicitudes[indTramite].info.costo_final : '1231',
							"tipoventa": 'terreno ?',
							"isai": this.usuario.tramite_id == process.env.TRAMITE_AVISO ? solicitud.info.campos.ISAI : 'SIATEST' ? this.usuario.solicitudes[indTramite].info.campos.ISAI : 'ISAIINFORMATIVO',
							"fechaprot": this.usuario.tramite_id == process.env.TRAMITE_AVISO ?  solicitud.info.campos['Fecha de protocolización'] : '',
							"fechafirma": new Date().toISOString().slice(0, 10),
							"escriturapub": this.usuario.tramite_id == process.env.TRAMITE_AVISO ? solicitud.info.campos['Número de Escritura Pública'] : '12345' ?  this.usuario.solicitudes[indTramite].info.campos['Número de Escritura Pública'] : '54321' ,
							"actafprot":  this.usuario.tramite_id == process.env.TRAMITE_AVISO ?  solicitud.info.campos['Acta Fuera Protocolo'] : '' ? this.usuario.solicitudes[indTramite].info.campos['Acta Fuera Protocolo'] : '' ,
							"avaluo":12345,
							"operacion": this.usuario.tramite_id == process.env.TRAMITE_AVISO ? 0 : 12345,
							"motivooperacion":"motivo",
							"fiscal":12345678,
							"folioforma":"12345",
							"descripcion_predio": this.usuario.tramite_id == process.env.TRAMITE_AVISO ? solicitud.info.campos['Anexo descripción'] : 'descripcionTest' ? this.usuario.solicitudes[indTramite].info.campos['Anexo descripcion'] : '',
							"adquirientes": adquirientes,
							"vendedores": vendedores,
						}
					}
				];
				var url =  process.env.TESORERIA_HOSTNAME + "/registro-catastro";

				await axios.post(url,  JSON.stringify(dataCatastro[0]) )
				// .then(res =>  JSON.parse(res))
				.then(res => {
					 var responseJson = JSON.parse(res.data.response.replace('\ufeff', ''));
					 	this.docFirmadosListos = [];
						console.log('url de catastro: ',responseJson.URL);
						this.responseCatastroDocument = responseJson.URL;
						responseJson.URL ?  this.urlConcatenadas += responseJson.URL + ',' : this.urlConcatenadas += "http://www.africau.edu/images/default/sample.pdf,";
						solicitud['tramite'] = this.usuario.tramite;
						solicitud['tramite_id'] = this.usuario.tramite_id;
						solicitud['required_docs'] = 1;
						solicitud['urlDocumentoFirmado'] = 'http://www.africau.edu/images/default/sample.pdf'
						this.docFirmadosListos.push(solicitud);
						tipoTramite == 15 ? this.$emit('docFirmadosListos', this.docFirmadosListos ) : '';
						tipoTramite == 15 ? this.countInformativo++ : '';
						// tipoTramite == 15 && this.usuario.solicitudes[indTramite].info.campos['Resultados Informativo Valor Catastral'].length == (tramiteInd + 1)  ? this.$emit('docFirmadosListos', this.docFirmadosListos ):  '' ;
						// this.$emit('docFirmado', 1);
						// this.$emit('urlFirmado', self.urlFirmado);
				})
				.catch( error => console.log(error));
			},
			getTotalInformativos(){
				for (let i = 0; i < this.usuario.solicitudes.length ; i++) {
					for (let k = 0; k < this.usuario.solicitudes[i].info.campos['Resultados Informativo Valor Catastral'].length ; k++) {
						this.TotalInformativos ++;
					}
				}
			}
		},
		  watch:{
            // usuario : (newVal) => console.log('newVal', newVal)
				usuario: {
					handler(newVal, oldVal) {
						if(this.usuario.tramite_id == process.env.TRAMITE_5_ISR ){
						
							this.doc = [];
							this.idFirmado = [];
							this.folio = [];
							this.llave = [];
							this.urlFirmado = [];
							this.docFirmadosListos= [];
							this.docFirmadosPendientes= [];
							let APP_URL = 'http://10.153.144.218/tramites-ciudadano';
							this.usuario.solicitudes.map((solicitud, ind) => {
								this.multiple = this.usuario.solicitudes.length > 1;
								var auxEnv = process.env.APP_URL;
								if ( auxEnv == "https://tramites.nl.gob.mx") {
									auxEnv = "http://tramites.nl.gob.mx";
								}
								let doc = `${APP_URL}/formato-declaracion/${solicitud.id}`;
								if(this.multiple){
									if(typeof this.doc === 'string') this.doc = [];
									this.doc.push(doc)
									
									if(typeof this.llave === 'string') this.llave = [];
									this.llave.push(`${solicitud.id}`)
									
									if(typeof this.folio === 'string') this.folio = [];
									this.folio.push( md5( (Date.now() % 1000) / 1000  ) + `${ind}`);
								
									if(solicitud.required_docs == 1){
										solicitud['urlDocumentoFirmado'] = `${process.env.INSUMOS_DOCS_HOSTNAME}/firmas/${this.usuario.tramite_id + "_" +  this.usuario.solicitudes[0].id}/${solicitud.id}_${this.usuario.tramite_id}_${this.usuario.solicitudes[0].id}_firmado.pdf`;
										solicitud['tramite_id'] = this.usuario.tramite_id;
										this.docFirmadosListos.push(solicitud);
									}else{
										solicitud['urlDocumentoFirmado'] =  `${process.env.INSUMOS_DOCS_HOSTNAME}/firmas/${this.usuario.tramite_id + "_" +  this.usuario.solicitudes[0].id}/${solicitud.id}_${this.usuario.tramite_id}_${this.usuario.solicitudes[0].id}_firmado.pdf`;
										solicitud['tramite_id'] = this.usuario.tramite_id;
										this.docFirmadosListos.push(solicitud);
									}

								}else{
									this.doc = doc;
									this.llave = `${solicitud.id}`;
									this.folio = md5( (Date.now() % 1000) / 1000  ) + `${ind}`;
									if(solicitud.required_docs == 1){
										solicitud['urlDocumentoFirmado'] = `${process.env.INSUMOS_DOCS_HOSTNAME}/firmas/${this.usuario.tramite_id + "_" +  this.usuario.solicitudes[0].id}/${solicitud.id}_${this.usuario.tramite_id}_${this.usuario.solicitudes[0].id}_firmado.pdf` ;
										solicitud['tramite_id'] = this.usuario.tramite_id;
										this.docFirmadosListos.push(solicitud);
									}else{
										solicitud['urlDocumentoFirmado'] = `${process.env.INSUMOS_DOCS_HOSTNAME}/firmas/${this.usuario.tramite_id + "_" +  this.usuario.solicitudes[0].id}/${solicitud.id}_${this.usuario.tramite_id}_${this.usuario.solicitudes[0].id}_firmado.pdf` ;
										solicitud['tramite_id'] = this.usuario.tramite_id;
										this.docFirmadosListos.push(solicitud);
									}
								}

							this.rfc= this.user.rfc; 
							this.idFirmado.push(solicitud.id);
							// console.log(this.idFirmado);
							this.urlFirmado.push( `${process.env.INSUMOS_DOCS_HOSTNAME}/firmas/${this.usuario.tramite_id + "_" +  this.usuario.solicitudes[0].id}/${solicitud.id}_${this.usuario.tramite_id}_${this.usuario.solicitudes[0].id}_firmado.pdf` );
							})
						this.accesToken();
						this.encodeData();
						}
						},
						immediate: true, 
					
				}
    	}
	}

</script>