<template>
    <div>
        <div v-if="campos && campos.length > 0" class="row">
            <div v-for="(campo, i) in campos"  :key="i" class="col-lg-6">
                <div v-if="campo.tipo === 'input'">
                    <strong>  {{ campo.nombre }} </strong> :  {{campo.valor}}
                </div>
                <div v-if="campo.tipo === 'select'">
                    <strong>  {{ campo.nombre }} </strong>:  {{campo.valor.nombre}}
                </div>
                <div v-if="campo.tipo === 'multiple'">
                    <strong>  {{ campo.nombre }} </strong>:  {{campo.valor.map( item => item.nombre ).join() }}
                </div>
            </div>
        </div>
    </div>
</template>
<script>

    import Vue from 'vue'

    export default {
        props: ['campos'],

    }
</script>
