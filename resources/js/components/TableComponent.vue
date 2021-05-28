<template>
    <div>
        <b-col cols="12"> 
            <div class="table-responsive">
                <b-table responsive striped hover :items="data" :fields="fields">
                    <template #cell(tipoPersona)="data">
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
                            hola mundo
                        </span>
                    </template>
                </b-table>             
            </div>
        </b-col>
        <b-col  cols="12" >
            <modal-aviso-enajenacion-component @addItem="addItem" :config="config"></modal-aviso-enajenacion-component>
       </b-col>
    </div>
</template>

<script>
import avisoEnajenacionService from '../services/avisoEnajenacion.services.js';
export default {
    props: [ 'type', 'config'],
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
            data:[]
        }
    },
    mounted() {
    },
    methods: {
        addItem(data){
            this.items.push( data );
            this.data = this.items.map( item => {
                item.tipoPersona = item.datosPersonales.tipoPersona;
                return item;
            })
        }
    },

}

</script>
