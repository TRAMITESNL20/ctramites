<template>
  <div>
        <div class="pt-10 pl-10 pr-10"  v-if=" (tramitesdoc).length > 0">
            <div >
                <p> El tramite seleccionado no cuenta con los documentos de CALCULO DEL ISR CONFORME AL 126 LISR y SAT </p>
                <modal-document-component 
                    :tramitesdoc="tramitesdoc" >
                </modal-document-component>
            </div>
        </div>

        <div 

        >
            <vue-pdf-component :urlSource="urlFirmado" v-if="docFirmado == 1" >  
            
            </vue-pdf-component>
        </div>

                                                   
        <div class="pt-10 pl-10 pr-10"  v-for="idTramite in usuario">
            <firma-electronica-component 
                :usuario="idTramite"   
                :user="user"  
                @docFirmado="docFirmadoMethod"
                @urlFirmado="urlFirmadoMethod">
            </firma-electronica-component>
        </div>

        <code>usuario {{usuario.typeof()}}
        </code>
        <code> tramitesdoc{{ tramitesdoc.typeof()}}
        </code>

       
  </div>

</template>

<script>
export default {
    props: ['usuario', 'tramitesdoc' , 'user'],
    data(){
        return {
            urlFirmado: [],
            docFirmado:'',
        }
    },
    methods: {
        urlFirmadoMethod(urlArray) {
           this.urlFirmado = urlArray; 
        },
        docFirmadoMethod(firmado){
            this.docFirmado =  firmado;
        }
    },
    

}
</script>

<style>

</style>