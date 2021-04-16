<template>
    <div >
      <div v-for="(url, index) in urlDownload">
        <iframe src="http://tramites.nl.gob.mx/formato-declaracion/687" style="width:100%; height:500px;" frameborder="0"></iframe>
      </div>
        
      <div v-for=" (url, index) in urlSource">
        <pdf :src="url"></pdf>
      </div>

    </div>
</template>

<script>
import pdf from 'vue-pdf'

export default {
  props:['urlSource', 'urlDownload'],
  components: {
    pdf
  }
}
</script>

<style>

</style>