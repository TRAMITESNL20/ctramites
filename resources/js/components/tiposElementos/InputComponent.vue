<template>
  <div class=" fv-plugins-icon-container">
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

    export default {
      
      props: ['campo', 'estadoFormulario', 'showMensajes'],
      created() {
        this.validar();
      },
      methods: {
        formatear(){
          var number = this.campo.valor ? Number((this.campo.valor+"").replace(/[^0-9.-]+/g,"")) : "";
          let caracteristicas= this.getCaracteristicas();
          if( caracteristicas.formato == "moneda" && this.campo.valido){
            const formatter  = new Intl.NumberFormat('en-US', {
              style: 'currency',
              currency: 'MXN',
              minimumFractionDigits: 0
            });
          if( caracteristicas.formato == 'curp'){
                var self = this;
                let url = process.env.TESORERIA_HOSTNAME + "/consultar-curp/" + campo.valor ;  
                // let url = process.env.TESORERIA_HOSTNAME + "/insumos-catastro-consulta/7090036008";  
                $.ajax({
                    type: "GET",
                    dataType: 'json', 
                    url,
                    success:function(data){
                        // self.rellenarForm(data);
                        console.log('..se consulto el curp respuesta : ' + data );
                        // this.$data = data.data.nombres;
                    },
                    error:function(error){
                        console.log(error);
                    },
                    complete:function(){
                        console.log('ya quedo');
                    }
                });
          }
            this.campo.valor = formatter.format(number);
            
          }
          if (this.campo.valor) {
            if( caracteristicas.formato == 'curp' && this.campo.valor.length  ==  18  ){
              var self = this;
                console.log('------');
                let url = process.env.TESORERIA_HOSTNAME + "/consultar-curp/" + this.campo.valor ;  
                $.ajax({
                  type: "GET",
                    dataType: 'json', 
                    url,
                    success:function(data){
                      // self.rellenarForm(data);
                        console.log('..se consulto el curp respuesta : ' + data );
                        // this.$data = data.data.nombres;
                        self.$emit('curpSearch', data)
                    },
                    error:function(error){
                      console.log('error: ', error);
                    },
                    complete:function(){
                      console.log('ya quedo');
                    }
                });
            }
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
          if( caracteristicas.hasOwnProperty('required') && caracteristicas.required === 'true') {
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