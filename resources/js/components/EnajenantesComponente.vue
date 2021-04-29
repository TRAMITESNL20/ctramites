 <template>
    <div>
<!--
        <b-row>
            <b-col cols="12" md="6">
                <b-form-group label="MONTO DE OPERACIÓN" label-for="monto-operacion-gral-input" >
                  <b-input-group  >
                    <template #prepend>
                      <b-input-group-text >$</b-input-group-text>
                    </template>
                    <b-form-input
                      id="monto-operacion-gral-input" name="montoOperacionGral" v-model="montoOperacion" style="background-color: #e5f2f5 !important"
                       @change="cambioMontoOperacionGBL()"></b-form-input>
                  </b-input-group>
                </b-form-group>
            </b-col>
        </b-row> -->     
        <b-row > 
            <b-col>
                <b-form-group label="Porcentaje que enajena" label-for="procentaje-venta-input" >
                    <b-form-input  id="procentaje-venta-input" name="procentaje-venta"  v-model="$v.porcentajeVenta.$model" @input="validar"  :state="$v.porcentajeVenta.$dirty ? !$v.porcentajeVenta.$error : null" aria-describedby="porcentajeVenta-input-feedback" max="100" type="number"  style="background-color: #e5f2f5 !important"></b-form-input>
                    <b-input-group prepend="0" append="100" >
                        <b-form-input  id="procentaje-venta-rango" name="procentaje-venta"  v-model="$v.porcentajeVenta.$model" type="range" max="100" @input="validar" :state="$v.porcentajeVenta.$dirty ? !$v.porcentajeVenta.$error : null" aria-describedby="porcentajeVenta-input-feedback"></b-form-input>
                    </b-input-group>
                    <b-form-invalid-feedback id="porcentajeVenta-input-feedback">
                        <span v-if="!$v.porcentajeVenta.isMayorQuePorcentajeAsignado"  class="form-text text-danger">
                           La suma de los porcentajes individuales es mayor al porcentaje de enajenación.
                        </span>
                        <span v-if="!$v.porcentajeVenta.isPorcentajeComplete"  class="form-text text-danger">
                           El porcentaje de venta debe ser de {{$v.porcentajeVenta.$model}}
                        </span>
                        <span v-if="!$v.porcentajeVenta.maxValue"  class="form-text text-danger">
                           El porcentaje de venta debe ser menor o igual a 100
                        </span>

                    </b-form-invalid-feedback>
                </b-form-group>
            </b-col>         
        </b-row>
        <b-row >
            <b-col  cols="12" >
                <div class="table-responsive" id="scrollDiv">
                        <div id="gradientBackgroundLeft" class="border-table-left" ></div>
                        <div id="gradientBackgroundRight" class="border-table-right" ></div>
                    <table class="table  table-striped" id="tableEnajenantes">
                        <thead style="border-bottom: solid;">
                            <tr>
                                <th class="text-center">
                                    Tipo de persona
                                </th>
                                <th>
                                    Nombre
                                </th>
                                <th>
                                    RFC 
                                </th>
                                <th>
                                    CURP
                                </th>
                                <th>
                                    % Venta 
                                </th>
                                <th>
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- <div style="right:10; width:20%; height:100%; position:absolute "> -->
                                <!-- <div class="border-table-left"></div> -->
                                <!-- </div> -->
                            <tr  v-for="(registro, key) in enajentantes"  >
                                <td class="text-center">
                                    <i class="fa fa-times" id="iconBtnEliminar"  @click="eliminar(key)" style="cursor: pointer; color: red;" title="Quitar"></i> 
                                    {{ registro.tipoPersona == 'pf' ? 'Persona Física' : 'Persona Moral' }}
                                </td>	
                                <td>
                                    {{ registro.tipoPersona == 'pm' ? registro.datosPersonales.razonSocial : (registro.datosPersonales.nombre + ' ' + registro.datosPersonales.apPat + ' ' + registro.datosPersonales.apMat) }}
                                </td>	
                                <td>
                                   {{ registro.datosPersonales.rfc }}
                                </td>	
                                <td>
                                    {{ registro.datosPersonales.curp }}
                                </td>	
                                <td>
                                    {{  registro.porcentajeCompra }}
                                </td>		
                                <td>
                                    <modal-component 
                                		@editaEnajentante="editaEnajentante"  
                                        :enajenanteEditado="registro" 
                                        :porcentajeAsignado="porcentajeTotalCompra" 
                                        :indexEnajenanteEditado="key"
                                        :porcentajeVenta="$v.porcentajeVenta.$model" 
                                        :listaCurps="listaCurps" 
                                        :configCostos="configCostos"
                                        :montoOperacionGbl="montoOperacion">
                                	</modal-component>                    	
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </div>
                Porcentaje de venta asignado 
                <b-progress :value="porcentajeTotalCompra" max="porcentajeVenta" show-value class="mb-3" :precision="$const.PRECISION"></b-progress>
            </b-col>
            <b-col v-if="totalMontoOperacionDeclarado != totalMontoOperacionDeEnajentantes && enajentantes.length > 0">
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                  <strong>IMPORTANTE!</strong> El monto de operación declarado, no corresponde al ingresado en el Aviso de Enajenación
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
            </b-col>
            <b-col  cols="12" >
        	    <modal-component 
        		@addEnajentante="addEnajentante" v-if="porcentajeTotalCompra < $v.porcentajeVenta.$model" 
                    :porcentajeAsignado="porcentajeTotalCompra" 
                    :porcentajeVenta="$v.porcentajeVenta.$model" 
                    :listaCurps="listaCurps" 
                    :configCostos="configCostos"
                    :montoOperacionGbl="montoOperacion">
        	   </modal-component>
           </b-col>
        </b-row> 
        <b-row v-if="configCostos.declararEn0">
            <b-col  cols="12" >
                <label>Motivo y Fundamento Legal</label>
                <textarea id="motivo" name="motivo" class="form-control  form-control-lg " style="background-color: #e5f2f5 !important" v-model="motivo" @input="validar" @change="validar"></b-form-input>
                </b-input-group>></textarea>
                <small  v-if="(!motivo || motivo.length < 0) && ( showMensajes || estadoFormulario > 0)" class="position-absolute">
                    <p  class="form-text text-danger">
                        Campo requerido
                    </p>
                </small>
            </b-col>
       </b-row>         
    </div>
