<template>
    <div class="content d-flex flex-column flex-column-fluid pt-0">
        <div class="subheader py-2 py-lg-4 subheader-transparent" id="kt_subheader">
            <div class="container d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <!--begin::Details-->
                <div class="d-flex align-items-center flex-wrap mr-2 w-100">
                    <!--begin::Title-->
                    <div>
                        <h5 class="text-dark font-weight-bold mt-2 mr-5">Trámites</h5>
                        <span class="text-dark-50 font-weight-bold" id="kt_subheader_total">{{ tramitesFiltrados.length }} Total</span>
                    </div>
                    <!--end::Title-->
                    <!--begin::Separator-->
                    <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-5 bg-gray-200"></div>
                    <!--end::Separator-->
                    <!--begin::Search Form-->
                    <div class="">
                        Búsqueda por fecha:
                        <date-range-picker
                            ref="picker"
                            opens="left"
                            :locale-data="{ firstDay: 1, format: 'dd-mm-yyyy' }"
                            v-model="dateRange"
                            :dateFormat="dateFormat"
                            :showDropdowns="false"
                            :ranges="false"
                            @update="updateValues"
                        >
                            <template v-slot:input="picker" style="min-width: 350px;">
                                {{ `${`${picker.startDate.getDate()}`.padStart(2,'0')}-${`${picker.startDate.getMonth()+1}`.padStart(2,'0')}-${picker.startDate.getFullYear()}` }} <i class="fas fa-arrow-right mx-2"></i> {{ `${`${picker.endDate.getDate()}`.padStart(2,'0')}-${`${picker.endDate.getMonth()+1}`.padStart(2,'0')}-${picker.endDate.getFullYear()}` }}
                            </template>
                        </date-range-picker>
                    </div>
                    <div class="d-flex align-items-center ml-auto" id="kt_subheader_search">
                        <div class="btn-group ml-10">
                            <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Buscar por: <strong>{{ searchBy.description || '' }}</strong>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <button :class="searchBy.name == null ? 'active' : ''" v-on:click="searchByFilter(null, '')" class="dropdown-item" type="button">Ninguno</button>
                                <button :class="searchBy.name == 'escritura' ? 'active' : ''" v-on:click="searchByFilter('escritura', 'Número de Escritura')" class="dropdown-item" type="button">Número de Escritura</button>
                                <button :class="searchBy.name == 'expediente' ? 'active' : ''" v-on:click="searchByFilter('expediente', 'Expediente Catastral')" class="dropdown-item" type="button">Expediente Catastral</button>
                                <button :class="searchBy.name == 'enajenante' ? 'active' : ''" v-on:click="searchByFilter('enajenante', 'Enajenante')" class="dropdown-item" type="button">Enajenante</button>
                                <button v-if="![99,80].includes(type)" :class="searchBy.name == 'folio_pago' ? 'active' : ''" v-on:click="searchByFilter('folio_pago', 'Folio de Pago')" class="dropdown-item" type="button">Folio de Pago</button>
                                <button v-if="![99,80].includes(type)" :class="searchBy.name == 'fse' ? 'active' : ''" v-on:click="searchByFilter('fse', 'FSE')" class="dropdown-item" type="button">FSE</button>
                            </div>
                        </div>
                        <form>
                            <div class="input-group input-group-sm input-group-solid" style="max-width: 175px">
                                <input type="text" class="form-control" id="kt_subheader_search_form" placeholder="Search..." v-on:keyup="search()" v-model="strBusqueda">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <span class="svg-icon">
                                            <!--begin::Svg Icon | path:/metronic/theme/html/demo7/dist/assets/media/svg/icons/General/Search.svg-->
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24"></rect>
                                                    <path d="M14.2928932,16.7071068 C13.9023689,16.3165825 13.9023689,15.6834175 14.2928932,15.2928932 C14.6834175,14.9023689 15.3165825,14.9023689 15.7071068,15.2928932 L19.7071068,19.2928932 C20.0976311,19.6834175 20.0976311,20.3165825 19.7071068,20.7071068 C19.3165825,21.0976311 18.6834175,21.0976311 18.2928932,20.7071068 L14.2928932,16.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"></path>
                                                    <path d="M11,16 C13.7614237,16 16,13.7614237 16,11 C16,8.23857625 13.7614237,6 11,6 C8.23857625,6 6,8.23857625 6,11 C6,13.7614237 8.23857625,16 11,16 Z M11,18 C7.13400675,18 4,14.8659932 4,11 C4,7.13400675 7.13400675,4 11,4 C14.8659932,4 18,7.13400675 18,11 C18,14.8659932 14.8659932,18 11,18 Z" fill="#000000" fill-rule="nonzero"></path>
                                                </g>
                                            </svg>
                                            <!--end::Svg Icon-->
                                        </span>
                                        <!--<i class="flaticon2-search-1 icon-sm"></i>-->
                                    </span>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!--end::Search Form-->
                    <div class="btn-group" v-if="tramitesCart.length > 0">
                        <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Acciones
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <button v-on:click="addToCart(1)" class="dropdown-item" type="button">Agregar al Carrito</button>
                            <button v-on:click="addToCart(null)" class="dropdown-item" type="button">Eliminar de Carrito</button>
                        </div>
                    </div>
                </div>
                <!--end::Details-->
            </div>
        </div>
        <div v-if="estatusTramites == '5'">
            <b-alert show variant="warning"> 
                <div class="text-justify text-white">
                    Si usted realizó su pago y verificó que se halla efectuado, su referencia será actualiza en trámites finalizados en un máximo de 48 horas.
                </div>
            </b-alert>
        </div>
        <div class="d-flex flex-column-fluid">
            <v-container v-if="loading">
                <v-row>
                    <v-col cols="12" md="12">
                        <v-skeleton-loader
                            class="card card-custom justify-content-center"
                            type="list-item-two-line, button"
                            v-for="(r,i) in [1,2,3,4,5,6]"
                            v-bind:key="i"
                            height="90px"
                            style="margin-bottom: 8px;"
                        ></v-skeleton-loader>
                    </v-col>
                </v-row>
            </v-container>
            <div class="w-100" v-if="!loading">
                <pagination-component
                    @obtenerTramites="obtenerTramites"
                    :type="type"
                    @processToCart="processToCart"
                    :items="tramitesFiltrados"
                    :tramitesCart="tramitesCart"
                    @updateListado="updateListado"
                ></pagination-component>
            </div>
        </div>
    </div>
