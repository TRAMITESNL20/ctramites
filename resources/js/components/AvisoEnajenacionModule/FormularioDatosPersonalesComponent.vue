<template>
    <div>
        <form ref="form">
            <b-row>
                <b-col cols="12" md="12">
                    <b-form-group label="Seleccione una nacionalidad" v-slot="{ ariaDescribedby }">
                        <b-form-radio-group id="nacionalidad" v-model="$v.form.nacionalidad.$model" :aria-describedby="ariaDescribedby" name="radio-options-nacionalidad">
                            <template #first>
                                <b-form-radio value="extrajero">Extrajero</b-form-radio>
                            </template>
                            <b-form-radio value="mexicana">Mexicana</b-form-radio>
                        </b-form-radio-group>
                    </b-form-group>
                </b-col>
                <b-col>
                    <b-form-group label="Tipo de Propietario" label-for="tipo-propietario-select" >
                        <b-form-select id="tipo-propietario-select" v-model="$v.form.tipoPropietario.$model" :options="options"></b-form-select>
                    </b-form-group>
                </b-col>   
                <b-col cols="12" md="12">
                    <b-form-group label="Seleccione el tipo de persona" v-slot="{ ariaDescribedby }" v-if="form.nacionalidad == 'mexicana'">
                        <b-form-radio-group id="tipoPersona" v-model="$v.form.tipoPersona.$model" :aria-describedby="ariaDescribedby" name="radio-options-tipoPersona">
                            <template #first>
                                <b-form-radio value="pf">Fisica</b-form-radio>
                            </template>
                            <b-form-radio value="pm">Moral</b-form-radio>
                        </b-form-radio-group>
                    </b-form-group>
                </b-col>
            </b-row>
            <b-row v-if="form.nacionalidad == 'mexicana'">
                <b-col cols="12" md="6" v-if="form.tipoPersona == 'pf'">
                    <b-form-group label="CURP" label-for="curp-input" >
                        <b-form-input id="curp-input" name="curp" v-model="$v.form.curp.$model" aria-describedby="curp-input-feedback" :state="$v.form.curp.$dirty ? !$v.form.curp.$error : null" v-uppercase @change="updateCurp"></b-form-input>
                        <b-form-invalid-feedback id="curp-input-feedback">
                            <span v-if="!$v.form.curp.required"  class="form-text text-danger">
                                CURP es requerida.
                            </span>
                            <span v-if="!$v.form.curp.curpPattern"  class="form-text text-danger">
                                La CURP no cumple con la regla de validación.
                            </span>
                            <!--
                            <span v-if="!$v.form.curp.isUnique"  class="form-text text-danger">
                                Esta CURP ya se encuentra agregada
                            </span>-->
                        </b-form-invalid-feedback>
                    </b-form-group>
                </b-col>
                <b-col cols="12" md="6">
                    <b-form-group label="RFC" label-for="rfc-input" >
                        <b-form-input id="rfc-input" name="rfc" v-model="$v.form.rfc.$model"  :state="$v.form.rfc.$dirty ? !$v.form.rfc.$error : null"  aria-describedby="rfc-input-feedback" v-uppercase></b-form-input>
                        <b-form-invalid-feedback id="rfc-input-feedback">
                            <span v-if="!$v.form.rfc.required"  class="form-text text-danger">
                                El RFC es requerido.
                            </span>
                            <span v-if="!$v.form.rfc.rfcPattern"  class="form-text text-danger">
                                El RFC no cumple con la regla de validación.
                            </span>
                        </b-form-invalid-feedback>
                    </b-form-group>
                </b-col>
                <b-col cols="12" md="6" v-if="form.tipoPersona == 'pm'">
                    <b-form-group label="Razón Social" label-for="razon-social-input" >
                        <b-form-input id="razon-social-input" name="razonSocial" v-model="$v.form.razonSocial.$model" aria-describedby="razonSocial-input-feedback" :state="$v.form.razonSocial.$dirty ? !$v.form.razonSocial.$error : null" v-uppercase></b-form-input>
                        <b-form-invalid-feedback id="razonSocial-input-feedback">
                            <span v-if="!$v.form.razonSocial.required"  class="form-text text-danger">
                                Campo requerido
                            </span>
                        </b-form-invalid-feedback>
                    </b-form-group>
                </b-col>
            </b-row>  
            <b-row v-if="form.tipoPersona == 'pf' || form.nacionalidad == 'extrajero'" >
                <b-col  cols="12" md="6">
                    <b-form-group label="Nombre(s)" label-for="nombre-input" >
                        <b-input-group class="mt-3" id="loadingcurp">
                            <template #append v-if="buscandoCurp">
                                <b-input-group-text >
                                    <strong class="loadingcurp">
                                        <b-spinner label="Loading..." small ></b-spinner>
                                    </strong>
                                </b-input-group-text>
                            </template>
                            <b-form-input id="nombre-input" name="nombre" v-model="$v.form.nombre.$model" aria-describedby="nombre-input-feedback" :state="$v.form.nombre.$dirty ? !$v.form.nombre.$error : null" :disabled="curpEncontrada || buscandoCurp" v-uppercase></b-form-input>
                            <b-form-invalid-feedback id="nombre-input-feedback">
                                <span v-if="$v.form.nombre.$invalid"  class="form-text text-danger">
                                    El Nombre es requerido.
                                </span>
                            </b-form-invalid-feedback>
                        </b-input-group>
                    </b-form-group>
                </b-col>
                <b-col  cols="12" md="6">
                    <b-form-group label="Apellido Paterno" label-for="apPat-input" >
                      <b-input-group class="mt-3" id="loadingcurp">
                        <template #append v-if="buscandoCurp">
                          <b-input-group-text >
                            <strong class="loadingcurp">
                             <b-spinner label="Loading..." small ></b-spinner>
                            </strong>
                          </b-input-group-text>
                        </template>
                        <b-form-input id="apPat-input" name="apPat" v-model="$v.form.apPat.$model"  :state="$v.form.apPat.$dirty ? !$v.form.apPat.$error : null"  aria-describedby="apPat-input-feedback"  :disabled="curpEncontrada || buscandoCurp" v-uppercase></b-form-input>
                        <b-form-invalid-feedback id="apPat-input-feedback">
                            <span v-if="!$v.form.apPat.required" class="form-text text-danger">
                                El Apellido Paterno es requerido.
                            </span>
                        </b-form-invalid-feedback>
                      </b-input-group>
                    </b-form-group>
                </b-col> 
                <b-col cols="12" md="6">
                    <b-form-group label="Apellido Materno" label-for="apmaterno-input" >
                      <b-input-group class="mt-3" id="loadingcurp">
                        <template #append v-if="buscandoCurp">
                          <b-input-group-text >
                            <strong class="loadingcurp">
                             <b-spinner label="Loading..." small ></b-spinner>
                            </strong>
                          </b-input-group-text>
                        </template>
                        <b-form-input  id="apmaterno-input" name="apmaterno"  v-model="$v.form.apMat.$model" :disabled="curpEncontrada || buscandoCurp" v-uppercase></b-form-input>
                      </b-input-group>
                    </b-form-group>
                </b-col>
                <b-col  cols="12" md="6">
                    <b-form-group label="Fecha de Nacimiento" label-for="fechaNacimiento-input" >
                        <b-input-group class="mt-3" id="loadingcurp">
                            <template #prepend v-if="buscandoCurp">
                                <b-input-group-text >
                                    <strong class="loadingcurp">
                                        <b-spinner label="Loading..." small ></b-spinner>
                                    </strong>
                                </b-input-group-text>
                            </template>
                            <b-form-input
                                id="fechanac-input"
                                v-model="$v.form.fechaNacimiento.$model"
                                type="text"
                                placeholder="DD-MM-AAAA"
                                autocomplete="off" :state="$v.form.fechaNacimiento.$dirty ? !$v.form.fechaNacimiento.$error : null" 
                                :disabled="curpEncontrada" aria-describedby="fechaNacimiento-input-feedback">
                            </b-form-input>
                            <b-input-group-append>
                                <b-form-datepicker  class="mb-2" id="fechaNacimiento-input" name="fechaNacimiento"  v-model="fechaDatepICKER" :state="$v.form.fechaNacimiento.$dirty ? !$v.form.fechaNacimiento.$error : null" aria-describedby="fechaNacimiento-input-feedback" :disabled="curpEncontrada || buscandoCurp"
                                button-only right aria-controls="fechanac-input"  @input="formatFechaNacimiento()"
                                ></b-form-datepicker>
                            </b-input-group-append>
                            <b-form-invalid-feedback id="fechaNacimiento-input-feedback">
                                <span v-if="!$v.form.fechaNacimiento.required" class="form-text text-danger">
                                    La fecha de nacimiento es requerida
                                </span>
                            </b-form-invalid-feedback>
                        </b-input-group>
                    </b-form-group>
                </b-col>                   
            </b-row>
            <b-row v-if="form.tipoPersona == 'pf' && form.nacionalidad == 'mexicana'" >
                <b-col>
                    <b-form-group label="Genero" label-for="tipo-genero-select" >
                        <b-form-select v-model="$v.form.genero.$model" :options="optionsGenero" :disabled="curpEncontrada" id="tipo-genero-select"></b-form-select>
                    </b-form-group>   
                </b-col>
                <b-col>
                    <b-form-group label="Estado de Nacimiento" label-for="tipo-estado-select" >
                        <b-form-select v-model="$v.form.estado.$model" :options="optionsEstado"
                            value-field="clave"
                            text-field="nombre" id="tipo-estado-select"></b-form-select> 
                    </b-form-group>
                </b-col>
            </b-row>
        </form>
    </div>
