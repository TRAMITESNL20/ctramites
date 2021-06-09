<template>
    <div>
        <!-- <iframe id="the_frame" v-on:load="validateSigned()" :src="firma" style="width:100%; height:600px;" frameborder="0"> </iframe> -->
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
				coutnLoad : 0

			}
		},
		mounted() {
			window.addEventListener("message", this.messageEvt, false);
			this.usuario.solicitudes.map((solicitud, ind) => {
				this.multiple = this.usuario.solicitudes.length > 1;
				var auxEnv = process.env.AAPP_URL;
				if ( auxEnv == "https://tramites.nl.gob.mx") auxEnv = "http://tramites.nl.gob.mx";
				var userEncoded =btoa(this.user.role.description + ' - ' +  this.user.name + ' ' +  this.user.fathers_surname + ' RFC: ' +  this.user.rfc ) ;
				console.log( JSON.stringify(solicitud) );
					
					if(this.multiple){
						if(typeof this.doc === 'string'){
							if(this.usuario.tramite_id == process.env.TRAMITE_5_ISR){ 
								let doc = `${auxEnv}/formato-declaracion/${solicitud.id}?data=${userEncoded}`;
								this.doc = [];
								this.doc.push(doc)
							}else if(this.usuario.tramite_id == process.env.TRAMITE_AVISO){
								this.doc = [];
								//se tiene que mandar el ws con el  paquete completo para los tramites  y ademas  y despues se busca el id que te regrese a buscar
								this.getDocumentCatastro(solicitud);
								this.doc.push("http://www.africau.edu/images/default/sample.pdf");
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
						}else if(this.usuario.tramite_id == process.env.TRAMITE_AVISO){
							this.doc = "http://www.africau.edu/images/default/sample.pdf";
						}
						this.llave = `${solicitud.id}`;
						this.folio = md5( (Date.now() % 1000) / 1000  ) + `${ind}`;
					}
				

				this.idFirmado.push(solicitud.id);
				this.urlFirmado.push( `${process.env.INSUMOS_DOCS_HOSTNAME}/firmas//${this.usuario.tramite_id + "_" +  this.usuario.solicitudes[0].id}/${solicitud.id}_${this.usuario.tramite_id}_${this.usuario.solicitudes[0].id}_firmado.pdf` );
			})

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
			getDocumentCatastro(solicitud){
				dataCatastro: [
					{
						"json":{
							"expedientecatastral":"7001001001",
							"pktramite":"9",
							"pknotaria":1,
							"estadonotaria":19,
							"foliopago":123456,
							"fechapago":"2021-06-07",
							"montopago":"123",
							"tipoventa":"Terreno y construcci\u00f3n",
							"isai":12345,
							"fechaprot":"2021-06-07",
							"fechafirma":"2021-06-07",
							"escriturapub":"",
							"actafprot":"",
							"avaluo":12345,
							"operacion":12345,
							"motivooperacion":"motivo",
							"fiscal":12345678,
							"folio_forma":"12345",
							"descripcion_predio":"descripcion de estructura medidas y colindancias",
							"adquirientes":[
								{
									"curprfc":"PRUE000111ISH",
									"clasepro":2,
									"tipopro":1,
									"nombrerazon":"NOMBRE DE PRUEBA",
									"apepat":"APATERNO",
									"apemat":"AMATENRO",
									"fecha_nac":"2021-01-01",
									"telefono":1234567890,
									"celular":1234567890,
									"correo":"correo@correo.com",
									"nuda":20,
									"usufructo":10,
									"direccion":{
										"asentamiento":"colonia",
										"cp":64000,
										"pktipovialidad":1,
										"calle":"calle",
										"cruza1":"",
										"cruza2":"",
										"numext":"123",
										"numint":"A",
										"numextant":"",
										"referencia":"",
										"municipio":70
									}
								},
								{
									"curprfc":"PRUE000111ISH",
									"clasepro":2,
									"tipopro":1,
									"nombrerazon":"NOMBRE DE PRUEBA",
									"apepat":"APATERNO",
									"apemat":"AMATENRO",
									"fecha_nac":"2021-01-01",
									"telefono":1234567890,
									"celular":1234567890,
									"correo":"correo@correo.com",
									"nuda":20,
									"usufructo":10,
									"direccion":{
										"asentamiento":"colonia",
										"cp":64000,
										"pktipovialidad":1,
										"calle":"calle",
										"cruza1":"",
										"cruza2":"",
										"numext":"123",
										"numint":"A",
										"numextant":"",
										"referencia":"",
										"municipio":70
									}
								}
							],
							"vendedores":[
								{
									"curprfc":"PRUE000111ISH",
									"clasepro":2,
									"tipopro":1,
									"nombrerazon":"NOMBRE DE PRUEBA",
									"apepat":"APATERNO",
									"apemat":"AMATENRO",
									"fecha_nac":"2021-01-01",
									"telefono":1234567890,
									"celular":1234567890,
									"correo":"correo@correo.com",
									"nuda":20,
									"usufructo":10,
									"direccion":{
										"asentamiento":"colonia",
										"cp":64000,
										"pktipovialidad":1,
										"calle":"calle",
										"cruza1":"",
										"cruza2":"",
										"numext":"123",
										"numint":"A",
										"numextant":"",
										"referencia":"",
										"municipio":70
										}
									},
									{
										"curprfc":"PRUE000111ISH",
										"clasepro":2,
										"tipopro":1,
										"nombrerazon":"NOMBRE DE PRUEBA",
										"apepat":"APATERNO",
										"apemat":"AMATENRO",
										"fecha_nac":"2021-01-01",
										"telefono":1234567890,
										"celular":1234567890,
										"correo":"correo@correo.com",
										"nuda":20,
										"usufructo":10,
										"direccion":{
											"asentamiento":"colonia",
											"cp":64000,
											"pktipovialidad":1,
											"calle":"calle",
											"cruza1":"",
											"cruza2":"",
											"numext":"123",
											"numint":"A",
											"numextant":"",
											"referencia":"",
											"municipio":70
											}
										}
							]
						}
					}
				]
			},
		},
	}
</script>