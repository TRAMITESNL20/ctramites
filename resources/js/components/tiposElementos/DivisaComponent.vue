<template>
	<div class=" fv-plugins-icon-container">
		<label>{{ campo.nombre }} divisa  {{JSON.parse(this.campo.caracteristicas + '').required == 'true' ? '*' : '' }}</label>
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
      props: ['campo', 'estadoFormulario', 'showMensajes', 'estado'],

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
      },
      mounted(){
        this.campo.valor = {clave: this.$store.state.DEFAULT_DIVISA.CLAVE, nombre: this.$store.state.DEFAULT_DIVISA.NAME} 
        this.validar();
      },
      methods: {
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


      
      
    }
</script>