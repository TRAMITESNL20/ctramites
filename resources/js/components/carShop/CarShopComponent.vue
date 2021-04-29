<template>                  
    <div class="container-fluid">
    <div class="row" v-if="informacion.length > 0 && false">
      <div class="col-lg-12">
        <div class="text-center">
            <b-alert show variant="secondary">
              {{informacion}}
            </b-alert>
          </div>
      </div>
    </div>
    <b-modal id="modalAgrupar"  @ok="confirm" ok-title = "Si" @cancel="cancel">
      <div class="d-block">
        Los actos que agrupaste deberán ingresarse de manera simultánea en el Instituto Registral y Catastral para que les puedan asignar la misma fecha y hora de prelación.
      </div>
    </b-modal>
    <div class="row">
      <transition name="slide-fade" appear>
          <div class="btn-group" v-if="mostrarAcciones">
                <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Acciones
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <button class="dropdown-item" type="button"  v-on:click="confirmarGrupo()" ref="confirGroup">
                      Agrupar Tramites
                    </button>
                  <!--
                    <button @click="agruparSeleccion()" class="dropdown-item" type="button">Agrupar Tramites</button>
                  -->
                </div>
          </div>
      </transition>
    </div>
    <div class="row">
        <!--Grid column-->
        <div class="col-lg-8">
            <!-- Card -->
            <div v-if="!mostrarMetodos && !mostrarReciboPago0">
              <v-container v-if="obteniendoTramites">
                    <v-row>
                        <v-col cols="12" md="12">
                            <v-skeleton-loader v-bind:key="i" type="list-item" v-for="(r,i) in [1,2,3,4,5,6]" height="90px" style="margin-bottom: 8px;"></v-skeleton-loader>
                        </v-col>
                    </v-row>
                </v-container>
                <div v-if="!obteniendoTramites && items.length > 0">
                  <div class="card list-item card-custom gutter-b col-lg-12"  v-for="(item, index) in items" :key="index"  
                  v-bind:style="item.items.length > 1 ? 'background-color: rgb(217, 222, 226) !important;' : ''" id="cart-container"
                                    @drop='onDrop($event, item)' 
                                    @dragover.prevent
                                    @dragenter.prevent >
   
                      <agrupacion-items-carrrito-component 
                        :agrupacion="item" 
                        :index="index" 
                        :idUsuario="idUsuario"
                        @updatingParent="updateList" 
                        @dragEvent="dragEvent" 
                        :tramitesServer="tramitesServer"
                        @selectionEvent="evtElementoSeleccionado"
                        @removeEvent="evtRemoveElementoSeleccionado">
                          
                      </agrupacion-items-carrrito-component>
   
                  </div>



                    <div class="card list-item card-custom gutter-b col-lg-12" id="elementDrop" style="border-style: dotted; background-color: rgba(0,0,0,0.3); display: none;"  @drop='onDropFuera($event, false)' 
                                @dragover.prevent
                                @dragenter.prevent >
                        <div class="card-body py-7" >

                            <div>
                                  Sacar...
                                </div>

                        </div>
                    </div>

                  <div class="card card-custom" >
                        <div class="card-body py-7">
                            <!--begin::Pagination-->
                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                                <div class="d-flex flex-wrap mr-3" >
                              <b-pagination
                                v-model="currentPage"
                                :total-rows="totalListTramites"
                                :per-page="porPage"
                                aria-controls="cart-container"
                                align="center"></b-pagination>
                                </div>

                                <div class="d-flex align-items-center">
                                    <select class="form-control form-control-sm text-primary font-weight-bold mr-4 border-0 bg-light-primary" style="width: 75px;" v-model="porPage"  v-on:change="chagenPorPage()">
                                        <option value="5" >5</option>
                                        <option value="30">30</option>
                                    </select>
                                </div>
                            </div>
                            <!--end:: Pagination-->
                        </div>
                    </div>
                </div>

                <div v-else-if="!obteniendoTramites && items.length == 0">
                  <div class="card" style="width: 100%;">
                    <div class="card-body text-center">
                      <h5 class="card-title" >Aún no haz iniciado algún trámite</h5>
                        Para continuar da click <a  class="card-link"  v-on:click="iniciarTramite()"> <span style="cursor: pointer;"> aquí </span> </a>
                    </div>
                  </div>
              </div>
            </div>
            <!-- Card -->
            <transition name="slide-fade" appear>
              <metodos-pago-component :infoMetodosPago="infoMetodosPago" v-if="mostrarMetodos"></metodos-pago-component>
            </transition>
            <b-row v-if="mostrarReciboPago0" >
              <iframe width="100%" height="880" :src="reciboPagoCeroURL"></iframe>
            </b-row>
        </div>
        <!--Grid column-->
        <!--Grid column-->
        <div class="col-lg-4" >
            <v-container v-if="obteniendoTramites">
              <v-row>
                  <v-col cols="12" md="12">
                      <v-skeleton-loader v-bind:key="i" type="list-item" v-for="(r,i) in [1]" height="150px" style="margin-bottom: 8px;"></v-skeleton-loader>
                  </v-col>
              </v-row>
            </v-container>
            <detalle-pago-component v-if="tramites.length > 0" 
              :tramites="tramites" 
              :obtenidoCostos="costosObtenidos" @updatingParent="recibirMetodosPago"  @cancelarPago="cancelarPago" >
            </detalle-pago-component>
            <transition name="slide-fade" appear>
              <div class="mb-3 shadow-sm p-3 bg-white rounded" v-if="mostrarMetodos">  
                <div class="pt-4">
                  <b-table responsive  striped hover :items="tramites" :fields="camposTablaTramites">
                    <template #cell(nombre)="data">
                      {{ data.item.nombre }}
                    </template>
                    <template #cell(importe_tramite)="data">
                      <div style="text-align: right;"  >
                        {{ data.item.importe_tramite | toCurrency }}
                      </div>
                      
                    </template>
                  </b-table>
                </div>
              </div>
             </transition>
        </div>
        <!--Grid column-->
    </div>
    <!-- Grid row -->
    </div>
