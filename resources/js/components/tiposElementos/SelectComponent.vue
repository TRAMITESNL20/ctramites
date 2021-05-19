<template>
	<div class=" fv-plugins-icon-container">
		<label>{{ campo.nombre }}  {{JSON.parse(this.campo.caracteristicas + '').required == 'true' ? '*' : '' }}</label>
    <multiselect v-model="campo.valor" :options="options" :multiple="campo.tipo == 'multiple'" label="nombre" track-by="clave" :searchable="true" @input="validar" :disabled="campo.disabled" ></multiselect>
		<small v-if="campo.mensajes && campo.mensajes.length > 0 && ( showMensajes || estadoFormulario > 0)" class="position-absolute">
        <p  class="form-text text-danger">
          {{ campo.mensajes[0].mensajeStr }}
        </p>
		</small>
	</div>
</template>
<script>
  import Multiselect from 'vue-multiselect';
    export default {
      components: { Multiselect },
      data(){
          return {
            options:[],
          }
      },
      props: ['campo', 'estadoFormulario', 'showMensajes', 'estado', 'distrito'],

      created(){
        let options = JSON.parse(this.campo.caracteristicas).opciones;
        if(options.length > 0){
          if(options && options[0]["clave"] && options[0]["nombre"]){
            this.options = options;
          } else {
            this.options= options.map( (opcion, key) =>{
              let nombre = opcion[Object.keys(opcion)];
              let clave = Object.keys(opcion)[0];
              let newOption = { clave, nombre }
              return newOption;
            });
          }
        }
        
        this.validar();
        this.setOpciones();
      },
      mounted(){
        if(this.campo.nombre== "Cambio de divisas" && !this.campo.valor){
          this.campo.valor = {clave: "PESOS", nombre: "Pesos"}
          //$("#"+ this.campo.nombre.split(' ').join('_') ).val("PESOS");
          this.validar();
        }
      },
      methods: {
        async setOpciones(){
          if( this.campo.nombre == 'Estado'){
            let url = process.env.TESORERIA_HOSTNAME + "/obtener-estados" ; 
            console.log(url);
            let options = await this.obtenerOptions(url);
            this.options = options; 
          }
          if( this.campo.nombre == 'Municipio'){
            if(this.estado && this.distrito.clave == 0){
                let url =  process.env.TESORERIA_HOSTNAME + "/obtener-municipios/" + this.estado.clave ;  
                let options = await this.obtenerOptions(url);
                this.options = options.map( option => {
                  option.claveEstado = this.estado.clave;
                  return option;
                }); ; 
            }
            if(this.distrito && this.estado.clave == 19 && this.distrito.clave != 0){
              let url =  process.env.TESORERIA_HOSTNAME + "/obtener-municipios/" + this.estado.clave ;  
              let options = await this.obtenerOptions(url);
              switch (this.distrito.clave) {
                case 1:
                  options = options.filter(el => el.nombre == "Monterrey" || el.nombre == "Guadalupe" || el.nombre == "San Nicolás de los Garza" || el.nombre == "General Escobedo"  || el.nombre == "Santiago" || el.nombre == "Santa Catarina" || el.nombre == "Pesquería"  || el.nombre == "Villa de García"  || el.nombre == "Salinas Victoria" || el.nombre == "General Zuazua"  || el.nombre == "Ciénega de Flores"  || el.nombre == "El Carmen"  || el.nombre == "Hidalgo"  || el.nombre == "Mina" || el.nombre == "Abasolo" );
                  break;

                case  2 :
                  options = options.filter(el => el.nombre == "Cadereyta Jiménez" || el.nombre == "Los Ramones" || el.nombre == "Juárez");
                  break;

                case 3 :
                  options = options.filter(el => el.nombre == "Linares" || el.nombre == "Hualahuises");
                  break;

                case 4 :
                  options = options.filter(el => el.nombre == "Dr. Arroyo" || el.nombre == "Aramberri" || el.nombre == "General Zaragoza");
                  break;

                case 5 :
                  options = options.filter(el => el.nombre == "Cerralvo" || el.nombre == "Dr. González" || el.nombre == "Melchor Ocampo" || el.nombre == "Agualeguas" || el.nombre == "Treviño" || el.nombre == "Marín" || el.nombre == "Parás" || el.nombre == "Higueras");
                  break;

                case 6  :
                  options = options.filter(el => el.nombre == "Villaldama" || el.nombre == "Bustamante" || el.nombre == "Vallecillo" || el.nombre == "Sabinas Hidalgo" || el.nombre == "Anáhuac" || el.nombre == "Colombia" || el.nombre == "Lampazos");
                  break;

                case 7  :
                  options = options.filter(el => el.nombre == "Montemorelos" || el.nombre == "Allende" || el.nombre == "Rayones" || el.nombre == "Terán" );
                  break;

                case 8  :
                  options = options.filter(el => el.nombre == "Galeana" || el.nombre == "Iturbide" );
                  break;

                case 9  :
                  options = options.filter(el => el.nombre == "China" || el.nombre == "Dr. Coss" || el.nombre == "Los Herrera" || el.nombre == "General Bravo" || el.nombre == "Los Aldama" );
                  break;

                default: 
                  options = options
                  break;
              }
               this.options = options.map( option => {
                  option.claveEstado = this.estado.clave;
                  return option;
               });
              // this.options= [{ clave: 1, municipio: 'mty' }]
            }
            
          }
           if( this.campo.nombre == 'Distrito'){
              // const index = this.campos.map(e => e.nombre).indexOf('Distrito');
            this.options = [{clave:1 , nombre: "Distrito 1"},{clave:2 , nombre: "Distrito 2"},{clave:3 , nombre: "Distrito 3"},{clave:4 , nombre: "Distrito 4"},{clave:5 , nombre: "Distrito 5"},{clave:6 , nombre: "Distrito 6"},{clave:7 , nombre: "Distrito 7"},{clave:8 , nombre: "Distrito 8"},{clave:9, nombre: "Distrito 9"}]
           

              // this.campo.valor = this.distrito.clave;

            
          }
        },
        async obtenerOptions(url){
          let response = await axios.get(url);
          let options = response.data ? response.data : [];
          return options;
        },
        validar(){
          let requeridoValido = true;
          let caracteristicas = {};
          var caracteristicasStr = this.campo.caracteristicas;
          this.campo.mensajes = [];
            
          try {
            caracteristicas = JSON.parse(this.campo.caracteristicas + '');
          }catch(err){
            console.log(err);
          }
          if( caracteristicas.hasOwnProperty('required') && caracteristicas.required === 'true') {
            if(this.campo.tipo == 'multiple'){
              requeridoValido =  this.campo.valor && this.campo.valor.length > 0; 
            } else {
              requeridoValido =  !!this.campo.valor; 
            }
            if( !requeridoValido ){
              let mensaje = { 
                tipo:'required',
                mensajeStr: "El campo " + this.campo.nombre.toLocaleLowerCase() + " es requerido"
              }
              this.campo.mensajes.push( mensaje );
            }
          }
          this.campo.valido = requeridoValido;
          this.$emit('updateForm', this.campo);
          this.$forceUpdate();
        }
      },

      watch: {
        estado: function() {
          if( this.campo.nombre == 'Municipio'){
            this.options = [];
            // this.campo.valor = null; //si no se permiten municipios de diferentes estados descomentar esta linea
            if( this.estado &&  this.estado.clave){
              console.log('watcher estado');
              this.setOpciones();
            }
          }
        },
        distrito: function() {
          if(this.campo.nombre == 'Municipio'){
            if(this.distrito && this.distrito.clave){
              this.setOpciones();
            }
          }
        }
      },
      
      
    }
</script>