<template>
    <div class="mb-3" id="divMetodoPago" >
        <div id="containerMetodoPago" > 
            <div class="card border-secondary shadow p-3 mb-5 bg-white rounded metodopago"" id="idVentanilla" v-if="tieneVentanilla">
                <div class="pt-4" >
                    <h6 class="mb-3"><strong>Pago Referenciado</strong></h6>

                    <div class="text-center">
                        <button type="button" class="btn btn-success btn-metodopago" id="metodopagoBtnRef"  v-on:click="obtenerReciboPagoVentanilla()" :disabled="obteniendoRecibo">
                            <span v-if="!obteniendoRecibo" class="text-white"> Pago Referenciado  </span>
                            <div id="spinner-ref" class="spinner-border spinner-border-sm float-right text-white" role="status" v-if="obteniendoRecibo">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card border-secondary shadow p-3 mb-5 bg-white rounded metodopago"" id="idNetPay" v-if="tienePagoNetPay">
                <div class="pt-4" >
                    <h6 class="mb-3"><strong>Pago en linea</strong></h6>

                    <div class="text-center">
                        <button type="button" class="btn btn-success btn-metodopago" id="metodopagoBtnNP"  v-on:click="pagoNetPay()" :disabled="obteniendoPagoEnLinea"> 
                            <span v-if="!obteniendoPagoEnLinea"  class="text-white"> NetPay  </span> 
                            <div id="spinner-ref" class="spinner-border spinner-border-sm float-right text-white" role="status" v-if="obteniendoPagoEnLinea">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </button>
                        <form v-if="urlNetPay.length > 0" id="formulariosNetPay" :action="urlNetPay" method="post">
                            <input type="hidden" name="jwt" :value="JWT">
                            <button type="submit" style="display: none;">Enviar</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="card border-secondary shadow p-3 mb-5 bg-white rounded metodopago"" id="idBancomer" v-if="tienePagoBancomer">
                <div class="pt-4" >
                    <h6 class="mb-3"><strong>Pago en Bancomer</strong></h6>
                    <div class="text-center">
                        <button type="button" class="btn btn-success btn-metodopago" id="metodopagoBtnBancomer"  v-on:click="pagoBancomer()"  :disabled="obteniendoPagoBancomer"> 
                            
                            <span v-if="!obteniendoPagoBancomer"  class="text-white"> Bancomer  </span> 
                            <div id="spinner-ref" class="spinner-border spinner-border-sm float-right text-white" role="status" v-if="obteniendoPagoBancomer">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </button>
                        <form v-if="urlBancomer.length > 0" id="formulariosBancomer" :action="urlBancomer" method="post">
                            <input type="hidden" v-for="(value,name) in this.datos" :name="name" :value="value" >
                            <button type="submit" style="display: none;">Enviar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>     

    </div>

</template>

