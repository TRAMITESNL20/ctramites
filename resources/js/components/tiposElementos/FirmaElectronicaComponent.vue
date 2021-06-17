<template>
    <div>
        <iframe id="the_frame" :src="firma" style="width:100%; height:600px;" frameborder="0"> </iframe>
    </div>
</template>

<script>
	const md5 = require('md5');
	export default {
		props: ['datosComplementaria', 'tipoTramite','usuario', 'pago', 'id', 'user', 'tramitesdoc'],
		data(){
			return{
				tramite : {},
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

			}
		},
		mounted() {
			console.log('=============');
			console.log(this.usuario.tramite_id);
			window.addEventListener("message", this.messageEvt, false);
			console.log(this.usuario);
			if(this.usuario.tramite_id == process.env.TRAMITE_5_ISR || this.usuario.tramite_id == process.env.TRAMITE_AVISO ){
				this.usuario.solicitudes.map((solicitud, ind) => {
				// console.log(solicitud);
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
								this.doc.push(this.responseCatastroDocument);
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
							this.doc = this.responseCatastroDocument;
						}
						this.llave = `${solicitud.id}`;
						this.folio = md5( (Date.now() % 1000) / 1000  ) + `${ind}`;
					}

				this.idFirmado.push(solicitud.id);
				this.urlFirmado.push( `${process.env.INSUMOS_DOCS_HOSTNAME}/firmas//${this.usuario.tramite_id + "_" +  this.usuario.solicitudes[0].id}/${solicitud.id}_${this.usuario.tramite_id}_${this.usuario.solicitudes[0].id}_firmado.pdf` );
				});
			}else if(this.usuario.tramite_id == 8 /*process.env.TRAMITE_INFORMATIVO*/){
				this.usuario.solicitudes.map((solicitud, ind) => {
					this.getDocumentCatastro(solicitud)
				});
			}	
			console.log('====-----====');
			console.log(this.usuario.tramite_id);
			console.log(process.env);
			console.log(this.usuario.tramite_id == process.env.TRAMITE_INFORMATIVO);
			this.rfc = this.user.rfc;
			this.accesToken();
			this.encodeData();
		},
		methods: {
			encodeData(ind){
				for (let i = 0; i <  this.tramitesdoc.length ; i++) {
					if(this.usuario.tramite_id == this.tramitesdoc[i].tramite_id){
						this.perfil = this.tramitesdoc[i].perfil;
					}
				}
				// console.log(this.perfil);
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
					'pagado' : 1,
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

					if( env.data[0].includes(this.usuario.solicitudes[0].id)  ){

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
								self.$emit('docFirmado', 1);
								self.$emit('urlFirmado', self.urlFirmado);
							}
							else console.log('Something goes wrong!',  res);
						});
					}

					
				}
			},
			getDocumentCatastro(solicitud , tramiteId){
				console.log( JSON.stringify(solicitud) );
				var adquirientes = [];
				var vendedores =[];
				//se debe de recorrer el vendedor 
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
									"curprfc": solicitud.info.campos['Datos de Propietarios'].seccionCompradores.compradores[i].datosPersonales.curp ?? '',
									"clasepro": solicitud.info.campos['Datos de Propietarios'].seccionCompradores.compradores[i].tipoPersona == 'pf' ? 1 : 2 ,
									"tipopro":1,
									"nombrerazon": solicitud.info.campos['Datos de Propietarios'].seccionCompradores.compradores[i].datosPersonales.razonSocial ?? '',
									"apepat":solicitud.info.campos['Datos de Propietarios'].seccionCompradores.compradores[i].datosPersonales.apPat ?? '',
									"apemat":solicitud.info.campos['Datos de Propietarios'].seccionCompradores.compradores[i].datosPersonales.apMat ?? '',
									"fecha_nac":solicitud.info.campos['Datos de Propietarios'].seccionCompradores.compradores[i].datosPersonales.fechaNacimiento ?? '',
									"telefono": solicitud.info.campos['Datos de Propietarios'].seccionCompradores.compradores[i].datosDireccion.telefonoCasa ?? '',
									"celular": solicitud.info.campos['Datos de Propietarios'].seccionCompradores.compradores[i].datosDireccion.telefonoFijo ?? '',
									"correo": solicitud.info.campos['Datos de Propietarios'].seccionCompradores.compradores[i].datosDireccion.correo ?? '',
									"nuda":20,
									"usufructo":10,
									"direccion":{
										"asentamiento": solicitud.info.campos['Datos de Propietarios'].seccionCompradores.compradores[i].datosDireccion.colonia ?? '',
										"cp": solicitud.info.campos['Datos de Propietarios'].seccionCompradores.compradores[i].datosDireccion.cp ?? '',
										"pktipovialidad": solicitud.info.campos['Datos de Propietarios'].seccionCompradores.compradores[i].datosDireccion.tipoVialidad ?? '',
										"calle": solicitud.info.campos['Datos de Propietarios'].seccionCompradores.compradores[i].datosDireccion.nombreVialidad ?? '',
										"cruza1":"",
										"cruza2":"",
										"numext":solicitud.info.campos['Datos de Propietarios'].seccionCompradores.compradores[i].datosDireccion.nExt ?? '',
										"numint": solicitud.info.campos['Datos de Propietarios'].seccionCompradores.compradores[i].datosDireccion.nInt ?? '',
										"numextant":"",
										"referencia":solicitud.info.campos['Datos de Propietarios'].seccionCompradores.compradores[i].datosDireccion.referencia ?? '',
										"municipio": solicitud.info.campos['Datos de Propietarios'].seccionCompradores.compradores[i].datosDireccion.municipio ?? '',
									}
								});
				}

				var dataCatastro = [
					{
						"json":{
							"expedientecatastral": solicitud.info.campos['No. EXP. CATASTRAL'] ?? '',
							"pktramite": this.usuario.tramite_id == process.env.TRAMITE_AVISO ? '9' : '15' ,
							"pknotaria": this.user.notary.notary_number ?? '',
							"estadonotaria": "19",
							"foliopago":123456,
							"fechapago": this.usuario.tramite_id == process.env.TRAMITE_AVISO ? new Date().toISOString().slice(0, 10) : '' ,
							"montopago": this.usuario.tramite_id == process.env.TRAMITE_AVISO ? '' : solicitud.info.costo_final ?? '' ,
							"tipoventa": solicitud.info.campos['Tipo de Operación'].nombre ?? '',
							"isai": solicitud.info.campos.ISAI ?? '',
							"fechaprot": this.usuario.tramite_id == process.env.TRAMITE_AVISO ?  solicitud.info.campos['Fecha de protocolización'] : '',
							"fechafirma": new Date().toISOString().slice(0, 10),
							"escriturapub": solicitud.info.campos['Número de Escritura Pública'],
							"actafprot": solicitud.info.campos['Acta Fuera Protocolo'],
							"avaluo":12345,
							"operacion": this.usuario.tramite_id == process.env.TRAMITE_AVISO ? 0 : 12345,
							"motivooperacion":"motivo",
							"fiscal":12345678,
							"folioforma":"12345",
							"descripcion_predio": solicitud.info.campos['Anexo descripción'] ?? '',
							"adquirientes": adquirientes,
							"vendedores": vendedores,
						}
					}
				];

				var url =  process.env.TESORERIA_HOSTNAME + "/registro-catastro";
				console.log(dataCatastro);
				fetch(url, { 'method': 'POST', 'body' : JSON.stringify(dataCatastro[0]) } )
				 .then(response => console.log(response.URL))
				 .catch( error => console.log(error));

			},
		},
	}
</script>