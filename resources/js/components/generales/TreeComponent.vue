<template>
    <div >
        <div v-for="(dato, key) in item" style="list-style-type: none; margin-left: 10%;" >
            <i class="fas fa-angle-right rotate" v-if="typeof dato == 'object'" @click="toggle(key)"></i>
            <span v-if="key" >
                <span class="text-muted" v-if="(typeof key) == 'string'"> 
                    {{key.split('_').map( ele => ele.toUpperCase() ).join(" ")}}: 
                </span>
            </span>
            <span :id="key">  
                <div v-if="Array.isArray(dato)" style="margin-left: 10%;">
                    <b-table :small="true" responsive  striped hover :items="dato" ></b-table>
                    <hr>
                </div>
                <span v-else-if="(typeof dato) == 'string'">
                    {{dato}}
                </span>
                <span v-else-if="(typeof dato) == 'object'">
                    <ul class="nested" >
                        <tree-component  class="item" :item="dato"></tree-component>
                    </ul>
                </span>
            </span>
        </div>
    </div>
</template>

<script>
import Vue from 'vue';
export default {
    props:['item'],
    mounted(){
        if(this.item.datos_catastrales){
            this.item.datos_catastrales.map( dato => {
                dato.valor_catastral = Vue.filter('toCurrency')(dato.valor_catastral);
                return dato;
            });
        }
        
    },
    methods: {
        toggle: function (key) {
            $("#" + key).toggle(200)
        },
    }
  }
</script>
