<template>
    <div>
        <b-button style="color:white" @click="openModal()"   :class="classBtn">
          <i style="color:white" :class="btnIcon"></i> 
          {{ labelBtnAddItem  }}
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
                                @estadoFormulario="estadoFormulario" :datosPersonales="datosPersonales">
                            </formulario-datos-personales-component>
                            <formulario-datos-aviso-enajenacion-component 
                                @estadoFormulario="estadoFormularioPorcentajes" :datosPorcentajes="datosPorcentajes" :config="config">
                            </formulario-datos-aviso-enajenacion-component>  
                        </v-expansion-panel-content>
                    </v-expansion-panel>
                    <v-expansion-panel>
                        <v-expansion-panel-header >
                            <span class="text-left">Dirección de notificación en el estado</span> 
                            <span class="text-muted" >
                                {{ config.name == 'vendedor' ? "(No obligatorio)" :  "" }}
                            </span>
                            <template v-slot:actions >
                                <i class="fa fa-angle-down" />
                            </template>
                        </v-expansion-panel-header>
                        <v-expansion-panel-content>
                            <formulario-direccion-notificacion-component @estadoFormulario="estadoFormularioDireccion" :datosDireccion="datosDireccion" :config="config"></formulario-direccion-notificacion-component>
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
    props: [ 'config', 'item', 'indexItem'],
    data(){
        return {
            idModal:'add_id' + this.config.name || uuid.v4() ,
            titleModal: this.config.titleModal || 'Agregar',
            labelBtnAddItem: this.config.labelBtnAddItem || 'Agregar',
            btnOkLabel:'Guardar',
            classBtn : "btn bg-success w-80 mb-4",
            btnIcon: "la la-plus",
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
        if(this.item){
            this.idModal =  'edit_id' + this.config.name + "-index-" + this.indexItem || uuid.v4();
            this.titleModal = this.config.titleModalEdit || 'Editar';
            this.labelBtnAddItem = '';
            this.classBtn = "btn-info";
            this.btnIcon = "la la-pencil"; 
        }
        if(this.config.name == 'comprador'){
            this.panel.push(1);
        }
        
    },
    methods: {
        openModal(){      
            if(this.item){
                this.datosPersonales = Object.assign({}, this.item.datosPersonales);
                this.datosPorcentajes = Object.assign({}, this.item.datosPorcentajes);
                this.datosDireccion = Object.assign({}, this.item.datosDireccion);
            } else {
                this.datosPersonales = {};
                this.datosPorcentajes = {}; 
            }
            this.$bvModal.show(this.idModal);
        },

        handleOk(bvModalEvt){
            bvModalEvt.preventDefault();
            let data = {};
            data.datosPersonales = this.datosPersonales;
            data.datosPorcentajes = this.datosPorcentajes;
            data.datosDireccion =  this.datosDireccion;

            if(this.item){
              let response = {
                item:data, 
                index:this.indexItem
              }
              this.$emit('editItem', response);
            }else{
              this.$emit('addItem', data);
            }
            this.$nextTick(() => {
                this.$bvModal.hide(this.idModal)
            })
        },

        resetModal(){

        },

        estadoFormulario(validationForm){
            this.formularioDatosPersonalesInvalid = validationForm.$invalid;
            if( !this.formularioDatosPersonalesInvalid ){
                this.datosPersonales = validationForm.form.$model ;
            } else {
                this.datosPersonales = {};
            }
        },

        estadoFormularioPorcentajes(validationForm){
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
