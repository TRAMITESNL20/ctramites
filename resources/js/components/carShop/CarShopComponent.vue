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
    <div class="row">
        <div class="btn-group" v-if="elementosSeleccionados.length > 1">
              <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Acciones
              </button>
              <div class="dropdown-menu dropdown-menu-right">
                  <button @click="agruparSeleccion()" class="dropdown-item" type="button">Agrupar Tramites</button>
              </div>
        </div>
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
                                    @dragenter = 'dragenter($event, item)'
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
  import { uuid } from 'vue-uuid';
    export default {
      props: ['idUsuario'],
        computed:{
            totalListTramites(){
                return this.tramitesAgrupados.length;
            },
            items(){
              console.log("cambios")
              this.tramitesAgrupados = [];
              this.tramites.forEach( tramite => {
                let item = { nombre: tramite.nombre, clave: tramite.calveTemp, items:[tramite], verDetalle:false, selected:false };
                let indice = this.tramitesAgrupados.findIndex( agrupado => agrupado.clave == tramite.calveTemp );
                if( indice < 0 ){
                  this.tramitesAgrupados.push( item );
                } else {
                  this.tramitesAgrupados[indice].items.push( tramite )
                }
              });
              return this.tramitesAgrupados.slice((this.currentPage - 1) * this.porPage,this.currentPage * this.porPage);        
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
              elementosSeleccionados:[]
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
          extraerDatosPersonalesSolicitante(solicitante){
            let datos_solicitante = {
              "nombre": solicitante.tipoPersona == "pm" ? "" : solicitante.nombreSolicitante || "",
              "apellido_paterno": solicitante.tipoPersona == "pm" ? "" : solicitante.apPat || "",
              "apellido_materno": solicitante.tipoPersona == "pm" ? "" : solicitante.apMat || "",
              "razon_social": solicitante.tipoPersona == "pm" ? solicitante.razonSocial : "",
              "rfc": solicitante.rfc,
              "curp": solicitante.curp || "",
              "email": solicitante.email|| "",
              "calle": solicitante.calle|| "",
              "colonia":solicitante.colonia|| "",
              "numexterior": solicitante.numexterior|| "",
              "numinterior": solicitante.numinterior|| "",
              "municipio": solicitante.municipio|| "",
              "codigopostal": solicitante.codigopostal|| "",
            }
            return datos_solicitante;
          },

          extraerDatosPersonalesEnajentante(enajenante){
            let datos_solicitante = {
              "nombre": enajenante.tipoPersona == "pm" ? "" : enajenante.datosPersonales.nombre || "",
              "apellido_paterno": enajenante.tipoPersona == "pm" ? "" : enajenante.datosPersonales.apPat || "",
              "apellido_materno": enajenante.tipoPersona == "pm" ? "" : enajenante.datosPersonales.apMat || "",
              "razon_social": enajenante.tipoPersona == "pm" ? enajenante.datosPersonales.razonSocial : "",
              "rfc": enajenante.datosPersonales.rfc,
              "curp": enajenante.tipoPersona == "pm" ? "" : enajenante.datosPersonales.curp  || "",
              "email": "-",
              "calle": "-",
              "colonia": "-",
              "numexterior": "-",
              "numinterior": "-",
              "municipio":  "-",
              "codigopostal":"-",
            }
            return datos_solicitante;
          },
          obtenerDatosSolicitante(soliciante){
            if(soliciante.info.hasOwnProperty('enajenante')){
              return this.extraerDatosPersonalesEnajentante(soliciante.info.enajenante);
            } else if(soliciante.info.hasOwnProperty('solicitante')){
              return this.extraerDatosPersonalesSolicitante(soliciante.info.solicitante);
            } else {
              return {};
            }
          },
          async construirJSONTramites( tramites ){
            let listadoTramites = [];
            let requestCostos = [];
            tramites.forEach(  tramiteInarray => {
            tramiteInarray.solicitudes.forEach(  soliciante => {
            
            let tramitesJson = {};
            tramitesJson.nombre = tramiteInarray.tramite;
            tramitesJson.id_seguimiento = tramiteInarray.tramite_id + "";
            tramitesJson.id_tipo_servicio = tramiteInarray.tramite_id;//397;//
            tramitesJson.idSolicitante = soliciante.id; 
            tramitesJson.id_tramite = soliciante.id;//soliciante.clave;
            tramitesJson.calveTemp = soliciante.clave;//soliciante.clave;
            if(soliciante.info.enajenante) soliciante.info = {...soliciante.info, ...soliciante.info.enajenante}
            let info = (typeof soliciante.info) == 'string' ? JSON.parse(soliciante.info) : soliciante.info;
            if(soliciante.info.hasOwnProperty('enajenante') && (soliciante.info.hasOwnProperty('solicitante') ) ){
              let solicitanteInfo = soliciante.info.solicitante;
              tramitesJson.auxiliar_1  = (solicitanteInfo.nombreSolicitante || '') + " " + (solicitanteInfo.apPat || '' )+ " " + (solicitanteInfo.apMat || '');
              let usuario = window.user;
              if(usuario && usuario.notary){
                tramitesJson.auxiliar_1 = tramitesJson.auxiliar_1 + " - Notaria " + usuario.notary.notary_number
              }
            } else {
              tramitesJson.auxiliar_1 =  "";//enviar como auxiliar el solicitante
            }
              
            tramitesJson.auxiliar_2 = "";
            tramitesJson.auxiliar_3 = "";
            tramitesJson.importe_tramite = '';
            
            tramitesJson.datos_solicitante = this.obtenerDatosSolicitante(soliciante);
            tramitesJson.datos_factura = tramitesJson.datos_solicitante;
            if( info.camposComplementaria && info.detalle && info.detalle.Complementaria){
              tramitesJson.importe_tramite = info.detalle.Complementaria['Cantidad a cargo'] ;
            } else {
              tramitesJson.importe_tramite = info.detalle && info.detalle.Salidas ?  info.detalle.Salidas['Importe total'] : info.costo_final ;
            }
            tramitesJson.detalle = [];

            if(info && info.detalle && info.detalle.Salidas){
              

              
              tramitesJson.detalle[0] = {
                concepto : 'Impuesto correspondiente a la entidad federativa',
                partida: 56754,
                importe_concepto: Number(Number(info.detalle.Salidas['Impuesto correspondiente a la entidad federativa']).toFixed(this.$const.PRECISION))      
              }
              tramitesJson.detalle[1] = {
                concepto : 'Parte actualizada del impuesto',
                partida: 57910,
                importe_concepto:  Number(Number(info.detalle.Salidas['Parte actualizada del impuesto']).toFixed(this.$const.PRECISION))  
              }

              tramitesJson.detalle[2] = {
                concepto : 'Recargos',
                partida: 57612,
                importe_concepto: Number(Number(info.detalle.Salidas['Recargos']).toFixed(this.$const.PRECISION))  
              }
               tramitesJson.detalle[3] = {
                concepto : 'Multa corrección fiscal',
                partida: 57505,
                importe_concepto: Number(Number(info.detalle.Salidas['Multa corrección fiscal']).toFixed(this.$const.PRECISION))     
              }
            } else {
              tramitesJson.detalle[0] = { 
                concepto : info.partidas ? info.partidas[0].descripcion : tramitesJson.nombre,//ponere nombre tramite
                partida: info.partidas ? info.partidas[0].id_partida : null,
                importe_concepto:tramitesJson.importe_tramite         
              }

              let descuentosAplicados = [];
              if(info.detalle && info.detalle.descuentos && Array.isArray(info.detalle.descuentos )  && info.detalle.descuentos.length > 0  ){
                let losdescuentos = info.detalle.descuentos.filter( descuento => descuento.concepto_descuento != "No aplica" );   
                losdescuentos = info.detalle.descuentos.filter( descuento => descuento.concepto_descuento != "El numero de oficio no coincide con el trámite" );    
                if( losdescuentos && losdescuentos.length > 0 ){
                  info.detalle.descuentos.forEach( descuento => {
                    let descuentoAplicado =  {
                            concepto_descuento: descuento.concepto_descuento,
                            importe_descuento: descuento.importe_subsidio,
                            partida_descuento: descuento.partida_descuento
                          }
                          descuentosAplicados.push( descuentoAplicado )
                  });
                  tramitesJson.detalle[0].descuentos = descuentosAplicados;               
                }
              }
            }

            //console.log(JSON.parse(JSON.stringify(tramitesJson)))

 
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
          console.log("ques es list")
          console.log(JSON.parse(JSON.stringify(list)))
          console.log(JSON.parse(JSON.stringify(evt)))
          const itemID = evt.dataTransfer.getData('itemID');
          console.log((itemID))
          this.tramites.map( tram =>{
              if( tram.id_tramite == itemID ){
                tram.calveTemp = list.clave;
              }
              return tram;
          } );
          $("#elementDrop").hide();

          this.saveCambios();
        },

        onDropFuera(evt, list){
          const itemID = evt.dataTransfer.getData('itemID');
          this.tramites.map( tram =>{
              if( tram.id_tramite == itemID ){
                tram.calveTemp = uuid.v4();
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

        dragenter(evt, item){
          console.log("event")
          console.log(JSON.parse(JSON.stringify(item)))
          console.log(JSON.parse(JSON.stringify(evt)))
        },

        evtElementoSeleccionado( data){
          let ids = data.items.map( item => item.id_tramite );

          if(data.selected){
            let elementosNuevos = ids.filter( elem => !this.elementosSeleccionados.includes(elem))
            this.elementosSeleccionados = this.elementosSeleccionados.concat(elementosNuevos);
          } else {
            data.items.forEach( (i, index)=>{
              if(this.elementosSeleccionados.includes( i.id_tramite )){
                this.elementosSeleccionados.splice( index, 1 );
              }
            });
          }
    
        },


        agruparSeleccion(){
          let claveGrupo = uuid.v4();
          this.tramites.forEach( tramite => { 
            if(this.elementosSeleccionados.includes( tramite.id_tramite )){
              tramite.calveTemp = claveGrupo;            
            }
          });
          this.elementosSeleccionados = [];

          this.saveCambios();
        },

        evtRemoveElementoSeleccionado(item){
          let claveGrupo = uuid.v4();
          this.tramites.forEach( tramite => { 
            if(item.id_tramite == tramite.id_tramite ){
              tramite.calveTemp = claveGrupo;            
            }
          });
          this.saveCambios();
        },

        saveCambios(){
          let updateSolicitudes = [];
          this.tramites.forEach( tramite => {
            let solicitudUpdate = {
              id:tramite.id_tramite,
              clave:tramite.calveTemp
            }
            updateSolicitudes.push(solicitudUpdate); 
          });
          console.log(JSON.parse(JSON.stringify(updateSolicitudes)))
          if(updateSolicitudes.length > 0){
            let url = process.env.TESORERIA_HOSTNAME + "/edit-solicitudes-info";
            axios.post(url, {data:updateSolicitudes}, {
                 headers:{
                    "Content-type":"application/json"
                }
            }).then(response => {
              console.log(JSON.parse(JSON.stringify(response.data.Code)))
              if( response && response.data && response.data.Code == "400" ){
                Command: toastr.error("Error!", response.data.message || "No fue posible guardar los cambios");
                this.obtenerTramitesAgregados();
              }
            }).catch(errors => {
              Command: toastr.error("Error!", error.message || "No fue posible guardar los cambios");
              this.obtenerTramitesAgregados();
            }).finally(() => {
            });
          }

        }


        

    }
  }
</script>