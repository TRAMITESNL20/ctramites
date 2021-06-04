<template>
        <div class="container">
            <div class="card">
                 <div class="card-header">
                    <div v-if="tramite.detalle && tramite.detalle.Salidas"  class="row">
                       
                        <button href="#" class="btn btn-sm btn-light-primary font-weight-bolder text-uppercase mr-2" v-on:click="toggleTabla()" >
                            Ver detalle <i class="fa fa-angle-down"></i>
                        </button>
                    </div>
                 </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="row">
                            <div class="col-lg-12 col-sm-12 ml-auto" v-if="!obteniendoCosto">
                                <table class="table table-clear" >
                                    <!--
                                    <tbody v-if="tramite.detalle && tramite.detalle.Salidas"  id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion" style="display: none;">
                                        <tr v-for="(salida, key) in tramite.detalle.Salidas" >
                                            <td class="left" style="width: 70%"  v-if="key != 'Importe total' ">
                                                <strong>{{ key }}</strong>
                                            </td>
                                            <td class="text-right" v-if="key != 'Importe total'" >
                                                    <span class="spinner-border spinner-border-sm" v-if="obteniendoCosto"></span>
                                                    <span v-if="!obteniendoCosto">   {{ currencyFormat(key, salida) }} </span>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tbody  v-if="tramite.detalle && tramite.detalle.Salidas && tipoTramite =='normal' ">
                                        <tr>
                                            <td class="left" style="width: 70%">
                                                <strong>Importe total</strong>
                                            </td>
                                            <td class="right">
                                                    <span class="spinner-border spinner-border-sm" v-if="obteniendoCosto"></span>
                                                    <span v-if="!obteniendoCosto"> 
                                                        {{ this.tramite.detalle.Salidas['Importe total'] | toCurrency }}

                                                    </span>
                                            </td>
                                        </tr>
                                    </tbody>-->
                                    <tbody v-if="tramite.detalle && tramite.detalle.costo_final >= 0">
                                        <tr v-if="tramite.detalle.pago_total">
                                            <td class="left">
                                                <strong>Total</strong>
                                            </td>
                                            <td class="right">
                                                <span v-if="!obteniendoCosto">  {{tramite.detalle.pago_total | formatoMoneda }} </span>
                                            </td>
                                        </tr>
                                        <tr v-if="tramite.detalle.costo_anterior">
                                            <td class="left">
                                                <strong>Pagado Anteriormente</strong>
                                            </td>
                                            <td class="right">
                                                <span v-if="!obteniendoCosto"> {{tramite.detalle.costo_anterior| formatoMoneda }} </span>
                                            </td>
                                        </tr>
                                        <tr >
                                            <td class="left">
                                                <strong> {{ tramite.detalle.pago_total ? 'Por Pagar' : 'Total' }}</strong>
                                            </td>
                                            <td class="right">
                                                <span v-if="!obteniendoCosto"> {{tramite.detalle.costo_final | formatoMoneda}} </span>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <!--
                                    <tbody v-else-if="tramite.detalle && tramite.detalle.Complementaria && tipoTramite =='complementaria'">
                                        <tr>
                                            <td colspan="2">
                                                <strong><h4> Complementaria</h4></strong>
                                            </td>
                                        </tr>
                                        <tr v-for="(salida, key) in tramite.detalle.Complementaria" >
                                            <td class="left" style="width: 70%" >
                                                <strong>{{ key }}</strong>
                                            </td>
                                            <td class="text-right"  >
                                                    <span class="spinner-border spinner-border-sm" v-if="obteniendoCosto"></span>
                                                    <span v-if="!obteniendoCosto">   {{ currencyFormat(key, salida) }} </span>
                                            </td>
                                        </tr>
                                    </tbody>-->
                                </table>
                                <div class="card-body text-center" v-if="!tramite.detalle">
                                    <h5 class="card-title" >Ocurrió un error al obtener el total</h5>
                                    Consulte al administrador del sistema
                                </div>
                            </div>
                            <div class="col-lg-12 col-sm-12 ml-auto">
                                <div v-if="obteniendoCosto" class="text-center">
                                    <b-spinner variant="primary" type="grow" label="Spinning"></b-spinner>
                                </div>
                            </div>
                        </div>
                    </div>
                                        
                    <div class="table-responsive-sm" v-if="listaSolicitantes.length > 0 && tipoTramite == 'normal'">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th class="center">RFC</th>
                                <th>Nombre ó Razon Social</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(sol, index) in listaSolicitantes" >
                                    <td class="left strong">{{ sol.rfc }}</td>
                                    <td class="center">
                                        {{ sol.tipoPersona == "pm" ? sol.razonSocial : sol.nombreSolicitante + ' ' + sol.apPat + ' '  + sol.apMat  }} 
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

</template>

