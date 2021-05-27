<template>
  <div class="container-fluid p-0 w-50" >
        <div class="card bg-transparent border-0" id="kt_login_signin_ciudadano_form">
            <div class="card-body justify-content-center align-items-center text-center">
                <img style="margin-left: auto;margin-right: auto; display: block;" src="images/logo.svg" alt="" width="200">
                <h3 class="mt-5"><strong>Datos de la cuenta</strong></h3>
                <p>Ingresa tus datos de acceso:</p>
                <form @submit.prevent="submit">
                    <div class="form-row">
                        <div class="form-group form-group--error col-md-6" >
                            <label for="inputEmail4">Nombre de usuario</label>
                            <input type="text" class="form-control" v-model="userName">
                            <div class="error" style="color:red !important" v-if="!$v.userName.required">Campo requerido</div>
                        </div>
                        <div class="form-group col-md-6" >
                            <label for="exampleFormControlSelect1">Tipo de Persona</label>
                            <select v-model="tipoPersona" class="form-control">
                                <option>Fisica</option>
                                <option>Moral</option>
                            </select>
                            <div class="error"  v-if="!$v.tipoPersona.required">Campo requerido</div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label > Nombre(s) </label>
                            <input v-model="nombre" type="text" class="form-control" >
                            <div class="error" v-if="!$v.nombre.required">Campo requerido</div>
                            <div class="error" v-if="!$v.nombre.minLength">3 caracteres almenos</div>
                            <div class="error" v-if="!$v.nombre.alpha">este campo no admite numeros ni caracteres especiales</div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">Apellido Paterno</label>
                            <input v-model="apePat" type="text" class="form-control" >
                            <div class="error" v-if="!$v.apePat.required">Campo requerido</div>
                            <div class="error" v-if="!$v.apePat.minLength">3 caracteres almenos</div>
                            <div class="error" v-if="!$v.apePat.alpha">este campo no admite numeros ni caracteres especiales</div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label > Apellido Materno </label>
                            <input  v-model="apeMat" type="text" class="form-control" >
                            <div class="error" v-if="!$v.apeMat.required">Campo requerido</div>
                            <div class="error" v-if="!$v.apeMat.minLength">3 caracteres almenos</div>
                            <div class="error" v-if="!$v.apeMat.alpha">este campo no admite numeros ni caracteres especiales</div>
                        </div>
                        <div class="form-group col-md-6" >
                            <label for="">RFC </label>
                            <input  v-model="rfc" type="text" class="form-control" >
                            <div class="error" v-if="!$v.rfc.required">Campo requerido</div>
                            <div class="error" v-if="!$v.rfc.rfc && tipoPersona =='Fisica'">Formato no valido</div>
                            <div class="error" v-if="!$v.rfc.required &&  tipoPersona == 'Moral'">Formato no valido</div>
                        </div>
                    </div>                    
                    <div class="form-row">
                        <div v-if="tipoPersona == 'Fisica'" class="form-group col-md-6">
                            <label > CURP </label>
                            <input v-model="curp" type="text" class="form-control" >
                            <div class="error" v-if="!$v.curp.required">Campo requerido</div>
                            <div class="error" v-if="!$v.curp.curp">Formato no valido</div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for=""> Telefono </label>
                            <input v-model="tel" type="number" class="form-control" >
                            <div class="error" v-if="!$v.tel.required">Campo requerido</div>
                            <div class="error" v-if="!$v.tel.nunmber">Campo requerido</div>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="inputAddress">Correo electronico</label>
                        <input type="text" v-model="email" class="form-control" placeholder="">
                        <div class="error" v-if="!$v.email.required">Campo requerido</div>
                        <div class="error" v-if="!$v.email.email">Campo requerido</div>
                    </div> 
                    <div class="form-group">
                        <label for="inputAddress2">Contraseña</label>
                        <input type="password" v-model="password" class="form-control" placeholder="">
                        <div class="error" v-if="!$v.password.required">Campo requerido</div>
                        <div class="error" v-if="!$v.password.maxLength">maximo 40 caracteres</div>
                        <div class="error" v-if="!$v.password.minLength">minimo 7 caracteres</div>
                    </div> 
                    <div class="form-group">
                        <label for="inputAddress2">Confirmar Contraseña</label>
                        <input type="password" v-model="confirmPassword" class="form-control" placeholder="">
                        <div class="error" v-if="!$v.confirmPassword.sameAs">Las contraseñas no coinciden</div>
                    </div> 
                    <button type="submit"  :disabled="submitStatus === 'PENDING'" class="btn btn-primary">Registrarme</button>
                </form>
            </div>
        </div>

    </div>
    