</template>

<script>
    import { validationMixin } from 'vuelidate';
    import datosPersonalesRulesService from '../../services/DatosPersonalesRules.services.js';
    export default {
        mixins: [validationMixin],
        props: [ 'datosPersonales'],
        data(){
            return {
                form: {
                    nacionalidad:'mexicana',
                    tipoPersona:  'pf',
                    curp:null,
                    rfc:null,
                    razonSocial:null,
                    tipoPropietario:null,
                    nombre:null,
                    apPat:null,
                    apMat:null,
                    fechaNacimiento:null,
                    genero:null,
                    estado:null
                },
                options: [
                  { value: null, text: 'Seleccione' },
                  { value: 'propietario', text: 'Propietario' },
                  { value: 'usufructuario', text: 'Usufructuario' },
                  { value: 'copropietario', text: 'Copropietario' },
                  { value: 'nudaPropiedad', text: 'Nuda Proipiedad' }
                ],
                buscandoCurp:false,
                curpEncontrada:false,
                fechaDatepICKER:'',
                optionsGenero:[
                    { value: null, text: 'Seleccione' },
                    { value: 'H', text: 'Hombre' },
                    { value: 'M', text: 'Mujer' },
                ],
                optionsEstado:[]
            }
        },
        computed:{
            rules(){
                if(this.form.nacionalidad == 'mexicana' && this.form.tipoPersona == 'pf'){
                    return datosPersonalesRulesService.getRulesMexicanPF();
                } else if (this.form.nacionalidad == 'mexicana' && this.form.tipoPersona == 'pm'){
                    return datosPersonalesRulesService.getRulesMexicanPM();
                } else {
                    /*
                    this.form.curp =  null;
                    this.form.estado =  null;
                    this.form.genero =  null;
                    this.form.rfc =  null;
                    this.curpEncontrada = false;*/
                    return datosPersonalesRulesService.getRulesExtranjero();
                }
            }
        },
        validations() {
            return {
                form: this.rules
            }
        },
        mounted() {
            if(Object.entries(this.datosPersonales).length > 0) {
                this.form = this.datosPersonales;
                this.$v.$touch()
            }

            this.getEstados();
        },
        methods: {
            async updateCurp(){
                if( !this.$v.form.curp.$invalid ){         
                    this.buscarCurp( this.$v.form.curp.$model );
                } else {
                    this.rellenarForm();
                }
            },

            async buscarCurp(curp) {
                this.buscandoCurp = true;
                let response = null;
                let url = process.env.TESORERIA_HOSTNAME + "/consultar-curp";

                try {
                    response = await axios.get(url + '/' + curp, {timeout: 5000});
                    this.buscandoCurp = false;
                    if(response.data){
                        if(response.data.status == 'error'){
                            Command: toastr.error("Error!", response.data.msg);
                            this.rellenarForm();
                        } else{
                            this.rellenarForm(response.data);
                        }
                    } else {
                        this.rellenarForm();
                    }
                } catch (err) {
                    Command: toastr.error("Error!", err.message);
                    this.buscandoCurp = false;
                    this.rellenarForm();
                    throw new Error(err.message);
                }

            },

            rellenarForm(data){
                if(data){
                    this.curpEncontrada = true;
                    this.form.nombre = data.data.nombres;
                    this.form.apPat = data.data.apePat;
                    this.form.apMat = data.data.apeMat;
                    this.form.fechaNacimiento =  data.data.fechaNac.split("/").join("-");/*reverse()*/
                    this.form.genero = data.data.sexo;
                } else {
                    this.curpEncontrada = false;
                    this.form.nombre = "";
                    this.form.apPat = "";
                    this.form.apMat = "";
                    this.form.fechaNacimiento = "";
                    this.form.genero = "";
                }
            },

            formatFechaNacimiento(){
                this.form.fechaNacimiento =  this.fechaDatepICKER.split("-").reverse().join("-");
            },

            async getEstados(){
                let url = process.env.TESORERIA_HOSTNAME + "/obtener-estados" ; 
                let options = await this.obtenerOptions(url);
                options.unshift({
                    clave:null,
                    nombre:'Seleccione'
                })
                this.optionsEstado = options; 
            },

            async obtenerOptions(url){
                let response = await axios.get(url);
                let options = response.data ? response.data : [];
                return options;
            },
        },
        watch: {
            form:{
                deep:true,
                handler(){
                    this.$emit('estadoFormulario', this.$v)
                }
            }
        }

    }

</script>
