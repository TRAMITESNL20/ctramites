<template>
    <div class="container-fluid">
        <div v-if="listaSolicitantes.length > 0 && !agregarMas" class="row">
            <div v-for="(sol, index) in listaSolicitantes" class="col-lg-12" style="padding-bottom: 9px; margin-top: 5px;">
                <!--<h6 class="font-weight-bolder mb-3">{{ sol.rfc }}</h6>-->
                <div class="text-dark-50 line-height-lg">
                    <!--
                    <div class="float-lg-left">
                        <div>{{ sol.tipoPersona == "pm" ? sol.razonSocial : sol.nombreSolicitante  }} </div>
                        <div>{{ sol.apPat }}</div>
                        <div>{{ sol.apMat }}</div>
                        <div>{{ sol.curp }}</div>
                    </div>-->
                    <div class="text-center">
                        <h3 v-if="sol.nombreSolicitante">
                          {{sol.nombreSolicitante }} {{ sol.apPat }} {{ sol.apMat }}<span class="font-weight-light">, {{ sol.email }}</span>
                        </h3>
                        <h3 v-if="sol.razonSocial">
                            {{sol.razonSocial }}
                        </h3>
                        <div class="h5 font-weight-300" v-if="sol.rfc">
                           <strong>RFC:</strong> <i class="ni location_pin mr-2"></i>{{ sol.rfc }}
                        </div>
                        <div class="h5 mt-4" v-if="sol.curp" >
                          <strong>CURP:</strong> <i class="ni business_briefcase-24 mr-2"></i>{{ sol.curp }}
                        </div>
                    </div>
                 
                    <div class="float-lg-right" v-if="puedeEditar">
                        <button type="button"  class="btn btn-danger"  id="btnEliminar" v-on:click="eliminar( index )">
                            <i class="fa fa-times" id="iconBtnEliminar"></i> 
                        </button>
                        <button type="button"  class="btn btn-info"  id="btnEditar" v-on:click="mostrarEditarSolicitante( sol, index )">
                            <i class="fa fa-edit" id="iconBtnEditar"></i>
                        </button> 
                    </div> 
                   
                </div>
            </div>
            <div class="col-lg-12" v-if="!isISR">
                <button type="button"  class="btn"  id="btnAddMore" v-on:click="mostrarAgregarSolicitante()">
                    <i class="fa fa-check" id="iconBtnAddMore"></i> 
                    Agregar Solicitante
                </button>  
            </div>
        </div>
        <div v-else-if="listaSolicitantes.length == 0 || agregarMas">
            <div class="row text-center">
                <div class="col-lg-12">   
                    <div class="form-check-inline">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" value="pf" name="tipoPersona"  v-model="solicitante.tipoPersona" id="tppf">Persona Física
                        </label>
                    </div>
                    <div class="form-check-inline">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" value="pm" name="tipoPersona"  v-model="solicitante.tipoPersona" id="tppm">Persona Moral
                        </label>
                    </div>
                </div>
            </div>  
            <div class="row" id="divPF" v-if="solicitante.tipoPersona == 'pf'">
                <!--
                <div class="col-xl-6">
                    <div class="">
                        <label>RFC</label>
                        <input type="text" placeholder="RFC " id="rfc" class="form-control  form-control-lg "  style="background-color: #e5f2f5 !important"  v-model="solicitante.rfc">
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="">
                        <label>CURP</label>
                        <input type="text" placeholder="CURP " id="curp" class="form-control  form-control-lg "  style="background-color: #e5f2f5 !important"  v-model="solicitante.curp">
                    </div>
                </div>-->
                <div class="col-xl-6">
                    <div class="">
                        <label>Nombre</label>
                        <input type="text" placeholder="Nombre " id="nombreSolicitante" class="form-control form-control-lg" style="background-color: #e5f2f5 !important" v-model="solicitante.nombreSolicitante" >
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="">
                        <label>Apellido Paterno</label>
                        <input type="text" placeholder="Apellido Paterno " id="apPatSolicitante" class="form-control form-control-lg" style="background-color: #e5f2f5 !important" v-model="solicitante.apPat">
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="">
                        <label>Apellido Materno</label>
                        <input type="text" placeholder="Apellido Materno " id="apMatSolicitante" class="form-control  form-control-lg" style="background-color: #e5f2f5 !important" v-model="solicitante.apMat">
                    </div>
                </div>
            </div>
            <div class="row" id="divPM" v-else-if="solicitante.tipoPersona == 'pm'">
                <div class="col-xl-12">
                    <div class="">
                        <label>RFC</label>
                        <input type="text" placeholder="RFC " id="rfc" class="form-control  form-control-lg" style="background-color: #e5f2f5 !important" v-model="solicitante.rfc">
                    </div>
                </div>
                <div class="col-xl-12">
                    <div class="">
                        <label>Razón Social</label>
                        <input type="text" placeholder="Razón Social " id="razonSocial" class="form-control  form-control-lg" style="background-color: #e5f2f5 !important" v-model="solicitante.razonSocial">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-right">
                        <button type="button"  class="btn btn-danger pull-rigth"  id="btnAddMoreCancel" v-on:click="agregarMas = false" v-if="listaSolicitantes.length > 0 ">
                            <i class="fa fa-times" id="iconBtnAddMoreCancel"></i> 
                            Cancelar
                        </button>
                    </div>
            
                    <div class="text-right">  
                        <button type="button"  class="btn btn-success green pull-rigth"  id="btnAdd" v-on:click="agregar()" v-if="!editando">
                            <i class="fa fa-check" id="iconBtnAdd"></i> 
                            Guardar
                        </button>
                        <button type="button"  class="btn btn-success green pull-rigth"  id="btnEditSi" v-on:click="editar(indiceEditando, solicitante)" v-if="editando">
                            <i class="fa fa-check" id="iconBtnSi"></i> 
                            Editar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    export default {
        props: ['solicitantesGuardados'],
        mounted() {
            let tramite = JSON.parse(localStorage.getItem('tramite'));
            this.isISR = tramite && tramite.id_tramite == process.env.TRAMITE_5_ISR; 
            if( tramite && tramite.id_tramite == process.env.TRAMITE_5_ISR ){
                let userTitular = window.user;

                if(userTitular && userTitular.notary && userTitular.notary.titular){
                    this.solicitante = {
                        rfc:userTitular.notary.titular.rfc,
                        tipoPersona:"pf",
                        nombreSolicitante:userTitular.notary.titular.name,
                        apPat:userTitular.notary.titular.fathers_surname,
                        apMat:userTitular.notary.titular.mothers_surname,
                        curp:userTitular.notary.titular.curp,
                        email:userTitular.notary.titular.email,
                        id:userTitular.notary.titular.id,
                        notary:userTitular.notary.notary_number
                    };                
                } else if( userTitular ) {
                    this.solicitante = {
                        rfc:userTitular.rfc,
                        tipoPersona:"pf",
                        nombreSolicitante:userTitular.name,
                        apPat:userTitular.fathers_surname,
                        apMat:userTitular.mothers_surname,
                        curp:userTitular.curp,
                        email:userTitular.email,
                        id:userTitular.id
                    }; 
                }
                console.log(JSON.parse(JSON.stringify(this.solicitante)))
                this.agregar();
            } else {
                this.solicitante = { tipoPersona:"pf" };
                this.puedeEditar = true;
            }
            let solicitantesGuardados = this.solicitantesGuardados.filter( Boolean );
            if( solicitantesGuardados && solicitantesGuardados.length > 0){
                this.listaSolicitantes = solicitantesGuardados;
                this.guardarInStorage();
            }
            if (localStorage.getItem('listaSolicitantes')) {
              try {
                this.listaSolicitantes = JSON.parse(localStorage.getItem('listaSolicitantes'));
              } catch(e) {
                localStorage.removeItem('listaSolicitantes');
              }
            }
            
            this.$emit('updatingSolicitante', this.listaSolicitantes.length > 0);

        },

        data(){
            return {
                solicitante: { tipoPersona:"pf" },
                listaSolicitantes:[],
                agregarMas: false,
                editando: false,
                indiceEditando:null,
                puedeEditar:false,
                isISR:false
            }
        },
  
        methods: {
            agregar: function (event) {
                this.solicitante.id=0;
                if( this.solicitante.tipoPersona == 'pf' ){
                    if(/*!!this.solicitante.rfc &&*/ !!this.solicitante.nombreSolicitante && !!this.solicitante.apPat){
                        this.listaSolicitantes.push( this.solicitante );
                        this.solicitante = { tipoPersona:"pf" };
                        this.agregarMas = false;
                        this.guardarInStorage();
                    }  else {
                        /*if(!this.solicitante.rfc ){
                            Command: toastr.warning("Error!", "RFC Requerido");
                        } else*/ if( !this.solicitante.nombreSolicitante ) {
                            Command: toastr.warning("Error!", "Nombre Requerido");
                        } else {
                            Command: toastr.warning("Error!", "Apellido paterno Requerido");
                        }
                    }

                } else {
                    if(!!this.solicitante.rfc && !!this.solicitante.razonSocial){

                        this.listaSolicitantes.push( this.solicitante );
                        this.solicitante = { tipoPersona:"pf" };
                        this.agregarMas = false;
                        this.guardarInStorage();
                    }  else {
                        if(!this.solicitante.rfc ){
                            Command: toastr.warning("Error!", "RFC Requerido");
                        } else if( !this.solicitante.razonSocial ) {
                            Command: toastr.warning("Error!", "Razón Social Requerido");
                        }
                    }
                }

            },

            eliminar( index ){
                this.listaSolicitantes = JSON.parse(localStorage.getItem('listaSolicitantes'));
                this.listaSolicitantes.splice(index, 1);
                this.guardarInStorage();
            },

            editar(index, solicitanteNuevo){
                if(this.solicitante.tipoPersona == 'pm' && !solicitanteNuevo.rfc ){
                    Command: toastr.warning("Error!", "RFC Requerido");
                    return false;
                } 

                if( solicitanteNuevo.tipoPersona === "pf" ){
                    delete solicitanteNuevo.razonSocial;
                    if( !solicitanteNuevo.nombreSolicitante ) {
                        Command: toastr.warning("Error!", "Nombre Requerido");
                        return false;
                    } else if( !solicitanteNuevo.apPat ){
                        Command: toastr.warning("Error!", "Apellido paterno Requerido");
                        return false;
                    }
                } else {
                    delete solicitanteNuevo.nombreSolicitante
                    delete solicitanteNuevo.apPat
                    delete solicitanteNuevo.apMat;
                    delete solicitanteNuevo.curp;
                    if( !solicitanteNuevo.razonSocial ) {
                        Command: toastr.warning("Error!", "Razón Social Requerido");
                        return false;
                    } 
                }
                    this.listaSolicitantes[index] = solicitanteNuevo;
                    this.solicitante = { tipoPersona:"pf" };
                    this.editando = false;
                    this.indiceEditando = null;
                    this.agregarMas = false;
                    this.guardarInStorage();

            },

            guardarInStorage(){
                this.$emit('updatingSolicitante', this.listaSolicitantes.length > 0);
                const parsed = JSON.stringify(this.listaSolicitantes);
                localStorage.setItem('listaSolicitantes', parsed);  
            },

            mostrarAgregarSolicitante(){
                this.agregarMas = true;
                this.solicitante = { tipoPersona:"pf" };
            },

            mostrarEditarSolicitante( solicitante, index ){
               this.solicitante = Object.assign({}, solicitante);
               this.editando = true; 
               this.agregarMas = true;  
               this.indiceEditando = index;
            }
            
        }
    }
</script>
