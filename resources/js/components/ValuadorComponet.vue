<template>
  <div>
    <form ref="form" @submit.stop.prevent="handleSubmit">
      <b-form-group label="¿Cuenta con avalúo?" v-slot="{ ariaDescribedby }">
        <b-form-radio-group id="tipoPersona" v-model="isValuable" :aria-describedby="ariaDescribedby" name="radio-options-tipoPersona" @change="validar">
          <template #first>
            <b-form-radio :value="true">Si</b-form-radio>
          </template>
          <b-form-radio :value="false">No</b-form-radio>
        </b-form-radio-group>
      </b-form-group>
      <b-row v-if="isValuable">
        <b-col  cols="12" md="6">
          <b-form-group label="Nombre" label-for="nombre-input" >
            <b-form-input id="nombre-input" name="nombre" v-model="$v.form.datosValuo.valuador.nombre.$model" style="background-color: #e5f2f5 !important" placeholder="Nombre" @change="validar" 
            :state="$v.form.datosValuo.valuador.nombre.$dirty ? !$v.form.datosValuo.valuador.nombre.$error : null"  aria-describedby="nombre-input-feedback"></b-form-input>
            <small id="nombre-input-feedback"  class="position-absolute">
              <p v-if="!$v.form.datosValuo.valuador.nombre.required"  class="form-text text-danger">
                El Nombre es requerido.
              </p>
            </small>
          </b-form-group>
        </b-col>
        <b-col cols="12" md="6">
          <b-form-group label="Apellido paterno" label-for="apPat-input" >
            <b-form-input id="apPat-input" name="apPat" v-model="$v.form.datosValuo.valuador.apPat.$model" style="background-color: #e5f2f5 !important" placeholder="Apellido paterno" 
            @change="validar" :state="$v.form.datosValuo.valuador.apPat.$dirty ? !$v.form.datosValuo.valuador.apPat.$error : null"  aria-describedby="apPat-input-feedback"></b-form-input>
              <small id="apPat-input-feedback"  class="position-absolute">
                <p v-if="!$v.form.datosValuo.valuador.apPat.required"  class="form-text text-danger">
                  Apellido paterno
                </p>
              </small>
          </b-form-group>
        </b-col>
        <b-col cols="12" md="6">
          <b-form-group label="Apellido materno" label-for="apMat-input" >
            <b-form-input id="apMat-input" name="apMat" v-model="$v.form.datosValuo.valuador.apMat.$model" style="background-color: #e5f2f5 !important" placeholder="Apellido materno" @change="validar"></b-form-input>
          </b-form-group>
        </b-col> 
        <b-col cols="12" md="6">
          <b-form-group label="RFC" label-for="rfc-input" >
            <b-form-input id="rfc-input" name="rfc" v-model="$v.form.datosValuo.valuador.rfc.$model" style="background-color: #e5f2f5 !important" placeholder="RFC" @change="validar" 
             :state="$v.form.datosValuo.valuador.rfc.$dirty ? !$v.form.datosValuo.valuador.rfc.$error : null"  aria-describedby="rfc-input-feedback"></b-form-input>
              <small id="rfc-input-feedback"  class="position-absolute">
                        <span v-if="!$v.form.datosValuo.valuador.rfc.required"  class="form-text text-danger">
                          El RFC es requerido.
                        </span>
                        <span v-if="!$v.form.datosValuo.valuador.rfc.rfcPattern"  class="form-text text-danger">
                          El RFC no cumple con la regla de validación.
                        </span>
              </small>
          </b-form-group>
        </b-col>          
      </b-row>
    </form> 
   
  </div>
</template>
  
<script>
    import Vue from 'vue';
    import { validationMixin } from 'vuelidate';
    import { required, helpers  } from 'vuelidate/lib/validators';
    const rfcPattern = helpers.regex("mob", /^[A-ZÑ&]{3,4}\d{6}(?:[A-Z\d]{3})?$/);
    export default {
      mixins: [validationMixin],
      props: ['campo', 'estadoFormulario', 'showMensajes'],
      data() {
        return {
          isValuable:false,
          form:{
            datosValuo: {
              valuador:{
                nombre:"", apPat:"", apMat:"", rfc:""
              }
            },
          }
        }
      },
      created() {
        if(this.campo.valor && this.campo.valor.datosValuo){
            this.form = {
              datosValuo : this.campo.valor.datosValuo,
             
            }
          this.isValuable= this.campo.valor.isValuable
        }
        this.validar();
      },
      validations() {
          return {
            form: this.rules
          }
      },
      computed:{
        rules(){
            if(this.isValuable){
              return {
                  datosValuo:{ 
                    valuador: { 
                      nombre:{required },
                      apPat:{required },
                      apMat:{required:false },
                      rfc:{required,rfcPattern }
                    }
                }
              }
            } else {
              return {
                  datosValuo:{ valuador: { 
                      nombre:{required:false },
                      apPat:{required:false  },
                      apMat:{required:false },
                      rfc:{required:false  }
                    }
                }
              }
          }
        }
      },
      methods: {
        handleSubmit(){

        },
        validar(){
          this.campo.valido =  !this.$v.form.$invalid;
          let valor = { isValuable:this.isValuable};

          if(this.isValuable){
            valor.datosValuo = this.form.datosValuo
          }

          this.campo.valor = valor;
          this.$emit('updateForm', this.campo);          
      
        },
      }
    }
</script>