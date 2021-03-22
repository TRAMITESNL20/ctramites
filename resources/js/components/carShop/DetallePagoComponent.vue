 <template>
    <div class="mb-3 shadow-sm p-3 bg-white rounded">  
        <div class="pt-4">
            <h5 class="mb-3">Total:</h5>
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between align-items-center px-0 pb-0" v-if="idSeguimiento">
                    Folio Seguimiento
                    <span id="subTotalTramites">{{idSeguimiento}}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center px-0 pb-0" v-if="folioMotor">
                    Folio transacción
                    <span id="subTotalTramites">{{folioMotor}}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 mb-3">
                    <div>
                        <strong>Total</strong>
                        <strong>
                            <p class="mb-0"></p>
                        </strong>
                    </div>
                    <span>
                        <h3 id="totalTramites" >
                         {{ calcularTotal | toCurrency }}
                        </h3>
                    </span>
                </li>
            </ul>

            <button id="metodoPagoBtn" type="button" class="btn btn-primary btn-block" v-on:click="metodoPago()" :disabled="!obtenidoCostos || consultandoMetodos">
                Pagar
                <div id="spinner-pago" class="spinner-border spinner-border-sm float-right" role="status" v-if="consultandoMetodos">
                    <span class="sr-only">Loading...</span>
                </div>
            </button>
        </div>
    </div>
</template>

<script>
    import { uuid } from 'vue-uuid';
    export default {
        props: ['tramites', 'obtenidoCostos'],

        data(){
            return {
                consultandoMetodos:false, mostrarCancelarPago: false, folioMotor:'', idSeguimiento:''
            }
        },
        computed:{
            calcularTotal(){
                var total = 0;
                this.tramites.forEach(tramite => total = total + parseFloat(tramite.importe_tramite) );
                return total;
            }
        },
        methods: {
            metodoPago() {
                $("#metodoPagoBtn").fadeOut();
                
                let tramitesAEnviar = [];
                this.consultandoMetodos = true;

                this.tramites.forEach(  tr =>{
                    let tramite = Object.assign({}, tr);
                    delete tramite.nombre;
                    delete tramite.idSolicitante;
                    delete tramite.calveTemp;
                    tramitesAEnviar.push( tramite );
                });

                let ids_tramites = tramitesAEnviar.map( tramite => { return {id:tramite.id_tramite}} );

                let dataToSave = {
                    ids_tramites,
                    status:45
                }


                let urlSaveTRANSACCION = process.env.TESORERIA_HOSTNAME +  "/save-transaccion"
                axios.post(urlSaveTRANSACCION, dataToSave, {
                     headers:{
                        "Content-type":"application/json"
                    }
                } ).then(response => {
                    let url = process.env.PAYMENTS_HOSTNAME  + "/v1/pay";
                    let idTRansaccion = response.data.id_transaccion;
                    this.idSeguimiento = idTRansaccion;
                    let data = {
                        "token": "DD0FDED2FE302392164520BF7090E1B3BEB7",
                        "referencia": "",
                        "url_retorno": "url",
                        "importe_transaccion":  this.calcularTotal, //"4687",
                        "id_transaccion":  this.idSeguimiento,//uuid.v4(),//"BMU8605134I82915082020",rellenar con 0
                        "entidad": process.env.ENTIDAD_REPOSITORIO,
                        "url_confirma_pago": "url",
                        "es_referencia": "1",
                        "tramite": tramitesAEnviar
                    }
                    
                    axios.post(url, data, {
                        headers:{
                            "Authorization":"Bearer " + process.env.PAYMENTS_KEY,
                            "Content-type":"application/json"
                        },
                    } ).then(responseTransaccion => {
                        let dataMotor = {
                            status:5,
                            "id_transaccion": idTRansaccion,
                            "id_transaccion_motor": responseTransaccion.data.response.folio,
                            json_envio: data,
                            json_recibo:responseTransaccion.data
                        }

                        if(responseTransaccion.data.response.pago_cero){
                            dataMotor.status = 0;
                        }

                        this.folioMotor = dataMotor.id_transaccion_motor;
                        this.guardarTransaccionMotor( dataMotor );

                        this.mostrarCancelarPago = true;
                        this.$emit('updatingParent', responseTransaccion);


                    }).catch(error=> {
                        let dataMotor = {
                            "status":5,
                            "id_transaccion": null,
                            "id_transaccion_motor":null,
                            json_envio: data,
                            json_recibo:error.response
                        }
                        this.guardarTransaccionMotor( dataMotor );
                        //this.mostrarMetodos = false;
                    }).finally(() => {
                        this.consultandoMetodos = false;
                    });
                }).catch((error)=> {
                    console.log("transaccion")
                    //this.mostrarMetodos = false;
                }).finally(() => {
                    this.consultandoMetodos = false;
                });




            },

            cancelarPago(){
                this.mostrarCancelarPago= false;
                this.$emit('cancelarPago', true);
            },


            guardarTransaccionMotor(data){
                let urlSaveTRANSACCIONMotor = process.env.TESORERIA_HOSTNAME +  "/save-transaccion-motor"
                axios.post(urlSaveTRANSACCIONMotor, data, {
                     headers:{
                        "Content-type":"application/json"
                    }
                } ).then(response => {
                    console.log("guardando transaccion motor")
                });
            }
        },

    }
</script>

