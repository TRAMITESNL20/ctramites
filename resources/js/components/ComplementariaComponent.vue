<template>
  <div class="row">
        <div class="col-lg-6 fv-plugins-icon-container"> 
          <label>
            Folio
          </label>
            <b-form-input id="folio-input" v-model="folio" placeholder="Folio" @change="getInformacion()" :disabled="buscandoInformacion"></b-form-input>
        </div>
        <div class="col-lg-6 fv-plugins-icon-container"">
          <label>
            Fecha de escritura o minuta
          </label>
          <b-form-input id="fecha-input" v-model="fechaEscritura" placeholder="DD-MM-AAAA" :disabled="true"></b-form-input>
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
               <v-expansion-panels  multiple v-model="panel">
                  <v-expansion-panel v-for="(tramite, index) in tramitesObtenidos" :key="tramite.id">
                    <v-expansion-panel-header >
                      <span class="text-left">
                          <b-form-checkbox v-model="tramite.selected" :key="tramite.id" name="flavour-3a"  @change="clickChecbox()" switch :id="tramite.id + '-checkbox'">
                            <span>
                            {{ tramite.info.enajenante.datosPersonales.curp ||  ""}}-  
                            {{ tramite.info.enajenante.datosPersonales.nombre || ""}} 
                            {{ tramite.info.enajenante.datosPersonales.apPat || ""}} 
                            {{ tramite.info.enajenante.datosPersonales.apMat || ""}}
                            </span>
                          </b-form-checkbox>
                          
                      </span>
                        <template v-slot:actions >

                          <i class="fa fa-angle-down" />
                        </template>
                    </v-expansion-panel-header>
                    <v-expansion-panel-content v-if="tramite.selected">
                      <formulario-complementaria-component :info="{
                        fechaEscritura, folio, idTramite:tramite.id, index, datosComplementaria: tramite.datosComplementaria
                      }" @updateForm="updateForm"></formulario-complementaria-component>
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
      props:{

        infoGuardada:{
          default: null,
        },

      },     
      data() {
        return {
          folio:'',
          tramitesObtenidos:[],
          buscandoInformacion:false,
          mensaje:'',
          fechaEscritura:'', 
          complementarias:[],
          panel:[]   
        }
      },
      created() {
        if(this.infoGuardada && this.infoGuardada.datosComplementaria) {
          this.folio = this.infoGuardada.datosComplementaria.folio;
          this.fechaEscritura = this.infoGuardada.datosComplementaria.fechaEscritura;
          this.getInformacion();
        } else{
          this.validar();
        }
      },
      methods: {
        async getData(){
          try {
            let url = process.env.TESORERIA_HOSTNAME + "/getInfoNormales/" + this.folio;
            let response = await axios.get(url);
            return response.data;
          } catch (error) {
        
          }
        },
        getInformacion(){
          let self = this;
          if( self.folio && self.folio.length > 0 ){
            let response = [];
            self.buscandoInformacion = true;
            (async () => {
              
              response = await self.getData();

              let tramitesObtenidos = response.tramites.length > 0 ? response.tramites[0].solicitudes : [];

              if(tramitesObtenidos.length > 0){
                let arrFecha =  tramitesObtenidos[0].info.detalle.Entradas.fecha_escritura.split("-");
                arrFecha[1] = arrFecha[1].padStart(2, "0");
                arrFecha[2] = arrFecha[2].padStart(2, "0");
                self.fechaEscritura = arrFecha.reverse().join("-");

                if(self.infoGuardada && self.infoGuardada.datosComplementaria && self.infoGuardada.datosComplementaria.complementarias.length > 0) {

                   tramitesObtenidos.map( tramite=>{
                    let seGuardoAnteriormente = self.infoGuardada.datosComplementaria.complementarias.find(complementariaGuardadda=> tramite.id == complementariaGuardadda.idTicketAnterior);
                    if(seGuardoAnteriormente){
                      tramite.selected = true;
                      tramite.datosComplementaria = seGuardoAnteriormente.datosComplementaria;
                    }
                    return tramite
                   });
                  self.tramitesObtenidos = tramitesObtenidos;
                  self.mensaje = self.tramitesObtenidos.length  == 0 ?  "No se encontraron tramites relacionados a este Folio" :  "";
                  self.clickChecbox();
                } else{
                  self.tramitesObtenidos = tramitesObtenidos;
                  self.mensaje = this.tramitesObtenidos.length  == 0 ?  "No se encontraron tramites relacionados a este Folio" :  "";
                  self.validar();
                }
                
              } 

            })()

          } else {
            self.tramitesObtenidos = [];
            self.mensaje = "";
            self.validar();
          }
          self.buscandoInformacion = false;
        },

        updateForm( response ){
          this.tramitesObtenidos[response.info.index].datosComplementaria = response.form;
          this.tramitesObtenidos[response.info.index].detalle = response.detalle;          
          this.tramitesObtenidos[response.info.index].fechaEscritura = response.info.fechaEscritura;
          this.tramitesObtenidos[response.info.index].folio = response.info.folio;
          this.tramitesObtenidos[response.info.index].formValid = response.valid;
          this.validar();
        },

        validar(){
          this.complementarias = this.tramitesObtenidos.filter( tramite => tramite.selected).map( tramite => {
              let complementaria = {};
              complementaria.datosComplementaria = tramite.datosComplementaria;
              complementaria.detalle = tramite.detalle;
              complementaria.fechaEscritura = tramite.fechaEscritura;
              complementaria.folioTransaccionAnterior = tramite.folio;
              complementaria.idTicketNormal = tramite.info.tipoTramite == 'normal' ? tramite.id : tramite.info.idTicketNormal;
              complementaria.idTicketAnterior = tramite.id;
              complementaria.valido = tramite.formValid;
              complementaria.enajenante =  {
                datosPersonales: tramite.info.enajenante.datosPersonales,
                nacionalidad: tramite.info.enajenante.nacionalidad,
                tipoPersona: tramite.info.enajenante.tipoPersona
              };
            return complementaria;
          });
          this.complementarias.map(complementaria => {
            complementaria.idsNormales =  this.complementarias.map( complementaria => complementaria.idTicketNormal);
            return complementaria;
          })

          let valido = this.complementarias.length > 0;
          this.complementarias.forEach( complementaria =>{
            valido = valido && complementaria.valido;
          });

          this.$emit('updatingScore', valido);
          //if(valido){
            let info = {
              folio:this.folio,
              fechaEscritura:this.fechaEscritura,
              complementarias:this.complementarias
            }
            this.$emit('sendData', info);
          //}
          
        },

        clickChecbox(){
          this.panel = this.tramitesObtenidos.map( (tram, index) =>{
            if(tram.selected){
              return index;
            }
          });
          this.validar();
        }

      }
    }
</script>