</template>

<script>
import Vue from 'vue';
import Vuelidate from 'vuelidate'
Vue.use(Vuelidate);
import {  required, email, sameAs,  minLength , maxLength,  helpers  } from 'vuelidate/lib/validators';
const alpha = helpers.regex('alpha', /^[a-zA-Z]*$/);
const nunmber = helpers.regex('number', /^[0-9]*$/);
const curp = helpers.regex('curp',  /^[A-Z]{1}[AEIOU]{1}[A-Z]{2}[0-9]{2}(0[1-9]|1[0-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1])[HM]{1}(AS|BC|BS|CC|CS|CH|CL|CM|DF|DG|GT|GR|HG|JC|MC|MN|MS|NT|NL|OC|PL|QT|QR|SP|SL|SR|TC|TS|TL|VZ|YN|ZS|NE)[B-DF-HJ-NP-TV-Z]{3}[0-9A-Z]{1}[0-9]{1}$/);
const rfc = helpers.regex("rfc", /^[A-ZÑ&]{3,4}\d{6}(?:[A-Z\d]{3})?$/);
const rfcMoral = helpers.regex("rfcMoral", /^[A-ZÑ&]{3,4}\d{6}(?:[A-Z\d]{3})?$/);

export default {
    props:[],
    data(){
        return{
            tipoPersona:'',
            userName: '',
            nombre: '',
            apePat : '',
            apeMat: '',
            rfc: '',
            curp: '',
            tel: '',
            submitStatus: '',
            email: '',
            password: '',
            confirmPassword: '',
        }
    },
    methods:{
        submit() {
            console.log('submit!');
            this.$v.$touch()
            if (this.$v.$invalid) {
                swal.fire({
                    text: "Error en formulario.",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok!",
                        customClass: {
                            confirmButton: "btn font-weight-bold btn-light-primary"
                        }
                });

            } else {
                // do your submit logic here
                this.submitStatus = 'PENDING'
                $.ajax({
					type: "POST",
					data: {                                      
                        username:"testeo0045",
                        email:"testeo0045@correo.com",
                        password:"zpvw3cx9BRhxy0G9",
                        name:"test",
                        mothers_surname:"test",
                        fathers_surname:"test",
                        curp:"CUBJ960215HHGRTN05",
                        rfc:"CUBJ960215UIA",
                        phone:"8888888888",
                        person_type:"fisica",
                        config_id: 11,
                        role_id: 1
					},
					dataType: 'json', 
					url: process.env.SESSION_HOSTNAME + '/signup',
					async: false,
					success:function(data){
                        swal.fire({
                                text: "Registro completado",
                                icon: "success",
                                buttonsStyling: false,
                                confirmButtonText: "OK",
                                customClass: {
                                    confirmButton: "btn font-weight-bold btn-light-primary"
                                }
                        })
                        console.log(data);
					},
					error:function(error){
						swal.fire({
                            text: error,
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok!",
                            customClass: {
                                confirmButton: "btn font-weight-bold btn-light-primary"
                            }
                        });
					},
					complete:function(){
						// console.log('id a consultar en insumos: ', self.resultId);
                        this.submitStatus = 'OK'
					}
				});
              
            }
        }
    },
    validations:{
        userName:{ required,  minLength: minLength(5) , maxLength: maxLength(15) },
        tipoPersona:{ required,  minLength: minLength(5) , maxLength: maxLength(15) },
        nombre:{ required, alpha,  minLength: minLength(5) , maxLength: maxLength(25) },
        apePat:{ required, alpha,  minLength: minLength(5) , maxLength: maxLength(25) },
        apeMat:{ required, alpha,  minLength: minLength(5) , maxLength: maxLength(25) },
        curp:{ required, curp,  minLength: minLength(5) , maxLength: maxLength(15) },
        rfc:{ required, rfc, rfcMoral, minLength: minLength(5) , maxLength: maxLength(15) },
        tel:{ required , nunmber,  minLength: minLength(10) , maxLength: maxLength(10) },
        email:{ required , email,  minLength: minLength(5) , maxLength: maxLength(45) },
        password:{ required , minLength: minLength(7) , maxLength: maxLength(40) },
        confirmPassword:{ sameAs: sameAs('password') },
    },
    watch() {
        $v:{
            console.log('v actualizado');
        }
    }
}
</script>
