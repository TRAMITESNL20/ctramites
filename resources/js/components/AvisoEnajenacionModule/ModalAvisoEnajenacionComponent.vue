<template>
    <div>
        <b-button style="color:white" @click="openModal()" >
          <i style="color:white"></i> 
          {{ config.labelBtnAddItem || 'Agregar' }}
        </b-button>
        <b-modal size="lg" :id="idModal" ref="modal" :title="titleModal"  @ok="handleOk" @hidden="resetModal" 
            :ok-title = "btnOkLabel"   no-close-on-backdrop :ok-disabled="formaIvalid">
            <b-container fluid>
                <v-expansion-panels v-model="panel" multiple>
                    <v-expansion-panel>
                        <v-expansion-panel-header >
                            <span class="text-left">Datos Personales</span>  
                            <template v-slot:actions >
                                <i class="fa fa-angle-down" />
                            </template>
                        </v-expansion-panel-header>
                        <v-expansion-panel-content>
                            <formulario-datos-personales-component 
                                @estadoFormulario="estadoFormulario">
                            </formulario-datos-personales-component>
                            <formulario-datos-aviso-enajenacion-component 
                                @estadoFormulario="estadoFormularioPorcentajes">
                            </formulario-datos-aviso-enajenacion-component>  
                        </v-expansion-panel-content>
                    </v-expansion-panel>
                    <v-expansion-panel>
                        <v-expansion-panel-header >
                            <span class="text-left">Dirección de notificación en el estado</span> 
                            <span class="text-muted">(No obligatorio)</span>
                            <template v-slot:actions >
                                <i class="fa fa-angle-down" />
                            </template>
                        </v-expansion-panel-header>
                        <v-expansion-panel-content>
                            <formulario-direccion-notificacion-component @estadoFormulario="estadoFormularioDireccion"></formulario-direccion-notificacion-component>
                        </v-expansion-panel-content>
                    </v-expansion-panel>
                </v-expansion-panels>
            </b-container>
        </b-modal>
    </div>
</template>

<script>
import { uuid } from 'vue-uuid';
export default {
    props: [ 'config'],
    data(){
        return {
            idModal:'mi_id' + this.config.name || uuid.v4() ,
            titleModal: this.config.titleModal || 'Agregar',
            btnOkLabel:'Guardar',
            form: {

            },
            panel: [0],
            formularioDatosPersonalesInvalid:true,
            datosPersonales:{},
            datosPorcentajes: {},
            formularioDatosPorsentajesInvalid:true,
            formularioDireccionInvalid:false,
            datosDireccion:{}
        }
    },

    computed:{
        formaIvalid(){
            return this.formularioDatosPersonalesInvalid || this.formularioDatosPorsentajesInvalid || this.formularioDireccionInvalid;
        }
    },
    mounted() {
        console.log("el MODAL AVISO");
    },
    methods: {
        openModal(){
            this.$bvModal.show(this.idModal);
        },

        handleOk(bvModalEvt){
            bvModalEvt.preventDefault();
            let data = this.datosPorcentajes;
            data.datosPersonales = this.datosPersonales;

            this.$emit('addItem', data);
            this.$nextTick(() => {
                this.$bvModal.hide(this.idModal)
            })
        },

        resetModal(){

        },

        estadoFormulario(validationForm){
            this.formularioDatosPersonalesInvalid = validationForm.$invalid;
            if( !this.formularioDatosPersonalesInvalid ){
                this.datosPersonales = validationForm.form.$model;
            } else {
                this.datosPersonales = {};
            }
        },

        estadoFormularioPorcentajes(validationForm){
                         console.log(JSON.parse( JSON.stringify(validationForm.form.$model) ))
            this.formularioDatosPorsentajesInvalid = validationForm.$invalid;
            if( !this.formularioDatosPorsentajesInvalid ){
                this.datosPorcentajes = validationForm.form.$model;
            } else {
                this.datosPorcentajes = {};
            }
        },

        estadoFormularioDireccion(validationForm){
            this.formularioDireccionInvalid = validationForm.$invalid;
            if( !this.formularioDireccionInvalid ){
                this.datosDireccion = validationForm.form.$model;
            } else {
                this.datosDireccion = {};
            }
        }
    },

}

</script>
