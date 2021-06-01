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
                <table-component :config="configVendedor" @listaItems="listaVendedores" :itemsPre="seccionVendedores.vendedores"></table-component>
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
            <b-col cols="12">
                Compradores 
                <table-component :config="configComprador" @listaItems="listaCompradores" :itemsPre="seccionCompradores.compradores"></table-component>

                <b-alert show variant="warning" v-if=" seccionVendedores.porcentajeVenta != seccionCompradores.porcentajePropiedad ">
                    <strong>
                        Información!
                    </strong>
                    El porcentaje de compra tiene que ser igual al porcentaje de venta.
                </b-alert> 
                % vENTA: {{seccionVendedores.porcentajeVenta }}
                <br/>
                PROPIEDAD  {{ seccionCompradores.porcentajePropiedad }}
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
                name:'vendedor',
                //isComprador:false
            },
            configComprador:{
                labelBtnAddItem:'Agregar Comprador',
                titleModal:'Agregar Comprador',
                name:'comprador',
                //isComprador:true
            },
            seccionVendedores:{
                vendedores:[],
                porcentajeUsufructo:0,
                porcentajePropiedad:0,
                porcentajeVenta:0
            },
            seccionCompradores:{
                compradores:[],
                porcentajePropiedad:0,
                porcentajeUsufructo:0
            }
        }
    },
    created() {
        avisoEnajenacionService.gettInfoCatastro(this.expediente).then(response => {
          console.log( JSON.parse( JSON.stringify( response.data) ) );
        }).catch(errors => {
          Command: toastr.error("Error!", error.message || "No fue posible obtener informacion del catastro");
        }).finally(() => {
          this.informacion = "";
        });

        if(this.campo.valor){
            if(this.campo.valor.seccionVendedores.vendedores.length > 0){
                this.seccionVendedores = this.campo.valor.seccionVendedores;
            }
            if(this.campo.valor.seccionCompradores.compradores.length > 0){
                this.seccionCompradores = this.campo.valor.seccionCompradores;
            }
            
            
        }
        this.validar();

    },
    methods: {
        listaVendedores(data){
            this.seccionVendedores.vendedores = data.items;
            this.seccionVendedores.porcentajePropiedad = data.config.porcentajePropiedadAsignado;
            this.seccionVendedores.porcentajeUsufructo = data.config.porcentajeUsufructoAsignado;
            this.seccionVendedores.porcentajeVenta = data.config.porcentajeVentaAsignado;

            this.validar();
        },

        listaCompradores(data){
            this.seccionCompradores.compradores = data.items;
            this.seccionCompradores.porcentajePropiedad = data.config.porcentajePropiedadAsignado;
            this.seccionCompradores.porcentajeUsufructo = data.config.porcentajeUsufructoAsignado;

            this.validar();
        },

        validar(){
            this.campo.valido =  this.seccionVendedores.porcentajePropiedad == 100 &&  this.seccionVendedores.porcentajeUsufructo == 100 && this.seccionCompradores.porcentajePropiedad == 100;
            let valor = {seccionVendedores:this.seccionVendedores, seccionCompradores: this.seccionCompradores};
            this.campo.valor = valor;
            this.$emit('updateForm', this.campo); 
        }
    },

}

</script>
