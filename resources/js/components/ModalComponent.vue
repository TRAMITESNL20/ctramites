<template>
  <div>
    <b-button style="color:white" @click="openModal(porcentajeAsignado)" :class="classBtn">
      <i :class="btnIcon" style="color:white"></i> 
      {{textBtnOpenModal}}
    </b-button>

    <b-modal size="xl" :id="idModa" ref="modal" :title="titleModal" @show="resetModal" @hidden="resetModal" @ok="handleOk" 
    :ok-title = "btnOkLabel">
      <b-container fluid>
        <form ref="form" @submit.stop.prevent="handleSubmit">
          <v-expansion-panels v-model="panel" multiple>
            <v-expansion-panel>
              <v-expansion-panel-header >
                Datos Personales
                 <template v-slot:actions >
                  <i class="fa fa-angle-down" />
              </template>
              </v-expansion-panel-header>
              <v-expansion-panel-content>
                <b-form-group label="Seleccione tipo de persona" v-slot="{ ariaDescribedby }" v-if="enajenante.nacionalidad == 'mexicano'">
                  <b-form-radio-group id="tipoPersona" v-model="enajenante.tipoPersona" :aria-describedby="ariaDescribedby" name="radio-options-tipoPersona">
                    <template #first>
                      <b-form-radio value="pf">Física</b-form-radio>
                    </template>
                    <b-form-radio value="pm">Moral</b-form-radio>
                  </b-form-radio-group>
                </b-form-group>
                <b-row>
                  <b-col v-if="enajenante.tipoPersona == 'pf'" cols="12" md="6">
                    <b-form-group label="CURP" label-for="curp-input" >
                      <b-form-input id="curp-input" name="curp" v-model="$v.form.datosPersonales.curp.$model" aria-describedby="curp-input-feedback" 
                         :state="$v.form.datosPersonales.curp.$dirty ? !$v.form.datosPersonales.curp.$error : null" @change="updateCurp">></b-form-input>
                      <b-form-invalid-feedback id="curp-input-feedback">
                        <span v-if="!$v.form.datosPersonales.curp.required"  class="form-text text-danger">
                          CURP es requerida.
                        </span>
                        <span v-if="!$v.form.datosPersonales.curp.curpPattern"  class="form-text text-danger">
                          La CURP no cumple con la regla de validación.
                        </span>
                        <span v-if="!$v.form.datosPersonales.curp.isUnique"  class="form-text text-danger">
                          Esta CURP ya se encuentra agregada
                        </span>

                      </b-form-invalid-feedback>
                    </b-form-group>
                  </b-col>
                  <b-col cols="12" md="6">
                    <b-form-group label="RFC" label-for="rfc-input" >
                      <b-form-input
                        id="rfc-input" name="rfc" v-model="$v.form.datosPersonales.rfc.$model"  :state="$v.form.datosPersonales.rfc.$dirty ? !$v.form.datosPersonales.rfc.$error : null"  aria-describedby="rfc-input-feedback" 
                      ></b-form-input>
                      <b-form-invalid-feedback id="rfc-input-feedback">
                        <span v-if="!$v.form.datosPersonales.rfc.required"  class="form-text text-danger">
                          El RFC es requerido.
                        </span>
                        <span v-if="!$v.form.datosPersonales.rfc.rfcPattern"  class="form-text text-danger">
                          El RFC no cumple con la regla de validación.
                        </span>
                      </b-form-invalid-feedback>
                    </b-form-group>
                  </b-col>
                  <b-col v-if="enajenante.tipoPersona == 'pm'" cols="12" md="6">
                    <b-form-group label="Razón Social" label-for="razon-social-input" >
                      <b-form-input id="razon-social-input" name="razonSocial" v-model="$v.form.datosPersonales.razonSocial.$model" aria-describedby="razonSocial-input-feedback" 
                         :state="$v.form.datosPersonales.razonSocial.$dirty ? !$v.form.datosPersonales.razonSocial.$error : null" ></b-form-input>
                      <b-form-invalid-feedback id="razonSocial-input-feedback">
                        <span v-if="!$v.form.datosPersonales.razonSocial.required"  class="form-text text-danger">
                          Campo requerido
                        </span>
                      </b-form-invalid-feedback>
                    </b-form-group>
                  </b-col>
                  <b-col v-if="enajenante.tipoPersona == 'pf'" cols="12" md="6">
                    <b-form-group label="Nombre(s)" label-for="nombre-input" >
                     <b-input-group class="mt-3" id="loadingcurp">
                        <template #append v-if="buscandoCurp">
                          <b-input-group-text >
                            <strong class="loadingcurp">
                             <b-spinner label="Loading..." small ></b-spinner>
                            </strong>
                          </b-input-group-text>
                        </template>
                        <b-form-input id="nombre-input" name="nombre" v-model="$v.form.datosPersonales.nombre.$model" aria-describedby="nombre-input-feedback" 
                                             :state="$v.form.datosPersonales.nombre.$dirty ? !$v.form.datosPersonales.nombre.$error : null" 
                                              :disabled="curpEncontrada || buscandoCurp"></b-form-input>
                      </b-input-group>
                      <b-form-invalid-feedback id="nombre-input-feedback">
                        <span v-if="!$v.form.datosPersonales.nombre.required"  class="form-text text-danger">
                          El Nombre es requerido.
                        </span>
                      </b-form-invalid-feedback>
                    </b-form-group>
                  </b-col>
                  <b-col v-if="enajenante.tipoPersona == 'pf'" cols="12" md="6">
                    <b-form-group label="Apellido Paterno" label-for="apPat-input" >
                      <b-input-group class="mt-3" id="loadingcurp">
                        <template #append v-if="buscandoCurp">
                          <b-input-group-text >
                            <strong class="loadingcurp">
                             <b-spinner label="Loading..." small ></b-spinner>
                            </strong>
                          </b-input-group-text>
                        </template>
                        <b-form-input id="apPat-input" name="apPat" v-model="$v.form.datosPersonales.apPat.$model"  :state="$v.form.datosPersonales.apPat.$dirty ? !$v.form.datosPersonales.apPat.$error : null"  aria-describedby="apPat-input-feedback"  :disabled="curpEncontrada || buscandoCurp"></b-form-input>
                      </b-input-group>
                      <b-form-invalid-feedback id="apPat-input-feedback">
                        <span v-if="!$v.form.datosPersonales.apPat.required" class="form-text text-danger">
                          El Apellido Paterno es requerido.
                        </span>
                      </b-form-invalid-feedback>
                    </b-form-group>
                  </b-col>          
                </b-row>
                <b-row v-if="enajenante.tipoPersona == 'pf'"  >
                  <b-col cols="12" md="6">
                    <b-form-group label="Apellido Materno" label-for="apmaterno-input" >
                      <b-input-group class="mt-3" id="loadingcurp">
                        <template #append v-if="buscandoCurp">
                          <b-input-group-text >
                            <strong class="loadingcurp">
                             <b-spinner label="Loading..." small ></b-spinner>
                            </strong>
                          </b-input-group-text>
                        </template>
                        <b-form-input  id="apmaterno-input" name="apmaterno"  v-model="$v.form.datosPersonales.apMat.$model" :disabled="curpEncontrada || buscandoCurp"></b-form-input>
                      </b-input-group>
                    </b-form-group>
                  </b-col> 
                  <b-col  cols="12" md="6">
                    <b-form-group label="Fecha de Nacimiento" label-for="fechaNacimiento-input" >

                      <b-input-group class="mt-3" id="loadingcurp">
                        <template #append v-if="buscandoCurp">
                          <b-input-group-text >
                            <strong class="loadingcurp">
                             <b-spinner label="Loading..." small ></b-spinner>
                            </strong>
                          </b-input-group-text>
                        </template>
                        <b-form-datepicker  class="mb-2" id="fechaNacimiento-input" name="fechaNacimiento"  v-model="$v.form.datosPersonales.fechaNacimiento.$model" :state="$v.form.datosPersonales.fechaNacimiento.$dirty ? !$v.form.datosPersonales.fechaNacimiento.$error : null" aria-describedby="fechaNacimiento-input-feedback" :disabled="curpEncontrada || buscandoCurp"></b-form-datepicker>
                      </b-input-group>
                      <b-form-invalid-feedback id="fechaNacimiento-input-feedback">
                        <span v-if="!$v.form.datosPersonales.fechaNacimiento.required" class="form-text text-danger">
                          La fecha de nacimiento es requerida
                        </span>
                      </b-form-invalid-feedback>
                    </b-form-group>
                  </b-col>        
                  <b-col cols="12" md="4">
                    <b-form-group label="Clave de INE" label-for="claveIne-input" >
                      <b-form-input  id="claveIne-input" name="claveIne"  v-model="$v.form.datosPersonales.claveIne.$model" aria-describedby="claveIne-input-feedback" 
                      :state="$v.form.datosPersonales.claveIne.$dirty ? !$v.form.datosPersonales.claveIne.$error : null" ></b-form-input>
                      <b-form-invalid-feedback id="claveIne-input-feedback">
                        <span v-if="!$v.form.datosPersonales.claveIne.inePattern"  class="form-text text-danger">
                          Clave INE incorrecta.
                        </span>
                      </b-form-invalid-feedback>
                    </b-form-group>
                  </b-col>
                  <b-col></b-col>
                  </b-row>
                <b-row >
                  <b-col>
                    <b-form-group label="Porcentaje de venta" label-for="procentaje-compra-input" >
                      <b-input-group prepend="0" >
                        <template #append>
                          <b-input-group-text >{{porcentajeVenta}}</b-input-group-text>
                        </template>
                        <b-form-input  id="procentaje-compra-range" name="procentaje-compra"  v-model="$v.form.porcentajeCompra.$model" :state="$v.form.porcentajeCompra.$dirty ? !$v.form.porcentajeCompra.$error : null" aria-describedby="porcentajeCompra-input-feedback" type="range" :max="porcentajeVenta"></b-form-input>
                      </b-input-group>
                      <b-form-input  id="procentaje-compra-input" name="procentaje-compra"  v-model="$v.form.porcentajeCompra.$model" :state="$v.form.porcentajeCompra.$dirty ? !$v.form.porcentajeCompra.$error : null" aria-describedby="porcentajeCompra-input-feedback" ></b-form-input>
                      <b-form-invalid-feedback id="porcentajeCompra-input-feedback">
                        <span v-if="!$v.form.porcentajeCompra.required" class="form-text text-danger">
                          Valor requerido
                        </span>
                        <span v-if="!$v.form.porcentajeCompra.between" class="form-text text-danger">
                          El valor debe de estar en un rando de 1 a {{maxProcentajePermitido}};
                        </span>
                      </b-form-invalid-feedback>
                    </b-form-group>
                  </b-col>         
                </b-row>
              </v-expansion-panel-content>
            </v-expansion-panel>
            <v-expansion-panel>
              <v-expansion-panel-header >
                Datos para determinar el impuesto
                 <template v-slot:actions >
                  <i class="fa fa-angle-down" />
              </template>
              </v-expansion-panel-header>
              <v-expansion-panel-content>
                <b-row>
                  <b-col cols="12" md="6">
                    <b-form-group label="GANANCIA OBTENIDA" label-for="ganancia-obtenida-input" >
                      <b-input-group  >
                        <template #prepend>
                          <b-input-group-text >$</b-input-group-text>
                        </template>
                        <b-form-input
                        id="ganancia-obtenida-input" name="gananciaObtenida" v-model="$v.form.datosParaDeterminarImpuesto.gananciaObtenida.$model"  :state="$v.form.datosParaDeterminarImpuesto.gananciaObtenida.$dirty ? !$v.form.datosParaDeterminarImpuesto.gananciaObtenida.$error : null"  aria-describedby="gananciaObtenida-input-feedback" @change="formatoMoneda('gananciaObtenida')" :disabled="configCostos.declararEn0"
                      ></b-form-input>
                      </b-input-group>
                      <b-form-invalid-feedback id="gananciaObtenida-input-feedback">
                        <span v-if="!$v.form.datosParaDeterminarImpuesto.gananciaObtenida.required"  class="form-text text-danger">
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
                          id="pago-provisional-input" name="pagoProvisional" v-model="$v.form.datosParaDeterminarImpuesto.pagoProvisional.$model"  :state="$v.form.datosParaDeterminarImpuesto.pagoProvisional.$dirty ? !$v.form.datosParaDeterminarImpuesto.pagoProvisional.$error : null"  aria-describedby="pagoProvisional-input-feedback" @change="formatoMoneda('pagoProvisional')" :disabled="configCostos.declararEn0"
                        ></b-form-input>
                      </b-input-group>
                      <b-form-invalid-feedback id="pagoProvisional-input-feedback">
                        <span v-if="!$v.form.datosParaDeterminarImpuesto.pagoProvisional.required"  class="form-text text-danger">
                          Campo requerido.
                        </span>
                      </b-form-invalid-feedback>
                    </b-form-group>
                  </b-col>          
                </b-row>

                <b-row>
                  <b-col cols="12" md="6">
                    <b-form-group label="MULTA POR CORRECCION FISCAL" label-for="multa-correccion-fiscal-input" >
                      
                      <b-input-group  > 
                        <template #prepend>
                          <b-input-group-text >$</b-input-group-text>
                        </template>
                        <b-form-input id="multa-correccion-fiscal-input" name="multaCorreccion" v-model="$v.form.datosParaDeterminarImpuesto.multaCorreccion.$model"  :state="$v.form.datosParaDeterminarImpuesto.multaCorreccion.$dirty ? !$v.form.datosParaDeterminarImpuesto.multaCorreccion.$error : null"  aria-describedby="multaCorreccion-input-feedback" 
                          @change="formatoMoneda('multaCorreccion')" :disabled="configCostos.declararEn0"></b-form-input>
                      </b-input-group>
                      <b-form-invalid-feedback id="multaCorreccion-input-feedback">
                        <span v-if="!$v.form.datosParaDeterminarImpuesto.multaCorreccion.required"  class="form-text text-danger">
                          Campo requerido.
                        </span>
                      </b-form-invalid-feedback>
                    </b-form-group>
                  </b-col>
                  <b-col cols="12" md="6">
                    <b-form-group label="MONTO DE OPERACIÓN DEL ENAJENANTE(reportado en el aviso de enajenación)" label-for="monto-operacion-input" >
                      <b-input-group  >
                        <template #prepend>
                          <b-input-group-text >$</b-input-group-text>
                        </template>
                        <b-form-input
                          id="monto-operacion-input" name="montoOperacion" v-model="$v.form.datosParaDeterminarImpuesto.montoOperacion.$model"  
                          @change="formatoMoneda('montoOperacion')"
                          :state="$v.form.datosParaDeterminarImpuesto.montoOperacion.$dirty ? !$v.form.datosParaDeterminarImpuesto.montoOperacion.$error : null"  
                          aria-describedby="montoOperacion-input-feedback" :disabled="configCostos.declararEn0"
                        ></b-form-input>
                      </b-input-group>
                      <b-form-invalid-feedback id="montoOperacion-input-feedback">
                        <span v-if="!$v.form.datosParaDeterminarImpuesto.montoOperacion.required"  class="form-text text-danger">
                          Campo requerido.
                        </span>
                      </b-form-invalid-feedback>
                    </b-form-group>
                  </b-col>          
                </b-row>
                <b-row>

                  <b-col cols="12" md="12" v-if="!$v.form.$anyError && actualizandoDatos">
                       <b-spinner type="grow" label="Spinning"></b-spinner>
                  </b-col>
                  <b-col cols="12" md="12" v-if="!$v.form.$anyError && !actualizandoDatos"> 
                    <calculo-costo-tramite-5-isr-component 
                                          :datosParaDeterminarImpuesto="$v.form.datosParaDeterminarImpuesto.$model" 
                                          :campos="configCostos.campos"
                                          :tramite="configCostos.tramite" 
                                          :tipoPersona="configCostos.tipoPersona" 
                                          @costosObtenidos="costosObtenidos">
                                          </calculo-costo-tramite-5-isr-component>

                     <div class="text-center" v-if="datosCostos">
                          <b-button title="Click para ver detalles" variant="primary" @click="verDetalle =  !verDetalle" class="mr-2 btn btn-block">
                            {{!verDetalle? "Ver detalle " : "Ocultar detalle "}}   
                          </b-button>
                      </div>    
                      <div>
                        <b-card no-body v-if="datosCostos && verDetalle">
                            <b-card-body id="nav-scroller"ref="content "style=" height:300px; overflow-y:scroll;">
                                <b-row v-for="(salida, key) in datosCostos.Salidas" :key="key">
                                    <b-col class="text-left" style="width: 100%" >
                                        <strong>{{ key }}</strong>
                                    </b-col>
                                    <b-col class="text-right" >
                                        <span class="text-muted">   {{ currencyFormat(key, salida) }} </span>
                                    </b-col>
                                </b-row>
                            </b-card-body> 
                        </b-card>

                      </div>                    
                  </b-col>

                </b-row>
              </v-expansion-panel-content>
            </v-expansion-panel>
          </v-expansion-panels>
        </form>
      </b-container>
    </b-modal>

  </div>
