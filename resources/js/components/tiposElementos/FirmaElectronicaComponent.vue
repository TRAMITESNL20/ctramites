<template>
    <div >
        <iframe v-if="tramiteFirmado == false" id="the_frame" v-on:load="validateSigned()" :src="firma" style="width:100%; height: 550px;" frameborder="0"> </iframe>
    </div>
</template>

<script>
import Vue from 'vue';
var md5 = require('md5');



export default {
    
	props: ['datosComplementaria', 'tipoTramite','usuario', 'pago', 'id', 'user'],
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
            tramiteFirmado : false
		}
	},
	mounted() {
		this.rfc = this.user.rfc;
		
    },
    methods: {
        
    	validateSigned (evt) {
    		this.coutnLoad++;
            var self = this;
            console.log(this.coutnLoad);
                
                if(this.coutnLoad >= 2  &&  this.coutnLoad <= 5  ){

                        fetch(this.urlFirmado[0], { method: 'GET' })
                        .then( function(response ) {
                                        console.log(self.idFirmado);
                                        console.log(response.status);
                                         // then log it out    
                                    if( response.status == 200 ){
                                        fetch(`${process.env.TESORERIA_HOSTNAME}/solicitudes-guardar-carrito`, {
                                            method : 'POST',
                                                body: JSON.stringify({ ids : self.idFirmado, status : 1, type : 'firmado', urls : self.urlFirmado, user_id: user.id })
                                            })
                                            .then(res => res.json())
                                                .then(res => {
                                                    if(res.code === 200){
                                                        console.log('Firmado');    
                                                        self.tramiteFirmado = true;
                                                        self.$emit('docFirmadosPendientes', self.docFirmadosPendientes);
                                                        self.$emit('docFirmadosListos', self.docFirmadosListos);
                                                        self.$emit('docFirmado', 1);
                                                        self.$emit('urlFirmado', self.urlFirmado);
                                                    }
                                                    else console.log('Something goes wrong!', res);
                                            });

                                    } 
                                
                            }) 
                            .catch(function(error) {
                                    console.log('no se encontro el archivo en insumos');
                                    console.log(error);
                            })
                
                        
                }
    	},
        encodeData(){
            var urlDataGeneric =  process.env.INSUMOS_API_HOSTNAME + '/data_generic';
            var url =  process.env.INSUMOS_API_HOSTNAME + "/v2/signature/iframe?id=";
            // var rfc = 'GOFF951130TJ0';
            

            var data = {
                'perfil' : 'EI',
                'multiple' : this.multiple,
                'tramite' :  this.usuario.tramite_id + "_" +  this.usuario.solicitudes[0].id,
                'llave' : this.llave,
                'doc' : this.doc,
                'folio' : this.folio,
                'rfc' : 'GOFF951130TJ0',
                // 'rfc' : this.rfc,
                'pagado' : 1,
                'descargable': false,
              
            };

            const headers = { 
                "Authorization": "Bearer my-token",
                'Content-Type': 'application/json',
            };

            // axios.post(urlDataGeneric,{"tramite_id" : tramite_id, "value": JSON.stringify(data), "access_token" : this.access_token}, {headers})
            //     .then((restult) => { 
            //         console.log('////', restult) 
            //         self.resultId = result.data.id;
            //     })
            //     .catch((error)=> {
            //         console.log(error)
            //     })
            //     .finally(() => console.log('listo'))
                $.ajax({
                    type: "POST",
                    data: {"tramite_id" : this.usuario.tramite_id, "value": JSON.stringify(data), "access_token" : this.access_token},
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
            //  axios.post(url, data)
            //     .then(response => {
            //         self.access_token = response.data.token;
            //         console.log('token: ' , response.data.token);
            //     })
            //     .catch(error => {
            //         console.log('error al generar token: ', error);
            //     });
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
                                this.docFirmadosListos.push(`${process.env.INSUMOS_DOCS_HOSTNAME}/firmas/${this.usuario.tramite_id + "_" +  this.usuario.solicitudes[0].id}/${solicitud.id}_${this.usuario.tramite_id}_${this.usuario.solicitudes[0].id}_firmado.pdf`);
                            }else{
                                this.docFirmadosPendientes.push(`${process.env.INSUMOS_DOCS_HOSTNAME}/firmas/${this.usuario.tramite_id + "_" +  this.usuario.solicitudes[0].id}/${solicitud.id}_${this.usuario.tramite_id}_${this.usuario.solicitudes[0].id}_firmado.pdf`);
                            }

                        }else{
                            this.doc = doc;
                            this.llave = `${solicitud.id}`;
                            this.folio = md5( (Date.now() % 1000) / 1000  ) + `${ind}`;
                            if(solicitud.required_docs == 1){
                                this.docFirmadosListos.push(`${process.env.INSUMOS_DOCS_HOSTNAME}/firmas/${this.usuario.tramite_id + "_" +  this.usuario.solicitudes[0].id}/${solicitud.id}_${this.usuario.tramite_id}_${this.usuario.solicitudes[0].id}_firmado.pdf`);
                            }else{
                                this.docFirmadosPendientes.push(`${process.env.INSUMOS_DOCS_HOSTNAME}/firmas/${this.usuario.tramite_id + "_" +  this.usuario.solicitudes[0].id}/${solicitud.id}_${this.usuario.solicitudes[0].id}_${this.usuario.tramite_id}_firmado.pdf`);
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