<script>
    export default {
        props: ['infoMetodosPago'],
        mounted() {
            this.cuentas = this.infoMetodosPago.cuentas;
            this.folio = this.infoMetodosPago.folio;
            

            this.tienePagoNetPay = this.cuentas.find( cuenta => cuenta.metodopago_id == 1 && cuenta.banco_id == 17 );
            this.tieneVentanilla =  this.cuentas.find( cuenta => cuenta.metodopago_id == 3 );
            this.tienePagoBancomer = this.cuentas.find( cuenta => cuenta.metodopago_id == 1 && cuenta.banco_id == 3);
        },

        data(){
            return {
                tieneVentanilla: false,
                tienePagoNetPay:false,
                tienePagoBancomer: false,
                cuentas:[],
                folio:0,
                obteniendoRecibo:false,
                obteniendoPagoEnLinea:false,
                obteniendoPagoBancomer:false,
                urlNetPay:"", JWT:"",
                urlBancomer:""
            }
        },
  
        methods: {

            cambiarStatusTransaccion(data /*status*/){
                /*let data ={
                    id_transaccion_motor:this.folio,
                    status
                }*/
                this.$emit('metodoDePagoSeleccionado', { success:true });
                let url = process.env.TESORERIA_HOSTNAME + "/solicitudes-update-status-tramite";
                axios.post(url, data, {
                     headers:{
                        "Content-type":"application/json"
                    }
                } ).then(response => {
                    console.log("cambiando estatus")
                    console.log(response)
                });
            },

            obtenerReciboPagoVentanilla: function () {
                $("#idNetPay").hide();
                $("#idBancomer").hide();
                this.obteniendoRecibo = true;
                let url = process.env.PAYMENTS_HOSTNAME +  "/v1/databank";  
                $.ajax({
                    type: "POST",
                    url,
                    data: JSON.stringify({ folio:this.folio, cuenta_id: this.tieneVentanilla.cuenta_id }),
                    headers: {
                        "Authorization":"Bearer " + process.env.PAYMENTS_KEY,
                        "Content-type":"application/json"
                    }
                }).done((response) => {
                    if(typeof response === 'string') response = JSON.parse(response);
                    let params = {
                        recibo_referencia:response.response.datos.recibo.url,
                        status:60,
                        id_transaccion_motor:this.folio
                    }
                    this.cambiarStatusTransaccion( params/*60*/ );
                    if(response.response.datos.recibo.pdf ) {
                        divContenedorSecundario.append('<div id="pdfContenido"><iframe id="frame" src="http://egobierno.nl.gob.mx/egob/formatoRepositorio.php?Folio=258310" width="100%" height="600"></iframe></div>')
                    } else {
                        window.open(response.response.datos.recibo.url);


                    }
                }).fail((rror)=> {
                    console.log( rror)
                }).always(() => {
                    this.obteniendoRecibo = false;
                    $("#spinner-ref").remove();
                });

            },

            pagoNetPay(){
                this.obteniendoPagoEnLinea = true;
                let url = process.env.PAYMENTS_HOSTNAME +  "/v1/databank"; 

                let data = JSON.stringify({ folio:this.folio, cuenta_id: this.tienePagoNetPay.cuenta_id });

                axios.post(url, 
                    data,
                    {
                    headers:{
                        "Authorization":"Bearer " + process.env.PAYMENTS_KEY,
                        "Content-type":"application/json"
                    },
                } )
                .then(response => {
                    let params = {
                        status:70,
                        id_transaccion_motor:this.folio
                    }
                    this.cambiarStatusTransaccion( params/*70*/ );

                    this.urlNetPay = response.data.response.url_response;
                    this.JWT= response.data.response.datos.jwt;
                    setTimeout(function(){ 
                        $("#formulariosNetPay").submit();
                    }, 3000);
                }).catch((error)=> {
                    console.log(error)
                    this.obteniendoPagoEnLinea = false;
                }).finally(() => {
                    
                });
            },

            pagoBancomer(){
                this.obteniendoPagoBancomer = true;
                let url = process.env.PAYMENTS_HOSTNAME +  "/v1/databank"; 

                let data = JSON.stringify({ folio:this.folio, cuenta_id: this.tienePagoBancomer.cuenta_id });

                axios.post(url, 
                    data,
                    {
                    headers:{
                        "Authorization":"Bearer " + process.env.PAYMENTS_KEY,
                        "Content-type":"application/json"
                    },
                } )
                .then(response => {
                    this.urlBancomer = response.data.response.url_response;
                    this.datos = response.data.response.datos;

                    let params = {
                        status:70,
                        id_transaccion_motor:this.folio
                    }
                    this.cambiarStatusTransaccion( params/*70*/ );
   
                    setTimeout(function(){ 
                        this.obteniendoPagoBancomer = false;
                        $("#formulariosBancomer").submit();
                    }, 3000);

                }).catch((error)=> {
                    console.log(error)
                    this.obteniendoPagoBancomer = false;
                }).finally(() => {
                    
                });
            }


        }
    }
</script>

