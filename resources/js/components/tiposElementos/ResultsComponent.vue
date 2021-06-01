<template>
	<div data-app>
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
						<th v-for="field in fields">{{ field }}</th>
					</tr>
				</thead>
				<tbody>
					<tr v-if="loading"><td :colspan="fields.length" class="text-center"><i class="fas fa-spinner fa-spin mr-2"></i></td></tr>
					<tr v-if="!loading && row.length != 0" v-for="(row, ind) in filteredHelper">
						<td v-for="(item, ind) in row" :colspan=" row.length !== fields.length && ind === row.length - 1 && (fields.length - (row.length - 1)) " class="text-center">
							{{ typeof item == 'object' ? item.label :item }}
							<span class="text-muted ml-2 cursor-pointer" v-if="item.tooltip" v-b-tooltip.hover :id="`tooltip-${ind}`">(+ {{item.tooltip.listItems.length-1}})</span>
							<b-tooltip :target="`tooltip-${ind}`" triggers="hover" v-if="item.tooltip">
								<h4 v-if="item.tooltip.title" class="text-uppercase"><strong>{{ item.tooltip.title }}</strong></h4>
								<ul v-if="item.tooltip.listItems.length > 1" class="text-left ml-5">
									<li v-for="(item, ind) in item.tooltip.listItems">{{ item }}</li>
								</ul>
							</b-tooltip>
						</td>
						<td v-if="JSON.parse(campo.caracteristicas).formato == 'seleccion' ">
						<input type="radio" @change="check($event)"  :value="row" name="checkbox" :id="row.expediente" class="text-center pl-4 radio" >
						</td>
					</tr>
				</tbody>
			</table>
			<div v-if="Object.entries(this.infoExtra).length > 0" class="col-md-3 col-12">
				<ul v-if="infoExtra.listItems" class="list-group">
					<li v-if="infoExtra.title" class="list-group-item bg-secondary"><h3>{{ infoExtra.title }}</h3></li>
					<li v-for="item in infoExtra.listItems" class="list-group-item"><strong>{{ item.label }}:</strong> {{ item.value }}</li>
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
				next-icon="mdi-menu-right"
				prev-icon="mdi-menu-left"
				style="color:black !important"                
			></v-pagination>
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
		props: ['campo', 'estadoFormulario', 'showMensajes', 'info', 'table', 'fields', 'rows', 'loading', 'infoExtra'],
		data(){
			return{
				propaux: this.rows,
				selectedId: '',
				searchTitle: null,
				page: 1,
				porPagina: 5,
				totalPaginas:  JSON.parse(JSON.stringify(this.rows).length),
				pageSizes: [5, 10, 20],
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
			},
			loading: function(newVal, oldVal) {
				this.loading = newVal;
			},
			infoExtra: function(newVal, oldVal) {
				this.infoExtra = newVal;
			}
		},
		methods: {

			check: function (e ) {
				let total = 0;
                self = this;
 					Array.from(document.getElementsByClassName('radio')).forEach(function(row,index){
						 if(document.getElementsByClassName('radio')[index].checked){
							//_value.costo_final no es el expediente catastral pero menciono ray que el armaria este para mostrarlo mas facil en la tabla
							self.campo.valor= row._value.costo_final;
							self.$emit('updateForm', self.campo);
						}
					});
					
			},
			handlePageSizeChange(newPorpagina){
				this.porPagina = newPorpagina;
				this.totalPaginas = Math.ceil(this.rows.length / this.porPagina);
				this.page = 1;
        	},
		},
		computed:{
			filteredHelper(){ 
				var inicio= (this.porPagina*(this.page -1));
					var arr_aux = [...this.rows];
					if(this.searchTitle != null)  {
						this.searchTitle = this.searchTitle.toUpperCase();
						// parametros con los que se basa la busqueda
						console.log(arr_aux);
						arr_aux = arr_aux.filter(search =>( search.solicitante.rfc.toUpperCase().includes(this.searchTitle) || (search.costo_final + '').includes( this.searchTitle)    ))
						this.totalPaginas = Math.ceil(arr_aux.length / this.porPagina);
					}   
					var filteredHelper = arr_aux.splice( inicio  , this.porPagina);
				return filteredHelper;
        	},
		}
	}
</script>