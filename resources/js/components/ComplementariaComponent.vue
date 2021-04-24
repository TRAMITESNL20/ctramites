<template>
  <div class="row">
        <div class="col-lg-6">
          <b-form-input v-model="folio" placeholder="FOLIO" @change="getInformacion()" :disabled="buscandoInformacion"></b-form-input>
        </div>
        <div class="col-lg-6">
          <b-form-input v-model="fechaEscritura" placeholder="FECHA DE ESCRITURA O MINUTA" :disabled="true"></b-form-input>
        </div>
      <div class="col-lg-12" >
        <div class="text-center" id="loadin" style=" margin-bottom: 9px;" v-if="buscandoInformacion" >
          <div class="spinner-grow" role="status">
              <span class="sr-only">Loading...</span>
          </div>
        </div>
      </div>
      <transition name="slide-fade" appear>
        <div v-if="!buscandoInformacion" class="col-lg-12">
          <div v-if="tramitesObtenidos.length > 0">
            <div  v-if="tramitesObtenidos.length > 0" >
               <v-expansion-panels  multiple>
                  <v-expansion-panel v-for="tramite in tramitesObtenidos" :key="tramite.id" >
                    <v-expansion-panel-header >
                      <span class="pull-left">
                          {{ tramite.info.enajenante.datosPersonales.curp ||  ""}}-  
                          {{ tramite.info.enajenante.datosPersonales.nombre || ""}} 
                          {{ tramite.info.enajenante.datosPersonales.apPat || ""}} 
                          {{ tramite.info.enajenante.datosPersonales.apMat || ""}} 
                      </span>
                        <template v-slot:actions >
                          <b-form-checkbox v-model="tramite.selected" :key="tramite.id" name="flavour-3a"  >
                          </b-form-checkbox>
                          <i class="fa fa-angle-down" />
                        </template>
                    </v-expansion-panel-header>
                    <v-expansion-panel-content v-if="tramite.selected">
                      <formulario-complementaria-component :info="{
                        fechaEscritura, folio
                      }"></formulario-complementaria-component>
                    </v-expansion-panel-content>
                  </v-expansion-panel>
                </v-expansion-panels>            
            </div>
          </div>
          <div v-else class="text-center">
            {{mensaje}}
          </div>
        </div>
      </transition>
  </div>
</template>
  
<script>

    export default {
      computed:{
          enajentantesSeleccionados(){
              return this.tramitesObtenidos.filter( tramite => tramite.selected);
          },
      },     
      data() {
        return {
          folio:'',
          tramitesObtenidos:[],
          buscandoInformacion:false,
          mensaje:'',
          fechaEscritura:''      
        }
      },
      created() {

      },
      methods: {
        async getInformacion(){
          this.buscandoInformacion = true;
          let url = process.env.TESORERIA_HOSTNAME + "/solicitudes-info/" + window.user.id;
          try {
            let response = await axios.get(url);
            this.tramitesObtenidos = response.data.tramites.length > 0 ? response.data.tramites[0].solicitudes : [];
            /*response.data.tramites[0].solicitudes[0].info.camposConfigurados.find( campos => {

            } )*/
            if(this.tramitesObtenidos.length > 0){
              let arrFecha = this.tramitesObtenidos[0].info.enajenante.detalle.Entradas.fecha_escritura.split("-");
              arrFecha[1] = arrFecha[1].padStart(2, "0");
              arrFecha[2] = arrFecha[1].padStart(2, "0");
              this.fechaEscritura = arrFecha.reverse().join("-")
              
            }
            
            this.mensaje = this.tramitesObtenidos.length  == 0 ?  "No se encontraron tramites relacionados a este Folio" :  "";
          } catch (error) {
            

          }
          
          this.buscandoInformacion = false;
        }

      }
    }
</script>