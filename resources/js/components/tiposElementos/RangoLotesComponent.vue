<template>
  <div>
      <table class="table  table-bordered">
          <tbody>
              <tr v-for="(manzana, index) in  cantidadManzanas" :key="index">
                  <td>
                      Manzana #{{index + 1}}
                  </td>
                  <td>
                      Lote Inicial
                  </td>
                  <td>
                        <input type="text" class="form-control" placeholder="000" >
                  </td>
                  <td>
                       Lote Final 
                  </td>
                  <td>
                        <input type="text" class="form-control" placeholder="000" >
                  </td>
                  <td>
                      <input type="checkbox" name="" id="">
                  </td>
              </tr>
          </tbody>

      </table>

  </div>
</template>

<script>
export default {
    props:[ 'cantidadManzanas' ],
    data(){
        return{

        }
    }
}
</script>