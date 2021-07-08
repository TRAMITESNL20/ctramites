<template>
    <div class="card card-custom card-transparent" >
        <div class="card-body p-0" >
            <!--begin: Wizard-->
            <div class="wizard wizard-4" id="kt_wizard" data-wizard-state="first" data-wizard-clickable="true" >
                <!--begin: Wizard Nav-->
                <div class="wizard-nav">
                    <div class="wizard-steps">
                        <!--begin::Wizard StepS Nav-->
                        <div v-for="(step, i) in steps" class="wizard-step wizard-step-mobile" data-wizard-type="step" :data-wizard-state="step.state" :id="step.id" v-on:click="goTo(step.clickGotTo)">
                            <div class="wizard-wrapper">
                                <div class="wizard-number">
                                  {{ step.wizardNumber}}
                                </div>
                                <div class="wizard-label">
                                    <div class="wizard-title">
                                      {{ step.wizardTitle}}
                                    </div>
                                    <div class="wizard-desc">
                                        {{ step.wizardDesc}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end::Wizard StepS Nav-->
                    </div>
                </div>
                <!--end: Wizard Nav-->
                <!--begin: Wizard Body-->
                <div class="card card-custom card-shadowless rounded-top-0">
                    <div class="card-body p-0">
                        <div class="row justify-content-center  py-lg-15 px-lg-10">
                            <div class="col-xl-12 col-xxl-12">
                                <!--begin: Wizard Form-->
                                    <!--begin: Wizard Step 1 Campos tramite-->
                                    <div class="pb-5 c" data-wizard-type="step-content" data-wizard-state="current" id="step1">
                                      <div v-if="tramite.id_tramite == TRAMITE_5_ISR && camposGuardadosObtenidos">
                                        <radio-option-component
                                          :default="tipoTramite"
                                          @valueRadio="cambioRadio"
                                          :disabledDefault='tipoTramiteDisabled'></radio-option-component>
                                      </div>
                                      <div v-if="(tipoTramite == 'normal' || tipoTramite == 'declaracionEn0') && camposGuardadosObtenidos" >
                                        <campos-tramite-component :tramite="tramite" v-if="currentStep == 1"
                                        :formularioValido="formularioValido" @updatingScore="updateScore" :comprobarEstadoFormularioCount="comprobarEstadoFormularioCount" @updatingFiles="updatingFiles" :infoGuardada="infoGuardada" :declararEn0="declararEn0" :usuario="usuario" :filesPrecargados="files">

                                        </campos-tramite-component>
                                      </div>
                                      <div v-else-if="tipoTramite == 'complementaria' && camposGuardadosObtenidos">
                                          <complementaria-component 
                                          @updatingScore="updateScore"
                                          @sendData="setDatosComplementaria"
                                          :infoGuardada="infoGuardada">
                                            
                                          </complementaria-component>
                                        <!--
                                          <formulario-complementaria-component @updatingScore="updateScore"
                                          @sendData="setDatosComplementaria" :infoGuardada="infoGuardada">
                                          </formulario-complementaria-component>-->
                                      </div>
                                    </div>
                                    <!--end: Wizard Step 1-->
                                    <!--begin: Wizard Step 2-->
                                    <div class="pb-5" data-wizard-type="step-content" id="step2" >
                                      <solicitantes-component v-if="currentStep == 2 && camposGuardadosObtenidos" @updatingSolicitante="updateSolicitante" :solicitantesGuardados="solicitantesGuardados" :usuario="usuario"></solicitantes-component>
                                    </div>
                                    <!--end: Wizard Step 2-->
                                    <!--begin: Wizard Step 3-->
                                    <div class="pb-5" data-wizard-type="step-content" id="step3" >
                                        <div v-if="tramite.id_tramite == TRAMITE_5_ISR ">
                                          <resumen-tramite-5-isr-component v-if="currentStep == 3"
                                          :tipoTramite="tipoTramite" 
                                          :datosComplementaria="datosComplementaria" 
                                          :files="files" 
                                          :usuario="usuario">
                                          </resumen-tramite-5-isr-component>
                                        </div>
                                        <div v-else>
                                          <resumen-tramite-component v-if="currentStep == 3" 
                                          :tipoTramite="tipoTramite" 
                                          :datosComplementaria="datosComplementaria" 
                                          ></resumen-tramite-component>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between border-top mt-5 pt-10">
                                        <div class="mr-2">
                                            <button type="button" class="btn btn-light-primary font-weight-bolder text-uppercase px-9 py-4" data-wizard-type="action-prev">Previous</button>
                                        </div>
                                        <div class="pb-5" >
                                          <div class="btn-group" role="group" aria-label="Basic example">
                                             <btn-guardar-tramite-component
                                              type="temporal"
                                              :tipoTramite="tipoTramite"
                                              :files="files"
                                              :datosComplementaria="datosComplementaria"
                                              :idUsuario="idUsuario"
                                              :infoGuardadaFull="infoGuardadaFull" v-if="currentStep != 3" labelBtn="Guardar y Continuar después "
                                              @tramiteAgregadoEvent="tramiteAgregadoEvent"
                                              ></btn-guardar-tramite-component>

                                            <btn-guardar-tramite-component
                                              :btnClass="['notary_titular', 'notary_substitute', 'notary_payments', 'notary_capturist_payments'].includes(user.role_name) ? 'btn btn-secondary font-weight-bolder text-uppercase px-9 py-4' : null"
                                              :tipoTramite="tipoTramite"
                                              :files="files"
                                              :datosComplementaria="datosComplementaria"
                                              :idUsuario="idUsuario"
                                              :infoGuardadaFull="infoGuardadaFull"
                                              v-if="currentStep == 3" 
                                              :labelBtn="['notary_titular', 'notary_substitute', 'notary_payments', 'notary_capturist_payments'].includes(user.role_name) ? 'Iniciar Nuevo Tramite' : 'Finalizar'"
                                              @tramiteAgregadoEvent="tramiteAgregadoEvent"
                                              ></btn-guardar-tramite-component>
                                            <btn-guardar-tramite-component
                                              type="finalizar"
                                              :tipoTramite="tipoTramite"
                                              :files="files"
                                              :datosComplementaria="datosComplementaria"
                                              :idUsuario="idUsuario"
                                              :infoGuardadaFull="infoGuardadaFull"
                                              v-if="currentStep == 3 && ['notary_titular', 'notary_substitute', 'notary_payments', 'notary_capturist_payments'].includes(user.role_name)"
                                              labelBtn="Pagar"
                                              @tramiteAgregadoEvent="tramiteAgregadoEvent"
                                              ></btn-guardar-tramite-component>
                                            <button type="button" id="btnWizard" class="btn btn-primary font-weight-bolder text-uppercase px-9 py-4 arrow-desktop" data-wizard-type="action-next" v-on:click="next()" v-if="currentStep != 3">
                                                Siguiente
                                            </button>
                                            <button type="button" id="btnWizard" class="btn btn-primary font-weight-bolder text-uppercase px-9 py-4 arrow-mobile" data-wizard-type="action-next" v-on:click="next()" v-if="currentStep != 3"> 
                                                <i class="fas fa-arrow-right"></i>
                                            </button>
                                          </div>
                                        </div>
                                    </div>
                                    <!--end: Wizard Actions-->
                                <!--end: Wizard Form-->
                            </div>
                        </div>
                    </div>
                </div>
                <!--end: Wizard Bpdy-->
            </div>
            <!--end: Wizard-->

            <div class="card card-custom card-shadowless rounded-top-0" style="display: none;" id="redirecLoad">
                  <div class="card-body p-0">
                    <div class="row justify-content-center py-8 px-8 py-lg-15 px-lg-10">
                      <div class="col-xl-12 col-xxl-12">
                        <div class="text-center">
                          <b-spinner variant="success"  style="width: 3rem; height: 3rem;" label="Large Spinner" type="grow"></b-spinner>
                        </div>
                      </div>
                    </div>
                  </div>
            </div>

        </div>

    </div>
</template>

<script>
  import { uuid } from 'vue-uuid';
  import FirmaElectronicaComponent from './tiposElementos/FirmaElectronicaComponent.vue';

    export default {
        props: ['tramite','idUsuario', 'clave', 'usuario'],
        computed:{
            declararEn0(){
                return this.tipoTramite == 'declaracionEn0';
            }
        },
        mounted() {
            this.tramite.id_seguimiento = this.clave ? this.clave : uuid.v4();
            $("#tramite-name span").text(this.tramite.tramite.toUpperCase())
            const parsed = JSON.stringify(this.tramite);
            localStorage.setItem('tramite', parsed);

            const datosFormulario = localStorage.getItem('datosFormulario') && JSON.parse(localStorage.getItem('datosFormulario')) ;
            if(datosFormulario){
              this.tipoTramite = datosFormulario.tipoTramite || 'normal';
            } else {
              this.tipoTramite == 'normal';
              localStorage.setItem('datosFormulario', JSON.stringify(datosFormulario)); 
            }

            if( this.clave ){
               this.obtenerCamposTemporales();
            } else {
              this.camposGuardadosObtenidos = true;
            }
        },

        data() {
            return {
                user : window.user,
                currentStep: 1,
                datosIncompletos: true,
                enviando:false, finalizando:false,
                registrado: false,
                formularioValido: false,
                solicitantesValido: false,
                comprobarEstadoFormularioCount:0,
                files:[],
                tipoTramite:'normal',
                datosComplementaria:[],
                infoGuardada:{}, camposGuardadosObtenidos: false,
                infoGuardadaFull:{},
                solicitantesGuardados:[],
                tipoTramiteDisabled:'',
                steps:[{
                  id:"tab1",
                  state:'current',
                  clickGotTo:1,
                  wizardNumber:1,
                  wizardTitle:'Datos',
                  wizardDesc:'Información sobre el trámite',
                },{
                  id:"tab2",
                  state:'pending',
                  clickGotTo:2,
                  wizardNumber:2,
                  wizardTitle:'Solicitante',
                  wizardDesc:'Solicitante del trámite',
                },{
                  id:"tab3",
                  state:'pending',
                  clickGotTo:3,
                  wizardNumber:3,
                  wizardTitle:'Finalizar',
                  wizardDesc:'Revisar y completar',
                }],
                TRAMITE_5_ISR:process.env.TRAMITE_5_ISR
                //declararEn0:false
            }
        },

        methods: {
          tramiteAgregadoEvent(data){

            if(data.respuesta){
              if( data.response.data && data.response.data.Code && data.response.data.Code == '200' ){
                $("#kt_wizard").fadeOut('slow', ()=> {
                  
                  $("#redirecLoad").fadeIn();
                })
                let totalAgregados = data.response ? 1 : data.responses.length;
                let totalCarritoActual = parseInt( $("#totalTramitesCarrito" ).text( ));
                $("#totalTramitesCarrito" ).text( totalCarritoActual + totalAgregados  );

                Command: toastr.success("Listo !", "El trámite ha sido agregado");
                
                if( data.type == "finalizar" ){
                  localStorage.removeItem('tramite'); 
                  localStorage.removeItem('listaSolicitantes');
                  localStorage.removeItem('datosFormulario');
                  redirect("/cart");
                } if(data.type=="temporal"){
                  localStorage.removeItem('tramite'); 
                  localStorage.removeItem('listaSolicitantes');
                  localStorage.removeItem('datosFormulario');
                  redirect("/tramites/borradores/80");
                }else {
                  localStorage.removeItem('listaSolicitantes');
                  localStorage.removeItem('datosFormulario');
                  delete this.tramite.detalle;
                  this.tramite.id_seguimiento = uuid.v4();
                  const parsed = JSON.stringify(this.tramite);
                  localStorage.setItem('tramite', parsed);
                  if(!['finalizar', 'temporal'].includes(data.type)) redirect("/nuevo-tramite");
                }
              } else {
                Command: toastr.success("Eroor !", data.response.data.Message || "Error al tratar de guardar la solicitud");
              }
            }

          },
          updateScore(formularioValido) {
            $("#btnWizard").attr("disabled", true);
            if( formularioValido ){
              $("#btnWizard").attr("disabled", false);
            }
            this.formularioValido = formularioValido;
            this.$forceUpdate()
          },

            setDatosComplementaria(datos){
              this.datosComplementaria = datos;
            },

            updatingFiles( files ){
              this.files = files;
            },

            updateSolicitante(solicitantesValido){
              this.solicitantesValido = solicitantesValido;
              $("#btnWizard").attr("disabled", !solicitantesValido);
            },

            next: function (event) {
              if( (this.currentStep + 1) === 2 ){
                  this.comprobarEstadoFormularioCount++;
              }
              if( (this.currentStep + 1) === 3 && (!this.solicitantesValido || !this.formularioValido)){
                Command: toastr.warning("Aviso!", "Datos requeridos");
                return false;
              }

              if( (this.currentStep + 1) === 2 &&  !this.formularioValido){
                Command: toastr.warning("Aviso!", "Campos requeridos");
                return false;
              }

              $("#tab" + (this.currentStep + 1)).attr("data-wizard-state", "current");
              $("#tab" +  parseInt( this.currentStep )).attr("data-wizard-state", "");

              $("#step" + (this.currentStep + 1)).attr("data-wizard-state", "current");
              $("#step" + parseInt( this.currentStep) ).attr("data-wizard-state", "");
              this.currentStep = this.currentStep + 1;
            },

            goTo( idStep ){

                if(idStep == 2){
                  this.comprobarEstadoFormularioCount++;
                }

                if( idStep === 3 && (!this.solicitantesValido || !this.formularioValido)){
                  Command: toastr.warning("Aviso!", "Datos requeridos");
                  return false;
                }

                if( idStep === 2 &&  !this.formularioValido){
                  Command: toastr.warning("Aviso!", "Campos requeridos");
                  return false;
                }


                $("#step" + this.currentStep ).attr("data-wizard-state", "");
                $("#tab" + this.currentStep).attr("data-wizard-state", "");

                $("#tab" + idStep).attr("data-wizard-state", "current");
                $("#step" + idStep).attr("data-wizard-state", "current");
                this.currentStep = idStep;
            },
            
            cambioRadio(valor){
              this.tipoTramite = valor;
              const datosFormulario = localStorage.getItem('datosFormulario') && JSON.parse(localStorage.getItem('datosFormulario')) ;
              datosFormulario.tipoTramite = valor;
              localStorage.setItem('datosFormulario', JSON.stringify(datosFormulario)); 
            },


            async obtenerCamposTemporales(){

              let url = process.env.TESORERIA_HOSTNAME + "/solicitudes-get-tramite/" + this.tramite.id_seguimiento;
              try {
                let response = await axios.get(url);

                this.infoGuardadaFull = response.data[0];

                this.infoGuardada =  JSON.parse( response.data[0].info );

                if( response.data[0].archivos.length > 0 ){
                  this.infoGuardada.archivosGuardados = response.data[0].archivos;
                }

                this.tipoTramite = this.infoGuardada.tipoTramite;
                

                //this.tipoTramiteDisabled = !this.infoGuardada.campos ? 'normal' : 'complementaria';

                this.camposGuardadosObtenidos = true;

                this.solicitantesGuardados = response.data.map( solicitante => {
                  let solicitanteNuevo = JSON.parse(solicitante.info).solicitante;
                  if( solicitanteNuevo ){
                    solicitanteNuevo.id = solicitante.id;
                  }
                  return solicitanteNuevo;
                });

              } catch (error) {
                  console.log(error);
              }
            },

        }
    }
</script>
