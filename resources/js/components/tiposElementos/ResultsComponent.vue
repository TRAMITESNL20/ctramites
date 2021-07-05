<template>
	<div data-app>
		<div v-if="registros">
			<div class="row">
                <v-col class="col-md-6 col-sm-1 " >
                        <v-select
                            :items="pageSizes"
                            v-model="porPagina"
                            label="Expediente por pagina"
                            @change="handlePageSizeChange"
                        >
                        </v-select>       
                </v-col>
                <div class="col-md-6 col-sm-8">
                    <v-text-field v-model="searchTitle" label="buscar Expediente"></v-text-field>
                </div>
        	</div>


			<div class="row">
				<input hidden :id="[[campo.campo_id]]" :name="[[campo.campo_id]]" :value="info">
				<table width="100%" class="table table-hover table-striped mt-3" :class="Object.entries(this.infoExtra).length > 0 && 'col-md-9 col-12' ">
					<thead class="thead-light">
						<tr v-if="fields.length != 0">
							<th v-for="(field , ind) in fields" :key="ind" >{{ field }}</th>
						</tr>
						
					</thead>
					<tbody>
						<tr v-if="loading"><td :colspan="fields.length" class="text-center"><i class="fas fa-spinner fa-spin mr-2"></i></td></tr>
						<tr v-if="!loading && row.length != 0 && !seleccionado" v-for="(row, ind) in filteredHelper" :key="ind">
							<td v-for="(item, ind) in row" :key="item.expediente_catastral" :colspan=" row.length !== fields.length && ind === row.length - 1 && (fields.length - (row.length - 1)) " class="text-center">
								{{ typeof item == 'object' ? item.label :item }}

								<span class="text-muted ml-2 cursor-pointer" v-if="item.tooltip" v-b-tooltip.hover :id="`tooltip-${ind}`">(+ {{item.tooltip.listItems.length-1}})</span>
								<b-tooltip :target="`tooltip-${ind}`" triggers="hover" v-if="item.tooltip">
									<h4 v-if="item.tooltip.title" class="text-uppercase"><strong>{{ item.tooltip.title }}</strong></h4>
									<ul v-if="item.tooltip.listItems.length > 1" class="text-left ml-5">
										<li v-for="(item, ind) in item.tooltip.listItems">{{ item }}</li>
									</ul>
								</b-tooltip>
							<!-- <code>{{ item.expediente_catastral }}</code> -->
							</td>
							<td v-if="JSON.parse(campo.caracteristicas).formato == 'seleccion' ">
								<!-- <input type="radio" @change="check(row.expediente_catastral)" :value="row" class="btn-check" name="checkbox" :id="row.expediente_castastral" autocomplete="off" checked> -->
								<div class="btn-group-toggle" data-toggle="buttons">
									<label class="btn btn-primary" :id="ind">
										<input type="radio" @click="check(row, ind)"  > {{labelRadio}}
									</label>
								</div>
								<!-- <input type="radio" @change="check($event)"  :value="row" name="checkbox" :id="row.expediente_castastral" class="text-center pl-4 radio" > -->
							</td>
						</tr>
						<tr v-if="seleccionado" >
							<td v-for="(expediente, ind) in expedienteSeleccionado" :key="ind" class="text-center">
								{{expediente}}
							</td>
							<td>
								<div class="btn-group-toggle" data-toggle="buttons">
									<label class="btn btn-danger" id="1">
										<input type="radio" @click="check(expediente, 1)"  > {{labelRadio}}
									</label>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
				<div v-if="Object.entries(this.infoExtra).length > 0" class="col-md-3 col-12">
					<ul v-if="infoExtra.listItems" class="list-group">
						<li v-if="infoExtra.title" class="list-group-item bg-secondary"><h3>{{ infoExtra.title }}</h3></li>
						<li v-for="(item, ind) in infoExtra.listItems" :key="ind" class="list-group-item"><strong>{{ item.label }}:</strong> {{ item.value }}</li>
					</ul>
				</div>
			</div>

			<div  class="row">
				<div class="col-md-12 col-sm-12">
				<v-pagination
					v-model="page"
					:length="totalPaginas"
					circle
					total-visible="7"
					color="black"
					next-icon="mdi-menu-right"
					prev-icon="mdi-menu-left"
					style="color:black !important"                
				></v-pagination>
				</div>
			</div>
		</div>
		<div v-if="registros == null">
			<div class="alert alert-warning text-center" role="alert">
			 	NO HAY ELEMENTOS PARA MSOTRAR
			</div>
		</div>	

	</div>
