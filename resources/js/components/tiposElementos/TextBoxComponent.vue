<template>
    <div class=" fv-plugins-icon-container" v-if="!campo.ocultar">
      <label> {{ campo.nombre }}  {{JSON.parse(this.campo.caracteristicas + '').required == 'true' ? '*' : '' }} </label>
      <textarea  
        :id="[[campo.idElemento ? campo.idElemento : campo.campo_id]]"
        :name="[[campo.campo_id]]" 
        class="form-control  form-control-lg " style="background-color: #e5f2f5 !important" v-model="campo.valor"
        @change="validar" ></textarea>
        <small   v-if="campo.mensajes && campo.mensajes.length > 0 && ( showMensajes || estadoFormulario > 0)" class="position-absolute">
            <p  class="form-text text-danger">
              {{ campo.mensajes[0].mensajeStr }}
            </p>
        </small>
      
    </div>
</template>

<script>
    export default {
      props: ['campo', 'estadoFormulario', 'showMensajes'],
      created() {
        //this.campo.valor = !!this.campo.valor ? this.campo.valor : ''; 
        //console.log(JSON.parse(JSON.stringify( this.campo )))
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


          if( caracteristicas.hasOwnProperty('required') && caracteristicas.required) {
            requeridoValido =  !!this.campo.valor && this.campo.valor.length > 0;
            if( !requeridoValido ){
              let mensaje = { 
                tipo:'required',
                mensajeStr: "El campo " + this.campo.nombre.toLocaleLowerCase() + " es requerido"
              }
              this.campo.mensajes.push( mensaje );
            }
          }

          this.$forceUpdate();
          this.campo.valido = requeridoValido;
          this.$emit('updateForm', this.campo);
        }
      },
      
      watch: {
          campo: {
            deep: true,
            handler(val, oldVal){
              this.$forceUpdate();
            }
          }
        }         
    }
</script>