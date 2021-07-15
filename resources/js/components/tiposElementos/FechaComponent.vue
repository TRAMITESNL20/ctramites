<template>
    <div class=" fv-plugins-icon-container" id="fechaElement">
        <label>
         {{ campo.nombre }}  {{JSON.parse(this.campo.caracteristicas + '').required == 'true' ? '*' : '' }}
        </label>
         
        <b-input-group >
          <b-form-input
            id="example-input"
            v-model="campo.show"
            type="text"
            placeholder="DD-MM-AAAA"
            autocomplete="off" style="background-color: #e5f2f5 !important" 
            @focus="validar"
            @change="cahngeEvent" 
          ></b-form-input>
          <b-input-group-append>
            <b-form-datepicker
              :id="campo.campo_id + ''"
              :name="campo.nombre"
              v-model="campo.valor"        
              @change="validar"
              @focus="validar"
              @input="validar"
              :show-decade-nav="showDecadeNav"
              button-only
              right
              aria-controls="example-input"
              @context="onContext"
            >
            </b-form-datepicker>
          </b-input-group-append>
        </b-input-group>
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
      data(){
        return {
          showDecadeNav: true,
          formatted: '',
        }
      },
      methods: {
        onContext(ctx) {
          if(ctx.selectedDate){
            let date = ctx.selectedYMD.split('-');
            if(date[2]) this.campo.show = `${date[2]}-${date[1]}-${date[0]}`;
          }
          this.formatted = ctx.selectedFormatted
          this.validar();
        },
        cahngeEvent(){
          //this.estadoFormulario = this.estadoFormulario + 1;
          this.validar();
        },
        validar(){
          let format = true;
          if(/\d{2}-\d{2}-\d{4}/.test(this.campo.show)){
            let date = this.campo.show.split('-');
            if(date[2]) this.campo.valor = `${date[2]}-${date[1]}-${date[0]}`;
          }else {
           format = false;
           this.campo.valor = this.campo.show;
          }

       		this.campo.mensajes = [];
          let requeridoValido = true;
          let validDate = false;
          let caracteristicas= this.getCaracteristicas();
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

            validDate = this.isValidDate(this.campo.valor);
            if( !validDate || !format ){
              let mensaje = { 
                tipo:'required',
                mensajeStr: "El campo " + this.campo.nombre.toLocaleLowerCase() + " no es valido"
              }
              this.campo.mensajes.push( mensaje );
            }

          this.campo.valido =  requeridoValido && validDate;
          this.$forceUpdate();
          this.$emit('updateForm', this.campo);
        },

        isValidDate(fecha) {
          var regex = /\d{4}-\d{2}-\d{2}/;
          if(regex.test(fecha)){
            let date = new Date(fecha);
            return date && Object.prototype.toString.call(date) === "[object Date]" && !isNaN(date);
          } else {
            return false;
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
      }
    }
</script>