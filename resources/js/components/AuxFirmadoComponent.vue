<template>
  <div>
        <div class="pt-10 pl-10 pr-10"  v-if=" (tramitesdoc).length  != 2">
            <div >
                <p> El tramite seleccionado no cuenta con los documentos de CALCULO DEL ISR CONFORME AL 126 LISR y SAT </p>
                <modal-document-component 
                    :tramitesdoc="tramitesdoc" >
                </modal-document-component>
            </div>
        </div>

        <div>
            <vue-pdf-component :urlSource="urlFirmado" >  
            
            </vue-pdf-component>
        </div>

                                                   
        <div class="pt-10 pl-10 pr-10"  v-for="idTramite in usuario">
            <firma-electronica-component 
                :usuario="idTramite"   
                :user="user"  
                @docFirmado="docFirmado"
                @urlFirmado="urlFirmadoMethod">
            </firma-electronica-component>
        </div>
        asda{{urlFirmado}}
  </div>

</template>

<script>
export default {
    props: ['usuario', 'tramitesdoc' , 'user'],
    data(){
        return {
            urlFirmado: [],
        }
    },
    methods: {
        urlFirmadoMethod(urlArray) {
           this.urlFirmado = urlArray; 
        }
    }

}
</script>

<style>

</style>