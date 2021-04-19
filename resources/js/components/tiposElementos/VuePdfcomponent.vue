<template>
    <div >
      <div v-for="(url, index) in urlSourceListo">
        <iframe :src="url" style="width:100%; height:500px;" frameborder="0"></iframe>
      </div>
        
      <div v-for=" (url, index) in urlSourcePendiente">
        <pdf :src="url"></pdf>
      </div>

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