</template>
<script>
  //import costosApi from '../../services/costosApi.services.js';
  import actualizadorCostos from '../../services/actualizadorCostos.service.js';
  import tramite5isr from '../../services/TramiteCarCtrl.js';
  import tramiteStrategy from '../../services/TramiteCarStrategy.js';

  import { uuid } from 'vue-uuid';
    export default {
      props: ['idUsuario'],
        computed:{
            totalListTramites(){
                return this.tramitesAgrupados.length;
            },
            items(){
              this.tramitesAgrupados = [];
              this.tramites.forEach( tramite => {
                let item = { nombre: tramite.nombre, grupo_clave: tramite.calveTemp, items:[tramite], verDetalle:false, selected:false };
                let indice = this.tramitesAgrupados.findIndex( agrupado => agrupado.grupo_clave == tramite.calveTemp );
                if( indice < 0 || !tramite.calveTemp){
                  this.tramitesAgrupados.push( item );
                } else {
                  this.tramitesAgrupados[indice].items.push( tramite )
                }
              });
              return this.tramitesAgrupados.slice((this.currentPage - 1) * this.porPage,this.currentPage * this.porPage);        
            },
            mostrarAcciones(){
              return this.items.filter( item => item.selected ).length > 1
            }
        },
        data() {
            return {
              tramites:[],
              mostrarMetodos:false,
              infoMetodosPago:{},
              porPage : 5, currentPage :1,
              obteniendoTramites:false,
              costosObtenidos:false,
              mostrarReciboPago0:false,
              reciboPagoCeroURL:'', tramitesAgrupados:[],
              informacion:'',
              camposTablaTramites:[
                    { key: 'nombre', label: 'Nombre' },
                    { key: 'importe_tramite', label: 'Importe' },
              ],
              tramitesServer:[],
              elementosSeleccionados:[],
              claveDroped:'',
              listDroped:null
            }
        },
  
        mounted() {
          this.obtenerTramitesAgregados();
        },
        methods: {
          updateList(  data ){
                let nuevaListaTTramites = this.tramites.filter( tramite => {         
                  let itemEliminado = data.idsDelete.find( id => {
                    return id == tramite.idSolicitante; 
                  });
                  return !itemEliminado;
                });
                this.tramites = nuevaListaTTramites;
          },
          cancelarPago(){
            this.mostrarMetodos = !this.mostrarMetodos;
          },
          async obtenerTramitesAgregados(){
              this.obteniendoTramites = true;
                let url = process.env.TESORERIA_HOSTNAME + "/solicitudes-info/" + this.idUsuario;
                try {
                  this.informacion = "Obteniendo tramites";
                    let response = await axios.get(url);

                    this.tramitesServer =  response.data.tramites ;
                    this.construirJSONTramites( this.tramitesServer );
                   /*
                    let arrayPromesasActualizacionDeCostos = [];

                    tramites.forEach(  (tramite, indexTramite) => {
                      tramite.solicitudes.forEach(  (solicitud) => {
                         arrayPromesasActualizacionDeCostos.push(actualizadorCostos.getRequestCosto(solicitud, indexTramite, tramite.tramite_id));
                      });
                    });

                    this.informacion = "Sus tramites han cambiado de costo, estamos actualizando los costos.";
                    let showMessageTimeOut = null;
                    let showMessageInterval = setInterval( () =>{ 
                      this.informacion = 'Espere un momento..';
                      showMessageTimeOut = setTimeout(() => {
                        this.informacion = 'Sus tramites han cambiado de costo, estamos actualizando los costos.';
                      }, 1000);
                       
                    }, 3000);
                    axios.all(arrayPromesasActualizacionDeCostos).then(axios.spread((...responses) => {
                      let updateSolicitudes = [];
                      responses = responses.filter(Boolean);
                      responses.forEach( res =>{
                        let indexSolicitud = tramites[res.config.headers.indexTramite].solicitudes.findIndex( solicitud => solicitud.id === res.config.headers.idSolicitud );

                        if(tramites[res.config.headers.indexTramite].solicitudes[indexSolicitud].info.enajenante){
                          tramites[res.config.headers.indexTramite].solicitudes[indexSolicitud].info.detalle = res.data;
                          tramites[res.config.headers.indexTramite].solicitudes[indexSolicitud].info.enajenante.detalle = res.data;
                        } else if( tramites[res.config.headers.indexTramite].solicitudes[indexSolicitud].info.costo_final ){
                          tramites[res.config.headers.indexTramite].solicitudes[indexSolicitud].info.costo_final = res.data[0].costo_final;
                          tramites[res.config.headers.indexTramite].solicitudes[indexSolicitud].info.detalle = res.data[0];
                        }

                        let solicitudUpdate = {
                          id:res.config.headers.idSolicitud,
                          info:tramites[res.config.headers.indexTramite].solicitudes[indexSolicitud].info
                        }
                        updateSolicitudes.push(solicitudUpdate);
                      });

                      this.informacion = "Guardando cambios tramites";
                      
                      if(updateSolicitudes.length > 0){
                        let url = process.env.TESORERIA_HOSTNAME + "/edit-solicitudes-info";
                        axios.post(url, {data:updateSolicitudes}, {
                             headers:{
                                "Content-type":"application/json"
                            }
                        }).then(response => {
                          this.construirJSONTramites( tramites );
                        }).catch(errors => {
                          Command: toastr.error("Error!", error.message || "No fue posible guardar los cambios");
                        }).finally(() => {
                          this.informacion = "";
                        });
                      } else {
                        this.construirJSONTramites( tramites );
                      }
                      
                    })).catch(errors => {
                      this.informacion = "";
                      //this.obteniendoTramites = false;
                    }).finally(() => {

                      clearInterval( showMessageInterval );
                      clearTimeout( showMessageTimeOut )
                      this.informacion = "";
                    });
                    */
                } catch (error) {
                  this.informacion = "";
                  this.obteniendoTramites = false;
                }
          },

          recibirMetodosPago( response ){
            if(response.data.response.pago_cero){
              this.mostrarReciboPago0 = true;
              this.reciboPagoCeroURL = response.data.response.pago_cero;

              let id_transaccion_motor = response.data.response.folio
              let data = {
                id_transaccion_motor,url_recibo:this.reciboPagoCeroURL
              }

              setTimeout(function(){ 
                let urlADDURLRecivo = process.env.TESORERIA_HOSTNAME +  "/solicitudes-update-tramite"
                axios.post(urlADDURLRecivo, data, {
                     headers:{
                        "Content-type":"application/json"
                    }
                } ).then(response => {
                    console.log("guardando url recibo")
                });
              }, 1000);

                     
            } else {
              this.infoMetodosPago = response.data.response;
              this.mostrarMetodos = true;         
            }
          },
          cambiarStatusTransaccion(status, id_transaccion_motor){
            let data ={
                id_transaccion_motor,
                status
            }
            let url = process.env.TESORERIA_HOSTNAME + "/solicitudes-update-status-tramite";
            axios.post(url, data, {
                 headers:{
                    "Content-type":"application/json"
                }
            } ).then(response => {
                console.log("cambiando estatus")
            });
          },

          async construirJSONTramites( tramites ){
            let listadoTramites = [];
            let requestCostos = [];
            tramites.forEach(  tramiteInarray => {
            tramiteInarray.solicitudes.forEach(  soliciante => {
                //patron factory
                let isrTramite = new tramite5isr();
                let strategia = new tramiteStrategy();
                strategia.setStrategy(isrTramite)
                let info = (typeof soliciante.info) == 'string' ? JSON.parse(soliciante.info) : soliciante.info;
                let tramitesJson =  strategia.create(tramiteInarray, soliciante); //str1.create(tramiteInarray, soliciante);
                listadoTramites.push( tramitesJson );
                
            });
          });
          this.tramites = listadoTramites;
          this.obteniendoTramites = false;
          this.costosObtenidos = true;
          
        },
        iniciarTramite(){
          redirect("/nuevo-tramite");
        },

        chagenPorPage(){
          this.currentPage = 1;
        },


        onDrop (evt, list) {
          const clave = evt.dataTransfer.getData('clave');
          this.claveDroped = clave;
          this.listDroped = list;
          this.confirmarGrupo();
        },


        onDropFuera(evt, list){
          let claveGrupo = uuid.v4();
                    const clave = evt.dataTransfer.getData('clave');
          this.tramites.map( tram =>{
              if( tram.claveIndividual == clave ){
                tram.calveTemp = claveGrupo;
              }
              return tram;
          } )
          $("#elementDrop").hide();

          this.saveCambios();
        },

        dragEvent(data){
          if(data.event == 'startdrag'){
            $("#elementDrop").show();
          } else {
            $("#elementDrop").hide();
          } 
            
        },

        evtElementoSeleccionado( data){
          let ids = data.items.map( item => item.claveIndividual ).filter((val, index, self) =>  self.indexOf(val) === index);
          if(data.selected){
            let elementosNuevos = ids.filter( elem => !this.elementosSeleccionados.includes(elem))
            this.elementosSeleccionados = this.elementosSeleccionados.concat(elementosNuevos);
          } else {
            data.items.forEach( (i, index)=>{
              if(this.elementosSeleccionados.includes( i.claveIndividual )){
                this.elementosSeleccionados.splice( index, 1 );
              }
            });
          }
    
        },


        agruparSeleccion(){
          let claveGrupo = uuid.v4();
          this.tramites.forEach( tramite => { 
            if(this.elementosSeleccionados.includes( tramite.claveIndividual )){
              tramite.calveTemp = claveGrupo;            
            }
          });
          this.elementosSeleccionados = [];

          this.saveCambios();
        },

        evtRemoveElementoSeleccionado(claveIndividual){
          let claveGrupo = uuid.v4();
          this.tramites.forEach( (tramite, index) => { 
            if( claveIndividual == tramite.claveIndividual ){
              tramite.calveTemp = claveGrupo; 
              //let index = this.elementosSeleccionados.findIndex( tramite => tramite.id_tramite == item.id_tramite );
              if(this.elementosSeleccionados.length > 0){
                this.elementosSeleccionados.splice( index, 1 );
              }           
            }
          });

     
          this.saveCambios();
        },

        saveCambios(){
          let claveGrupo = uuid.v4();
          let updateSolicitudes = [];
          this.tramites.forEach( tramite => {
            let solicitudUpdate = {
              id:tramite.id_tramite,
              grupo_clave:tramite.calveTemp
            }
            updateSolicitudes.push(solicitudUpdate); 
          });

          if(updateSolicitudes.length > 0){
            let url = process.env.TESORERIA_HOSTNAME + "/edit-solicitudes-info";
            axios.post(url, {data:updateSolicitudes}, {
                 headers:{
                    "Content-type":"application/json"
                }
            }).then(response => {
              if( response && response.data && response.data.Code == "400" ){
                Command: toastr.error("Error!", response.data.message || "No fue posible guardar los cambios");
                this.obtenerTramitesAgregados();
              } 

                this.elementosSeleccionados = [];
            }).catch(errors => {
              Command: toastr.error("Error!", error.message || "No fue posible guardar los cambios");
              this.obtenerTramitesAgregados();
            }).finally(() => {

            });
          }

        },

        confirmarGrupo(){
          this.$root.$emit('bv::show::modal', 'modalAgrupar', '#confirGroup')
        },

        confirm(){
          if(this.claveDroped.length > 0){
 
            this.tramites.map( tram =>{
                if( tram.claveIndividual == this.claveDroped ){
                  tram.calveTemp = this.listDroped.grupo_clave;
                }
                return tram;
            } );
            $("#elementDrop").hide();

            this.saveCambios();
          } else {
            this.agruparSeleccion();
          }
          
        },

        cancel(){
          this.claveDroped = '';
          this.listDroped = null;
        }


        

    }
  }
</script>