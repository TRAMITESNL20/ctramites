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
                                <strong>{{usuario.name + ' ' + usuario.fathers_surname + ' ' + usuario.mothers_surname }}</strong>
                            </div>
                            <div>CURP: {{usuario.curp}}</div>
                            <div>RFC: {{usuario.rfc}}</div>
                            <div>Email: {{usuario.email}}</div>
                            <div>Teléfono: {{usuario.phone}}</div>
                        </div>
                    </b-row>                                      
                    <b-row>
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
                                    <div v-if="data.item.detalle && data.item.detalle.Salidas">
                                        <div class="text-center">
                                            {{currencyFormat('Importe total', data.item.detalle.Salidas['Importe total'])}}
                                        </div>                          
                                    </div>
                                    <div v-else-if="!data.item.detalle">
                                        <div class="text-center text-danger">
                                          No fue posible obtener información <br>   
                                          <span class="text-muted text-danger">    
                                                Consulte al administrador del sistema
                                          </span>
                                        </div>                          
                                    </div>
                                </template>
                                <template #cell(status)="data">
                                    <div v-if="data.item.detalle">
                                        <b-link title="Click para ver detalles" @click="data.toggleDetails" class="mr-2 btn btn-link">
                                            {{!data.detailsShowing ? "Ver detalle " : "Ocultar detalle "}}
                                        </b-link>
                                    </div>
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
                    <b-row>
                        <div class="col-sm-12">
                            <h2 class="border-bottom my-3">Archivos</h2>
                        </div>
                        <div class="col-sm-12">
                            <b-table responsive striped hover :items="files" :fields="camposArchivos">
                                <template #cell(nombrreFile)="data">
                                    {{ data.item.nombrreFile|| "No se selecciono ninguno"}}
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

    export default {

        props: ['datosComplementaria','tipoTramite', 'files', 'usuario'],
        mounted() {
            this.obtenerInformacionDelTramite();
            
            this.camposGenerales = this.datosFormulario.campos;
            let campoEnajenantes = this.camposGenerales.find( campo =>  campo.tipo == 'enajenante');
            let campoExpedientes = this.camposGenerales.find( campo =>  campo.tipo == 'expedientes');


            this.listaEnajentantes = campoEnajenantes.valor.enajenantes.map( (enajenante, index) => {
                enajenante.index = index;
                return enajenante;
            });
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
                camposEnajenantes:[]
            }
        },
  
        methods: {

            obtenerInformacionDelTramite(){
                let informacionEnStorage = ["datosFormulario"];
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
                            "Parte actualizada del impuesto", "Recargos", "Multa corrección fiscal", "Importe total"];
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

