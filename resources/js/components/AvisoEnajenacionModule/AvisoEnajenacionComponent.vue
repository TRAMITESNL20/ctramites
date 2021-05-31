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
                <table-component type="vendedor" :config="configVendedor" @listaItems="listaVendedores" :itemsPre="seccionVendedores.vendedores"></table-component>
                <b-alert show variant="warning" v-if="seccionVendedores.vendedores.length > 0 && (seccionVendedores.porcentajeUsufructo != 100 || seccionVendedores.porcentajePropiedad != 100)">
                    <strong>
                        Información!
                    </strong>
                    Los siguientes detalles deben de tomarse en cuenta:
                    <ul>
                        <li>La suma de los Porcentajes de propiedad debe ser un 100%</li>
                        <li>La suma de los usufructos debe de ser de 100%</li>
                    </ul>

                </b-alert> 
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
            seccionVendedores:{
                vendedores:[],
                porcentajeUsufructo:0,
                porcentajePropiedad:0
            }
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

        if(this.campo.valor){
            this.seccionVendedores.vendedores = this.campo.valor.seccionVendedores.vendedores;
            this.seccionVendedores.compradores = this.campo.valor.seccionVendedores.compradores;
        }

    },
    methods: {
        listaVendedores(data){
            this.seccionVendedores.vendedores = data.items;
            this.seccionVendedores.porcentajePropiedad = data.config.porcentajePropiedadAsignado;
            this.seccionVendedores.porcentajeUsufructo = data.config.porcentajeUsufructoAsignado;

            this.validar();
        },

        validar(){
            this.campo.valido =  this.seccionVendedores.porcentajePropiedad == 100 &&  this.seccionVendedores.porcentajeUsufructo == 100;
            let valor = {seccionVendedores:this.seccionVendedores};
            this.campo.valor = valor;
            this.$emit('updateForm', this.campo); 
        }
    },

}

</script>
