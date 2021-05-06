<template>
  <div>
    
    <div class="" v-for="(solicitud, index) in urlSourceListo" >
      <div class="card list-item card-custom gutter-b col-lg-12" style="background-color: rgb(217, 222, 226) !important" >
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
            <a :href="solicitud.urlDocumentoFirmado" :disabled="solicitud.required_docs != 1"  v-bind:class="[ solicitud.required_docs== 1 ?  '' : 'disabled' ]" class="btn btn-sm btn-primary font-weight-bolder text-uppercase text-white mr-2" >DESCARGAR</a>
            <!---->
            <button
              type="button"
              data-toggle="collapse"
              :data-target="`#collapse-${index}`" 
              aria-expanded="false"
              aria-controls="collapse-4936bff8-9312-4ad3-aebb-fed66e193e5d"
              class="btn btn-secondary"
            >
              <i class="fas fa-chevron-down p-0"></i>
            </button>
          </div>
        </div>
        <!-- ----- COLAPSABLE ---- -->
        <div  :id="`collapse-${index}`" class="collapse " style="" >
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
  data: {
    aDisabled: {
            color: 'currentColor',
            cursor: 'not-allowed',
            opacity: '0.5',
            color: '#464E5F',
      }
  }

};
</script>

<style>
</style>