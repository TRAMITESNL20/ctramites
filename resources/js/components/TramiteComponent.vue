<template>
    <div class="card list-item card-custom gutter-b col-lg-12" :class="cartComponent && !tramite.en_carrito ? 'd-none' : ''">
        <div class="card-body" :class="group ? 'p-0' : ''">
            <!--begin::Top-->
            <div class="d-flex">
                <!--begin: Info-->
                <div class="flex-grow-1">
                    <!--begin::Title-->
                    <div class="d-flex align-items-center justify-content-between flex-wrap" :class="!group ? 'mt-2' : ''">
                        <div class="mr-7 espace-checkbox" v-if="!group && tramite.status && (tramite.status == 99 || tramite.status == 98) && !cartComponent && ['notary_titular', 'notary_substitute'].includes(user.role_name)"><input type="checkbox" :id="tramite.id" style="width:18px; height:18px;" v-on:change="processToCart(tramite, true)"></div>
                        <!--begin::User-->
                        <div class="mr-auto " v-bind:class="{'espace-checkbox-text' : !group && tramite.status && (tramite.status == 99 || tramite.status == 98) && !cartComponent && ['notary_titular', 'notary_substitute'].includes(user.role_name)}" v-bind:style="[ { maxWidth: '60%', minWidth: '40%' } ]">
                            <!--begin::Name-->
                            <a v-on:click="goTo(tramite)" class="d-flex flex-column text-dark over-primary font-size-h5 font-weight-bold mr-3" v-if="">
                                <strong v-if="!group && !cartComponent" class="text-uppercase text-truncate">{{ tramite.nombre_servicio && tramite.titulo ? (( tramite.nombre_servicio.toLowerCase() != tramite.titulo.toLowerCase()) ? `${tramite.nombre_servicio} - ` : `${tramite.titulo} - `) : '' }}{{ (tramite.info && tramite.info.tipoTramite) || tramite.tramite || tramite.titulo | capitalize }}</strong>
                                <span :class="group ? '' : 'text-muted'" v-if="type !== undefined && tramite.info && !group">
                                    <span style="font-weight: normal;" v-if="tramite.tramites && tramite.tramites[0] && tramite.tramites[0].id_transaccion_motor"><strong>FOLIO PAGO:</strong> {{ `${tramite.tramites[0].id_transaccion_motor} -` }}</span>
                                    <span style="font-weight: normal;" v-if="tramite.tramites && tramite.tramites[0] && tramite.tramites[0].id"><strong>FSE:</strong> {{ tramite.tramites[0].id }}</span>
                                    <span v-if="!group && type !== undefined && tramite.info && !cartComponent">
                                        - {{ tramite.created_at }}
                                    </span>
                                </span>
                                <span style="font-weight: normal;" class="text-muted" :class="!group ? 'mt-3' : ''" v-if="type !== undefined && tramite.info && ((!group && cartComponent) || !cartComponent)">
                                    <strong class="text-muted">Tramite ID:</strong>  {{ tramite.id || '' }}
                                </span>
                                <span v-bind:style="[ (!group && cartComponent) || !cartComponent ? {} : { 'font-size' : '12px' } ]" v-if="type !== undefined && solicitante">{{ solicitante.rfc ||solicitante.curp || "" }} - {{ solicitante.tipoPersona == "pm" ? solicitante.razonSocial : `${solicitante.nombreSolicitante || solicitante.nombre} ${solicitante.apPat} ${solicitante.apMat}` }}</span>
                            </a>
                            <!--end::Name-->
                        </div>
                        <!--begin::User-->
                        <!--begin::Actions-->
                        <div class="my-lg-0 my-1 boton-lista-mobil">
                            <button v-on:click="addToCart(tramite)" v-if="!group && tramite.status == 99 && !['notary_capturist'].includes(user.role_name)" type="button" class="btn btn-sm" :class="tramite.en_carrito ? 'btn-primary' : 'btn-outline-primary'">
                                <span v-if="tramite.loading"><i class="fas fa-spinner fa-spin"></i></span>
                                <span v-if="!tramite.loading"><i :class="tramite.en_carrito == 1 ? (cartComponent ? 'fas fa-trash' : 'fas fa-check-circle') : 'fas fa-plus-circle'"></i> {{ tramite.en_carrito == 1 ? (cartComponent ? 'ELIMINAR' : 'QUITAR DEL CARRITO') : 'AGREGAR AL CARRITO' }}</span>
                            </button>
                            <button v-on:click="addToSign(tramite)" v-if="!group && type == 'pendiente_firma' && ['notary_titular', 'notary_substitute'].includes(user.role_name)" type="button" class="btn btn-sm" :class="tramite.por_firmar ? 'btn-primary' : 'btn-outline-primary'">
                                <span v-if="tramite.loadingSign"><i class="fas fa-spinner fa-spin"></i></span>
                                <span v-if="!tramite.loadingSign"><i :class="tramite.por_firmar == 1 ? 'fas fa-check-circle' : 'fas fa-plus-circle'"></i> {{ tramite.por_firmar == 1 ? 'DESELECCIONAR' : 'PREPARAR PARA FIRMAR' }}</span>
                            </button>
                            <a v-on:click="goTo(tramite)" class="btn btn-sm btn-primary font-weight-bolder text-uppercase text-white" v-if="!tramite.info">
                                INICIAR TRAMITE
                            </a>
                            <a v-on:click="goTo(tramite.recibo_referencia, true)" class="btn btn-sm btn-primary font-weight-bolder text-uppercase text-white" v-if="tramite.recibo_referencia && [5].includes(type) && !group">VER REFERENCIA</a>
                            <a v-on:click="goTo(tramite.doc_firmado, true)" class="btn btn-sm btn-primary font-weight-bolder text-uppercase text-white" v-if="tramite.doc_firmado && [2,3].includes(type)">VER DECLARACIÓN</a>
                            <!-- <modal-document-component  :tramitesdoc="tramitesdoc" v-if="tramite.required_docs === 0"   ></modal-document-component> -->
                            <a v-on:click="goTo(tramite.tramites[0].url_recibo, true)" class="btn btn-sm btn-primary font-weight-bolder text-uppercase text-white" v-if="tramite.tramites && tramite.tramites[0] && tramite.tramites[0].url_recibo && [2,3].includes(type) && !group">VER RECIBO DE PAGO</a>
                            <div class="btn-group" v-if="tramite.info && !cartComponent && !group && ![7,8].includes(tramite.status)">
                                <a v-on:click="goTo(tramite)" class="btn btn-sm btn-primary font-weight-bolder text-uppercase text-white" :class="files.length == 0 ? 'rounded' : ''">
                                    <span class="text-white">VER DETALLES</span>
                                </a>
                                <button v-if="files.length > 0" type="button" class="btn btn-sm btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a v-for="(file, ind) in files" class="dropdown-item" :href="file.href || file" :key="ind"><i class="fas fa-download mr-2"></i> {{ file.name || file }}</a>
                                </div>
                            </div>
                            <a v-on:click="goTo(`detalle-tramite/${tramite.tramite_id}?clave=${tramite.clave}`)" class="btn btn-sm btn-success font-weight-bolder text-uppercase text-white" v-if="[7,8].includes(tramite.status)">
                                CONTINUAR
                            </a>
                            <span v-if="cartComponent" class="btn btn-secondary">MX{{ new Intl.NumberFormat('es-MX', { style : 'currency', currency : 'MXN' }).format(tramite.importe_tramite) }}</span>
                            <span v-if="!group && tramite.info && tramite.descripcion && !cartComponent" class="btn btn-secondary">{{ tramite.descripcion || "CERRADO" }} </span>
                        </div>
                        <!--end::Actions-->
                    </div>
                    <!--end::Title-->
                </div>
                <!--end::Info-->
            </div>
            <!--end::Top-->
        </div>
    </div>
