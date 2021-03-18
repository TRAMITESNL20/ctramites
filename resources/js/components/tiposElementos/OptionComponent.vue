<template>
  	<div>
      <label> {{ campo.nombre }}</label>
  		<div class="" :class="campos_count ? 'd-inline-block mr-3' : ''" v-for="opcion in campo.caracteristicas.opciones">
  			<input type="radio" class=" "    
  				:id="[[campo.campo_id]]"
  			 	:name="[[campo.campo_id]]"
  			 	:value="[[Object.keys(opcion)[0] ]]" v-model="campo.valor" @change="validar" @focus="validar" >
  			 	<label> {{ opcion[Object.keys(opcion)[0]] }}</label>
  		</div>
	</div>
</template>

<script>
    export default {
      props: ['campo', 'estadoFormulario', 'showMensajes'],
      created() {
        if(typeof this.campo.caracteristicas == 'string')
          try{
            this.campo.caracteristicas = JSON.parse(this.campo.caracteristicas);
          }catch(err){
            console.log(err);
          }
          this.campos_count = this.campo.caracteristicas.opciones.length < 6;
        this.validar();
      },
      methods: {

        validar(){

          let requeridoValido = true;
          let caracteristicas = {};
          var caracteristicasStr = this.campo.caracteristicas;
          this.campo.mensajes = [];
          

          if( caracteristicas.hasOwnProperty('required') && caracteristicas.required) {
            requeridoValido =  !!this.campo.valor;
            if( !requeridoValido ){
              let mensaje = { 
                tipo:'required',
                mensajeStr: "El campo " + this.campo.nombre.toLocaleLowerCase() + " es requerido"
              }
              this.campo.mensajes.push( mensaje );
            }
          }
          this.campo.valido = requeridoValido ;
          this.$emit('updateForm', this.campo);
        }
      }
    }
</script>