<template>
        <div class="container">
            <div class="card">
                 <div class="card-header">
                    Resumen
                 </div>
                <div class="card-body">
                    <b-row v-if="vendedores && vendedores.length > 0 ">
                        <div class="col-sm-12">
                            <h2 class="border-bottom my-3">Vendedores</h2>
                        </div>
                        <div class="col-sm-12">
                            <table-component :config="configVendedor" :itemsPre="vendedores"></table-component>
                        </div>
                    </b-row> 
                    <b-row v-if="compradores && compradores.length > 0 ">
                        <div class="col-sm-12">
                            <h2 class="border-bottom my-3">Compradores</h2>
                        </div>
                        <div class="col-sm-12">
                            <table-component :config="configComprador" :itemsPre="compradores"></table-component>
                        </div>
                    </b-row> 
                </div>
            </div>
        </div>

</template>
<script>

    import Vue from 'vue'

    export default {

        props: ['tipoTramite'],
        data(){
            return {
                datosFormulario:{},
                vendedores:[],
                compradores:[],
                configComprador:{
                    name:'comprador',
                    onlyRead:true
                },
                configVendedor:{
                    name:'vendedor',
                    onlyRead:true
                },
                tramite:{}
            }
        },
        mounted() {
            this.obtenerInformacionDelTramite();

            this.camposGenerales = this.datosFormulario.campos;
            let campoDatos= this.camposGenerales.find( campo =>  campo.tipo == 'table');
            this.compradores = campoDatos.valor.seccionCompradores.compradores;
            this.vendedores = campoDatos.valor.seccionVendedores.vendedores;
            delete this.datosFormulario.tipoPersona;
            delete this.datosFormulario.tipo_costo_obj;
            delete this.datosFormulario.tipo;
            delete this.datosFormulario.tipoTramite;
            localStorage.setItem('datosFormulario', JSON.stringify(this.datosFormulario)); 

            this.tramite.detalle = { costo_final:0 };
            localStorage.setItem('tramite', JSON.stringify(this.tramite)); 
        },


  
        methods: {

            obtenerInformacionDelTramite(){
                let informacionEnStorage = ["datosFormulario", "listaSolicitantes", "tramite"];
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



        }
    }
</script>

