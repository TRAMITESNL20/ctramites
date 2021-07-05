<template>
  <div>
        <div class="pt-10 pl-10 pr-10"  style="background-color:white" v-if=" (tramitesdoc.length > 0 && docFirmado != 1 && docPendientes != 0  )">
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
                :tramitesdoc="tramitesdoc"
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
        this.tramitesdoc.map((solicitud, index) => {
            solicitud.required_docs == 0 ? this.docPendientes ++ : '' ; 
        });
        console.log( this.tramitesdoc.length > 0 && this.docFirmado != 1 && this.docPendientes != 0);
    },
    data(){
        return {
            urlFirmadoListo: [],
            docFirmado:'',
            clonedData: '',
            solicitudes : this.usuario,
            docPendientes: 0,
        }
    },
    methods: {
        urlFirmadoListoMethod(urlArray) {
           this.urlFirmadoListo = urlArray; 
           console.log('urlFirmadoListo', this.urlFirmadoListo);
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