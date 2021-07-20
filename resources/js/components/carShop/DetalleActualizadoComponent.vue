<template>
    <span v-if="tieneDetalleAnterior"> 
        <a class="btn btn-sm btn-warning font-weight-bolder text-uppercase text-white mr-2" :title="msjTitle" @click="verDetalle" style="padding: 2px;">                    
            <span class="text-center text-white">
                <small class="text-center text-white">
                    Costo actualizado
                </small>
            </span>
        </a>
        <b-modal v-model="modalShow" title="Actualización" size="xl">
            <div class="row" v-for="(item, index) in elementos" :key="index" >
                <div class="col-lg-6" v-if="item.detalleAnterior">
                    <div class="text-center">
                        <h4>Anterior </h4>
                        <strong v-if="item.detalleAnterior.detalle.Salidas"> 
                            {{ currencyFormat('Importe total',item.detalleAnterior.detalle.Salidas['Importe total'])  }} 
                        </strong>
                        <strong v-else-if="item.detalleAnterior.detalle.costo_final"> 
                            {{ item.detalleAnterior.detalle.costo_final | toCurrency  }} 
                        </strong>                        
                    </div>
                    <transition name="slide-fade" v-if="item.detalleAnterior.detalle.Salidas">
                        <b-card no-body v-if="item.detalleAnterior">
                            <b-card-body id="nav-scroller"ref="content"style="position:relative; height:400px; overflow-y:scroll;">
                                <b-row v-for="(salida, key) in item.detalleAnterior.detalle.Salidas" :key="key">
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
                </div>
                <div class="col-lg-6">
                    <div class="text-center">
                        <h4>Actual</h4>
                        <strong v-if="item.detalleActual.Salidas "> 
                            {{ currencyFormat('Importe total',item.detalleActual.Salidas['Importe total'])  }} 
                        </strong>
                        <strong v-else-if="item.detalleActual.costo_final"> 
                            {{ item.detalleActual.costo_final | toCurrency  }} 
                        </strong>  
                    </div>
                    <transition name="slide-fade" v-if="item.detalleActual.Salidas">
                        <b-card no-body v-if="item.detalleActual">
                            <b-card-body id="nav-scroller"ref="content"style="position:relative; height:400px; overflow-y:scroll;">
                                <b-row v-for="(salida, key) in item.detalleActual.Salidas" :key="key">
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
                </div>
            </div>
        </b-modal>

    </span>
</template>

<script>

    import Vue from 'vue';
    import { uuid } from 'vue-uuid';

    export default {
        props: ['items','inCart'],
        data(){
            return {
                idItem:null,
                modalShow:false,
                msjTitle:'',
                elementos:[]

            }
        },
        mounted() {
        	this.idItem = uuid.v4();
            this.msjTitle = 'Costo Actualizado';

            if(typeof this.items == 'array' || this.inCart){
                this.elementos = this.items.map( item => {
                    if( !item.detalleActual && item.detalle){
                        item.detalleActual = item.detalle;
                    }
                    return item;
                });
            } else {
                this.elementos.push(this.items);
                this.elementos = this.elementos.map( item => {
                    if( !item.detalleActual && item.detalle){
                        item.detalleActual = item.detalle;
                    }
                    return item;
                });
            }


        },

        computed:{
            tieneDetalleAnterior(){
                let tiene = this.elementos.find( item => !!item.detalleAnterior );
                return tiene;
            },
        },
        methods: {

            verDetalle(){
                //if(!this.onlyRead){
                    this.modalShow = !this.modalShow;
                //}
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