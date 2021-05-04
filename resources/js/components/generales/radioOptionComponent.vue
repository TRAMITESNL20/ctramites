<template>
    <div class="col-md-12 col-lg-12">
        <div class="text-center">
            <div class="custom-control custom-radio custom-control-inline"  v-for="(opcion, i) in opciones" :key="i">
                <input type="radio" :value="opcion.value"  :name="opcion.name" class="custom-control-input" :id="opcion.id" v-model="modelo"  @change="cambioInput" :disabled="opcion.disabled">
                <!--<input  name="tipoPersona"  >-->
                <label class="custom-control-label" :for="opcion.id">
                  {{opcion.label}}
                </label>
            </div>
        </div>
    </div>   
</template>

<script>
    export default {
        data() {
            return {
                opciones : [{
                    value:'normal',
                    name:'normal',
                    id:'checkTipoTramiteNormal',
                    label:'Normal',
                    disabled: 'normal' == this.disabledDefault
                },
                {
                    value:'complementaria',
                    name:'complementaria',
                    id:'checkTipoTramiteComp',
                    label:'Complementaria',
                    disabled: true
                },{
                    value:'declaracionEn0',
                    name:'declaracionEn0',
                    id:'checkTipoTramite0',
                    label:'Declarar en Cero',
                    disabled: 'declaracionEn0' == this.disabledDefault
                }],
                modelo:null
            }
        },
        props: ['default', 'disabledDefault'],
        mounted() {
            this.modelo = this.default;
        },

        methods:{
            cambioInput(){
                this.$emit('valueRadio', this.modelo);
            }
        }
    }
</script>


