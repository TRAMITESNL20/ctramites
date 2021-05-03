<template>
    <div id="contenedorCampos" class="container-fluid">
        <form id="form" ref="form"  >
                <b-row>
                  <b-col cols="12" md="6">
                    <b-form-group label="GANANCIA OBTENIDA" label-for="ganancia-obtenida-input" >
                      <b-input-group  >
                        <template #prepend>
                          <b-input-group-text >$</b-input-group-text>
                        </template>
                        <b-form-input
                        id="ganancia-obtenida-input" name="gananciaObtenida" v-model="$v.form.gananciaObtenida.$model"  :state="$v.form.gananciaObtenida.$dirty ? !$v.form.gananciaObtenida.$error : null"  aria-describedby="gananciaObtenida-input-feedback" @change="getDetalle" v-currency

                      ></b-form-input>
                      </b-input-group>
                      <b-form-invalid-feedback id="gananciaObtenida-input-feedback">
                        <span v-if="!$v.form.gananciaObtenida.required"  class="form-text text-danger">
                          Campo requerido.
                        </span>
                      </b-form-invalid-feedback>
                    </b-form-group>
                  </b-col>
                  <b-col cols="12" md="6">
                    <b-form-group label="PAGO PROVISIONAL CONFORME AL ARTICULO 126 LISR" label-for="pago-provisional-input" >
                      <b-input-group  >
                        <template #prepend>
                          <b-input-group-text >$</b-input-group-text>
                        </template>
                        <b-form-input
                          id="pago-provisional-input" name="pagoProvisional" v-model="$v.form.pagoProvisional.$model"  :state="$v.form.pagoProvisional.$dirty ? !$v.form.pagoProvisional.$error : null"  aria-describedby="pagoProvisional-input-feedback"  @change="getDetalle"  v-currency
                        ></b-form-input>
                      </b-input-group>
                      <b-form-invalid-feedback id="pagoProvisional-input-feedback">
                        <span v-if="!$v.form.pagoProvisional.required"  class="form-text text-danger">
                          Campo requerido.
                        </span>
                      </b-form-invalid-feedback>
                    </b-form-group>
                  </b-col>
                  <!--
                  <b-col cols="12" md="6">
                    <b-form-group label="MONTO OBTENIDO CONFORME AL ART 127 LISR" label-for="monto-obtenido-conforme-isr-input" >
                      <b-input-group  >
                        <template #prepend>
                          <b-input-group-text >$</b-input-group-text>
                        </template>
                        <b-form-input
                          id="monto-obtenido-conforme-isr-input" name="montoObtenidoConformeISR"
                          :disabled="true" :value="datosCostos && datosCostos.Salidas  ? currencyFormat('Monto obtenido conforme al art 127 LISR',  datosCostos.Salidas['Monto obtenido conforme al art 127 LISR']) : ''"
                        ></b-form-input>
                      </b-input-group>
                    </b-form-group>
                  </b-col> -->
                  <b-col cols="12" md="6">
                    <b-form-group label="MULTA POR CORRECCION FISCAL" label-for="multa-correccion-fiscal-input" >
                      
                      <b-input-group  > 
                        <template #prepend>
                          <b-input-group-text >$</b-input-group-text>
                        </template>
                        <b-form-input id="multa-correccion-fiscal-input" name="multaCorreccion" v-model="$v.form.multaCorreccion.$model"  :state="$v.form.multaCorreccion.$dirty ? !$v.form.multaCorreccion.$error : null"  aria-describedby="multaCorreccion-input-feedback"  @change="getDetalle"  v-currency ></b-form-input>
                      </b-input-group>
                      <b-form-invalid-feedback id="multaCorreccion-input-feedback">
                        <span v-if="!$v.form.multaCorreccion.required"  class="form-text text-danger">
                          Campo requerido.
                        </span>
                      </b-form-invalid-feedback>
                    </b-form-group>
                  </b-col>
                  
                  <b-col cols="12" md="6" >
                    <b-form-group label="MONTO DE OPERACIÓN (proporcional conforme al % de venta)" label-for="monto-operacion-input" >
                      <b-input-group  >
                        <template #prepend>
                          <b-input-group-text >$</b-input-group-text>
                        </template>
                        <b-form-input
                          id="monto-operacion-input" name="montoOperacion" v-model="$v.form.montoOperacion.$model" 
                          :state="$v.form.montoOperacion.$dirty ? !$v.form.montoOperacion.$error : null"  
                          aria-describedby="montoOperacion-input-feedback"  @change="getDetalle"  v-currency
                        ></b-form-input>
                      </b-input-group>
                      <b-form-invalid-feedback id="montoOperacion-input-feedback">
                        <span v-if="!$v.form.montoOperacion.required"  class="form-text text-danger">
                          Campo requerido.
                        </span>
                      </b-form-invalid-feedback>
                    </b-form-group>
                  </b-col>    
                </b-row>

                <b-row>
                  <b-col cols="12" md="12" v-if="calculandoCostos">
                       <b-spinner type="grow" label="Spinning"></b-spinner>
                  </b-col>
                  <b-col cols="12" md="12" v-if="!$v.form.$anyError && !calculandoCostos">
                     <div class="text-center" v-if="datosCostos">
                          <b-button title="Click para ver detalles" variant="primary" @click="verDetalle =  !verDetalle" class="mr-2 btn btn-block">
                            {{!verDetalle? "Ver detalle " : "Ocultar detalle "}}   
                          </b-button>
                      </div>    
                      <div>
                        <b-card no-body v-if="datosCostos && verDetalle">
                            <b-card no-body  v-if="typeof datosCostos == 'object'">
                                <template #header>
                                  <h4 class="mb-0">Complementaria</h4>
                                  <hr>
                                </template>
                                <b-card-body id="nav-scroller"ref="content"style="position:relative; height:400px; overflow-y:scroll;">
                                    <b-row v-for="(salida, key) in datosCostos.Salidas" :key="key">
                                        <b-col class="text-left" style="width: 70%" >
                                            <strong>{{ key }}</strong>
                                        </b-col>
                                        <b-col class="text-right" >
                                            <span class="text-muted">   {{ currencyFormat(key, salida) }} </span>
                                        </b-col>
                                    </b-row>
                                </b-card-body> 
                            </b-card> 
                            <b-card-body id="nav-scroller"ref="content "style=" height:300px; overflow-y:scroll;" v-if="typeof datosCostos != 'object'">
                              <div class="text-center">
                                <h5 class="card-title" >Datos incorrectos</h5>
                                Verificar fecha de escritura o minuta
                              </div>
                                
                            </b-card-body> 
                        </b-card>

                      </div>                    
                  </b-col>

                </b-row>

        </form>
     </div> 
