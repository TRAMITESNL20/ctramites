<template>
  <div class=" fv-plugins-icon-container" v-if="visible">
    <label>
        {{ campo.nombre }}  {{JSON.parse(this.campo.caracteristicas + '').required == 'true' ? '*' : '' }}
    </label>
    <span class="currencyinput">
      <input
        :name="campo.nombre"
        type="text"
        class="form-control"
        style="background-color: #e5f2f5 !important"
        :placeholder="[[campo.nombre]]"
        :id="[[campo.campo_id]]"
        v-model="campo.valor"
        @change="validar"
        @focus="validar"
        :disabled="campo.disabled"
      />
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
      
      props: ['campo', 'estadoFormulario', 'showMensajes'],
      data() {
        return {
            visible:true
        }
      },
      created() {
        this.validar();
      },
      mounted(){
        let self = this;
        this.$root.$on('chambioDivisa',  function (data) {
          let caracteristicas= self.getCaracteristicas();
          if( caracteristicas.formato == "moneda" && self.campo.valido){
            let style = self.$store.state.DEFAULT_DIVISA.STYLE;
            let currency = self.$store.state.DEFAULT_DIVISA.CURRENCY;
            let number =  self.campo.valor ? Vue.filter('toNumber')( self.campo.valor ) : '';
            self.campo.valor = Vue.filter('toCurrency')( number, style, currency  );
          }
        });
        this.$root.$on('tipo_costo_obj_change',  function (data) {
          let caracteristicas= self.getCaracteristicas();
          if( self.campo.nombre == self.$const.NOMBRES_CAMPOS.CAMPO_VALOR_OPERACION){
            self.campo.valido = false;
            caracteristicas.required = !data.activo;
            self.visible = !data.activo;
            self.campo.caracteristicas = JSON.stringify(caracteristicas);
            self.validar()
          }
        });
      },
      methods: {
        formatear(){
          let caracteristicas= this.getCaracteristicas();
          if( caracteristicas.formato == "moneda" && this.campo.valido){
            let style = this.$store.state.DEFAULT_DIVISA.STYLE;
            let currency = this.$store.state.DEFAULT_DIVISA.CURRENCY;
            let number =  this.campo.valor ? Vue.filter('toNumber')( this.campo.valor ) : '';
            this.campo.valor = Vue.filter('toCurrency')( number, style, currency  );
          }
          if(caracteristicas.formato == 'manzana'){
           this.campo.valor =  this.padLeft(this.campo.valor, 3);  
          }
          this.$forceUpdate();
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

          if(a && a.target && a.target.name === this.campo.nombre)
            this.campo.valor = a.target.value;
            
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
          // console.log(caracteristicas.hasOwnProperty('required') && caracteristicas.required === 'true');
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
          this.formatear();
          this.$emit('updateForm', this.campo);
          
        },

        padLeft(value, length) {
            if(!value) return value;
            return (value.toString().length < length) ? this.padLeft("0" + value, length) : 
            value;
        }
      }
    }
</script>