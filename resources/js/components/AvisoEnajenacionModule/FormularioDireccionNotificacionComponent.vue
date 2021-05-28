<template>
    <div>
        <form ref="form">
            <b-row >
                <b-col>
                    <b-form-group label="Correo" label-for="correo-input" >
                        <b-form-input id="correo-input" name="correo" v-model="$v.form.correo.$model" aria-describedby="correo-input-feedback" :state="$v.form.correo.$dirty ? !$v.form.correo.$error : null" ></b-form-input>
                        <b-form-invalid-feedback id="correo-input-feedback">
                            <span v-if="!$v.form.correo.required"  class="form-text text-danger">
                                Correo requerido.
                            </span>
                            <span v-if="!$v.form.correo.email"  class="form-text text-danger">
                                El Correo no es válido.
                            </span>
                        </b-form-invalid-feedback>
                    </b-form-group>
                </b-col>
            </b-row>
            <b-row > 
                <b-col>
                    <b-form-group label="Teléfono fijo" label-for="telefono-fijo-input" >
                        <b-form-input id="telefono-fijo-input" name="telefono-fijo-input" v-model="$v.form.telefonoFijo.$model" aria-describedby="telefonoFijo-input-feedback" :state="$v.form.telefonoFijo.$dirty ? !$v.form.telefonoFijo.$error : null" ></b-form-input>
                        <b-form-invalid-feedback id="telefonoFijo-input-feedback">
                            <span v-if="!$v.form.telefonoFijo.required"  class="form-text text-danger">
                                Campo requerido.
                            </span>
                        </b-form-invalid-feedback>
                    </b-form-group>
                </b-col> 
                <b-col>
                    <b-form-group label="Teléfono de casa" label-for="telefono-casa-input" >
                        <b-form-input id="telefono-casa-input" name="telefono-casa-input" v-model="$v.form.telefonoCasa.$model" aria-describedby="telefonoCasa-input-feedback" :state="$v.form.telefonoCasa.$dirty ? !$v.form.telefonoCasa.$error : null" ></b-form-input>
                        <b-form-invalid-feedback id="telefonoCasa-input-feedback">
                            <span v-if="!$v.form.telefonoCasa.required"  class="form-text text-danger">
                                Campo requerido.
                            </span>
                        </b-form-invalid-feedback>
                    </b-form-group>
                </b-col>
            </b-row>
            <b-row>
                <b-col>
                    <b-form-group label="Tipo de vialidad" label-for="tipo-vialidad-select" >
                        <b-form-select id="tipo-vialidad-select" v-model="$v.form.tipoVialidad.$model"></b-form-select>
                    </b-form-group>
                </b-col>  
                <b-col>
                    <b-form-group label="Nombre de vialidad" label-for="nombre-vialidad-input" >
                        <b-form-input id="nombre-vialidad-input" name="nombre-vialidad-input" v-model="$v.form.nombreVialidad.$model" aria-describedby="nombre-vialidad-input-feedback" :state="$v.form.nombreVialidad.$dirty ? !$v.form.nombreVialidad.$error : null" ></b-form-input>
                        <b-form-invalid-feedback id="nombre-vialidad-input-feedback">
                            <span v-if="!$v.form.telefonoCasa.required"  class="form-text text-danger">
                                Campo requerido.
                            </span>
                        </b-form-invalid-feedback>
                    </b-form-group>
                </b-col>                
            </b-row>
            <b-row>
                <b-col>
                    <b-form-group label="N° Exterior" label-for="nExt-input" >
                        <b-form-input id="nExt-input" name="nExt-input" v-model="$v.form.nExt.$model" aria-describedby="nExt-input-feedback" :state="$v.form.nExt.$dirty ? !$v.form.nExt.$error : null" ></b-form-input>
                        <b-form-invalid-feedback id="nExt-input-feedback">
                            <span v-if="!$v.form.nExt.required"  class="form-text text-danger">
                                Campo requerido.
                            </span>
                        </b-form-invalid-feedback>
                    </b-form-group>
                </b-col> 
                <b-col>
                    <b-form-group label="N° Interior" label-for="nInt-input" >
                        <b-form-input id="nInt-input" name="nInt-input" v-model="$v.form.nInt.$model" aria-describedby="nInt-input-feedback" :state="$v.form.nInt.$dirty ? !$v.form.nInt.$error : null" ></b-form-input>
                        <b-form-invalid-feedback id="nInt-input-feedback">
                            <span v-if="!$v.form.nInt.required"  class="form-text text-danger">
                                Campo requerido.
                            </span>
                        </b-form-invalid-feedback>
                    </b-form-group>
                </b-col>
                <b-col>
                    <b-form-group label="Código Postal" label-for="cp-input" >
                        <b-form-input id="cp-input" name="cp-input" v-model="$v.form.cp.$model" aria-describedby="cp-input-feedback" :state="$v.form.cp.$dirty ? !$v.form.cp.$error : null" ></b-form-input>
                        <b-form-invalid-feedback id="cp-input-feedback">
                            <span v-if="!$v.form.cp.required"  class="form-text text-danger">
                                Campo requerido.
                            </span>
                        </b-form-invalid-feedback>
                    </b-form-group>
                </b-col>                  
            </b-row>
            <b-row>
                <b-col>
                    <b-form-group label="Colonia" label-for="colonia-input" >
                        <b-form-input id="colonia-input" name="colonia-input" v-model="$v.form.colonia.$model" aria-describedby="cp-input-feedback" :state="$v.form.colonia.$dirty ? !$v.form.colonia.$error : null" ></b-form-input>
                        <b-form-invalid-feedback id="colonia-input-feedback">
                            <span v-if="!$v.form.colonia.required"  class="form-text text-danger">
                                Campo requerido.
                            </span>
                        </b-form-invalid-feedback>
                    </b-form-group>
                </b-col> 
                <b-col>
                    <b-form-group label="Municipio" label-for="municipio-input" >
                        <b-form-input id="municipio-input" name="municipio-input" v-model="$v.form.municipio.$model" aria-describedby="municipio-input-feedback" :state="$v.form.municipio.$dirty ? !$v.form.municipio.$error : null" ></b-form-input>
                        <b-form-invalid-feedback id="municipio-input-feedback">
                            <span v-if="!$v.form.municipio.required"  class="form-text text-danger">
                                Campo requerido.
                            </span>
                        </b-form-invalid-feedback>
                    </b-form-group>
                </b-col>  
            </b-row>
            <b-row>
                <b-col>
                    <b-form-group label="Referencia" label-for="referencia-input" >
                        <b-form-input id="referencia-input" name="referencia-input" v-model="$v.form.referencia.$model" aria-describedby="referencia-input-feedback" :state="$v.form.referencia.$dirty ? !$v.form.referencia.$error : null" ></b-form-input>
                        <b-form-invalid-feedback id="referencia-input-feedback">
                            <span v-if="!$v.form.referencia.required"  class="form-text text-danger">
                                Campo requerido.
                            </span>
                        </b-form-invalid-feedback>
                    </b-form-group>
                </b-col>
            </b-row>
        </form>
    </div>
</template>

<script>
    import { validationMixin } from 'vuelidate';
    import { required, between, email  } from 'vuelidate/lib/validators';
    export default {
        mixins: [validationMixin],
        data(){
            return {
                form: {
                    correo:'',
                    telefonoFijo:'',
                    telefonoCasa:'',
                    tipoVialidad:'',
                    nombreVialidad:'',
                    nExt:'',
                    nInt:'',
                    cp:'',colonia:'',municipio:'',referencia:''
                },

            }
        },

        computed:{

        },

        validations() {
            return {
                form:{
                    correo: {required:false, email },
                    telefonoFijo:{required:false},
                    telefonoCasa:{ required: false},
                    tipoVialidad:{required: false},
                    nombreVialidad:{ required: false},
                    nExt:{required: false },
                    nInt:{required: false},
                    cp:{required: false},
                    colonia:{required: false},
                    municipio:{required: false},
                    referencia:{required: false},
                }
            }
        },
        mounted() {
            this.$emit('estadoFormulario', this.$v);
        },  
        methods: {

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
