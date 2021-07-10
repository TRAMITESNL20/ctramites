<template>
        <div class="container">
            <div class="card">
                 <div class="card-header">
                    Resumen
                 </div>
                <div class="card-body">
                    <b-row>
                        <div class="col-sm-6">
                            <h6 class="mb-3">Solicitante:</h6>
                            <div>
                                <strong>{{usuario.nombreSolicitante + ' ' + usuario.apPat + ' ' + usuario.apMat }}</strong>
                            </div>
                            <div>CURP: {{usuario.curp}}</div>
                            <div>RFC: {{usuario.rfc}}</div>
                            <div>Email: {{usuario.email}}</div>
                            <div>Teléfono: {{usuario.phone}}</div>
                        </div>
                    </b-row>                                      
                    <b-row v-if="listaEnajentantes.length > 0  && tipoTramite != 'complementaria'" >
                        <div class="col-sm-12">
                            <h2 class="border-bottom my-3">Enajenantes</h2>
                        </div>
                        <div class="col-sm-12">
                            <b-table responsive striped hover :items="listaEnajentantes" :fields="camposEnajenantes" ref="table"  class="text-center">
                                <template #cell(tipoPersona)="data">
                                    {{ data.item.tipoPersona == 'pf' ? 'Persona Física' : 'Persona Moral' }}
                                </template>
                                <template #cell(porcentajeCompra)="data">
                                    <span class="badge badge-pill badge-success">
                                        {{data.item.porcentajeCompra}}
                                    </span>
                                </template>
                                <template #cell(datosPersonales)="data">
                                    <template-datos-personales-component :datosPersonales="data.item.datosPersonales"></template-datos-personales-component>
                                </template>
                                <template #cell(detalle)="data" >
                                    <transition name="slide-fade">
                                        <div v-if="!actualizandoDatos">
                                            <div v-if="data.item.detalle && data.item.detalle.Salidas">
                                                <div class="text-center">
                                                    {{currencyFormat('Importe total', data.item.detalle.Salidas['Importe total'])}}
                                                </div>                          
                                            </div>
                                            <div v-else-if="!data.item.detalle || typeof data.item.detalle != 'object'">
                                                <div class="text-center text-danger">
                                                  No fue posible obtener información <br>   
                                                  <span class="text-muted text-danger">    
                                                        Consulte al administrador del sistema
                                                  </span>
                                                </div>                          
                                            </div>
                                        </div>
                                        <div v-else-if="actualizandoDatos">
                                            <b-spinner small type="grow" label="Loading..."></b-spinner>
                                        </div>
                                    </transition>
                                </template>
                                <template #cell(status)="data">
                                    <transition name="slide-fade">
                                        <div v-if="data.item.detalle && typeof data.item.detalle == 'object' && !actualizandoDatos ">
                                            <b-link title="Click para ver detalles" @click="data.toggleDetails" class="mr-2 btn btn-link">
                                                {{!data.detailsShowing ? "Ver detalle " : "Ocultar detalle "}}
                                            </b-link>
                                        </div>

                                        <div v-else-if="actualizandoDatos">
                                            <b-spinner small  type="grow" label="Loading..."></b-spinner>
                                        </div>
                                    </transition>
                                </template> 
                                <template #row-details="data" #title="Detalle">
                                    <transition name="slide-fade">
                                    <b-card no-body v-if="data">
                                        <b-card-body id="nav-scroller"ref="content"style="position:relative; height:400px; overflow-y:scroll;">
                                            <b-row v-for="(salida, key) in data.item.detalle.Salidas" :key="key">
                                                <b-col class="text-left" style="width: 70%" >
                                                    <strong>{{ key }}</strong>
                                                </b-col>
                                                <b-col class="text-right" >
                                                    <span class="text-muted">   {{ currencyFormat(key, salida) }} </span>
                                                </b-col>
                                            </b-row>
                                        </b-card-body> 
                                    </b-card>
                                    </transition>
                                </template>                                                          
                            </b-table>
                        </div>
                    </b-row>        
                    <b-row v-if="files.length > 0 && tipoTramite != 'complementaria'">
                        <div class="col-sm-12">
                            <h2 class="border-bottom my-3">Archivos</h2>
                        </div>
                        <div class="col-sm-12">
                            <b-table responsive striped hover :items="files" :fields="camposArchivos">
                                <template #cell(nombrreFile)="data">
                                    <span v-if="data.item.nombrreFile && data.item.nombrreFile.split('/').length > 0">
                                        {{ data.item.nombrreFile.split('/')[ data.item.nombrreFile.split("/").length - 1 ]  || "No se selecciono ninguno" }}
                                    </span>
                                </template>
                            </b-table>
                        </div>
                    </b-row> 
                    <b-row v-if="datosComplementaria && datosComplementaria.complementarias && datosComplementaria.complementarias.length > 0">
                        <div class="col-sm-12">
                            <h2 class="border-bottom my-3">Complementarias</h2>
                        </div>
                        <div class="col-sm-12">
                            <b-table responsive striped hover :items="datosComplementaria.complementarias" :fields="camposComplementarias">
                                <template #cell(enajenante)="data">
                                    <template-datos-personales-component :datosPersonales="data.item.enajenante.datosPersonales"></template-datos-personales-component>
                                </template>
                                <template #cell(detalle)="data" >
                                    <div v-if="data.item.detalle && data.item.detalle.Salidas">
                                        <div class="text-center">
                                            {{currencyFormat('Cantidad a cargo', data.item.detalle.Salidas['Cantidad a cargo'])}}
                                        </div>                          
                                    </div>
                                    <div v-else-if="!data.item.detalle || typeof data.item.detalle != 'object'">
                                        <div class="text-center text-danger">
                                          Error de comunicacion. <br>   
                                          <span class="text-muted text-danger">    
                                            Favor de iniciar el tramite.
                                          </span>
                                        </div>                          
                                    </div>
                                </template>
                                <template #cell(status)="data">
                                    <div v-if="data.item.detalle && typeof data.item.detalle == 'object'">
                                        <b-link title="Click para ver detalles" @click="data.toggleDetails" class="mr-2 btn btn-link">
                                            {{!data.detailsShowing ? "Ver detalle " : "Ocultar detalle "}}
                                        </b-link>
                                    </div>
                                </template> 
                                <template #row-details="data" #title="Detalle">
                                    <transition name="slide-fade" tag="b-card">
                                        <b-card key="1" no-body v-if="data && data.item.detalle.Salidas" >
                                                <template #header>
                                                  <h4 class="mb-0">Complementaria</h4>
                                                </template>
                                                <b-card-body id="nav-scroller"ref="content"style="position:relative; height:400px; overflow-y:scroll;">
                                                    
  
                                                    <b-row v-for="(salida, key) in data.item.detalle.Salidas" :key="key">
                                                        <b-col class="text-left" style="width: 70%" >
                                                            <strong>{{ key }}</strong>
                                                        </b-col>
                                                        <b-col class="text-right" >
                                                            <span class="text-muted">   {{ currencyFormat(key, salida) }} </span>
                                                        </b-col>
                                                    </b-row>
                                                </b-card-body> 
                                        </b-card>
                                    </transition>
                                </template>   
                            </b-table>
                        </div>
                    </b-row> 
                </div>
            </div>
        </div>

