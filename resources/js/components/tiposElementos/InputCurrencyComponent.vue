<template>
  <div class=" fv-plugins-icon-container">
    <label>
        {{ campo.nombre }}  {{JSON.parse(this.campo.caracteristicas + '').required == 'true' ? '*' : '' }}
    </label>
    <span class="currencyinput">
      <b-form-input
        :name="campo.nombre"
        type="text"
        class="form-control"
        style="background-color: #e5f2f5 !important"
        :placeholder="campo.nombre"
        :id="campo.campo_id+''"
        v-model="campo.valor"
        @change="validar"
        @focus="validar"
        :disabled="campo.disabled">
      </b-form-input>
    </span>
    <small  v-if="campo.mensajes && campo.mensajes.length > 0 && ( showMensajes || estadoFormulario > 0)" class="position-absolute">
          <p  class="form-text text-danger">
            {{ campo.mensajes[0].mensajeStr }}
          </p>
    </small>
  </div>
</template>

<script>
  import Vue from 'vue';
  export default {
    props: ['campo', 'estadoFormulario', 'showMensajes','divisa'],

      created() {
        this.validar();
      },
      watch: {
        divisa:  {
            handler: function (val, oldVal) {
                this.validar();
            },
            deep: true        
        },

      },
      mounted(){
        this.modelFormat();
      },
      methods: {
        modelFormat(value){
          let style = this.divisa.STYLE;
          let currency = this.divisa.CURRENCY;
          if(this.campo.valor){
            let number =  Vue.filter('toNumber')( this.campo.valor + "" );
            this.campo.valor =  Vue.filter('toCurrency')( number, style, currency  );
          }
        },
        getCaracteristicas(){
          let caracteristicas = {};
          try {
            caracteristicas = JSON.parse(this.campo.caracteristicas + '');
          }catch(err){
            console.log(err);
          }
          return caracteristicas;
        },

        validar(a){
          let exregValida = true;
          let requeridoValido = true;
          let caracteristicas = {};
          var caracteristicasStr = this.campo.caracteristicas;
          this.campo.mensajes = [];            
          try {
            caracteristicas = this.getCaracteristicas();
          }catch(err){
            console.log(err);
          }
          if( this.campo.valor && caracteristicas.expreg ){
            var regex = new RegExp(caracteristicas.expreg, "i");
            exregValida = regex.test(this.campo.valor)

            if( !exregValida ){
              let mensaje = { 
                tipo: 'regex',
                mensajeStr: "El campo " + this.campo.nombre.toLocaleLowerCase() + " no cumple con la regla de validación."
              }
              this.campo.mensajes.push( mensaje );
            }
          } 
          if( caracteristicas.hasOwnProperty('required') &&  caracteristicas.required === 'true' || (typeof  caracteristicas.required == 'boolean' && caracteristicas.required)  ) {
            requeridoValido =  !!this.campo.valor && (this.campo.valor+"").length > 0;
            if( !requeridoValido ){
              let mensaje = { 
                tipo:'required',
                mensajeStr: "El campo " + this.campo.nombre.toLocaleLowerCase() + " es requerido"
              }
              this.campo.mensajes.push( mensaje );
            }
          }

          this.campo.valido =  requeridoValido && exregValida;
          if(this.campo.valido){
            this.modelFormat()
          }
          this.$emit('updateForm', this.campo);
          
        },

      }
    }
</script>