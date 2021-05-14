<template>

    <div :id="'header-' + agrupacion.grupo_clave ">
        <div class="card-header container-fluid not-padding-left-mobile" style="background-color: transparent;">
            <div class="row">
                <div class="col-xl-6 col-sm-10">
                    <h4 >
                        <strong class="ml-3 text-uppercase text-truncate text-initial">
                        {{  sameTramites ? agrupacion.nombre : 'Grupo de Tramites'  }} <span v-if="totalItemInGroup >1 || !!agrupacion.isComplemento"> [ {{ agrupacion.grupo_clave }} ] </span>
                        </strong>
                    </h4>
                </div>
                <div class="col-xl-6 col-sm-2 absolute-mobile" >
                    <button class="close" type="button" data-toggle="collapse" :data-target="`#collapse-${index}`" aria-expanded="false" :aria-controls="`collapse-${index}`" v-if="totalItemInGroup >1 ">
                        <i class="fas fa-chevron-down p-0"></i>
                    </button>

                    <button type="button" class="close" aria-label="Close" title="Quitar"  v-on:click="showConfirm()" :disabled="desabilitar" :ref="'btnConfirm-' + index">
                      <span><i class="fas fa-trash" v-if="totalItemInGroup > 0" style="color:#808080;"></i> </span>
                    </button>
                </div>
            </div>
        </div>    
            <div class="d-flex align-items-center mb-3" :id="idItem">
                <!----> 
                <div class="mr-3 ml-4">
                    <input v-if="isAgrupable" type="checkbox" style="width:18px; height:18px;" @change="addSelect(agrupacion)" v-model="agrupacion.selected">
                </div>
                <div class="mr-auto" style="width: 60%;">

                    <span class="ml-3" style="font-size: 12px;cursor:move;"  v-if="totalItemInGroup  == 1 && agrupacion.items[0].datos_solicitante" draggable @dragstart="startdrag($event, agrupacion.items[0])" @dragend="dragend($event)" >
                       {{ agrupacion.items[0].datos_solicitante.rfc || agrupacion.items[0].datos_solicitante.curp || "" }} - {{ agrupacion.items[0].datos_solicitante.razon_social ? agrupacion.items[0].datos_solicitante.razon_social : agrupacion.items[0].datos_solicitante.nombre + " " + agrupacion.items[0].datos_solicitante.apellido_paterno + " " + agrupacion.items[0].datos_solicitante.apellido_materno }}
                    </span>

                </div>
                <div class="my-lg-0 my-1">
                    <div class="d-flex p-2">
                        <span>
                            <h3>{{ total | toCurrency }} </h3>
                        </span>
                    </div>
                </div>
           </div>
        
           <div :id="`collapse-${index}`"  v-bind:class="totalItemInGroup > 1 ? 'collapse' : ''" v-if="totalItemInGroup >1 ">
              <div v-bind:class="totalItemInGroup > 1 ? 'card' : ''" class="list-item card-custom gutter-b col-lg-12"  v-for="(item, i) in agrupacion.items" draggable @dragstart="startdrag($event, item)" @dragend="dragend($event)" style="cursor:move;">

                 <div class="card-body p-0">
                    <div class="d-flex">
                       <div class="flex-grow-1">
                        
                            <div class="d-flex align-items-center justify-content-between flex-wrap" >
    
                                <div class="mr-3 ml-4" style="cursor:pointer;">
                                    <i class="fas fa-undo" title="Qutar del grupo" style="width:18px; height:18px;"  @click="quitarSelect(item.claveIndividual)"></i>
                                </div>
                                 <div class="mr-auto" style="width: 60%;">
                                    <div v-if="!sameTramites">
                                        {{   item.nombre }}
                                    </div>
                                    <a class="d-flex text-dark over-primary font-size-h5 font-weight-bold mr-3 flex-column">
                                        <span class="mt-3" style="font-size: 12px;"  v-if="item.datos_solicitante">
                                           {{ item.datos_solicitante.rfc || item.datos_solicitante.curp || "" }} - {{ item.datos_solicitante.razon_social ? item.datos_solicitante.razon_social : item.datos_solicitante.nombre + " " + item.datos_solicitante.apellido_paterno + " " + item.datos_solicitante.apellido_materno }}
                                        </span>
                                    </a>
                                 </div>
                                 <div class="my-lg-0 my-1" >
                                    <span class="btn btn-secondary mr-2">
                                        {{item.importe_tramite | toCurrency}}                        
                                    </span>
                                 </div>
                            </div>
                       </div>
                    </div>
                 </div>
              </div>
           </div>
          <b-modal :id="'modalDelet-'+ index"  @ok="confirm" ok-title = "Si">
            <div class="d-block">¿Confirma que desea enviar este item a pendientes de pago?</div>
          </b-modal>
    </div>
</template>