</template>

<script>
import Vue from 'vue';
import Vuetify from 'vuetify';

Vue.use(Vuetify);
	export default {
		vuetify: new Vuetify(),
		props: ['campo', 'estadoFormulario', 'showMensajes', 'info', 'table', 'fields', 'rows', 'loading', 'infoExtra', 'response'],
		data(){
			return{
				propaux: '',
				selectedId: '',
				searchTitle: null,
				page: 1,
				porPagina: 5,
				totalPaginas:  JSON.parse(JSON.stringify(this.rows).length),
				pageSizes: [5, 10, 20],
				labelRadio: 'Seleccionar',
				seleccionado: false,
				expediente:'',
				expedienteSeleccionado: '',
				registros: true,
		}},
		mounted () {
			this.campo.valido = true;
			this.$emit('updateForm', this.campo);
		},
		watch : {
			rows: function(newVal, oldVal) {
				this.rows = newVal;
			},
			info: function(newVal, oldVal) {
				this.info = newVal;
				console.log(this.info);
			},
			loading: function(newVal, oldVal) {
				this.loading = newVal;
			},
			infoExtra: function(newVal, oldVal) {
				this.infoExtra = newVal;
			},
			response: function(response){
				this.campo.valor = response.filter(ele => ele.bloqueado && ele.bloqueado === '0');
				this.$emit('updateForm', this.campo);
			}
		},
		methods: {
			check: function (e, ind ) {
				//_value.expediente_castastral es el expediente catastral  
				if (document.getElementById(ind).classList.contains('btn-primary') ){
						document.getElementById(ind).classList.remove('btn-primary');
						document.getElementById(ind).classList.add('btn-danger');		
						this.labelRadio = 'Deseleccionar';
						this.campo.valor= e.expediente_catastral;
						this.seleccionado = true;
						this.totalPaginas = 1;
						this.expedienteSeleccionado = e;
				}else{
						document.getElementById(ind).classList.remove('btn-danger');		
						document.getElementById(ind).classList.add('btn-primary');
						this.labelRadio = 'Seleccionar'
						this.campo.valor= null;
						this.seleccionado = false;
						this.searchTitle= '0'
						this.expedienteSeleccionado= '0'
				}

				this.$emit('updateForm', this.campo);

			},
			handlePageSizeChange(newPorpagina){
				this.porPagina = newPorpagina;
				this.totalPaginas = Math.ceil(this.rows.length / this.porPagina);
				this.page = 1;

        	},
		},
		computed:{
			filteredHelper(e){ 
				var inicio= (this.porPagina*(this.page -1));
				var arrayFinal = []; 
				if(JSON.parse(this.campo.caracteristicas).formato == 'seleccion'){
					this.propaux = this.rows;
					if(this.propaux.length > 0){
						for (let i = 0; i < this.propaux.length; i++) {
							if(this.propaux[i].camposConfigurados){
								for (let k = 0; k < this.propaux[i].camposConfigurados.length; k++) {	
									if(this.propaux[i].camposConfigurados[k].nombre === "Resultados Informativo Valor Catastral"  && this.propaux[i].camposConfigurados[k].valor ){
										for (let z = 0; z < this.propaux[i].camposConfigurados[k].valor.length ; z++) {
											if(this.propaux[i].camposConfigurados[k].valor[z].expediente_catastral ){
												arrayFinal.push({"expediente_catastral" : this.propaux[i].camposConfigurados[k].valor[z].expediente_catastral , "folio":"", "Días Restantes": "", "Fecha pago informativo": "", "Capturista" : "" })
											}	
										}
									}
								}
							}
						}
					}
					else{
						console.log('no hay registros para mostrar');
						this.registros = null
					}
					if(this.searchTitle != null)  {
						this.searchTitle = this.searchTitle.toUpperCase();
						// parametros con los que se basa la busqueda
						if(JSON.parse(this.campo.caracteristicas).formato == 'seleccion'){
							arrayFinal = arrayFinal.filter(search =>(  search.expediente_catastral + '').includes( this.searchTitle) )
						}	
						this.totalPaginas = Math.ceil(arrayFinal.length / this.porPagina);
					}   
					var filteredHelper = arrayFinal.splice( inicio  , this.porPagina);
					
				}else{
					var filteredHelper = this.rows;
					this.totalPaginas = Math.ceil(this.rows.length / this.porPagina);
					filteredHelper.length < 0 ? 	this.registros = null : '' ;
				}
				
				return filteredHelper;
        	},
		}
	}
</script>