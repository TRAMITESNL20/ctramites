<template>
    <div>
        <b-col cols="12"> 
            <div class="table-responsive">
                <b-table responsive striped hover :items="data" :fields="fields">
                    <template #cell(tipoPersona)="data">
                        <button @click="eliminar(data.index)">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <span >
                            {{data.item.datosPersonales.nacionalidad | capitalize}}
                        </span>
                        <span v-if="data.item.datosPersonales.nacionalidad == 'mexicana'">
                            : {{ data.item.tipoPersona == 'pf' ? 'Persona Física' : 'Persona Moral' }}    
                        </span>

                    </template>
                    <template #cell(datosPersonales)="data">
                        <template-datos-personales-component :datosPersonales="data.item.datosPersonales"></template-datos-personales-component>
                    </template>
                    <template #cell(porcentajePropiedad)="data">
                        <span class="badge badge-pill badge-success">
                            {{data.item.porcentajePropiedad}}
                        </span>
                    </template>
                     <template #cell(porcentajeUsufructo)="data">
                        <span class="badge badge-pill badge-success">
                            {{data.item.porcentajeUsufructo}}
                        </span>
                    </template>
                    <template #cell(porcentajeVenta)="data" v-if="config.name=='vendedor'">
                        <span class="badge badge-pill badge-success">
                            {{data.item.porcentajeVenta}}
                        </span>
                    </template>
                    <template #cell(actions)="data" v-if="!config.onlyRead">
                        <span>
                            <modal-aviso-enajenacion-component 
                            @editItem="editItem" :config="config" :item="data.item" :indexItem="data.index">
                                
                            </modal-aviso-enajenacion-component>
                        </span>
                    </template>
                </b-table>             
            </div>
        </b-col>
        <b-col  cols="12" v-if="config.porcentajePropiedadAsignado  != 100 ||  config.porcentajeUsufructoAsignado != 100" >
            <modal-aviso-enajenacion-component @addItem="addItem" :config="config" v-if="!config.onlyRead">
            </modal-aviso-enajenacion-component>
       </b-col>
    </div>
</template>

<script>
import avisoEnajenacionService from '../services/avisoEnajenacion.services.js';
export default {
    props: [ 'type', 'config', 'itemsPre'],
    data(){
        return {
            isVendedor:false,
            items: [],
            fields:[
                { key: 'tipoPersona', label: 'Tipo Persona' },
                { key: 'datosPersonales', label: 'Datos Personales' },
                { key: 'porcentajePropiedad', label: '% propiedad' },
                { key: 'porcentajeUsufructo', label: 'Usufructo' }

            ],
            data:[],
            porcentajePropiedadAsignado: 0,
            porcentajeUsufructoAsignado:0,
            porcentajeVentaAsignado:0
        }
    },
    mounted() {
        if(this.itemsPre && this.itemsPre.length > 0) {
            this.items = this.itemsPre;
        }
        if(this.config.name == 'vendedor'){
            this.fields.push({ key: 'porcentajeVenta', label: '% Venta' });
        }
        if(!this.config.onlyRead){
            this.fields.push({ key: 'actions', label: 'Acciones' });
        }
        
        this.mapearDatos();
    },
    methods: {
        addItem(data){
            this.items.push( data );
            this.mapearDatos();
        },

        editItem( res ){
            this.items[res.index] = res.item;
            this.mapearDatos();
        },

        mapearDatos(){
            this.cleanData();
            this.data = this.items.map( item => {

                if(item.datosPersonales.nacionalidad=='mexicana'){
                    item.tipoPersona = item.datosPersonales.tipoPersona; 
                }
                
                item.porcentajePropiedad =  item.datosPorcentajes.porcentajePropiedad;
                item.porcentajeUsufructo = item.datosPorcentajes.porcentajeUsufructo;
                item.porcentajeVenta = item.datosPorcentajes.porcentajeVenta;
                return item;
            }); 
            if(!this.config.onlyRead){
                this.calcularPorcentajePropiedadAndUsufructo();
            }           
        },

        cleanData(){
            this.items = this.items.map( item => {
                if(item.datosPersonales.nacionalidad=='mexicana'){
                    if(item.datosPersonales.tipoPersona == 'pf'){
                        item.datosPersonales.razonSocial = null;
                    } else {
                        item.datosPersonales.apMa = null;
                        item.datosPersonales.apPat = null;
                        item.datosPersonales.curp = null;
                        item.datosPersonales.estado = null;
                        item.datosPersonales.fechaNacimiento = null;
                        item.datosPersonales.genero = null;
                        item.datosPersonales.nombre = null;
                    }
                }
                return item;
            });
        },

        calcularPorcentajePropiedadAndUsufructo(){
            let totalPorcentajePropiedad = 0;
            let totalPorcentajeUsufructo = 0;
            let totalPorcentajeVentta = 0;
            if(this.items && this.items.length > 0){
                this.items.forEach( item  => {
                    totalPorcentajePropiedad = Number(Number(Number(totalPorcentajePropiedad) + Number(item.datosPorcentajes.porcentajePropiedad)).toFixed(this.$const.PRECISION));
                    totalPorcentajeUsufructo = Number(Number(Number(totalPorcentajeUsufructo) + Number(item.datosPorcentajes.porcentajeUsufructo)).toFixed(this.$const.PRECISION));
                    if(this.config.name == 'vendedor'){
                        totalPorcentajeVentta = Number(Number(Number(totalPorcentajeVentta) + Number(item.datosPorcentajes.porcentajeVenta)).toFixed(this.$const.PRECISION));
                    }
                });
            }

            this.config.porcentajePropiedadAsignado = totalPorcentajePropiedad;
            this.config.porcentajeUsufructoAsignado = totalPorcentajeUsufructo;

            if(this.config.name == 'vendedor'){
                this.config.porcentajeVentaAsignado = totalPorcentajeVentta;                
            }

            this.notify();
        },

        notify(){
            let data = {
                items:this.items,
                config:this.config
            }
            this.$emit('listaItems', data);
        },

        eliminar(indice){
            this.items.splice(indice,1);
            this.mapearDatos();
        }





    },

}

</script>
