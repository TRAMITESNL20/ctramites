<template>
  <div>
        <div class="pt-10 pl-10 pr-10"  style="background-color:white" v-if=" (tramitesdoc).length > 0 && docFirmado != 1">
            <div >
                <p> El tramite seleccionado no cuenta con los documentos de CALCULO DEL ISR CONFORME AL 126 LISR y SAT </p>
                <modal-document-component 
                    :tramitesdoc="tramitesdoc" 
                    :idtramites="usuario"
                    @updatedTramites="updatedTramitesMethod"
                    >
                </modal-document-component>
            </div>
        </div>
                                                   
        <div class="p-10" style="background-color:white"  v-for="idTramite in solicitudes" v-if="docFirmado != 1">
            <firma-electronica-component 
                :usuario="idTramite"   
                :user="user"  
                @docFirmado="docFirmadoMethod"
                @docFirmadosListos="urlFirmadoListoMethod"
                >
            </firma-electronica-component>
        </div>

            <vue-pdf-component class="pt-10" 
                    :urlSourceListo="urlFirmadoListo" 
            >  
            </vue-pdf-component>
        
  </div>

</template>

<script>
export default {
    props: ['usuario', 'tramitesdoc' , 'user'],
    mounted(){

    },
    data(){
        return {
            urlFirmadoListo: [],
            docFirmado:'',
            clonedData: '',
            solicitudes : this.usuario
        }
    },
    methods: {
        urlFirmadoListoMethod(urlArray) {
           this.urlFirmadoListo = urlArray; 
        },
        // urlFirmadoPendienteMethod(urlArray) {
        //    this.urlFirmadoPendiente = urlArray;
        // },
        docFirmadoMethod(firmado){
            this.docFirmado =  firmado;
        },
        updatedTramitesMethod(newTramites){
            console.log('tramites updateed en aux' );
            console.log( newTramites);
            this.usuario = newTramites;
            // this.solicitudes = newTramites;
            var cloned =  _.cloneDeep(newTramites);
            this.clonedData = cloned;
            console.log('------clonedData');
            console.log(this.clonedData);
            this.solicitudes = this.clonedData;
            this.$forceUpdate();
        }
    },

}
</script>

<style>

</style>