<template>
	<div class=" fv-plugins-icon-container">
		<label>{{ campo.nombre }}  {{JSON.parse(this.campo.caracteristicas + '').required == 'true' ? '*' : '' }}</label>
    <multiselect v-model="campo.valor" :options="options" :multiple="campo.tipo == 'multiple'" label="nombre" track-by="clave" :searchable="true" @input="validar" :disabled="campo.disabled" selectLabel="Presione enter para seleccionar" deselectLabel="Presione enter para eliminar"></multiselect>
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
        if(options && options.length > 0){
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
              let url =  process.env.TESORERIA_HOSTNAME + "/obtener-distrito/distrito/" + this.distrito.clave ;  
              let options = await this.obtenerOptions(url);
              
               this.options = options.map( option => {
                  option.claveEstado = this.estado.clave;
                  return option;
               });
            }
            
          }
           if( this.campo.nombre == 'Distrito'){

             let url =  process.env.TESORERIA_HOSTNAME + "/obtener-distritos";  
              let options = await this.obtenerOptions(url);
              
               this.options = options.map( option => {
                  option.claveEstado = this.estado.clave;
                  return option;
               });
            
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