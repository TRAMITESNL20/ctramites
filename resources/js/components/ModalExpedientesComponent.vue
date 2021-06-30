<template>
  <div>
    <b-button style="color:white" @click="openModal()" :class="classBtn">
      <i :class="btnIcon" style="color:white"></i> 
      Agregar expediente
    </b-button>

    <b-modal size="xl" :id="idModa" ref="modal" :title="titleModal" @show="resetModal" @hidden="resetModal" @ok="handleOk" 
    :ok-title = "btnOkLabel" :ok-disabled="!direccion.datos_direccion || buscandoInsumos || buscandoDatosDomicilio">
      <b-container fluid>
        <form ref="form" @submit.stop.prevent="handleSubmit">
          <b-row>
            <b-col cols="" md="4" >
              <b-form-group label="Folio del aviso de enajenación o folio forma" label-for="folio-input" >
                <b-input-group size="lg"> 
                  <b-form-input
                    id="folio-input" name="folio" v-model="$v.form.folio.$model"  :state="$v.form.folio.$dirty ? !$v.form.folio.$error : null"  aria-describedby="folio-input-feedback"
                    @change="getMontoOperacion" v-uppercase></b-form-input>
                  <template #append>
                    <b-input-group-text v-b-tooltip.hover title="Localizado en el informativo de valor catastral o aviso de enajenación" style="font-size: 7px;" >
                      <i class="fa fa-question-circle" style="color: cornflowerblue;"></i>
                    </b-input-group-text>
                  </template>
                </b-input-group>
                <b-form-invalid-feedback id="folio-input-feedback">
                  <span v-if="!$v.form.folio.required"  class="form-text text-danger">
                    El Folio es requerido.
                  </span>
                </b-form-invalid-feedback>
              </b-form-group>
            </b-col>
            <b-col cols="" md="4" >
              <b-form-group label="Estado" label-for="estado-select" >
                <multiselect id="estado-select" v-model="$v.form.estado.$model" :options="estados" label="nombre" track-by="clave" 
                :searchable="true" @input="getMunicipios" :state="$v.form.estado.$dirty ? !$v.form.estado.$error : null"  aria-describedby="estado-select-feedback" 
                :disabled="desabilitarSelecEstados" ></multiselect>
                <b-form-invalid-feedback id="estado-select-feedback">
                  <span v-if="!$v.form.estado.required"  class="form-text text-danger">
                    Estado requerido.
                  </span>
                </b-form-invalid-feedback>
              </b-form-group>
            </b-col>
            <b-col cols="" md="4" >
              <b-form-group label="Municipio" label-for="municipio-select" >
                <multiselect id="municipio-select" v-model="$v.form.municipio.$model" :options="municipios" label="nombre" track-by="clave" 
                :searchable="true" :state="$v.form.municipio.$dirty ? !$v.form.municipio.$error : null"  aria-describedby="municipio-select-feedback"  @input="setValMunicipio"></multiselect>
                <b-form-invalid-feedback id="municipio-select-feedback">
                  <span v-if="!$v.form.municipio.required"  class="form-text text-danger">
                    Municipio requerido.
                  </span>
                </b-form-invalid-feedback>
              </b-form-group>
            </b-col>
            <b-col cols="" md="4" >
              <b-form-group label="No. Expediente Catastral" label-for="expediente-input" >
                <b-input-group size="lg"> 
                  <template #prepend>
                    <b-input-group-text >{{clave}}</b-input-group-text>
                  </template>
                  <b-form-input v-mask="'##-###-###'"
                    id="expediente-input" name="expediente" v-model="$v.form.expediente.$model"  :state="$v.form.expediente.$dirty ? !$v.form.expediente.$error : null"  aria-describedby="expediente-input-feedback" @change="getDatosDomicilio"
                    :disabled="$v.form.municipio.$invalid"></b-form-input>
                </b-input-group>
                <b-form-invalid-feedback id="expediente-input-feedback">
                  <span v-if="!$v.form.expediente.required"  class="form-text text-danger">
                    El Expediente es requerido.
                  </span>
                </b-form-invalid-feedback>
              </b-form-group>
            </b-col>
          </b-row>        
        </form>
        <transition name="slide-fade">
          <div v-if="!buscandoDatosDomicilio">
            <div class="card" v-if="direccion" key="yes">
              <div class="card-body">           
                <h6 class="pt-3 pl-3">Datos Catastro</h6>
                <hr>
                <div class="overflow-auto" style="height:350px;">
                    <tree-component
                      class="item"
                      :item="direccion"
                    ></tree-component>
                </div>
              </div>
            </div>
            <div v-else-if="$v.form.expediente.$dirty && !$v.form.expediente.$invalid && !(direccion && direccion.datos_direccion)" key="no">
              Ubicación No encontrada
            </div>
          </div>
          <div v-if="buscandoDatosDomicilio" class="text-center">
            <b-spinner variant="primary" type="grow" label="Spinning"></b-spinner>
          </div>
        </transition>
      </b-container>
    </b-modal>

  </div>
</template>

