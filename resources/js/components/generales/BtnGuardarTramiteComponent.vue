<template>
	<button type="button" :class="btnClass ? btnClass : 'btn btn-success font-weight-bolder text-uppercase px-9 py-4 mobil-font'"
		v-on:click="agregar()" :disabled="enviando">
        {{ labelBtn }}
        <div id="spinner-guardaFina" class="spinner-border spinner-border-sm float-right" role="status" 
        	v-if="enviando" style="margin-left: 5px;">
            <span class="sr-only">Loading...</span>
        </div>
    </button>
</template>

<script>
    import BtnGuardarTramiteParent from './BtnGuardarTramiteParent'
    export default {
        props: ['btnClass'],
        data() {
            return {
              enviando:false
            }
        },

        created(){  },
        extends: BtnGuardarTramiteParent, //heredamos del componente BtnGuardarTramiteParent!
        
        methods:{
            agregar(){
              let url = process.env.TESORERIA_HOSTNAME + (  this.type == 'temporal' ? "/solicitudes-register-temporal" : "/solicitudes-register" )
              this.guardar(url);
            },


            
            async guardar(url){
              
              let formData = null; 
              this.enviando = true;

              let datosTabs = JSON.parse( JSON.stringify(this.obtenerDatosTabs() ) );

              let listaSolicitantes = datosTabs[0];
              let tramite = datosTabs[1];
              let datosFormulario = datosTabs[2];

              let informacion = this.getInformacion( tramite, datosFormulario );
              
              if(this.tipoTramite == 'complementaria'){
                if(this.type != 'temporal'){
                  let detallesComplete = true;
                  this.datosComplementaria.complementarias.forEach( complementaria => {
                    detallesComplete = detallesComplete && !!complementaria.detalle && typeof complementaria.detalle == 'object'; 
                  });
                  formData = this.formDataComplementaria();

                  if(detallesComplete){
                    this.guardarTramiteUnico(formData, url );
                  } else {
                    this.enviando = false;
                    Command: toastr.warning("Aviso!", "Importe total requerido");
                  }
                } else {
                  formData = this.formDataComplementaria({ temporal:true });
                  this.guardarTramiteUnico(formData, url );
                }
              } else if(!!this.tieneEnajentantes(datosFormulario) && this.type != 'temporal' && this.tipoTramite != 'complementaria' ){
                let enajenantes = this.extraerEnajentantes(datosFormulario, tramite, informacion, listaSolicitantes );
                formData = this.getFormData(enajenantes);

                let detallesComplete = true;
       
                enajenantes.forEach( enajenante => {
                  detallesComplete = detallesComplete && !!enajenante.detalle && typeof enajenante.detalle == 'object'; 
                });

                
                if(detallesComplete ||  this.type == 'temporal'){
                  this.guardarTramiteUnico(formData, url );
                } else {
                  this.enviando = false;
                  Command: toastr.warning("Aviso!", "Importe total requerido");
                }
              } else {
                if(tramite.detalle ||  this.type == 'temporal'){
                  formData = this.getFormData();
                  this.guardarTramiteUnico(formData, url); 
                } else {
                  this.enviando = false;
                  Command: toastr.warning("Aviso!", "Importe total requerido");
                }

              }
            },

            async guardarTramiteUnico(formData, url){

              try {
                if(this.type === 'finalizar') formData.append('en_carrito', 1);
                let response = await axios.post(url, formData, {
                  headers:{
                      'Content-Type': 'application/json',
                  },
                });
                this.$emit('tramiteAgregadoEvent', {type:this.type, respuesta:true, response});
              } catch (error) {
                console.log(error);
                Command: toastr.warning("Error!", "No fue posible registrar intente de nuevo");
              }
              this.enviando = false;
            },

            tieneEnajentantes(datosFormulario){
              return datosFormulario.campos.find( (campo) => campo.tipo == 'enajenante');
            },

                
            extraerEnajentantes(datosFormulario, tramite, informacion, listaSolicitantes){
                let camposEnajenantes = this.tieneEnajentantes(datosFormulario);
                let id_seguimiento = tramite.id_seguimiento;
                if( !!camposEnajenantes && camposEnajenantes.valor && camposEnajenantes.valor.enajenantes){
                  let requests = [];
                  let enajenantes = enajenantes = camposEnajenantes.valor.enajenantes;
                  let listaEnajentantes = [];
                  enajenantes.forEach( (enajenante, indice) => {
                    let inf = Object.assign({} , informacion);
                      inf.version = '1.0.0';
                      inf.detalle = enajenante.detalle;
                      inf.enajenante = enajenante;
                      inf.id = 0;
                      inf.solicitante = listaSolicitantes[0];
                      listaEnajentantes.push(inf);
                      /*listaEnajentantes.push({
                        info: inf, id:0
                      })*/
                  });
                  return listaEnajentantes;
                }
            }





        }
    }
</script>