</template>
<script>

    import Vue from 'vue'
    import costosApi from '../services/costosApi.services.js';

    export default {

        props: ['datosComplementaria','tipoTramite', 'files'],
        mounted() {
            

            this.obtenerInformacionDelTramite();
            this.usuario = this.listaSolicitantes && this.listaSolicitantes.length > 0 ? this.listaSolicitantes[0] : {};
            
            this.camposGenerales = this.datosFormulario.campos;
            let campoEnajenantes = this.camposGenerales.find( campo =>  campo.tipo == 'enajenante');
            let campoExpedientes = this.camposGenerales.find( campo =>  campo.tipo == 'expedientes');
            let campoFecha= this.camposGenerales.find( campo =>  campo.nombre == 'Fecha de escritura o minuta');

            let promises = [];

            this.listaEnajentantes = campoEnajenantes.valor.enajenantes.map( (enajenante, index) => {
                if(enajenante.datosParaDeterminarImpuesto){
                    let data = {
                        multa_correccion_fiscal:    enajenante.datosParaDeterminarImpuesto.multaCorreccion,
                        monto_operacion:            enajenante.datosParaDeterminarImpuesto.montoOperacion,
                        pago_provisional_lisr:      enajenante.datosParaDeterminarImpuesto.pagoProvisional,
                        ganancia_obtenida:          enajenante.datosParaDeterminarImpuesto.gananciaObtenida,
                        fecha_escritura:            campoFecha.valor
                    }
                    promises.push(costosApi.getISRRequest(data,  {  index }  ));    
                }
                enajenante.index = index;
                return enajenante;
            });
            this.actualizandoDatos = true;
            this.$emit('actualizandoDatos', this.actualizandoDatos);
            axios.all(promises).then(axios.spread((...responses) => {
                responses.forEach( res => {
                    this.listaEnajentantes[res.config.headers.index].detalle = res.data
                });

                this.datosFormulario.campos.map( campo => {
                    if( campo.tipo == 'enajenante' ){
                        campo.valor.enajenantes = this.listaEnajentantes;
                    }
                    return campo;
                });
                localStorage.setItem('datosFormulario', JSON.stringify(this.datosFormulario));
                this.actualizandoDatos = false;
                this.$emit('actualizandoDatos', this.actualizandoDatos); 
            })).catch(errors => {
                console.log( errors);
                console.log("error al obtener la info")
            }).finally( () => {


            } )            
            this.listaExpedientes = campoExpedientes.valor.expedientes;
            this.camposExpedientes = ['expediente', 'direccion'];

            this.camposArchivos = ['nombre',{ key: 'nombrreFile', label: 'Archivo' }];
            this.camposEnajenantes =[
                    { key: 'porcentajeCompra', label: '% Venta' },
                    { key: 'datosPersonales', label: 'Datos Personales' },
                    { key: 'tipoPersona', label: 'Tipo Persona' },
                    { key: 'detalle', label: 'Total' },
                    { key: 'status', label:"Acciones" }
            ];
        },

        data(){
            return {
                datosFormulario:{},
                listaEnajentantes:[],
                listaExpedientes:[],
                camposExpedientes:[],
                camposArchivos:[],
                camposGenerales:[],
                camposEnajenantes:[],
                camposComplementarias:[
                    { key: 'enajenante', label: 'Datos Enajenante' },
                    { key: 'detalle', label: 'Total' },
                    { key: 'fechaEscritura', label:"Fecha Escritura" },
                    { key: 'status', label:"Acciones" }
                ],
                usuario:{},
                actualizandoDatos:false
            }
        },
  
        methods: {

            obtenerInformacionDelTramite(){
                let informacionEnStorage = ["datosFormulario", "listaSolicitantes"];
                informacionEnStorage.forEach( name => {
                    if (localStorage.getItem(name)) {
                      try {
                        this[name] = JSON.parse(localStorage.getItem(name));
                      } catch(e) {
                        letocalStorage.removeItem(name);
                      }
                    }
                });
            },


            currencyFormat(campoName, salida){
                let arr = ["Ganancia Obtenida","Monto obtenido conforme al art 127 LISR",
                            "Pago provisional conforme al art 126 LISR","Impuesto correspondiente a la entidad federativa",
                            "Parte actualizada del impuesto", "Recargos", "Multa corrección fiscal", "Importe total", "Cantidad a cargo",
                            "Monto pagado en la declaracion inmediata anterior", "Pago en exceso", "Diferencia de Impuesto correspondiente a la Entidad Federativa", "Importe total a pagar"];
                if(arr.includes(campoName)){
                    let text = Vue.filter('toCurrency')(salida);
                    return text;
                } else{
                    return salida;
                }
            }

        }
    }
</script>

