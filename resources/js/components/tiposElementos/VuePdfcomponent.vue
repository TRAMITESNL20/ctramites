<template>
    <div>
      
    <div v-for=" (url, index) in urlSource">
      <pdf :src="url"></pdf>
    </div>

    </div>
</template>

<script>
import pdf from 'vue-pdf'

export default {
  props:['urlSource'],
  components: {
    pdf
  }
}
</script>

<style>

</style>