<template>
    <div>
        <form ref="form">
            <b-row >
                <b-col>
                    <b-form-group label="Porcentaje de propiedad" label-for="procentaje-propiedad-input" >
                      <b-input-group >                        
                        <template #prepend>
                          <b-input-group-text >{{minPorcentajePermitido}}</b-input-group-text>
                        </template>
                        <template #append>
                          <b-input-group-text >{{maxProcentajePermitido}}</b-input-group-text>
                        </template>
                        <b-form-input  id="procentaje-propiedad-range" name="procentaje-propiedad"  v-model="$v.form.porcentajePropiedad.$model" :state="$v.form.porcentajePropiedad.$dirty ? !$v.form.porcentajePropiedad.$error : null" aria-describedby="porcentajePropiedad-input-feedback" type="range" :max="maxProcentajePermitido" @change="changePorcentajeProp"></b-form-input>
                      </b-input-group>
                      <b-form-input  id="procentaje-propiedad-input" name="procentaje-propiedad"  v-model="$v.form.porcentajePropiedad.$model" :state="$v.form.porcentajePropiedad.$dirty ? !$v.form.porcentajePropiedad.$error : null" aria-describedby="porcentajePropiedad-input-feedback" @change="changePorcentajeProp"></b-form-input>
                      
                      <b-form-invalid-feedback id="porcentajePropiedad-input-feedback">
                        <span v-if="!$v.form.porcentajePropiedad.required" class="form-text text-danger">
                          Valor requerido
                        </span>
                        <span v-if="!$v.form.porcentajePropiedad.between" class="form-text text-danger">
                          El valor debe de estar en un rango de {{minPorcentajePermitido}} a {{maxProcentajePermitido}};
                        </span>
                      </b-form-invalid-feedback>
                    </b-form-group>
                </b-col> 
                <b-col v-if="config.name == 'vendedor'">
                    <b-form-group label="Porcentaje de Venta" label-for="procentaje-venta-input" >
                      <b-input-group prepend="0" >
                        <template #append>
                          <b-input-group-text >100</b-input-group-text>
                        </template>
                        <b-form-input  id="procentaje-venta-range" name="procentaje-venta"  v-model="$v.form.porcentajeVenta.$model" :state="$v.form.porcentajeVenta.$dirty ? !$v.form.porcentajeVenta.$error : null" aria-describedby="porcentajeVenta-input-feedback" type="range" :max="maxProcentajeVentaPermitido"></b-form-input>
                      </b-input-group>
                      <b-form-input  id="procentaje-venta-input" name="procentaje-venta"  v-model="$v.form.porcentajeVenta.$model" :state="$v.form.porcentajeVenta.$dirty ? !$v.form.porcentajeVenta.$error : null" aria-describedby="porcentajeVenta-input-feedback" ></b-form-input>
                      
                      <b-form-invalid-feedback id="porcentajeVenta-input-feedback">
                        <span v-if="!$v.form.porcentajeVenta.required" class="form-text text-danger">
                          Valor requerido
                        </span>
                        <span v-if="!$v.form.porcentajeVenta.between" class="form-text text-danger">
                          El valor debe de estar en un rango de 1 a {{maxProcentajeVentaPermitido}};
                        </span>
                      </b-form-invalid-feedback>
                    </b-form-group>
                </b-col> 


            </b-row>
            <b-row>
                <b-col>
                    <b-form-group label="¿Presentar usufructo?" label-for="procentaje-propiedad-input" >
                        <input type="checkbox"    
                            id="presentaUsufructo"
                            name="presentaUsufructo"
                            v-model="form.presentaUsufructo"  @change="changeUsufructo" >
                    </b-form-group>
                </b-col>
                <b-col v-if="form.presentaUsufructo">
                    <b-form-group label="Usufructo" label-for="procentaje-usufructo-input" >
                      <b-input-group >
                        <template #prepend>
                          <b-input-group-text >{{minPorcentajeUPermitido}}</b-input-group-text>
                        </template>
                        <template #append>
                          <b-input-group-text >{{maxProcentajeuPermitido}}</b-input-group-text>
                        </template>
                        <b-form-input  id="procentaje-usufructo-range" name="procentaje-usufructo"  v-model="$v.form.porcentajeUsufructo.$model" :state="$v.form.porcentajeUsufructo.$dirty ? !$v.form.porcentajeUsufructo.$error : null" aria-describedby="porcentajeUsufructo-input-feedback" type="range" :max="maxProcentajeuPermitido"></b-form-input>
                      </b-input-group>
                      <b-form-input  id="procentaje-usufructo-input" name="procentaje-usufructo"  v-model="$v.form.porcentajeUsufructo.$model" :state="$v.form.porcentajeUsufructo.$dirty ? !$v.form.porcentajeUsufructo.$error : null" aria-describedby="porcentajeUsufructo-input-feedback" ></b-form-input>
                      
                      <b-form-invalid-feedback id="porcentajeUsufructo-input-feedback">
                        <span v-if="!$v.form.porcentajeUsufructo.required" class="form-text text-danger">
                          Valor requerido
                        </span>
                        <span v-if="!$v.form.porcentajeUsufructo.between" class="form-text text-danger">
                          El valor debe de estar en un rango de {{minPorcentajeUPermitido}} a {{maxProcentajeuPermitido}};
                        </span>
                      </b-form-invalid-feedback>
                    </b-form-group>
                </b-col>
            </b-row>
      
        </form>
        <b-alert show variant="secondary">
            <p class="text-justify">
                * Éste valor no representa el porcentaje total de la propiedad, sino, el correspondiente a la propiedad del(los) vendedores. Ésto es, si el(los) vendedor(es) posee un 50% de la propiedad y vende el 50% de su porcentaje, en realidad, está vendiendo el 25% del total de la propiedad.
            </p>
        </b-alert>
    </div>
