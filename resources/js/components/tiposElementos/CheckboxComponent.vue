<template>
    <div class="">
      <input type="checkbox"    
        :id="[[campo.campo_id]]"
        :name="[[campo.campo_id]]"
        v-model="campo.valor" @change="validar" @focus="validar" :disabled="campo.disabled">
        <label> {{ campo.nombre }}</label>
    </div>
</template>
  
<script>

    export default {
      
      props: ['campo', 'estadoFormulario', 'showMensajes'],
      created() {
        this.validar();
      },
      methods: {

        validar(){
          this.campo.valido = true;
          this.$emit('updateForm', this.campo);
        }
      }
    }
</script>