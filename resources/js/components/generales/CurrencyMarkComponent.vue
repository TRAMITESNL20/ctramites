<template>
    <s>
        <small  v-if="detalle.Salidas" class="text-muted">
            {{ detalle.Salidas['Importe total'] | toCurrency }}
        </small >
        <small  v-else-if="detalle.costo_final" class="text-muted">
            {{detalle.costo_final | toCurrency }}
        </small >
    </s>
</template>

<script>

    import Vue from 'vue';

    export default {
        props: ['detalle']
    }
</script>