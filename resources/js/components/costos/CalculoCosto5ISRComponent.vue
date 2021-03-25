<template>

</template>
<script>

    import Vue from 'vue'
    const CAMPO_DIVISAS = "Cambio de divisas";
    const CAMPO_FECHA_DE_ESCRITURA_O_MINUTA                     = "FECHA DE ESCRITURA O MINUTA";

    export default {
        name: 'CalculoCosto5ISRComponent',
        props: ['datosParaDeterminarImpuesto', 'campos', 'tramite', 'tipoPersona', 'index', 'updateInfo'],
        mounted() {
            this.updateCostos();
        },

        watch: {
          updateInfo:{
            handler: function (val, oldVal) {
              this.$emit('obteniendoCostos', {calculandoCostos:true});
              this.updateCostos();
            },
          },
          tipoPersona:{
            handler: function (val, oldVal) {
              this.$emit('obteniendoCostos', {calculandoCostos:true});
              this.updateCostos();
            }
          },
        },
  
        methods: {

            getCampoByName( nameCampo ){
                return this.campos.find( campo => campo.nombre.toLowerCase()  === nameCampo.toLowerCase() );
            }, 


            formatoNumero(numberStr){
                return  Vue.filter('toNumber')(numberStr +""); 
            },


            updateCostos(){
                let paramsCosto = {};
                let multaCorreccion            = this.datosParaDeterminarImpuesto.multaCorreccion;

                paramsCosto.monto_operacion     = this.formatoNumero(this.datosParaDeterminarImpuesto.montoOperacion);
                paramsCosto.pago_provisional_lisr    = this.formatoNumero(this.datosParaDeterminarImpuesto.pagoProvisional);
                paramsCosto.ganancia_obtenida   = this.formatoNumero(this.datosParaDeterminarImpuesto.gananciaObtenida);
                let campoEscritura = this.getCampoByName(CAMPO_FECHA_DE_ESCRITURA_O_MINUTA);
                if(campoEscritura && campoEscritura.valor){
                    paramsCosto.fecha_escritura   = campoEscritura.valor.split("-").map(dato => Number(dato)).join("-");
                } else {
                    //alert("seleccione fecha")
                    return false;
                }
                let campoDivisas               = this.getCampoByName(CAMPO_DIVISAS);
                if( multaCorreccion ){
                    paramsCosto.multa_correccion_fiscal = this.formatoNumero(multaCorreccion);
                }
                if( campoDivisas ){
                    paramsCosto.divisa = campoDivisas.valor.clave;
                }

                this.getCosto(paramsCosto);
            },

            async getCosto(params){
                params.id_seguimiento = this.tramite.id_seguimiento,
                params.tramite_id = this.tramite.id_tramite,
                params.tipoPersona = this.tipoPersona
                try {
                    let url = process.env.APP_URL + "/getcostoImpuesto";
                    let response = await axios.post(url, params);
                    
                    this.$emit('costosObtenidos', {respuestaCosto:response.data, indice:this.index, success:true});

                } catch (error) {
                    console.log(error);
                    this.$emit('costosObtenidos', {respuestaCosto:error, indice:this.index, success:false});
                }
            },
        }
    }
</script>
