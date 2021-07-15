<template>
    <div>
        <div v-if="tramite.status == 80">
            <button type="button" title="Quitar"  class="btn font-weight-bolder text-uppercase text-white" @click="eliminar()" :disabled="eliminando">
              <span><i class="fas fa-trash" style="color:#808080;"></i> </span>
            </button>
        </div>
    </div>
</template>

<script>
import Vue from 'vue';

import Dialog from 'bootstrap-vue-dialog';
Vue.use(Dialog)
export default {
    props:['tramite'],
    data(){
        return {
            eliminando:false
        }
    },
    methods: {
        eliminar(){
            this.$dialog.confirm({ text: '¿Esta seguro de eliminar de borrador ' + this.tramite.nombre_servicio + ' ?',

                actions: [{
                    text: 'No',
                    color: 'red',
                    class:'danger',
                    key: false,
                    handle: () => {

                    }
                },{
                    text: 'Si',
                    color: 'blue',
                    key: true,
                    handle: () => {
                        this.cambiarStatus();
                    }
                }]
            })
        },

        cambiarStatus(){
            this.eliminando = true;
            let data ={
                ids_tickets:[{id:this.tramite.id}],
                status:0
            }

            let url = process.env.TESORERIA_HOSTNAME + "/solicitudes-update";
            axios.post(url, data, {
                 headers:{
                    "Content-type":"application/json"
                }
            } ).then(response => {
                this.$emit('responseDelete', response);
            }).catch((err)=>{
                Command: toastr.error("Error!", "No fue posible eliminar");
            }).finally( () => {
                this.eliminando = false;
            });
        }
    }
}
</script>
