<template>
    <div data-app class="col-lg-8" style="margin-bottom: 30px;">
        <div v-if="usuarios.length == 0">
            <div class="alert alert-warning d-flex justify-content-center mt-10 ml-25 mr-25">
                NO SE ENCONTRO NINGUN USUARIO
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
            <div class="row ">            
                <div class="col-md-4" v-for="usuario in filteredHelper" :key="usuario.id"> 
                    <div class="card " style="height: 300px;width: 280px;">
                            <div class="card-body"  style="position:relative">
                                <div class="text-center">
                                    <a type="button" ><img src="images/avatar6.png" class="mt-8" style="max-width: 70px;border-radius: 50%" ></img></a>
                                </div>
                            <div class="text-center pt-2">
                                <div>
                                        <span style="font-style: oblique;font-size: 15px">{{usuario.name }} {{usuario.mothers_surname}}  {{usuario.fathers_surname}}</span>
                                </div>
                                <div>
                                    <span class="form-text text-muted">{{usuario.email}} </span>
                                </div>
                            </div>
                                <div class="text-center" style="bottom:25px; position:absolute; padding-left: inherit;">
                                    <a :href="'mailto:'+ usuario.email " class="p-3 m-2" type="button" style="background-color: #d7e4f4;border-radius: 50%" ><i style="color: #347ab6" class="fas flaticon-multimedia"></i></a>
                                    <a class="p-3 m-2" type="button" style="background-color: #d2dae0;border-radius: 50%" ><i style="color: #25457e" class="fas fas fa-phone-alt"></i></a>
                                    <a class="p-3 m-2" type="button" style="background-color: #ddd5ea;border-radius: 50%" ><i style="color: #3f2174" class="fas flaticon2-arrow"></i></a>
                                </div>
                            </div>
                    </div>
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
            let url = ( process.env.TESORERIA_HOSTNAME +  '/session-api/notary-offices/'+ this.notary +'/users'); 
            let response =axiosInstance.get(url)
                .then((data) => {
                    this.usuarios = data.data.response.notary_office_users;
                    this.totalPaginas = Math.ceil(this.usuarios.length / this.porPagina);
                }).catch((error)=> {
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
                 // parametros con los que se basa la busqueda
                arr_aux = arr_aux .filter(search =>( search.name.includes(this.searchTitle) || search.mothers_surname.includes(this.searchTitle)  || search.fathers_surname.includes(this.searchTitle)  ))
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