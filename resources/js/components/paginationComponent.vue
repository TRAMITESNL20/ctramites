<template>
	<div class="pagination flex-column">
		<div class="pagination-content">
			<div v-for="(tramite, index) in tramitesPaginados" :class="!tramite[0].en_carrito && cartComponent ? 'd-none' : '' ">
				<div class="card list-item card-custom gutter-b col-lg-12" :data-test="JSON.stringify(tramite)" style="background-color: #d9dee2 !important;" v-if="tramite.length > 1">
					<div class="d-flex mobile-lista-multiple align-items-center mb-3">
						<div class="mr-3 ml-4  espace-checkbox" v-if="tramite[0].status && (tramite[0].status == 99 || tramite[0].status == 98) && !cartComponent && ['notary_titular', 'notary_substitute'].includes(user.role_name)"><input type="checkbox" :id="tramite[0].id" style="width:18px; height:18px;" v-on:change="processToCart(tramite[0], true)"></div>
						<div class="mr-auto espace-checkbox-text desktop-agrupacion-width" v-bind:style="[ cartComponent ? { width : '60%' } : { width: '70%' } ]">
							<h4 class="ml-3 text-uppercase text-truncate"><strong>{{ tramite[0].different ? 'GRUPO DE TRAMITES' : `${ tramite[0].nombre_servicio } ${tramite[0].nombre_servicio && (tramite[0].titulo && tramite[0].nombre_servicio.toLowerCase() != tramite[0].titulo.toLowerCase()) ? `- ${ (tramite[0].info && tramite[0].info.tipoTramite) || tramite[0].tramite || tramite[0].titulo | capitalize }` : '' }` }}</strong></h4>
							<h5 class="ml-3">
                                <span style="font-weight: normal;" v-if="tramite[0].tramites[0] && tramite[0].tramites[0].id_transaccion_motor"><strong>FOLIO PAGO:</strong> {{ tramite[0].tramites[0].id_transaccion_motor ? `${tramite[0].tramites[0].id_transaccion_motor} -` : '' }}</span>
                                <span style="font-weight: normal;" v-if="tramite[0].tramites[0] && tramite[0].tramites[0].id"><strong>FSE:</strong> {{ tramite[0].tramites[0].id ? `${tramite[0].tramites[0].id}` : '' }}</span>
                            </h5>
                            <h5 class="ml-3"><span style="font-weight: normal;" v-if="tramite[0].grupo_clave"><strong>ID GRUPO:</strong> {{ tramite[0].grupo_clave }}</span></h5>
                            <h5 class="ml-3"><span style="font-weight: normal;"><strong>{{ tramite[0].created_at }}</strong></span></h5>
						</div>
						<div class="my-lg-0 my-1">
                            <a v-on:click="goTo(tramite[0].recibo_referencia, true)" class="btn btn-sm btn-primary font-weight-bolder text-uppercase text-white mr-2" v-if="tramite[0].recibo_referencia && [5].includes(type)">VER REFERENCIA</a>
                            <!-- <a v-on:click="redirect(tramite[0].doc_firmado, true)" class="btn btn-sm btn-primary font-weight-bolder text-uppercase text-white mr-2" v-if="tramite[0].doc_firmado && [2,3].includes(type)">VER DECLARACIÓN</a> -->
                            <a v-on:click="goTo(tramite[0].tramites[0].url_recibo, true)" class="btn btn-sm btn-primary font-weight-bolder text-uppercase text-white mr-2" v-if="tramite[0].tramites && tramite[0].tramites[0] && tramite[0].tramites[0].url_recibo && [2,3].includes(type)">VER RECIBO DE PAGO</a>
							<button v-on:click="addToCart(tramite[0])" v-if="tramite[0].status == 99 && !['notary_capturist'].includes(user.role_name)" type="button" class="btn btn-sm mr-2" :class="tramite[0].en_carrito ? 'btn-primary' : 'btn-outline-primary'">
                                <span v-if="tramite[0].loading"><i class="fas fa-spinner fa-spin"></i></span>
                                <span v-if="!tramite[0].loading"><i :class="tramite[0].en_carrito == 1 ? (cartComponent ? 'fas fa-trash' : 'fas fa-check-circle') : 'fas fa-plus-circle'"></i> {{ tramite[0].en_carrito == 1 ? (cartComponent ? 'ELIMINAR' : 'QUITAR DEL CARRITO') : 'AGREGAR AL CARRITO' }} ({{ tramite.length }})</span>
                            </button>
                            <button v-on:click="addToSign(tramite[0])" v-if="type == 'pendiente_firma' && ['notary_titular', 'notary_substitute'].includes(user.role_name)" type="button" class="btn btn-sm mr-2" :class="tramite[0].por_firmar ? 'btn-primary' : 'btn-outline-primary'">
                                <span v-if="tramite[0].loadingSign"><i class="fas fa-spinner fa-spin"></i></span>
                                <span v-if="!tramite[0].loadingSign"><i :class="tramite[0].por_firmar == 1 ? 'fas fa-check-circle' : 'fas fa-plus-circle'"></i> {{ tramite[0].por_firmar == 1 ? 'DESELECCIONAR' : 'PREPARAR PARA FIRMAR' }}</span>
                            </button>
                            <div class="btn-group mr-2 mobile-detalles" v-if="tramite[0].info && !cartComponent">
                                <a v-on:click="goTo(tramite[0], true)" class="btn btn-sm btn-primary font-weight-bolder text-uppercase text-white" :class="!tramite[0].files || tramite[0].files.length == 0 ? 'rounded' : ''">
                                    <span class="text-white">VER DETALLES</span>
                                </a>
                                <button v-if="tramite[0].files && tramite[0].files.length > 0" type="button" class="btn btn-sm btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a v-for="(file, ind) in tramite[0].files" class="dropdown-item" :href="file.href || file" target="_blank" :key="ind"><i class="fas fa-download mr-2"></i> {{ file.name || file }}</a>
                                </div>
                            </div>
                            <span v-if="cartComponent" class="btn btn-secondary mr-2">{{ new Intl.NumberFormat('es-MX', { style : 'currency', currency : 'MXN' }).format(tramite.map(ele => ele.importe_tramite).reduce((a,b) => a+b)) }} </span>
                            <span v-if="tramite[0].info && tramite[0].descripcion && !cartComponent" class="btn btn-secondary mr-2">{{ tramite[0].descripcion || "CERRADO" }} </span>
                            <button class="btn btn-secondary" type="button" data-toggle="collapse" :data-target="`#collapse-${index}`" :aria-expanded="type != 2 ? 'true' : 'false'" :aria-controls="`collapse-${index}`"><i class="fas fa-chevron-down p-0"></i></button>
						</div>
					</div>
                    <div class="collapse" :id="`collapse-${index}`" :class="type != 2 ? 'show' : ''">
    					<tramite-component :different="tramite[0].different" :last="tramite[ind-1]" :cartComponent="cartComponent" :group="true" :type="type" v-for="(solicitud, ind) in tramite" @processToCart="processToCart" @processDelete="processDelete" :tramitesCart="tramitesCart" :tramite="solicitud" v-bind:key="ind" v-if="totalItems != 0"></tramite-component>
                    </div>
				</div>
				<tramite-component :cartComponent="cartComponent" :type="type" @processToCart="processToCart" @processDelete="processDelete" :tramitesCart="tramitesCart" :tramite="tramite[0]" v-bind:key="index"  v-if="tramite.length == 1 && totalItems != 0"></tramite-component>
			</div>
            <div class="card mb-4 pt-5" v-if="totalItems === 0">
                <div class="card-body">
                    <h3 class="text-center">{{ message || '¡Lo sentimos! No se encuentran registros para mostrar.' }}</h3>
                    <p class="text-center w-75 m-auto" v-if="cartComponent">Para continuar puedes <a class="card-link m-0 cursor-pointer" v-on:click="redirect('/nuevo-tramite')">inciar un tramite nuevo</a> o agregar trámites desde el listado de <a class="card-link m-0 cursor-pointer" v-on:click="redirect('/tramites/pendiente-de-pago/99')">Pendientes de Pago</a></p>
                </div>
            </div>
		</div>
		<div class="card card-custom">
		    <div class="card-body py-7">
		        <!--begin::Pagination-->
		        <div class="d-flex justify-content-between align-items-center flex-wrap">
		            <div class="d-flex flex-wrap mr-3" >
		                <a  class="btn btn-icon btn-sm btn-light-primary mr-2 my-1" :class="totalItems == 0 ? 'disabled' : ''" v-if="currentPage != 1" v-on:click="goto(1)">
		                    <i class="ki ki-bold-double-arrow-back icon-xs"></i>
		                </a>
		                <a  class="btn btn-icon btn-sm btn-light-primary mr-2 my-1" :class="totalItems == 0 ? 'disabled' : ''" v-if="currentPage != 1" v-on:click="goto(currentPage - 1)">
		                    <i class="ki ki-bold-arrow-back icon-xs"></i>
		                </a>
		                <a class="btn btn-icon btn-sm border-0 btn-hover-primary mr-2 my-1" v-for="(r) in pages"
		                v-bind:class="[ currentPage === r ? 'active' : '']" v-on:click="goto(r)"> 
		                   {{ r }}
		                </a>
		                <a  class="btn btn-icon btn-sm btn-light-primary mr-2 my-1" v-if="currentPage !== (pages.length)" :class="totalItems == 0 ? 'disabled' : ''" v-on:click="goto(currentPage + 1)">
		                    <i class="ki ki-bold-arrow-next icon-xs"></i>
		                </a>
		                <a  class="btn btn-icon btn-sm btn-light-primary mr-2 my-1" v-if="currentPage !== (pages.length)" :class="totalItems == 0 ? 'disabled' : ''"v-on:click="goto(pages[pages.length - 1])">
		                    <i class="ki ki-bold-double-arrow-next icon-xs"></i>
		                </a>
		            </div>
		            <div class="d-flex align-items-center">
		                <select class="form-control form-control-sm text-primary font-weight-bold mr-4 border-0 bg-light-primary" style="width: 75px;" v-model="limit" @change="calcularPage">
		                    <option value="10" selected>10</option>
		                    <option value="20">20</option>
		                    <option value="30">30</option>
		                </select>
		            </div>
		        </div>
		        <!--end:: Pagination-->
		    </div>
		</div>
	</div>
