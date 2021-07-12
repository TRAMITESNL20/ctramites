<template>
  <div>
    <div class="w-100" v-for="(solicitud, index) in urlSourceListo" >
      <div class="card list-item card-custom mb-5 col-lg-12" style="background-color: rgb(217, 222, 226) !important" >
        <div class="d-flex mobile-lista-multiple align-items-center mb-3">
          <!---->
          <div class="mr-auto espace-checkbox-text desktop-agrupacion-width" style="width: 70%">
            <h4 class="ml-3 text-uppercase text-truncate">
              <strong>ISR 5% POR ENAJENACIÓN DE INMUEBLES - Normal</strong>
            </h4>
            <h5 class="ml-3">
              <span style="font-weight: normal" >
                <strong> {{solicitud.info.enajenante.datosPersonales.nombre }} {{solicitud.info.enajenante.datosPersonales.apPat}} {{solicitud.info.enajenante.datosPersonales.apMat }} </strong>-{{solicitud.info.enajenante.datosPersonales.rfc}}
              </span>
            </h5>
          </div>
          <div class="my-lg-0 my-1">
            <!---->
            <div  class="input-group-append">
              <!-- alerta de que no puedes descargar el documento -->

              <button 
                v-if="solicitud.required_docs != 1"
                class="btn btn-danger disabled" 
                style="border-top-right-radius: 0px;border-bottom-right-radius: 0px;font-size: 7px;border-radius: 1 1 0 0 !important;" 
                data-toggle="tooltip" 
                data-placement="top" 
                data-title="No es posbile descargar este archivo al no contar con el documento del CALCULO DEL ISR CONFORME AL 126 LISR O COMPROBANTE DE LA EXENCIÓN ">
                    <i class="text-white fa fa-question-circle"></i>
              </button>

                <!-- boton de descarga  -->
                <a  v-if="solicitud.required_docs != 1" :href="solicitud.urlDocumentoFirmado" target="_blank" :disabled="solicitud.required_docs != 1"  v-bind:class="[ solicitud.required_docs== 1 ?  '' : 'disabled' ]"  style="border-top-left-radius:0px;border-bottom-left-radius:0px"  class="btn btn-primary font-weight-bolder text-uppercase text-white mr-5" >DESCARGAR</a>
                <a  v-if="solicitud.required_docs == 1" :href="solicitud.urlDocumentoFirmado" target="_blank" v-bind:class="[ solicitud.required_docs== 1 ?  '' : 'disabled' ]"   class="btn btn-primary font-weight-bolder text-uppercase text-white mr-5" >DESCARGAR</a>
                <!-- boton de collapse -->
                <button
                  class="btn btn-secondary"
                  type="button"
                  data-toggle="collapse"
                  :data-target="`#collapse-${index}`" 
                  aria-expanded="false"
                  :aria-controls="`collapse-${index}`"
                >
                <i class="fas fa-chevron-down p-0"></i>
                </button>
            </div>
            <!---->
          </div>
        </div>
        <!-- ----- COLAPSABLE ---- -->
        <div  :id="`collapse-${index}`" class="collapse" style="" >
          <div class="card list-item card-custom gutter-b col-lg-12" >
            <div class="card-body p-0">
              <div class="d-flex">
                <div class="flex-grow-1">
                  <div class="d-flex align-items-center justify-content-between flex-wrap" >
                    <!---->
                    <div class="mr-auto" style="width: 100%; height:350px; overflow:scroll">
                      <a class="d-flex text-dark over-primary font-size-h5 font-weight-bold mr-3 flex-column" ><!---->
                        <!---->

                        <pdf :src="solicitud.urlDocumentoFirmado"></pdf>

                      </a>
                    </div>
              
                  </div>
                </div>
              </div>
            </div>
          </div>
         
        </div>

      </div>
      <!---->
    </div>

  </div>
</template>

<script>
import pdf from "vue-pdf";
export default {
    props: ["urlSourceListo", "urlSourcePendiente"],
    components: {
        pdf,
    },
    data() {
        return{
            aDisabled: {
                color: 'currentColor',
                    cursor: 'not-allowed',
                    opacity: '0.5',
                    color: '#464E5F',
            }
        }
    },
    mounted(){
        $('[data-toggle=tooltip]').tooltip()
    }
}
</script>

<style scoped lang="scss">
div#nonExistingSCSSIdHack {
    display: none;
}
</style>