</template>
<script>
    import DateRangePicker from 'vue2-daterange-picker'
    import 'vue2-daterange-picker/dist/vue2-daterange-picker.css'
    export default {    
        components: { DateRangePicker },
        data() {
            let endDate = new Date();
            let startDate = new Date().setDate(new Date().getDate()-30);
            return {
                type : null,
                tramites: [], loading:true, porPage : 30, pages:[0], currentPage :1, strBusqueda:"", totalTramites:0, tramitesFiltrados:[], tramitesCart : [],
                searchBy: {name:null, description:''},
                dateRange: {
                    startDate,
                    endDate
                },
                picker:null,
                date:'',
                ...this.$attrs,estatusTramites:null
            }
        },
        created() {
            let url = window.location.href;
            this.estatusTramites = url.split("/")[url.split("/").length - 1]
            this.obtenerTramites();
        },
        methods: {
            searchByFilter(name, description){
                this.searchBy = {
                    name, description
                };
            },
            processToCart (tramite) {
                let index = this.tramitesCart.findIndex(ele => ele.id == tramite.id)
                if(index < 0) this.tramitesCart.push(tramite);
                else this.tramitesCart = this.tramitesCart.filter((ele, ind) => index != ind)
            },
            addToCart(status){
                let ids = this.tramitesCart.map(ele => ele.id);
                let onCart = parseInt($('#totalTramitesCarrito').text());
                fetch(`${process.env.TESORERIA_HOSTNAME}/solicitudes-guardar-carrito`, {
                    method : 'POST',
                    body: JSON.stringify({ ids, status, type : 'en_carrito', user_id: user.id })
                })
                .then(res => res.json())
                .then(res => {
                    if(res.code === 200){
                        this.tramitesCart.map(tramite => {
                            onCart = status ? onCart+1 : onCart-1;
                            tramite.en_carrito = status;
                            $(`input[type=checkbox]#${tramite.id}`).prop('checked', false);
                        })

                        this.tramitesCart = [];
                        $('#totalTramitesCarrito').text(res.count);
                    }
                });
            },
            async obtenerTramites(){
                if(!this.loading) this.loading = true;
                let url = process.env.TESORERIA_HOSTNAME + "/solicitudes-filtrar";
                moment.lang("es");
                try {
                    let estatus = this.type;
                    let notary_id = this.notary || null;
                    let id_usuario = this.user || null;
                    let pendiente_firma = null;

                    if(estatus === 98){
                        pendiente_firma = true;
                        this.type = 'pendiente_firma';
                        estatus = null;
                    }

                    let data = { };
                    if(estatus) data.estatus = estatus;
                    if(pendiente_firma) data.pendiente_firma = pendiente_firma;
                    // if(this.notary) data.notary_id = this.notary;
                    if(window.user) data.id_usuario = window.user.id;

                    let response = await axios.post(url, data);
                    this.tramites = response.data;
                    this.tramitesFiltrados = this.tramites.filter( tramite => tramite.titulo.toLocaleLowerCase().includes(this.strBusqueda.toLocaleLowerCase()) ) ;
                    this.tramitesFiltrados.map(tramite => {
                        tramite.created_at = moment(tramite.created_at).format("D MMMM YYYY, hh:mm A")
                    })
                    let pagesTotal = Math.ceil( this.tramitesFiltrados.length / this.porPage);
                    let pages = [];

                    for (var i = 0; i < pagesTotal; i++) {
                        pages.push( i + 1 );
                    }
                    this.pages = pages;
                } catch (error) {
                    console.log(error);
                }
                this.$forceUpdate()
                this.loading = false;
            },

            search(){
                let searchStr = new RegExp(this.strBusqueda, 'i');
                if(this.strBusqueda == '') return this.tramitesFiltrados = this.tramites;
                this.tramitesFiltrados = this.tramites.filter(tramite => {
                    let camposConfigurados = {};
                    tramite.info && tramite.info.camposConfigurados && tramite.info.camposConfigurados.map(campo => camposConfigurados[campo.alias] = campo);
                    if(this.searchBy.name === 'expediente' && camposConfigurados.expedientes){
                        let search = camposConfigurados.expedientes.valor.expedientes.filter(exp => exp.expediente.search(searchStr) >= 0)
                        return search.length > 0;
                    }
                    else if (this.searchBy.name === 'enajenante' && camposConfigurados.listado_de_enajenantes) {
                        let search = camposConfigurados.listado_de_enajenantes.valor.enajenantes.filter(enajenante => 
                            (enajenante.datosPersonales.apMat && enajenante.datosPersonales.apMat.search(searchStr) >= 0) ||
                            (enajenante.datosPersonales.apPat && enajenante.datosPersonales.apPat.search(searchStr) >= 0) ||
                            (enajenante.datosPersonales.nombre && enajenante.datosPersonales.nombre.search(searchStr) >= 0) ||
                            (enajenante.datosPersonales.curp && enajenante.datosPersonales.curp.search(searchStr) >= 0) ||
                            (enajenante.datosPersonales.rfc && enajenante.datosPersonales.rfc.search(searchStr) >= 0)
                        )
                        return search.length > 0;
                    }
                    else if (this.searchBy.name === 'folio_pago' && tramite.tramites && tramite.tramites[0] && tramite.tramites[0].id_transaccion_motor) {
                        console.log('folio_pago');
                        return searchStr.test(tramite.tramites[0].id_transaccion_motor)
                    }
                    else if (this.searchBy.name === 'fse' && tramite.tramites && tramite.tramites[0] && tramite.tramites[0].id) {
                        console.log('fse');
                        return searchStr.test(tramite.tramites[0].id)
                    }
                    else if (this.searchBy.name === 'escritura' && camposConfigurados.escritura) {
                        return camposConfigurados.escritura.valor.search(searchStr) >= 0
                    }
                    else{
                        return (tramite.nombre_servicio && tramite.nombre_servicio.search(searchStr) >= 0) ||
                        (tramite.titulo && tramite.titulo.search(searchStr) >= 0)
                    }
                })

                let pagesTotal = Math.ceil( this.tramitesFiltrados.length / this.porPage);
                let pages = [];

                for (var i = 0; i < pagesTotal; i++) {
                    pages.push( i + 1 );
                }
                this.pages = pages;
                this.$forceUpdate()
            },

            updateListado(res){
                this.loading = true;
                this.tramites = [];
                this.tramitesFiltrados = [];
                this.tramitesCart = [];
                this.obtenerTramites();
            },
            dateFormat(classes, date){
                return classes
            },
            updateValues (dates) {
                let today = new Date();
                let startDate = new Date(dates.startDate);
                startDate = new Date(startDate.getFullYear(), startDate.getMonth(), startDate.getDate(), 0, 0, 0)
                let endDate = new Date(dates.endDate);
                endDate = new Date(endDate.getFullYear(), endDate.getMonth(), endDate.getDate(), 0, 0, 0)
                this.tramitesFiltrados = this.tramites.filter((tramite, ind) => {
                    let created_at = new Date(tramite.created_at);
                    return (startDate <= created_at && endDate >= created_at)
                })

                let pagesTotal = Math.ceil( this.tramitesFiltrados.length / this.porPage);
                let pages = [];

                for (var i = 0; i < pagesTotal; i++) {
                    pages.push( i + 1 );
                }
                this.pages = pages;
                this.$forceUpdate()
            },
            checkOpen (a) {
                console.log('checkOpen', a);
            }
        }


    }
</script>