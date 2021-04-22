<template>
    <div id="contenedorCampos" class="container-fluid">
        <form id="formularioComplementaria" ref="form">
            <div class="panel panel-default" >
                <div class="panel-heading">
                    <div class="row">
                        <div  class="col-md-6 col-sm-6 col-xs-6">
                            <div class="form-group fv-plugins-icon-container"  >
                                <label>FOLIO</label>
                                <input @change="cambioModelo" type="text" class="form-control form-control-solid form-control-lg"  placeholder="Folio" id="folio" v-model="form.folio_anterior" />
                            </div>
                        </div>
                        <div  class="col-md-6 col-sm-6 col-xs-6">
                            <div class="form-group fv-plugins-icon-container">
                                <label>FECHA DE ESCRITURA O MINUTA</label>
                                <b-input-group >
                                  <b-form-input
                                    type="text"
                                    placeholder="DD-MM-YYYY"
                                    autocomplete="off" id="fecha"  v-model="form.fecha_escritura"        
                                      @change="cambioModelo" style="background-color: #e5f2f5 !important"
                                    @focus="cambioModelo" @input="cambioModelo"
                                  ></b-form-input>
                                  <b-input-group-append>
                                    <b-form-datepicker   id="datepickerfecha" name="datepickerfecha"  v-model="fechaDatepICKER"        
                                    style="background-color: #e5f2f5 !important" @input="formatFechaNacimiento()"  button-only right aria-controls="fecha"  :show-decade-nav="showDecadeNav">
                                    </b-form-datepicker>
                                  </b-input-group-append>
                                </b-input-group>
                            </div>
                        </div>
                        <div  class="col-md-6 col-sm-6 col-xs-6">
                            <div class="form-group fv-plugins-icon-container"  >
                                <label>MONTO DE OPERACIÓN</label>
                                <input @change="cambioModelo"  type="text" class="form-control form-control-solid form-control-lg"  placeholder="Monto Operación" id="montoOper" v-model="form.monto_operacion"  v-currency/>
                            </div>
                        </div>
                        <div  class="col-md-6 col-sm-6 col-xs-6">
                            <div class="form-group fv-plugins-icon-container"  >
                                <label>GANANCIA OBTENIDA</label>
                                <input @change="cambioModelo" type="text" class="form-control form-control-solid form-control-lg"  placeholder="Ganancia Obtenida" id="ganancia_obtenida" v-model="form.ganancia_obtenida" v-currency/>
                            </div>
                        </div>
                        <div  class="col-md-6 col-sm-6 col-xs-6">
                            <div class="form-group fv-plugins-icon-container"  >
                                <label>PAGO PROVISIONAL CONFORME AL ARTICULO 126 LISR</label>
                                <input @change="cambioModelo" type="text" class="form-control form-control-solid form-control-lg"  placeholder="Pago provisional" id="fecha" v-model="form.pago_provisional_lisr" v-currency/>
                            </div>
                        </div>
                        <div  class="col-md-6 col-sm-6 col-xs-6">
                            <div class="form-group fv-plugins-icon-container"  >
                                <label>MULTA POR CORRECCION FISCAL</label>
                                <input @change="cambioModelo" type="text" class="form-control form-control-solid form-control-lg"  placeholder="Multa Corrección Fiscal" id="fecha" v-model="form.multa_correccion_fiscal" v-currency
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
     </div> 
</template>

<script>
    import Vue from 'vue';
    import { validationMixin } from 'vuelidate';
    import { required, helpers  } from 'vuelidate/lib/validators';

    export default {
        mixins: [validationMixin],
        props: ['infoGuardada'],
        data() {
            return {
                form:{
                    folio_anterior:'',
                    fecha_escritura:'', 
                    monto_operacion: Vue.filter('formatoMoneda')("0"),
                    ganancia_obtenida:Vue.filter('formatoMoneda')("0"), 
                    multa_correccion_fiscal:Vue.filter('formatoMoneda')("0"),
                    pago_provisional_lisr:Vue.filter('formatoMoneda')("0"),
                },
                showDecadeNav:true,
                fechaDatepICKER:''
            }
        },

        validations() {
            return {
                form:{
                    folio_anterior:{required},
                    fecha_escritura:{required},
                    monto_operacion:{required},
                    ganancia_obtenida:{required},
                    pago_provisional_lisr:{required},
                    multa_correccion_fiscal:{required},
                }
            }
        },

        mounted() {

            this.form = this.infoGuardada && this.infoGuardada.camposComplementaria ? this.infoGuardada.camposComplementaria : this.form;

            if(this.infoGuardada && this.infoGuardada.camposComplementaria){
                this.form={
                    folio_anterior:'',
                    fecha_escritura:'', 
                    monto_operacion: Vue.filter('formatoMoneda')(this.form.monto_operacion),
                    ganancia_obtenida:Vue.filter('formatoMoneda')(this.form.ganancia_obtenida), 
                    multa_correccion_fiscal:Vue.filter('formatoMoneda')(this.form.multa_correccion_fiscal),
                    pago_provisional_lisr:Vue.filter('formatoMoneda')(this.form.pago_provisional_lisr),
                }
                
            }
            this.cambioModelo();
        },

        methods:{
            cambioModelo(){
                console.log(JSON.parse(JSON.stringify(this.form)));
                let valido =  !this.$v.form.$invalid;
                console.log(JSON.parse(JSON.stringify(this.$v.form)));
                this.$emit('updatingScore', valido);
                this.$emit('sendData', this.form);
            },

            formatFechaNacimiento(){
                this.form.fecha_escritura =  this.fechaDatepICKER.split("-").reverse().join("-");

                this.cambioModelo();
            }
        }
    }
</script>