<script>
	import { uuid } from 'vue-uuid';
    export default {
        props: ['agrupacion', 'index', 'idUsuario', 'tramitesServer'],
        data(){
            return {
                campos:['datos_solicitante', 'importe_tramite'],
                desabilitar:false,
                idItem:null
            }
        },
        mounted() {
        	this.idItem = uuid.v4();
        },

        computed:{
            totalItemInGroup(){
                return this.agrupacion.items.length;
            },
            total(){
                var total = 0;
                this.agrupacion.items.forEach(tramite => total = total + parseFloat(tramite.importe_tramite) );
                return total;
            },

            itemCamposShow(){
            	return this.agrupacion.items.map( item => {
            		let itemSHow = { importe_tramite:item.importe_tramite, datos_solicitante:item.datos_solicitante }
            		return itemSHow;
            	});
            },
            sameTramites(){
                let sameNameInAll = true;
                let nameAux = this.agrupacion.items[0].nombre;
                this.agrupacion.items.forEach(tramite => {
                    sameNameInAll = sameNameInAll && tramite.nombre === nameAux;
                });
                return sameNameInAll ;
            },

            isAgrupable(){
                let es_agrupable = false;
                if( this.agrupacion.items && this.agrupacion.items.length > 0){
                    es_agrupable = !this.agrupacion.items.find( item => item.isAgrupable == false )
                } else {
                    es_agrupable = this.agrupacion.isAgrupable;
                }
                return es_agrupable;                
            }
        },
        methods: {
            eliminar(){
            	let idsDelete = this.agrupacion.items.map( solicitud =>{ return solicitud.idSolicitante });
                let data = {
					status : null,
					ids : idsDelete,
					type : "en_carrito",
					user_id: user.id
                }


                //$("#spinner-pago-solicitud-" + this.idItem ).show();
                let url = process.env.TESORERIA_HOSTNAME +  "/solicitudes-guardar-carrito";
                let elItem = this;
                axios.post(url, data).then(response => {
                    let res = { response, idsDelete };
                    this.$emit('updatingParent', res);
                    let totalCarritoActual = parseInt( $("#totalTramitesCarrito" ).text( ));
                    $("#totalTramitesCarrito" ).text( totalCarritoActual - idsDelete.length  );
                }).catch(error => {
                    console.log("no fue posible eliminar la solicitud");
                    console.log(error)
                }).finally( () => {
                    //$("#spinner-pago-solicitud-" + this.solicitud.idSolicitante ).hide();
                });
            },

            verDetalle(){
            	this.agrupacion.verDetalle =  !this.agrupacion.verDetalle;
            	this.$forceUpdate();
            },


            startdrag(evt, item){
                let infoTramite = this.tramitesServer.find( tramiteServer => {
                    return tramiteServer.tramite === item.nombre;
                });
                let solicitudStartDrag = infoTramite.solicitudes.find( solicitud => {
                    return item.id_tramite == solicitud.id;
                });
                if(!item.isAgrupable){
                    evt.dataTransfer.dropEffect = 'none'
                    evt.dataTransfer.effectAllowed = 'none'
                    return false;
                } else {

                    this.$emit('dragEvent', {event:'startdrag'});

                    evt.dataTransfer.dropEffect = 'move'
                    evt.dataTransfer.effectAllowed = 'move'
                    evt.dataTransfer.setData('itemID', item.id_tramite)
                    evt.dataTransfer.setData('clave',  item.claveIndividual);
                }
            },

            dragend(evt){
                this.$emit('dragEvent', {event:'dragend'});
            },

            addSelect(agrupacion){
                let data = { 
                    selected:agrupacion.selected,
                    items: agrupacion.items,
                    index:this.index,
                    grupo_clave:agrupacion.grupo_clave
                }
                this.$emit('selectionEvent', data);

            },

            quitarSelect(claveIndividual){
                this.$emit('removeEvent', claveIndividual);  
            }, 

            //isAgrupable(agrupacion){

            //    let es_agrupable = false;
            //    if( agrupacion.items ){
            //        es_agrupable = !agrupacion.items.find( item => item.isAgrupable == false )
            //    } else {
            //        es_agrupable = agrupacion.isAgrupable;
            //   }

            //    return es_agrupable;
                //return !agrupacion.isComplemento ;
                /*let infoTramite = null;
                this.tramitesServer.forEach( tramiteServer => {
                   let encontrado = tramiteServer.solicitudes.find( solicitud => solicitud.id == item.id_tramite );
                   if(encontrado) {
                    infoTramite = encontrado;
                   }
                });

                let clavesCantidad = {};
                this.tramitesServer.forEach( tramiteServer => {
                    tramiteServer.solicitudes.forEach( solicitud => {
                        if(clavesCantidad[solicitud.clave]){
                            clavesCantidad[solicitud.clave].cantidad = clavesCantidad[solicitud.clave].cantidad + 1;
                        } else {
                            clavesCantidad[solicitud.clave] = { cantidad : 1 };
                        }
                    });

                });
console.log( JSON.parse( JSON.stringify( item ) ))
                return (clavesCantidad[item.clave || item.claveIndividual].cantidad == 1) ;*/
            //},

            showConfirm(){
                this.$root.$emit('bv::show::modal', 'modalDelet-'+ this.index, '#btnConfirm-' +this.index)
            },

            confirm(){
               this.eliminar(); 
            }




        }
    }
</script>