</template>

<script>
    import Vue from 'vue';
    import { validationMixin } from 'vuelidate';
    import { required, helpers  } from 'vuelidate/lib/validators';

    export default {
        mixins: [validationMixin],
        props: ['info'],
        data() {
            return {
                form:{
                    montoOperacion: Vue.filter('formatoMoneda')("0"),
                    gananciaObtenida:Vue.filter('formatoMoneda')("0"), 
                    multaCorreccion:Vue.filter('formatoMoneda')("0"),
                    pagoProvisional:Vue.filter('formatoMoneda')("0"),
                },
                calculandoCostos:false,
                datosCostos:false,
                verDetalle:false
            }
        },

        validations() {
            return {
                form:{
                    montoOperacion:{required},
                    gananciaObtenida:{required},
                    pagoProvisional:{required},
                    multaCorreccion:{required},
                }
            }
        },

        mounted() {
            this.getDetalle();
        },

        methods:{
            async getDetalle(){
                this.$emit('updateForm', {valid:false,info:this.info});

                this.calculandoCostos = true
                let url = process.env.APP_URL + "/getComplementaria";
     
                let params = {};
                params.fecha_escritura = this.info.fechaEscritura.split("-").reverse().map(dato => Number(dato)).join("-");
                params.ganancia_obtenida = Vue.filter('toNumber')(this.form.gananciaObtenida +""); 
                params.monto_operacion = Vue.filter('toNumber')(this.form.montoOperacion +""); 
                params.multa_correccion_fiscal = Vue.filter('toNumber')(this.form.multaCorreccion +""); 
                params.pago_provisional_lisr = Vue.filter('toNumber')(this.form.pagoProvisional +""); 
                params.folio_anterior = this.info.folio;
                try {
                    let response = await axios.post(url, params);
                    this.datosCostos = response.data;
                    this.$forceUpdate();
                } catch (error) {
                    Command: toastr.error("Error!", error.message || "No fue posible obtener los costos");
                }
                this.calculandoCostos = false;

                this.hasChanged();
            },

            currencyFormat(campoName, salida){
                  let arr = ["Ganancia Obtenida","Monto obtenido conforme al art 127 LISR",
                              "Pago provisional conforme al art 126 LISR","Impuesto correspondiente a la entidad federativa",
                              "Parte actualizada del impuesto", "Recargos", "Multa corrección fiscal", "Importe total","Monto pagado en la declaracion inmediata anterior","Pago en exceso", "Cantidad a cargo", "Diferencia de Impuesto correspondiente a la Entidad Federativa"];
                  if(arr.includes(campoName)){
                      let text = Vue.filter('toCurrency')(salida);
                      return text;
                  } else{
                      return salida;
                  }
            },

            hasChanged(  ){
              let response = {
                form:this.form,
                valid:!this.$v.form.$invalid,
                detalle:this.datosCostos,
                info:this.info
              }
              this.$emit('updateForm', response);
            }

        }
    }
</script>