<script>
    const CAMPO_LOTE            = "Lote";
    const CAMPO_HOJA            = "Hoja";
    const CAMPO_SUBSIDIO        = "Subsidio";
    const CAMPO_VALOR_CATASTRAL = "Valor catastral";
    const CAMPO_VALOR_OPERACION = "Valor de operacion";
    const CAMPO_TIPO_OPERACION  = "Tipo de Operación";

    //CAMPOS CALCULO IMPUESTO
    /*
    const CAMPO_GANANCIA_OBTENIDA                               = "GANANCIA OBTENIDA";
    const CAMPO_MONTO_DE_OPERACIÓN                              = "MONTO DE OPERACIÓN (reportado en el aviso de enajenación)";
    const CAMPO_MULTA_POR_CORRECCION_FISCAL                     = "MULTA POR CORRECCION FISCAL";
    const CAMPO_FECHA_DE_ESCRITURA_O_MINUTA                     = "FECHA DE ESCRITURA O MINUTA";
    const CAMPO_PAGO_PROVISIONAL_CONFORME_AL_ARTICULO_126_LISR  = "PAGO PROVISIONAL CONFORME AL ARTICULO 126 LISR";
    */

    const CAMPO_DIVISAS = "Cambio de divisas";

    import Vue from 'vue'

    export default {

        props: ['datosComplementaria','tipoTramite', 'infoGuardadaFull'],
        mounted() {
            this.obtenerInformacionDelTramite();


            /*
            if(this.tipoTramite == 'declaracionEn0'){
                this.obteniendoCosto= false;
                this.tramite.detalle = {costo_final:0};
                const parsed = JSON.stringify(this.tramite);
                localStorage.setItem('tramite', parsed);  
                this.$forceUpdate();
                this.obteniendoCosto = false;
            } else {
                this.obtenerCosto();    
            }*/
            if( this.infoGuardadaFull && this.infoGuardadaFull.status == this.$const.STATUS_ERROR_MUNICIPIO ){
                this.tramite.detalle = JSON.parse(  this.infoGuardadaFull.info ).detalle;
                const parsed = JSON.stringify(this.tramite);
                localStorage.setItem('tramite', parsed);  
                this.$forceUpdate();
                this.obteniendoCosto = false;
            } else {
                this.obtenerCosto();    
            }
            
        },

        data(){
            return {
                tramite: {  },
                listaSolicitantes:[],
                datosFormulario:{},
                obteniendoCosto:true
            }
        },
  
        methods: {
            obtenerInformacionDelTramite(){
                let informacionEnStorage = ["listaSolicitantes", "tramite", "datosFormulario"];
                informacionEnStorage.forEach( name => {
                    if (localStorage.getItem(name)) {
                      try {
                        this[name] = JSON.parse(localStorage.getItem(name));
                      } catch(e) {
                        letocalStorage.removeItem(name);
                      }
                    }
                });
            },

            toggleTabla(){
                $( "#collapseOne" ).toggle('slow');
            },

            getCampoByName( nameCampo ){
                return this.datosFormulario.campos.find( campo => campo.nombre.toLowerCase()  === nameCampo.toLowerCase() );
            }, 

            getParamsCalculoCosto( consulta_api , params, tipo_costo_obj){
                let paramsCosto = {};
                if(this.tipoTramite =='normal'  ){
                    if( consulta_api == "/getcostoImpuesto" ){
                        /*let CAMPOS CALCULO IMPUESTO
                        let campoMonto              = this.getCampoByName(CAMPO_MONTO_DE_OPERACIÓN);
                        let campoMulta              = this.getCampoByName(CAMPO_MULTA_POR_CORRECCION_FISCAL);
                        let campoFechaMinuta        = this.getCampoByName(CAMPO_FECHA_DE_ESCRITURA_O_MINUTA);
                        let campoPagoProvisional    = this.getCampoByName(CAMPO_PAGO_PROVISIONAL_CONFORME_AL_ARTICULO_126_LISR);
                        let campoGananciaObtenida   = this.getCampoByName(CAMPO_GANANCIA_OBTENIDA);

                        paramsCosto.fecha_escritura = campoFechaMinuta.valor.split("-").map(dato => Number(dato)).join("-");
                        paramsCosto.monto_operacion = this.formatoNumero(campoMonto.valor);
                        paramsCosto.ganancia_obtenida = this.formatoNumero(campoGananciaObtenida.valor);    
                        paramsCosto.pago_provisional_lisr = this.formatoNumero(campoPagoProvisional.valor);
                        if( campoMulta ){
                            paramsCosto.multa_correccion_fiscal = this.formatoNumero(campoMulta.valor);
                        }*/
                    } else {

                        if ( tipo_costo_obj.tipo_costo == '1' && (tipo_costo_obj.tipoCostoRadio == 'hoja'||tipo_costo_obj.tipoCostoRadio == 'lote') ){
                            paramsCosto.tipo_costo = tipo_costo_obj.tipo_costo;
                            paramsCosto.tipoCostoRadio = tipo_costo_obj.tipoCostoRadio;
                            paramsCosto.hojaInput = tipo_costo_obj.hojaInput;
                        }  else {
                            let campoLote           = this.getCampoByName(CAMPO_LOTE);
                            let campoHoja           = this.getCampoByName(CAMPO_HOJA);
                            let campoSubsidio       = this.getCampoByName(CAMPO_SUBSIDIO);
                            let campoCatastral      = this.getCampoByName(CAMPO_VALOR_CATASTRAL);
                            let campoValorOperacion = this.getCampoByName(CAMPO_VALOR_OPERACION);  
                            let tipoOperacion       = this.getCampoByName(CAMPO_TIPO_OPERACION);
                            if( campoCatastral ){
                                paramsCosto.valor_catastral = this.formatoNumero(campoCatastral.valor);
                            }

                            if(campoSubsidio){                            
                                if( campoSubsidio.tipo == 'select'  ){
                                    //paramsCosto.subsidio = campoSubsidio.valor[0][0];//62 
                                    paramsCosto.subsidio = campoSubsidio.valor.clave;
                                } else {
                                    paramsCosto.subsidio = campoSubsidio.valor;//62    
                                }
                                
                            }

                            if(campoValorOperacion ){
                                paramsCosto.valor_operacion = this.formatoNumero(campoValorOperacion.valor);
                            }

                            if( campoHoja ){
                                paramsCosto.hoja = campoHoja.valor; 
                            }

                            if( campoLote ){
                                paramsCosto.lote = campoLote.valor
                            }
                            if(this.infoGuardadaFull && this.infoGuardadaFull.id && (this.infoGuardadaFull.status == this.$const.STATUS_FALTA_PAGO || this.infoGuardadaFull.status == this.$const.STATUS_ERROR_MUNICIPIO) ) {
                                paramsCosto.id_ticket = this.infoGuardadaFull.id;
                            }

                            if(tipoOperacion){
                                paramsCosto.tipoOperacion = tipoOperacion.valor.clave;
                            }
                        }                 
                    }
                    let campoDivisas              = this.getCampoByName(CAMPO_DIVISAS);
                    if( campoDivisas ){
                        paramsCosto.divisa = campoDivisas.valor.clave;
                        //paramsCosto.divisa = campoDivisas.valor[0][0];
                    }
                } else {
                    /*
                    let params = {};
                    params.fecha_escritura = this.datosComplementaria.fecha_escritura.split("-").reverse().map(dato => Number(dato)).join("-");
                    params.ganancia_obtenida = this.formatoNumero(this.datosComplementaria.ganancia_obtenida);
                    params.monto_operacion = this.formatoNumero(this.datosComplementaria.monto_operacion);
                    params.multa_correccion_fiscal = this.formatoNumero(this.datosComplementaria.multa_correccion_fiscal);
                    params.pago_provisional_lisr = this.formatoNumero(this.datosComplementaria.pago_provisional_lisr);
                    return params;*/
                }

                return Object.assign(params, paramsCosto);
            },

            formatoNumero(numberStr){
                return  Vue.filter('toNumber')(numberStr +"");
            },

            async obtenerCosto(){    
                let url = "";
                let consulta_api =  this.datosFormulario.consulta_api;
                let tipo_costo_obj = this.datosFormulario.tipo_costo_obj ;
                
                if( this.tipoTramite =='normal'  ){
                    url = process.env.APP_URL + (consulta_api ?  consulta_api :  "/getcostoTramite"); 
                } else if(this.tipoTramite =='complementaria'){
                    url = process.env.APP_URL + "/getComplementaria"; 
                }

                let data = {  
                    id_seguimiento: this.tramite.id_seguimiento,
                    tramite_id: this.tramite.id_tramite,
                    tipoPersona:this.listaSolicitantes[0].tipoPersona,
                    campos: this.campos
                }
                
                data = this.getParamsCalculoCosto(consulta_api, data, tipo_costo_obj);
                
                try {
                    // CONSULTA EL COSTO
                    let response = await axios.post(url, data);
                    let detalleTramite = response.data;

                    if( consulta_api == "/getcostoImpuesto" || this.tipoTramite =='complementaria'  ){
                        this.tramite.detalle =  detalleTramite;
                    } else {
                        this.tramite.detalle =  detalleTramite[0];
                
                    }

                    const parsed = JSON.stringify(this.tramite);
                    localStorage.setItem('tramite', parsed);  
                    this.$forceUpdate();
                    this.obteniendoCosto = false;
                } catch (error) {
                    this.obteniendoCosto = false;
                    console.log(error);
                    Command: toastr.error("Error!", error.message || "No fue posible obtener los costos");
                }
                
            },

            currencyFormat(campoName, salida){
                let arr = ["Ganancia Obtenida","Monto obtenido conforme al art 127 LISR",
                            "Pago provisional conforme al art 126 LISR","Impuesto correspondiente a la entidad federativa",
                            "Parte actualizada del impuesto", "Recargos", "Multa corrección fiscal", "Importe total", 'Cantidad a cargo'];
                return  arr.includes(campoName) ?  Vue.filter('toCurrency')(salida) : salida; 
            }



        }
    }
</script>

