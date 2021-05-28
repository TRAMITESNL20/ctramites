<template>
    <div>
        <b-row>
            <b-col cols="12" class="text-center">
                <b-alert show variant="light">
                    Expediente catastral: {{expediente}}
                </b-alert>
            </b-col>  
        </b-row>
        <b-row>
            <b-col cols="12">
                Propietarios y Vendedores
                <table-component type="vendedor" :config="configVendedor"></table-component>
            </b-col>
            <b-col cols="12" v-if="false">
                Compradores 
                <table-component type="comprador" :config="configComprador"></table-component>
            </b-col>
        </b-row>
    </div>
</template>

<script>
import avisoEnajenacionService from '../../services/avisoEnajenacion.services.js';
export default {
    props: [ 'expediente', 'campo'],
    data(){
        return {
            configVendedor:{
                labelBtnAddItem:'Agregar Vendedor',
                titleModal:'Agregar Vendedor',
                name:'vendedor'
            },
            configComprador:{
                labelBtnAddItem:'Agregar Comprador',
                titleModal:'Agregar Comprador',
                name:'comprador'
            },
        }
    },
    mounted() {
        avisoEnajenacionService.gettInfoCatastro(this.expediente).then(response => {
          console.log( JSON.parse( JSON.stringify( response.data) ) );
        }).catch(errors => {
          Command: toastr.error("Error!", error.message || "No fue posible obtener informacion del catastro");
        }).finally(() => {
          this.informacion = "";
        });
    },
    methods: {

    },

}

</script>