</template>

<script>
  import Vue from 'vue';
  import { uuid } from 'vue-uuid';
  import { validationMixin } from 'vuelidate'
  import { required, helpers, between  } from 'vuelidate/lib/validators';
  const rfcPattern = helpers.regex("mob", /^[A-ZÑ&]{3,4}\d{6}(?:[A-Z\d]{3})?$/);
  const rfcMoralPattern = helpers.regex("mob", /^[A-ZÑ&]{3,4}\d{6}$/);

  const curpPattern = helpers.regex("mob", /^[A-Z]{1}[AEIOU]{1}[A-Z]{2}[0-9]{2}(0[1-9]|1[0-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1])[HM]{1}(AS|BC|BS|CC|CS|CH|CL|CM|DF|DG|GT|GR|HG|JC|MC|MN|MS|NT|NL|OC|PL|QT|QR|SP|SL|SR|TC|TS|TL|VZ|YN|ZS|NE)[B-DF-HJ-NP-TV-Z]{3}[0-9A-Z]{1}[0-9]{1}$/);

  const inePattern = helpers.regex("mob", /^[A-Za-z0-9_-]{18,18}$/);  

  export default {
    mixins: [validationMixin],
    props:{
      porcentajeAsignado: {
        default: 0,
        type: Number
      },
      enajenanteEditado:{
        default: null,
        type: Object
      },
      indexEnajenanteEditado:{
        default: null,
        type: Number
      },
      porcentajeVenta:{
         type: [Number, String],
      },listaCurps:{
        type: Array
      },
      configCostos:{
        type: Object
      }
    },

    mounted(){
              
      if(this.enajenanteEditado){
        this.titleModal = "Editar";
        this.btnOkLabel = "Aceptar";
        this.btnIcon = "la la-pencil";
        this.textBtnOpenModal = " ";
        this.classBtn = "btn-info";
      } else {
        this.titleModal = "Agregar";
        this.btnOkLabel = "Aceptar";
        this.btnIcon = "la la-plus";
        this.textBtnOpenModal = "Agregar enajenante";
        this.classBtn = "btn bg-success w-80 mb-4";
      }
    },
    data() {
      return {
        enajenante: {
            nacionalidad:'mexicano',
            tipoPersona:'pf',
        },
        form: {
          porcentajeCompra:1,
          datosPersonales:{
            curp:'',rfc:'', nombre:'', apPat:'', fechaNacimiento:'',razonSocial:'',apMat:'', claveIne:''
          },
          datosParaDeterminarImpuesto:{
            gananciaObtenida:this.formatter('0'),pagoProvisional:this.formatter('0'), multaCorreccion:this.formatter('0'), montoOperacion:this.formatter('0'),
          }
        },
        idModa:  uuid.v4(),
        btnIcon:'',titleModal:'', btnOkLabel:'', textBtnOpenModal:'',classBtn:'',
        maxProcentajePermitido:100,
        panel: [0,1], curpEncontrada:false, buscandoCurp:false,
        datosCostos:false, verDetalle:false, actualizandoDatos:false

      }
    },
    computed:{
        rules(){
            if(this.enajenante.nacionalidad == 'mexicano' && this.enajenante.tipoPersona == 'pf'){
              return {
                porcentajeCompra:{ required,  between: between(1, this.maxProcentajePermitido) },
                datosPersonales:{
                  curp: { 
                    required, 
                    curpPattern,
                    isUnique(value) {
                      if(this.enajenanteEditado){
                        return this.listaCurps.indexOf(value) < 0 || this.enajenanteEditado.datosPersonales.curp == value;  
                      } else {
                        return this.listaCurps.indexOf(value) < 0;
                      }
                      
                    },
                  },
                  rfc: { required, rfcPattern },
                  nombre: { required },
                  apPat: { required },
                  fechaNacimiento:{ required }, 
                  apMat: { required:false },
                  claveIne:{inePattern}
                },
                datosParaDeterminarImpuesto: {
                  gananciaObtenida:{ required },
                  pagoProvisional:{ required },
                  multaCorreccion:{ required }, 
                  montoOperacion:{ required }
                }

              }
            } else if(this.enajenante.nacionalidad == 'mexicano' && this.enajenante.tipoPersona == 'pm'){
              return {

                porcentajeCompra:{ required,  between: between(1, this.maxProcentajePermitido) },
                datosPersonales:{
                  rfc: { required, rfcMoralPattern },
                  razonSocial:{ required }
                },
                datosParaDeterminarImpuesto: {
                  gananciaObtenida:{ required },
                  pagoProvisional:{ required },
                  multaCorreccion:{ required }, 
                  montoOperacion:{ required }
                }
              }
            }
        }
    },
    validations() {
      return {
        form: this.rules
      }
    },
    methods: {
      resetModal() {
        if(!this.enajenanteEditado){
          this.form = { porcentajeCompra:1,
            datosPersonales:{
              curp:'',rfc:'', nombre:'', apPat:'', fechaNacimiento:'', razonSocial:'',apMat:'',claveIne:''
            },                
            datosParaDeterminarImpuesto:{
              gananciaObtenida:this.formatter('0'),pagoProvisional:this.formatter('0'), multaCorreccion:this.formatter('0'), montoOperacion:this.formatter('0'),
            }
          }
        }
      },
      handleOk(bvModalEvt) {
        // Prevent modal from closing
        bvModalEvt.preventDefault()
        // Trigger submit handler
        this.handleSubmit()
      },
      handleSubmit() {

        this.$v.form.$touch()
        // Exit when the form isn't valid
        if (this.$v.form.$anyError) {
          return
        }
        // Push the name to submitted names
        //this.submittedNames.push(this.name)
        let enajenante = this.form;
        enajenante.nacionalidad =  this.enajenante.nacionalidad;
        enajenante.tipoPersona =  this.enajenante.tipoPersona;
        if(enajenante.tipoPersona == 'pf'){
          delete enajenante.datosPersonales.razonSocial;
        } else {
          delete enajenante.datosPersonales.curp;
          delete enajenante.datosPersonales.nombre;
          delete enajenante.datosPersonales.apPat;
          delete enajenante.datosPersonales.fechaNacimiento;
          delete enajenante.datosPersonales.apMat;
          delete enajenante.datosPersonales.claveIne;
        }

        enajenante.detalle = this.datosCostos;

        if(this.enajenanteEditado){
          let response = {
            enajenante:enajenante, 
            index:this.indexEnajenanteEditado
          }
          this.$emit('editaEnajentante', response);
        }else{
          this.$emit('addEnajentante', enajenante);
        }
        // Hide the modal manually
        this.$nextTick(() => {
          this.$v.$reset();
          this.$bvModal.hide(this.idModa)
        })
      },
      openModal(porcentajeAsignado){

       if(this.enajenanteEditado){


            let oldEnajentanteEditado = JSON.parse(JSON.stringify(this.enajenanteEditado));//Object.assign({}, this.enajenanteEditado);
            this.enajenante.nacionalidad = oldEnajentanteEditado.nacionalidad;
            this.enajenante.tipoPersona = oldEnajentanteEditado.tipoPersona;
            
            this.form.datosPersonales.claveIne = oldEnajentanteEditado.datosPersonales.claveIne;
            this.form.datosPersonales.apMat = oldEnajentanteEditado.datosPersonales.apMat;
            this.form.datosPersonales.rfc = oldEnajentanteEditado.datosPersonales.rfc;
            this.form.datosPersonales.curp = oldEnajentanteEditado.datosPersonales.curp;
            this.form.datosPersonales.nombre = oldEnajentanteEditado.datosPersonales.nombre;
            this.form.datosPersonales.apPat = oldEnajentanteEditado.datosPersonales.apPat;
            this.form.datosPersonales.fechaNacimiento = oldEnajentanteEditado.datosPersonales.fechaNacimiento;
            this.form.datosPersonales.razonSocial = oldEnajentanteEditado.datosPersonales.razonSocial;
            this.form.porcentajeCompra= oldEnajentanteEditado.porcentajeCompra;

            this.form.datosParaDeterminarImpuesto.gananciaObtenida = oldEnajentanteEditado.datosParaDeterminarImpuesto.gananciaObtenida;
            this.form.datosParaDeterminarImpuesto.montoOperacion = oldEnajentanteEditado.datosParaDeterminarImpuesto.montoOperacion;
            this.form.datosParaDeterminarImpuesto.multaCorreccion = oldEnajentanteEditado.datosParaDeterminarImpuesto.multaCorreccion;
            this.form.datosParaDeterminarImpuesto.pagoProvisional= oldEnajentanteEditado.datosParaDeterminarImpuesto.pagoProvisional;
            
            this.formatoMoneda('gananciaObtenida');
            this.formatoMoneda('montoOperacion');
            this.formatoMoneda('multaCorreccion');
            this.formatoMoneda('pagoProvisional');


            this.maxProcentajePermitido = parseFloat(this.porcentajeVenta)  - (parseFloat(porcentajeAsignado) - parseFloat(this.form.porcentajeCompra) )  ;
          } else {
            this.maxProcentajePermitido = parseFloat(this.porcentajeVenta) - porcentajeAsignado ;
          }
        this.$bvModal.show(this.idModa);
      },
      async updateCurp(){
        if( !this.$v.form.datosPersonales.curp.$invalid ){         
          this.buscarCurp( this.$v.form.datosPersonales.curp.$model );
        } else {
          this.rellenarForm();
        }
      },
          
      async buscarCurp(curp) {
        this.buscandoCurp = true;
        let url = process.env.TESORERIA_HOSTNAME + "/consultar-curp";
        let response = await axios.get(url + '/' + curp);
        this.buscandoCurp = false;
        if(response.data){
          if(response.data.status == 'error'){
            Command: toastr.error("Error!", response.data.msg);
            this.rellenarForm();
          } else{
            
            this.rellenarForm(response.data);
          }
        } else {
          this.rellenarForm();
        }

      },

      rellenarForm(data){
        if(data){
          this.curpEncontrada = true;
          this.form.datosPersonales.nombre = data.data.nombres;
          this.form.datosPersonales.apPat = data.data.apePat;
          this.form.datosPersonales.apMat = data.data.apeMat;
          this.form.datosPersonales.fechaNacimiento =  data.data.fechaNac.split("/").reverse().join("-");
        } else {
          this.curpEncontrada = false;
          this.form.datosPersonales.nombre = "";
          this.form.datosPersonales.apPat = "";
          this.form.datosPersonales.apMat = "";
          this.form.datosPersonales.fechaNacimiento = "";
        }
      },

      formatter(value){
        const formatter  = new Intl.NumberFormat('en-US', {
          style: 'currency',
          currency: 'MXN',
          minimumFractionDigits: 2
        });
        return formatter.format(value);
      },

      formatoMoneda(name){
        this.actualizandoDatos = true;
        let self = this;
        if(this.$v.form.datosParaDeterminarImpuesto[name].$model){
          let numero = this.formatoNumero(this.$v.form.datosParaDeterminarImpuesto[name].$model);
          this.$v.form.datosParaDeterminarImpuesto[name].$model =  this.formatter(numero);
          
          setTimeout(function(){
            self.actualizandoDatos = false; 
          }, 1000);
        } else{
          return null;
        }
      },

      formatoNumero(numberStr){
          let valor =  Number((numberStr+"").replace(/[^0-9.-]+/g,""));          
          return valor;
      },

      costosObtenidos(res){
        this.datosCostos = false;
        
        if(res.success){
          this.datosCostos = res.respuestaCosto;
        } else {
           Command: toastr.error("Error!", "No fue posible obtener información del impuesto");
        }
        
      },

      currencyFormat(campoName, salida){
          let arr = ["Ganancia Obtenida","Monto obtenido conforme al art 127 LISR",
                      "Pago provisional conforme al art 126 LISR","Impuesto correspondiente a la entidad federativa",
                      "Parte actualizada del impuesto", "Recargos", "Multa corrección fiscal", "Importe total"];
          if(arr.includes(campoName)){
              let text = Vue.filter('toCurrency')(salida);
              return text;
          } else{
              return salida;
          }
      }
    },
    watch: {
      enajenante:{
        handler: function (val, oldVal) {
          if( val.nacionalidad == 'extranjero'){
            delete val.tipoPersona;
          } else {
            val.tipoPersona = val.tipoPersona ? val.tipoPersona : 'pf';
          }
        },
        deep: true

      }
    }
  }
</script>