<template>
  <div>
        <div class="pt-10 pl-10 pr-10"  v-if=" (tramitesdoc).length > 0">
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

        <div 

        >
            <vue-pdf-component 
                :urlSourceListo="urlFirmadoListo" 
                :urlSourcePendiente="urlFirmadoPendiente" 
                v-if="docFirmado == 1" >  
            
            </vue-pdf-component>
        </div>

                                                   
        <div class="pt-10 pl-10 pr-10"  v-for="idTramite in usuario" v-if="docFirmado != 1">
            <firma-electronica-component 
                :usuario="idTramite"   
                :user="user"  
                @docFirmado="docFirmadoMethod"
                @urlFirmadoListo="urlFirmadoListoMethod"
                @urlFirmadoPendiente="urlFirmadoPendienteMethod"
                >
            </firma-electronica-component>
        </div>

        <!-- <code>usuario {{usuario.typeof()}}
        </code>
        <code> tramitesdoc{{ tramitesdoc.typeof()}}
        </code> -->

       
  </div>

</template>

<script>
export default {
    props: ['usuario', 'tramitesdoc' , 'user'],
    data(){
        return {
            urlFirmadoListo: [],
            urlFirmadoPendiente: [],
            docFirmado:'',
        }
    },
    methods: {
        urlFirmadoListoMethod(urlArray) {
           this.urlFirmadoListo = urlArray; 
        },
        urlFirmadoPendienteMethod(urlArray) {
           this.urlFirmadoPendiente = urlArray; 
        },
        docFirmadoMethod(firmado){
            this.docFirmado =  firmado;
        },
        updatedTramitesMethod(newTramites){
            this.usuario = newTramites;
        }
    },
    

}
</script>

<style>

</style>