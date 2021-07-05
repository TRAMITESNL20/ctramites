<template>
        
        <div class="card">
            <div class="col-lg-12 col-sm-12">
			    <div class="container">
                    <div class="card-body">
                        <div class="row" >
                            <iframe v-if="tramiteFirmado == false" id="the_frame" :src="firma" style="width:100%; height: 600px;" frameborder="0"> </iframe>
                        </div>
                        <!-- <div>
                            <iframe id="the_frame" :src="firma" style="width:100%; height:600px;" frameborder="0"> </iframe>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>

</template>

<script>
var md5 = require('md5');
export default {
    
	props: ['datosComplementaria', 'tipoTramite','usuario', 'pago', 'id', 'user', 'tramitesdoc'],
	data(){
		return{
			tramite : {},
			tramiteInfo: '',
			firma: '',
			access_token: '',
			resultId: '',
			multiple: '',
			doc: '',
			rfc: '',
			folio:'',
			llave:'',
			idFirmado: [],
			urlFirmado: [],
			guardado: false,
			coutnLoad : 0,
            docFirmadosListos: [],
            docFirmadosPendientes: [],
            tramiteFirmado : false,
		}
	},
	mounted() {
		this.rfc = this.user.rfc;
        window.addEventListener("message", this.messageEvt, false);
			// this.usuario.solicitudes.map((solicitud, ind) => {
			// 	this.multiple = this.usuario.solicitudes.length > 1;
			// 	var auxEnv = process.env.APP_URL;
			// 	if ( auxEnv == "https://tramites.nl.gob.mx") auxEnv = "http://tramites.nl.gob.mx";
			// 	var userEncoded =btoa(this.user.role.description + ' - ' +  this.user.name + ' ' +  this.user.fathers_surname + ' RFC: ' +  this.user.rfc ) ;
			// 	let doc = `${auxEnv}/formato-declaracion/${solicitud.id}?data=${userEncoded}`;
			// 	if(this.multiple){
			// 		if(typeof this.doc === 'string') this.doc = [];
			// 		this.doc.push(doc)
			// 		if(typeof this.llave === 'string') this.llave = [];
			// 		this.llave.push(`${solicitud.id}`)
			// 		if(typeof this.folio === 'string') this.folio = [];
			// 		this.folio.push( md5( (Date.now() % 1000) / 1000  ) + `${ind}`);
			// 	}else{
			// 		this.doc = doc;
			// 		this.llave = `${solicitud.id}`;
			// 		this.folio = md5( (Date.now() % 1000) / 1000  ) + `${ind}`;
			// 	}
			// 	this.idFirmado.push(solicitud.id);
			// 	this.urlFirmado.push( `${process.env.INSUMOS_DOCS_HOSTNAME}/firmas//${this.usuario.tramite_id + "_" +  this.usuario.solicitudes[0].id}/${solicitud.id}_${this.usuario.tramite_id}_${this.usuario.solicitudes[0].id}_firmado.pdf` );
			// })
			this.accesToken();
			this.encodeData();
		},
		methods: {
			encodeData(){
				var urlDataGeneric =  process.env.INSUMOS_API_HOSTNAME + '/data_generic';
				var url =  process.env.INSUMOS_API_HOSTNAME + "/v2/signature/iframe?id=";
				var data = {
					'perfil' : 'EI',
					'multiple' : this.multiple,
					'tramite' :  this.usuario.tramite_id + "_" +  this.usuario.solicitudes[0].id,
					'llave' : this.llave,
					'doc' : this.doc,
					'folio' : this.folio,
					'rfc' : window.rfc || this.rfc,
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
				console.log('menssageEvt', evt.data);
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
								self.$emit('docFirmado', 1);
								self.$emit('urlFirmado', self.urlFirmado);
							}
							else console.log('Something goes wrong!',  res);
						});
					}
					
				}
			}
			
		},
		watch:{
            // usuario : (newVal) => console.log('newVal', newVal)
				usuario: {
					handler(newVal, oldVal) {
							this.doc = [];
							this.idFirmado = [];
							this.folio = [];
							this.llave = [];
							this.urlFirmado = [];
							this.docFirmadosListos= [];
							this.docFirmadosPendientes= [];
							console.log('Prop changed: ', newVal );
							console.log('Prop changed| was: ', oldVal);
							console.log('tramite actualizado en firma');
							let APP_URL = 'http://10.153.144.218/tramites-ciudadano';
							this.usuario.solicitudes.map((solicitud, ind) => {
								console.log(solicitud);
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
										console.log('/////');
										console.log(solicitud);
										solicitud['urlDocumentoFirmado'] = `${process.env.INSUMOS_DOCS_HOSTNAME}/firmas/${this.usuario.tramite_id + "_" +  this.usuario.solicitudes[0].id}/${solicitud.id}_${this.usuario.tramite_id}_${this.usuario.solicitudes[0].id}_firmado.pdf`;
										this.docFirmadosListos.push(solicitud);
									}else{
										(this.tramitesdoc[ind].id == solicitud.id && this.tramitesdoc[ind].required_docs == 1 ) ? solicitud.required_docs = 1 : '' ;
										solicitud['urlDocumentoFirmado'] =  `${process.env.INSUMOS_DOCS_HOSTNAME}/firmas/${this.usuario.tramite_id + "_" +  this.usuario.solicitudes[0].id}/${solicitud.id}_${this.usuario.tramite_id}_${this.usuario.solicitudes[0].id}_firmado.pdf`;
										this.docFirmadosListos.push(solicitud);
									}
								}else{
									this.doc = doc;
									this.llave = `${solicitud.id}`;
									this.folio = md5( (Date.now() % 1000) / 1000  ) + `${ind}`;
									if(solicitud.required_docs == 1){
										solicitud['urlDocumentoFirmado'] = `${process.env.INSUMOS_DOCS_HOSTNAME}/firmas/${this.usuario.tramite_id + "_" +  this.usuario.solicitudes[0].id}/${solicitud.id}_${this.usuario.tramite_id}_${this.usuario.solicitudes[0].id}_firmado.pdf` ;
										this.docFirmadosListos.push(solicitud);
									}else{
										(this.tramitesdoc[ind].id == solicitud.id && this.tramitesdoc[ind].required_docs == 1 ) ? solicitud.required_docs = 1 : '' ;
										solicitud['urlDocumentoFirmado'] = `${process.env.INSUMOS_DOCS_HOSTNAME}/firmas/${this.usuario.tramite_id + "_" +  this.usuario.solicitudes[0].id}/${solicitud.id}_${this.usuario.tramite_id}_${this.usuario.solicitudes[0].id}_firmado.pdf` ;
										this.docFirmadosListos.push(solicitud);
									}
								}
							this.rfc= this.user.rfc; 
							this.idFirmado.push(solicitud.id);
							console.log(this.idFirmado);
							this.urlFirmado.push( `${process.env.INSUMOS_DOCS_HOSTNAME}/firmas/${this.usuario.tramite_id + "_" +  this.usuario.solicitudes[0].id}/${solicitud.id}_${this.usuario.tramite_id}_${this.usuario.solicitudes[0].id}_firmado.pdf` );
							})
						this.accesToken();
						this.encodeData();
						},
						immediate: true, 
					
				}
			}
	}
</script>