</template>

<script>
    import { validationMixin } from 'vuelidate';
    import { required, between } from 'vuelidate/lib/validators';
    export default {
        mixins: [validationMixin],
        props: [ 'datosPorcentajes', 'config'],
        data(){
            return {
                form: {
                    porcentajePropiedad: 0,
                    porcentajeVenta: this.config.name == 'vendedor' ? 0 : null,
                    porcentajeUsufructo:0,
                    presentaUsufructo:false,
                },
                maxProcentajePermitido:100,
                maxProcentajeuPermitido:100,
                maxProcentajeVentaPermitido:100,
                minPorcentajePermitido:0,
                minPorcentajeUPermitido:0
            }
        },

        computed:{
            rules(){
                if(!this.form.presentaUsufructo){
                    this.minPorcentajePermitido =  1;
                    this.minPorcentajeUPermitido = 1;
                    return {
                        porcentajePropiedad:{required,  between: between(this.minPorcentajePermitido, this.maxProcentajePermitido) },
                        porcentajeVenta:{ required: this.config.name == 'vendedor', between: between(this.minPorcentajeUPermitido, this.maxProcentajeVentaPermitido) },
                    }
                } else {
                    this.minPorcentajePermitido =  0;
                    this.minPorcentajeUPermitido = 0;
                    return {
                        porcentajePropiedad:{required,  between: between(this.minPorcentajePermitido, this.maxProcentajePermitido) },
                        porcentajeVenta:{ required:this.config.name == 'vendedor', between: between(this.minPorcentajePermitido, this.maxProcentajeVentaPermitido) },
                        porcentajeUsufructo:{required,  between: between(0, this.maxProcentajeuPermitido) },
                        presentaUsufructo:{required }
                        
                    }                
                }                
            }
        },

        validations() {
            return {
                form: this.rules
            }
        },
        mounted() {
            
            if(Object.entries(this.datosPorcentajes).length > 0) {
                this.maxProcentajePermitido =  Number( Number(Number(this.maxProcentajePermitido)  - Number(this.config.porcentajePropiedadAsignado) + Number( this.datosPorcentajes.porcentajePropiedad) ).toFixed(this.$const.PRECISION)) ;
                this.maxProcentajeuPermitido =  Number( Number(Number(this.maxProcentajeuPermitido)  - Number(this.config.porcentajeUsufructoAsignado) + Number( this.datosPorcentajes.porcentajeUsufructo) ).toFixed(this.$const.PRECISION)) ;
                    
                this.form = this.datosPorcentajes;
            } else {
                this.maxProcentajePermitido = Number(Number(Number(this.maxProcentajePermitido)  - Number(this.config.porcentajePropiedadAsignado)).toFixed(this.$const.PRECISION)) ;
                this.maxProcentajeuPermitido =  Number( Number(Number(this.maxProcentajeuPermitido)  - Number(this.config.porcentajeUsufructoAsignado) ).toFixed(this.$const.PRECISION)) ;
            }
            this.$emit('estadoFormulario', this.$v)
        },
        methods: {
            changeUsufructo(){
                this.form.porcentajeUsufructo = this.form.porcentajePropiedad;
                //this.form.porcentajeUsufructo = Number(100 - Number( this.config.porcentajeUsufructoAsignado ) );
                //this.$emit('estadoFormulario', this.$v);
            },

            changePorcentajeProp(){
                this.form.porcentajeUsufructo = this.form.porcentajePropiedad;
                //this.$emit('estadoFormulario', this.$v);
            }
        },
        watch: {
            form:{
                deep:true,
                handler(){
                    this.$emit('estadoFormulario', this.$v);
                }
            }
        }

    }

</script>