</template>
<script>
    import Vue from 'vue';
    import { validationMixin } from 'vuelidate'
    import { maxValue } from 'vuelidate/lib/validators';
	export default {
        mixins: [validationMixin],
        validations() {
          return {
                porcentajeVenta:{
                    isMayorQuePorcentajeAsignado(value) {
                        return this.porcentajeTotalCompra <= value
                    },
                    isPorcentajeComplete(value){
                        if(this.enajentantes && this.enajentantes.length > 0){
                            return this.porcentajeTotalCompra == value;
                        } else {
                            return true;
                        }
                        
                    },
                    maxValue: maxValue(100)
                }
            }
        },
        computed:{
            listaCurps(){
                return this.enajentantes.map( enajentante => enajentante.datosPersonales.curp );
            },

/*            totalMontoOperacionDeclarado(){
                let eltotal = 0;
                debugger;
                let campoExpedientes = this.configCostos.campos.find(campo => campo.nombre == "Expedientes");
                if(campoExpedientes && campoExpedientes.valor){
                    if(campoExpedientes.valor.expedientes){
                      if( campoExpedientes.valor.expedientes.length > 0){
                        campoExpedientes.valor.expedientes.forEach( expediente => {
                          
                          if( expediente.insumos && expediente.insumos.data && expediente.insumos.data.valor_operacion){
                            let total = Vue.filter('toNumber')(expediente.insumos.data.valor_operacion);
                            eltotal = eltotal + total;
                            return eltotal; 
                          }
                          
                        });
                      }
                    } else {
                      Command: toastr.error("Error!", "No se encontro configurado la seccion de expedientes, Consulte al administrador del sistema");
                    }
                }

                return eltotal; 
            },*/
/*
            totalMontoOperacionDeEnajentantes(){
                return Vue.filter('toNumber')(this.montoOperacion);
            }*/
        },
		mounted(){
            if(this.campo.valor){
                this.montoOperacion =  Vue.filter('formatoMoneda')(this.campo.valor.montoOperacion);
            }
            if(this.campo.valor && this.campo.valor.enajenantes && this.campo.valor.enajenantes.length > 0){
                this.enajentantes = this.campo.valor.enajenantes;
                this.$v.porcentajeVenta.$model  = this.campo.valor.porcentajeVenta;
                this.motivo = this.campo.valor.motivo;
                
                this.calcularTotalPorcentaje();
                this.calcularTotalMontoOperacionDeEnajentantes();
            }
            this.validar();
            $('#gradientBackgroundLeft').hide();
            
		},
        updated(){
            var height = $("#tableEnajenantes")[0].clientHeight;
            $("#gradientBackgroundLeft").css( "width" ,'12%');
            $("#gradientBackgroundRight").css( "width" ,'12%');
            $("#gradientBackgroundLeft").css( "height" ,  height+"px" );
            $("#gradientBackgroundRight").css( "height" ,  height+"px" );
            $('#scrollDiv').scroll( function() {
                ( $('#scrollDiv').scrollLeft() == ($('#scrollDiv table').width() - $('#scrollDiv').width())) ?  $('#gradientBackgroundRight').hide() : $('#gradientBackgroundRight').show();

                ( $('#scrollDiv').scrollLeft() > 0) ? $('#gradientBackgroundLeft').show() : $('#gradientBackgroundLeft').hide();
            });
        },
        props:{

          campo:{
            default: null,
            type: Object
          },
          estadoFormulario:{
            default: null,
            type: Number
          },
          showMensajes:{
            default: false,
            type: Boolean
          },
          configCostos:{
            type: Object
          }
        },
	    data(){
	        return {
	            enajentantes: [],
                porcentajeTotalCompra: 0,
                porcentajeVenta:100,
                motivo:'',
                totalMontoOperacionDeEnajentantes:null,
                montoOperacion:Vue.filter('formatoMoneda')("0"),
                totalMontoOperacionDeclarado:null
	        }
	    },
		methods : {
            eliminar( index ){
                this.enajentantes.splice(index, 1);
                this.calcularTotalPorcentaje();
                this.calcularTotalMontoOperacionDeEnajentantes();
            },

           	addEnajentante(enajentante){
           		this.enajentantes.push(enajentante);
                //this.$forceUpdate();
                this.calcularTotalPorcentaje();
                this.calcularTotalMontoOperacionDeEnajentantes();
           	},

            editaEnajentante(response){
                if( response.enajenante.tipoPersona === "pf" ){
                    delete response.enajenante.datosPersonales.razonSocial;
                } else {
                    delete response.enajenante.datosPersonales.nombre
                    delete response.enajenante.datosPersonales.apPat
                    delete response.enajenante.apMat;
                    delete response.enajenante.datosPersonales.curp;
                    delete response.enajenante.datosPersonales.estado;
                    delete response.enajenante.datosPersonales.fechaNacimiento;
                    delete response.enajenante.datosPersonales.genero;
                    delete response.enajenante.ife;
                }
            	this.enajentantes[response.index] = response.enajenante;
                
                this.calcularTotalPorcentaje();
                this.calcularTotalMontoOperacionDeEnajentantes();

           	},

            calcularTotalPorcentaje(){
                
                this.$v.$touch()
                var total = 0;

                this.enajentantes.forEach(enajentante => {
                    total = Number(Number(Number(total) + Number(enajentante.porcentajeCompra)).toFixed(this.$const.PRECISION));
                });
                this.porcentajeTotalCompra = total;
                this.validar();
                this.$forceUpdate();
            },

            validar(){
                this.campo.valido =  this.porcentajeTotalCompra == this.$v.porcentajeVenta.$model;

                let valor = {enajenantes:this.enajentantes, porcentajeVenta:this.$v.porcentajeVenta.$model};
                valor.montoOperacion = this.montoOperacion;
                if(this.configCostos.declararEn0){
                    valor.motivo = this.motivo;
                    if( this.motivo == undefined || this.motivo.length == 0){
                        this.campo.valido = false;
                    }
                }
                this.campo.valor = valor;
                this.$emit('updateForm', this.campo);
          
            },

            cambioMontoOperacionGBL(){
                this.formatoMoneda();
                this.calcularPorcentajePorEnajenantes();
            },

            formatoMoneda(){
                this.montoOperacion = Vue.filter('formatoMoneda')(this.montoOperacion +"");
                this.validar();
            },

            calcularPorcentajePorEnajenantes(){
                this.enajentantes = this.enajentantes.map(enaj => {
                    let procenttaje = (enaj.porcentajeCompra / 100);
                    let montoOperacionGbl =  Vue.filter('toNumber')(this.montoOperacion);          
                    let montoCorrespondiente =  montoOperacionGbl * procenttaje;
                    enaj.datosParaDeterminarImpuesto.montoOperacion = Vue.filter('formatoMoneda')( montoCorrespondiente ); 
                    return enaj;
                });
                this.validar();                
            },

            calcularTotalMontoOperacionDeEnajentantes(){
                let eltotal = 0;
                if(this.enajentantes && this.enajentantes.length > 0){
                    this.enajentantes.forEach( enajenante => {
                        let total = Vue.filter('toNumber')(enajenante.datosParaDeterminarImpuesto.montoOperacion);
                        eltotal = eltotal + total;
                    });
                }
                this.totalMontoOperacionDeEnajentantes = eltotal;
                this.calcularTotalMontoOperacionDeclarado();
            },

            calcularTotalMontoOperacionDeclarado(){
                let eltotal = 0;
                
                let campoExpedientes = this.configCostos.campos.find(campo => campo.nombre == "Expedientes");
                console.log(JSON.parse(JSON.stringify(campoExpedientes)))
                if(campoExpedientes && campoExpedientes.valor){
                    if(campoExpedientes.valor.expedientes){
                      if( campoExpedientes.valor.expedientes.length > 0){
                        campoExpedientes.valor.expedientes.forEach( expediente => {
                          
                          if( expediente.insumos && expediente.insumos.data && expediente.insumos.data.valor_operacion){
                            let total = Vue.filter('toNumber')(expediente.insumos.data.valor_operacion);
                            eltotal = eltotal + total;
                            return eltotal; 
                          }
                          
                        });
                      }
                    } else {
                      Command: toastr.error("Error!", "No se encontro configurado la seccion de expedientes, Consulte al administrador del sistema");
                    }
                }
                this.totalMontoOperacionDeclarado = eltotal;
                //return eltotal; 
            }

		},

        watch: {
            'configCostos.declararEn0':function (val, oldVal){
                if(this.enajentantes.length > 0) {
                        this.enajentantes = this.enajentantes.map( enajentante => {
                        enajentante.datosParaDeterminarImpuesto.gananciaObtenida = '0';
                        enajentante.datosParaDeterminarImpuesto.montoOperacion = '0';
                        enajentante.datosParaDeterminarImpuesto.multaCorreccion = '0';
                        enajentante.datosParaDeterminarImpuesto.pagoProvisional = '0';
                        return enajentante;
                    });  
                }
                this.validar();
            }
        }

	};
</script>