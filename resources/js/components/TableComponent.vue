<template>
    <div>
        <b-col cols="12"> 
            <div class="table-responsive">
                <b-table responsive striped hover :items="data" :fields="fields">
                    <template #cell(tipoPersona)="data">
                        <button @click="eliminar(data.index)">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        {{ data.item.tipoPersona == 'pf' ? 'Persona Física' : 'Persona Moral' }}
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
                    <template #cell(porcentajeVenta)="data">
                        <span class="badge badge-pill badge-success">
                            {{data.item.porcentajeVenta}}
                        </span>
                    </template>
                    <template #cell(actions)="data">
                        <span>
                            <modal-aviso-enajenacion-component 
                            @editItem="editItem" :config="config" :item="data.item" :indexItem="data.index">
                                
                            </modal-aviso-enajenacion-component>
                        </span>
                    </template>
                </b-table>             
            </div>
        </b-col>
        <b-col  cols="12" v-if=" config.porcentajePropiedadAsignado  != 100 ||  config.porcentajeUsufructoAsignado != 100">
            <modal-aviso-enajenacion-component @addItem="addItem" :config="config">
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
                { key: 'porcentajeUsufructo', label: 'Usufructo' },
                { key: 'porcentajeVenta', label: '% Venta' },
                { key: 'actions', class:'text-center', label:'Acciones'}

            ],
            data:[],
            porcentajePropiedadAsignado: 0,
            porcentajeUsufructoAsignado:0
        }
    },
    mounted() {
        if(this.itemsPre && this.itemsPre.length > 0) {
            this.items = this.itemsPre;
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
            this.data = this.items.map( item => {
                item.tipoPersona = item.datosPersonales.tipoPersona;
                item.porcentajePropiedad =  item.datosPorcentajes.porcentajePropiedad;
                item.porcentajeUsufructo = item.datosPorcentajes.porcentajeUsufructo;
                item.porcentajeVenta = item.datosPorcentajes.porcentajeVenta;
                return item;
            }); 
            this.calcularPorcentajePropiedadAndUsufructo();           
        },

        calcularPorcentajePropiedadAndUsufructo(){
            let totalPorcentajePropiedad = 0;
            let totalPorcentajeUsufructo = 0;
            if(this.items && this.items.length > 0){
                this.items.forEach( item  => {
                    totalPorcentajePropiedad = Number(Number(Number(totalPorcentajePropiedad) + Number(item.datosPorcentajes.porcentajePropiedad)).toFixed(this.$const.PRECISION));
                    totalPorcentajeUsufructo = Number(Number(Number(totalPorcentajeUsufructo) + Number(item.datosPorcentajes.porcentajeUsufructo)).toFixed(this.$const.PRECISION));
                });
            }

            this.config.porcentajePropiedadAsignado = totalPorcentajePropiedad;
            this.config.porcentajeUsufructoAsignado = totalPorcentajeUsufructo;

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
