<template>
    <div data-app class="col-lg-8">
        <div v-if="loading">
            <div class="card d-flex justify-content-center">
                <div class="card-body text-center">
                    <i class="fas fa-circle-notch fa-spin mr-2"></i> BUSCANDO USUARIOS...
                </div>
            </div>
        </div>
        <div v-if="usuarios.length == 0 && !loading">
            <div class="card bg-warning d-flex justify-content-center">
                <div class="card-body text-center text-white">
                    NO SE ENCONTRO NINGUN USUARIO
                </div>
            </div>
        </div>


        
        <div v-if="usuarios.length != 0">
            <!-- busqueda y numero de usuarios por pagina -->
            <div class="row">
                <v-col class="col-md-6 col-sm-1 " >
                        <v-select
                            :items="pageSizes"
                            v-model="porPagina"
                            label="Usuarios por pagina"
                            @change="handlePageSizeChange"
                        >
                        </v-select>       
                </v-col>
                <div class="col-md-6 col-sm-8">
                    <v-text-field v-model="searchTitle" label="buscar usuario"></v-text-field>
                </div>
            </div>

            <!-- lista de usuarios -->
            
              <div>
                <div class="w-100" v-for="(usuario, index) in filteredHelper" >
                    <div class="card list-item card-custom mb-5 col-lg-12" style="background-color: rgb(217, 222, 226) !important" >
                        <div class="d-flex mobile-lista-multiple align-items-center mb-3">
                        <!---->
                        <div class="symbol symbol-75 mr-5">
                            <span class="symbol-label font-size-h2 font-weight-bold bg-dark-o-30"> {{  usuario.name.substr(0,1) }} {{ usuario.fathers_surname.substr(0,1 )  }}</span>
                        </div>
                        <div class="mr-auto espace-checkbox-text desktop-agrupacion-width" style="width: 70%">
                            <h4 class="ml-3 text-uppercase text-truncate">
                            <strong>{{ usuario.name }} {{ usuario.mothers_surname}} {{usuario.fathers_surname}}</strong>
                            </h4>
                            <h5 class="ml-3">
                            <span style="font-weight: normal" >
                                <strong> {{ usuario.roles.description }}   </strong> - {{usuario.curp}}
                            </span>
                            </h5>
                        </div>
                        <div class="my-lg-0 my-1">
                            <!---->
                            <div  class="input-group-append">
                            <!-- alerta de que no puedes descargar el documento -->
<!-- 
                            <button 
                                class="btn btn-danger disabled" 
                                style="border-top-right-radius: 0px;border-bottom-right-radius: 0px;font-size: 7px;border-radius: 1 1 0 0 !important;" 
                                data-toggle="tooltip" 
                                data-placement="top" 
                                data-original-title="No es posbile descargar este archivo al no contar con el documento del CALCULO DEL ISR CONFORME AL 126 LISR O COMPROBANTE DE LA EXENCIÓN ">
                                    <i class="text-white fa fa-question-circle"></i>
                            </button> -->

                                <!-- boton de descarga  -->
                                <a  href="mailto:usuario.email" target="_blank"  class="btn btn-primary font-weight-bolder text-uppercase text-white mr-5" >Enviar Correo </a>
                                <!-- boton de collapse -->
                            </div>
                            <!---->
                        </div>
                        </div>

                    </div>
                <!---->
                </div>

            </div>






            <!-- paginado -->
            <div  class="row">
                <div class="col-md-12 col-sm-12">
                <v-pagination
                    v-model="page"
                    :length="totalPaginas"
                    circle
                    total-visible="7"
                    next-icon="mdi-menu-right"
                    prev-icon="mdi-menu-left"                
                ></v-pagination>
                </div>
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
    data() {
        return{
            usuarios:  [
         
             
            ],
            helper: [],            
            searchTitle: null,
            page: 1,
            totalPaginas: 0,
            porPagina: 12,
            
            items: ['Foo', 'Bar', 'Fizz', 'Buzz'],

            pageSizes: [12, 15, 21],
            notary : this.$attrs.notary,
            loading: true
        }
    },
    // props: ['rol_id'],
    methods: {
        leerDatos() {
            axios.defaults.headers.common['Authorization'] = '';

            const axiosInstance = axios.create({
            headers: {
                "Access-Control-Allow-Origin": "*",
                'Content-Type': 'application/json',
            }
            });
            // todo: agregar el rol_id dinamico 
            let url = (`${process.env.SESSION_HOSTNAME}/notary-offices/${this.notary}/users`); 
            let response =axiosInstance.get(url)
                .then((data) => {
                    this.loading=false;
                    this.usuarios = data.data.response.notary_office_users;
                    this.totalPaginas = Math.ceil(this.usuarios.length / this.porPagina);
                }).catch((error)=> {
                    this.loading=false;
                    console.log(error)
                    this.porPagina = 0 
                })
            
        },
    
        handlePageSizeChange(newPorpagina){
            this.porPagina = newPorpagina;
            this.totalPaginas = Math.ceil(this.usuarios.length / this.porPagina);
            this.page = 1;
        },

    },
    computed:{
         filteredHelper(){ 
           var inicio= (this.porPagina*(this.page -1));
            var arr_aux = [...this.usuarios];
             if(this.searchTitle != null)  {
                 this.searchTitle = this.searchTitle.toUpperCase();
                 // parametros con los que se basa la busqueda
                arr_aux = arr_aux .filter(search =>( search.name.toUpperCase().includes(this.searchTitle) || search.mothers_surname.includes(this.searchTitle)  || search.fathers_surname.includes(this.searchTitle)  ))
                this.totalPaginas = Math.ceil(arr_aux.length / this.porPagina);
             }   
            var filteredHelper = arr_aux.splice( inicio  , this.porPagina);
           return filteredHelper;
        },
    
    },
    beforeMount(){
        this.leerDatos();
    },

}
</script>