</template>

<script>
import ModalDocumentComponent from './tiposElementos/ModalDocumentComponent.vue';
    export default {
  components: { ModalDocumentComponent },
        data() {
            return {
                files : [],
                solicitante : {},
                user : window.user,
                declaracion : null,
                recibo: null,
                tramitesdoc: [this.tramite]
            }
        },
        props: ['tramite', 'type', 'group', 'cartComponent'],
        mounted() {
            this.files = [];
            if(this.tramite.info && typeof this.tramite.info === 'string')
                this.tramite.info = JSON.parse(this.tramite.info)
            if(this.tramite.mensajes && this.tramite.mensajes.length > 0){
                this.tramite.mensajes.map(msg => {
                    if(msg.attach && msg.attach != ""){
                        this.files.push(msg.attach);
                    }
                })
            }
            if(this.tramite.doc_firmado) this.files.push({ name : 'Declaración', href : this.tramite.doc_firmado })
            // if(this.tramite.doc_firmado) this.files.push({ name : 'Declaración', href : this.tramite.doc_firmado })
            this.tramite.loading = false;
            if(this.tramite.info && this.tramite.info.enajenante){
                this.tramite.info = {
                    ...this.tramite.info,
                    ...this.tramite.info.enajenante
                }
            }

            this.solicitante = null;
            if(this.tramite.info){
                if(this.tramite.info.enajenante){
                    this.solicitante = this.tramite.info.enajenante.datosPersonales;
                    this.solicitante.tipoPersona = this.tramite.info.tipoPersona;
                }else if(this.tramite.info.solicitante)
                    this.solicitante = this.tramite.info.solicitante;
                else if(this.tramite.info.datosPersonales){
                    this.solicitante = this.tramite.info.datosPersonales;
                    this.solicitante.tipoPersona = this.tramite.info.tipoPersona;
                }
            }
        },
        methods:{
            goTo(tramite, _blank=false){
                if(typeof tramite === 'string') return redirect(tramite, _blank);
                if(window.location.href.indexOf("borradores") >= 0){
                    redirect("detalle-tramite/" + tramite.tramite_id + "?clave=" + tramite.clave, _blank);
                } else {
                    redirect(`/detalle${ tramite.id_tramite ? "-tramite" : "" }/` +  (tramite.id_tramite || tramite.id), _blank);
                }
                
            },
            processToCart(tramite){
                this.$emit('processToCart', tramite);
            },
            addToCart(tramite, multiple=false, status=null){
                if(!multiple) this.tramites = [ tramite ];

                this.tramites.map(tramite => {
                    let status = tramite.en_carrito != 1 ? 1 : null;
                    let onCart = parseInt($('#totalTramitesCarrito').text());
                    onCart = status ? onCart+1 : onCart-1;
                    tramite.loading = true;
                    fetch(`${process.env.TESORERIA_HOSTNAME}/solicitudes-guardar-carrito`, {
                        method : 'POST',
                        body: JSON.stringify({ ids : [ tramite.id ], status, type : 'en_carrito', user_id: user.id })
                    })
                    .then(res => res.json())
                    .then(res => {
                        if(res.code === 200){
                            tramite.en_carrito = status;
                            if(status === null && this.cartComponent)
                                this.$emit('processDelete', tramite);
                            $('#totalTramitesCarrito').text(res.count);
                        }
                        tramite.loading = false;
                    });
                });
            },

            addToSign(tramite, multiple=false, status=null){
                if(!multiple) this.tramites = [ tramite ];

                this.tramites.map(tramite => {
                    let status = tramite.por_firmar != 1 ? 1 : null;
                    let onSign = parseInt($('#totalTramitesFirma').text());
                    onSign = status ? onSign+1 : onSign-1;
                    tramite.loadingSign = true;
                    fetch(`${process.env.TESORERIA_HOSTNAME}/solicitudes-guardar-carrito`, {
                        method : 'POST',
                        body: JSON.stringify({ ids : [ tramite.id ], status, type : 'por_firmar', user_id: user.id })
                    })
                    .then(res => res.json())
                    .then(res => {
                        if(res.code === 200){
                            tramite.por_firmar = status;
                            $('#totalTramitesFirma').text(res.count);
                        }
                        tramite.loadingSign = false;
                    });
                });
            }
        }
    }
</script>