</template>
<script>
	export default {
		props: ['tramitesCart', 'type', 'cartComponent', 'items'],
		mounted(){
            localStorage.removeItem('datosFormulario');
            localStorage.removeItem('listaSolicitantes');
            localStorage.removeItem('tramite');
			
            this.calcularPage()
            this.pagination(1);

            Object.entries(this.tramitesPaginados).map(obj => {
                let [ind, tramite] = obj;
                let files = [];
                if(tramite[0].info && typeof tramite[0].info === 'string')
                    tramite[0].info = JSON.parse(tramite[0].info)
                if(tramite[0].mensajes && tramite[0].mensajes.length > 0){
                    tramite[0].mensajes.map(msg => {
                        if(msg.attach && msg.attach != ""){
                            files.push(msg.attach);
                        }
                    })
                }
                
                tramite[0].files = files;

                this.tramitesPaginados[ind] = tramite;
            })
		},
		data () {
			let attrs = this.$attrs;
			// if(!attrs.items) attrs.items = [];
			if(!attrs.currentPage) attrs.currentPage = 1;
			if(!attrs.pages) attrs.pages = 0;
			if(!attrs.index) attrs.index = 1;
			if(!attrs.tramitesPaginados) attrs.tramitesPaginados = [];
			attrs.limit = 30;
			attrs.totalItems = 0;
            attrs.user = window.user;
			if(!attrs.message) attrs.message = null;
			return attrs;
		},
        watch: {
            items (props) {
                this.items = props;
            }
        },
		methods : {
            goTo(tramite, _blank=false){
                if(typeof tramite === 'string') return redirect(tramite, _blank);
                if(window.location.href.indexOf("borradores") >= 0){
                    redirect("detalle-tramite/" + tramite.tramite_id + "?clave=" + tramite.clave, _blank);
                } else {
                    redirect(`/detalle${ tramite.id_tramite ? "-tramite" : "" }/` +  (tramite.id_tramite || tramite.id), _blank);
                }
                
            },
			processToCart(tramite){
				this.$emit('processToCart', tramite);
			},
            processDelete(tramite){
                this.$emit('processDelete', tramite);
            },
            calcularPage(){
                let pages = [];
                let pagesTotal = Math.ceil( this.items.length  / this.limit);
                if(pagesTotal == 0) pagesTotal = 1;
                for (var i = 0; i < pagesTotal; i++) {
                    pages.push( i + 1 );
                }
                this.pages = pages;
                this.goto(1);
            },
            pagination( page ){
            	let groups = {};
                let limitInt = parseInt(this.limit);
                let indiceInicial = (page - 1 ) * limitInt;
                let indiceFinal = ( (page - 1 ) * limitInt  )  + limitInt;


                this.tramitesPaginados = this.items.slice(indiceInicial, indiceFinal);
                this.tramitesPaginados.map(tramite => {
                    let group = tramite.grupo_clave || tramite.clave;
                	if(groups[group]) groups[group].push(tramite);
                	else groups[group] = [tramite];
                })

                Object.entries(groups).map(obj => {
                    let [key, tramites] = obj;
                    let name = `${tramites[0].nombre_servicio} ${tramites[0].nombre_servicio && (tramites[0].titulo && tramites[0].nombre_servicio.toLowerCase() != tramites[0].titulo.toLowerCase()) ? `- ${ (tramites[0].info && tramites[0].info.tipoTramite) || tramites[0].tramite || tramites[0].titulo }` : '' }`.toUpperCase()
                    if(tramites.length > 1)
                        tramites.map(tramite => {
                            let thisName = `${tramite.nombre_servicio} ${tramite.nombre_servicio && (tramite.titulo && tramite.nombre_servicio.toLowerCase() != tramite.titulo.toLowerCase()) ? `- ${ (tramite.info && tramite.info.tipoTramite) || tramite.tramite || tramite.titulo }` : '' }`.toUpperCase();
                            if(thisName !== name) groups[key][0].different = true;
                        });
                });

                this.tramitesPaginados = groups;
                // console.log(this.tramitesPaginados);
                this.totalItems = this.items.length;
            },
            goto( page ){ 
                console.log('pages', this.pages);
                this.pagination(page);
                this.currentPage = page;
            },
            addToCart(tramite, multiple=false, status=null){
                if(!multiple) this.tramites = [ tramite ];

                this.tramites.map(tramite => {
                    let status = tramite.en_carrito != 1 ? 1 : null;
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
            },
            redirect($url){
                redirect($url);
            }
		}
	};
</script>