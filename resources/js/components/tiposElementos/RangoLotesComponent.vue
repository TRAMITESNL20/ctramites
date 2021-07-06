<template>
  <div>
      <table v-if="cantidadManzanas > 0" class="table  table-bordered">
          <thead>
              <tr>
                  <td></td>
                  <td>Lote inicial</td>
                  <td>Lote Final</td>
                  <td>Lote Unico</td>
              </tr>
          </thead>
          <tbody>
              <tr v-for="(manzana, index) in  cantidadManzanas" :key="index">
                  <td>
                      Manzana #{{index + 1}}
                  </td>
                  <td>
                        <input type="text" class="form-control" placeholder="000" >
                  </td>
                  <td>
                        <input type="text" class="form-control" placeholder="000" >
                  </td>
                  <td class="text-center">
                      <input class="text-center" type="checkbox" name="" id="">
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