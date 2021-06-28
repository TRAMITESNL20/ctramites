<template>
  <div>
      <div>
            <div v-for="(idTramite ,index) in solicitudes" :key="index" class="w-100" >
                <div v-if="docFirmado != 1 && idTramite.tramite_id != 8" :id="idTramite.tramite_id"  class="card list-item mt-5 card-custom col-lg-12" style="background-color: rgb(217, 222, 226) !important" >
                    <div class="d-flex mobile-lista-multiple align-items-center mb-3">
          <!---->
                        <div class="mr-auto espace-checkbox-text desktop-agrupacion-width" style="width: 70%">
                            <h4 class="ml-3 text-uppercase text-truncate">
                            <strong>{{idTramite.tramite}}</strong>
                            </h4>
                            <h5 class="ml-3">
                                <strong >Tramite ID:  -
                                    <span style="font-weight: normal" v-for="(solicitud, ind) in idTramite.solicitudes" :key="ind"> {{ idTramite.solicitudes[ind].id }} -   </span> 
                                </strong> 
                           </h5> 
                        </div>
                         <div class="my-lg-0 my-1">
                            <div  class="input-group-append">
                                 <button
                                class="btn btn-secondary"
                                type="button"
                                data-toggle="collapse"
                                :data-target="`#collapse-${idTramite.tramite_id}`" 
                                aria-expanded="false"
                                :aria-controls="`collapse-${idTramite.tramite_id}`"
                                >
                                <i class="fas fa-chevron-down p-0"></i>
                                </button>
                            </div>
                         </div>
                         
                    </div>
                    <div  :id="`collapse-${idTramite.tramite_id}`" class="collapse" style="" >
                        <div class="card list-item card-custom gutter-b col-lg-12" >
                            <div class="card-body p-0">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                <div class="d-flex align-items-center justify-content-between flex-wrap" >
                                    <!---->
                                    <div class="mr-auto" style="width: 100%; height:470px; overflow:scroll">
                                    <a class="d-flex text-dark over-primary font-size-h5 font-weight-bold mr-3 flex-column" ><!---->
                                        <!---->

                                        <div class="pt-10 pl-10 pr-10"  style="background-color:white" v-if=" (tramitesdoc).length > 0 && docFirmado != 1 && idTramite.tramite_id == TRAMITE_5_ISR ">
                                            <div >
                                                <span> El tramite seleccionado no cuenta con los documentos de CALCULO DEL ISR CONFORME AL 126 LISR y SAT </span>
                                                <modal-document-component 
                                                    :tramitesdoc="tramitesdoc" 
                                                    :idtramites="usuario"
                                                    @updatedTramites="updatedTramitesMethod"
                                                    >
                                                </modal-document-component>
                                            </div>
                                        </div>
                                                                                
                                        <div v-if="docFirmado != 1">
                                            <firma-electronica-component
                                                :tramitesdoc="tramitesdoc" 
                                                :usuario="idTramite"   
                                                :user="user"  
                                                @docFirmado="docFirmadoMethod"
                                                @docFirmadosListos="urlFirmadoListoMethod"
                                                >
                                            </firma-electronica-component>
                                        </div>

                                    </a>
                                    </div>
                            
                                </div>
                                </div>
                            </div>
                            </div>
                        </div>
                    
                    </div>
                </div>
                <div  v-if="idTramite.tramite_id == 8"  >
                    <firma-electronica-component
                        :tramitesdoc="tramitesdoc" 
                        :usuario="idTramite"   
                        :user="user"  
                        @docFirmado="docFirmadoMethod"
                        @docFirmadosListos="urlFirmadoListoMethod"
                        >
                    </firma-electronica-component>
                </div>
            </div>
          

        </div>
          
        <!-- {{solicitudes}} -->
        <vue-pdf-component class="pt-5" 
                :urlSourceListo="urlFirmadoListo" 
        >  
        </vue-pdf-component>
        
  </div>

</template>

<script>
export default {
    props: ['usuario', 'tramitesdoc' , 'user'],
    mounted(){
        $('[data-toggle=tooltip]').tooltip();
    },
    data(){
        return {
            urlFirmadoListo: [],
            docFirmado:'',
            clonedData: '',
            solicitudes : this.usuario,
            TRAMITE_5_ISR: process.env.TRAMITE_5_ISR,
            TRAMITE_INFORMATIVO: process.env.TRAMITE_INFORMATIVO
        }
    },
    methods: {
        urlFirmadoListoMethod(urlArray) {

            for (let i = 0; i < urlArray.length; i++) {
                this.urlFirmadoListo.push(urlArray[i]);
            }
                console.log(this.urlFirmadoListo);
        },
        docFirmadoMethod(firmado, tramite_id){
            // this.docFirmado =  firmado;
            document.getElementById(tramite_id).style.display="none";
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