<script>
  import Vue from 'vue';
  import { uuid } from 'vue-uuid';
  import { validationMixin } from 'vuelidate'
  import { required, helpers, between  } from 'vuelidate/lib/validators';
  import Multiselect from 'vue-multiselect';
  import VueMask from 'v-mask';
  Vue.use(VueMask);
  export default {
    components: { Multiselect },
    mixins: [validationMixin],
    props:{
      usuario:{
        type:Object
      }
    },
    mounted(){
        this.titleModal = "Agregar";
        this.btnOkLabel = "Agregar";
        this.btnIcon = "la la-plus";
        this.textBtnOpenModal = "Agregar enajenante";
        this.classBtn = "btn bg-success w-80 mb-4 btn-block";

        
        this.getEstados();
    },
    data() {
      return {
        direccion:{},
        form: {
          expediente:'', estado:{ "clave": "19", "nombre": "NUEVO LEÓN" }, municipio:{ "clave": "70", "nombre": "MONTERREY", "claveEstado": "19" }, folio:''
        },
        idModa:  uuid.v4(),
        btnIcon:'',titleModal:'', btnOkLabel:'', textBtnOpenModal:'',classBtn:'',
        estados:[], municipios:[], clave: "70",
        desabilitarSelecEstados:true,
        insumos:{},
        buscandoDatosDomicilio:false,
        buscandoInsumos:false
      }
    },
    computed:{
        rules(){
          return {
            expediente: { required },
            estado: { required }, municipio: { required },folio:{required}
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
        
        this.form = { 
            expediente:'', estado:{ "clave": "19", "nombre": "NUEVO LEÓN" }, municipio:{ "clave": "70", "nombre": "MONTERREY", "claveEstado": "19" }, folio:''
        }
        this.clave = this.$v.form.municipio.$model.clave;
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
        let expediente = this.form;
        expediente.direccion =  this.direccion;
        expediente.expediente = this.$v.form.expediente.$model;
        expediente.insumos = this.insumos;
        //expediente.folio = this.$v.form.folio.$model;
        this.$emit('addExpediente', expediente);

        // Hide the modal manually
        this.$nextTick(() => {
          this.resetModal();
          this.$v.$reset();
          this.$bvModal.hide(this.idModa)
        })
      },
      openModal(porcentajeAsignado){
        this.direccion = {};
        this.insumos = {};

        this.$bvModal.show(this.idModa)
      },
      async getDatosDomicilio(){
        this.buscandoDatosDomicilio = true;
        if( !this.$v.form.expediente.$invalid ){         
          this.buscarDatosDomicilio( this.$v.form.expediente.$model );

          this.getMontoOperacion();
        } else {
          this.buscandoDatosDomicilio = false;
          this.rellenarForm();

        }
      },

      async getMontoOperacion(){
        this.valorOperacion( this.$v.form.expediente.$model );
      },

      async buscarDatosDomicilio(nExpediente) {
        let url = process.env.TESORERIA_HOSTNAME + "/insumos-catastro-consulta" ;
        let response = await axios.get(url + '/' + this.clave + nExpediente.split("-").join(""));
        if(response.data){
          this.rellenarForm(response.data);
        } else {
          this.rellenarForm();
        }
        this.buscandoDatosDomicilio = false;
      },

      rellenarForm(data){
        if(data){
          delete data.cta_valida;
          delete data.vinculado_rpp;
          delete data.bloqueado;
          delete data.datos_propietarios;
          delete data.datos_informacion_validada;
          delete data.porcentaje_validacion;
          delete data.descripcion_del_predio;
          this.direccion = data;
        } else {
          this.direccion = {};
        }
      },

      async getEstados(){
          let url = process.env.TESORERIA_HOSTNAME + "/obtener-estados" ; 
          let options = await this.obtenerOptions(url);
          this.estados = options; 
          this.getMunicipios();
      },

      async obtenerOptions(url){
        let response = await axios.get(url);
        let options = response.data ? response.data : [];
        return options;
      },

      async getMunicipios(){
        
        if(this.$v.form.estado.$model.clave){
          let clave = this.$v.form.estado.$model.clave;
          let url =  process.env.TESORERIA_HOSTNAME + "/obtener-municipios/" + clave ;  
          let options = await this.obtenerOptions(url);
          this.municipios = options.map( option => {
            option.claveEstado = clave;
            option.nombre = option.nombre.toUpperCase();
            return option;
          }); 
        }
      },

      setValMunicipio(){
        this.clave = this.$v.form.municipio.$model.clave;
        this.getDatosDomicilio();
      },

      async valorOperacion(nExpediente){
        if( this.usuario && this.usuario.notary  ){
          if(this.$v.form.folio.$model){
            this.buscandoInsumos = true;
            let url = process.env.TESORERIA_HOSTNAME + "/insumos-montos";
            let params = {
              expediente:this.clave + nExpediente.split("-").join(""),
              folio:this.$v.form.folio.$model,
              id_notaria: this.usuario.notary.notary_number
            }
            let response = await axios.get(url , {params} );
            if(response.data){
              this.insumos = response.data;
            } else {
              this.insumos = false;
            }
            this.buscandoInsumos = false;
          } else {
            this.insumos.status = false;
            this.insumos.msg = "Ingrese Folio";
          }
        } else {
          console.warn("El usuario no tiene asignada una notaria")
        }
      }
    }
  }
</script>