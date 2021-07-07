<template>
    <div>
        <pre>
            {{expediente}}
        </pre>
        <div v-if="expediente">   
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
                    <b-alert show variant="warning" v-if="seccionVendedores.vendedores.length > 0 && (seccionVendedores.porcentajeUsufructo != 100 || seccionVendedores.porcentajePropiedad != 100 || !seccionVendedores.porcentajeVenta || seccionVendedores.porcentajeVenta < 0)">
                        <strong>
                            Información!
                        </strong>
                        Los siguientes detalles deben de tomarse en cuenta:
                        <ul>
                            <li>La suma de los Porcentajes de propiedad debe ser un 100%</li>
                            <li>La suma de los usufructos debe de ser de 100%</li>
                            <li>Debe de existir un porcentaje de venta</li>
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
                </b-col>
            </b-row>
        </div>
        <div  style="padding-top: 10px; min-height: 600px;" class="content d-flex flex-column flex-column-fluid" v-if="!expediente">
            <div >
                <div class="card" style="width: 100%;">
                    <div class="card-body text-center">
                        <h5 class="card-title" >Seleccione expediente</h5>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</template>

<script>
import Vue from 'vue';
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
            },
            datosCatastrales:[],
            datosFormulario:[]
        }
    },
    created() {
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

    mounted(){

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
            this.campo.valido =  this.seccionVendedores.porcentajePropiedad == 100 &&  this.seccionVendedores.porcentajeUsufructo == 100 && this.seccionCompradores.porcentajePropiedad == 100 && this.seccionVendedores.porcentajeVenta && this.seccionVendedores.porcentajeVenta > 0; 
            let valor = {seccionVendedores:this.seccionVendedores, seccionCompradores: this.seccionCompradores, datosCatastrales:[]};
            this.campo.valor = valor;
            this.$emit('updateForm', this.campo); 
        },

        async getInformacion(){
            let self =  this;
            if(this.expediente){
                try {
                    let response = await avisoEnajenacionService.gettInfoCatastro(this.expediente);
                    let datosPropietarios = response.data ? response.data.datos_propietarios : [];
                    this.datosCatastrales = response.data ? response.data.datos_catastrales : [];

                    if(this.datosCatastrales && this.datosCatastrales.length > 0){
                        this.obtenerInformacionDelTramite();
                        let camposGenerales = this.datosFormulario.campos;
                        
                        let campoValorCatastral = camposGenerales.find( campo =>  campo.nombre == 'Valor catastral');
                       
                        //campoValorCatastral.valor = Vue.filter('formatoMoneda')( datosCatastrales[0].valor_catastral );
                        $("#"  + campoValorCatastral.campo_id).val( Vue.filter('formatoMoneda')( this.datosCatastrales[0].valor_catastral));
                        $("#"  + campoValorCatastral.campo_id).prop('disabled', true);
                    }

                    if( datosPropietarios && datosPropietarios.length > 0){
                        

                        let vendedores = datosPropietarios.map( (propietario) => {
                            let vendedor = { datosPersonales:{}, datosPorcentajes:{} };
                            vendedor.datosPersonales.nacionalidad =  propietario.clasePro == "PERSONA FÍSICA" || propietario.clasePro == "PERSONA MORAL"  ? "mexicana" : "extrajero"; 
                            vendedor.datosPersonales.tipoPropietario = propietario.tipoPro.toLowerCase(); 
                            if(vendedor.datosPersonales.nacionalidad == 'mexicana'){
                                if(propietario.clasePro == "PERSONA FÍSICA"){
                                    vendedor.datosPersonales.tipoPersona = 'pf';
                                    vendedor.datosPersonales.curp = propietario.id_propietario;
                                    vendedor.datosPersonales.rfc = "";
                                    vendedor.datosPersonales.nombre = propietario.nombrePro;
                                    vendedor.datosPersonales.apPat = propietario.apePat;
                                    vendedor.datosPersonales.apMat = propietario.apeMat;
                                    if(propietario.fecha_nac){
                                        vendedor.datosPersonales.fechaNacimiento = propietario.fecha_nac.split("/").reverse().join("-");
                                    } else {
                                        vendedor.datosPersonales.fechaNacimiento = '';
                                    }
                                    vendedor.datosPersonales.genero = propietario.sexo;
                                    vendedor.datosPersonales.estado = propietario.entidad;
                                } else if(propietario.clasePro == "PERSONA MORAL") {
                                    vendedor.datosPersonales.tipoPersona = 'pm';
                                    vendedor.datosPersonales.razonSocial = propietario.nombrePro;
                                    vendedor.datosPersonales.rfc = ""
                                }
                            }

                            vendedor.datosPorcentajes.porcentajePropiedad = Number(propietario.nuda).toFixed(this.$const.PRECISION);
                            vendedor.datosPorcentajes.porcentajeUsufructo = Number(propietario.usufructo).toFixed(this.$const.PRECISION);
                            vendedor.datosPorcentajes.porcentajeVenta = 0;
                         
                            return vendedor;
                        });
                        this.seccionVendedores.vendedores = vendedores;
                    
                    }
                } catch (error) {

                }
            }           
        },

        obtenerInformacionDelTramite(){
            let informacionEnStorage = ["datosFormulario"];
            informacionEnStorage.forEach( name => {
                if (localStorage.getItem(name)) {
                  try {
                    this[name] = JSON.parse(localStorage.getItem(name));
                  } catch(e) {
                    letocalStorage.removeItem(name);
                  }
                }
            });
        },
    },

    watch: {
        expediente (newValue, oldValue) {
            if(newValue && newValue == oldValue){
                if(this.seccionVendedores && this.seccionVendedores.vendedores.length < 0){
                    console.log("hello")
                    this.getInformacion();
                }
            } else {

                this.seccionVendedores = {
                    vendedores:[],
                    porcentajeUsufructo:0,
                    porcentajePropiedad:0,
                    porcentajeVenta:0
                };
                this.seccionCompradores = {
                    compradores:[],
                    porcentajePropiedad:0,
                    porcentajeUsufructo:0
                }; 

                this.datosCatastrales = [];
                this.datosFormulario = [];

                this.validar();
            }
        }
    },

}

</script>
