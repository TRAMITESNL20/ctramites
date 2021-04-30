<template>
    <div >
      vue-pdf
      <div v-for="(url, index) in urlSourceListo">
        <iframe :src="url" style="width:100%; height:500px;" frameborder="0"></iframe>
      </div>
        
      <div v-for=" (urlx, index) in urlSourcePendiente">
        <pdf :src="urlx"></pdf>
      </div>

      <code>listo: {{urlSourceListo}}</code>
      <code>pendiente: {{urlSourcePendiente}}</code>
    </div>
</template>

<script>
import pdf from 'vue-pdf'

export default {
  props:['urlSourceListo', 'urlSourcePendiente'],
  components: {
    pdf
  }
}
</script>

<style>

</style>