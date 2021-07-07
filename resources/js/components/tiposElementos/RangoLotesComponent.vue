<template>
    <div>
        <div v-if="loteError || loteFinalError">
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <span v-if="loteError">hijoles el lote  tiene que ser de 3 digitos</span>
                <span v-if="loteFinalError">hijoles el lote final tiene que ser mayor al inicial paiiiii</span>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
        <table v-if="cantidadManzanas > 0" class="table  table-bordered">
            <thead>
                <tr >
                    <th></th>
                    <th class="text-center">Lote inicial</th>
                    <th class="text-center">Lote Final</th>
                    <th class="text-center">Lote Unico</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(manzana, index) in  cantidadManzanas" :key="index">
                    <td>
                        Manzana #{{(index ).toString().padStart(3,"0") }}
                    </td>
                    <td>
                            <input type="number" class="form-control" :id="'inicial'+index" placeholder="000" @change="addZeros('inicial'+index, index)" >
                    </td>
                    <td>
                            <input type="number" class="form-control" :id="'final'+index" placeholder="000"  @change="addZeros('final'+index, index)">
                    </td>
                    <td class="text-center">
                        <input class="text-center" type="checkbox" name="" :id="'checkbox'+index" @change="loteUnico('checkbox'+index , index)">
                    </td>
                </tr>
            </tbody>

      </table>

      <div class="pt-10 pr-4 pb-5" style="float: right">
          <button type="button" class="btn btn-success">Buscar</button>
      </div>

    </div>
</template>

<script>
export default {
    props:[ 'cantidadManzanas' ],
    data(){
        return{
            loteError : null,
            loteFinalError: null,
        }
    },
    methods:{
        addZeros(id, index){
            if(id.includes('final') )
                (document.getElementById('final'+index).value - document.getElementById('inicial'+index).value) > 1 ? '' :  this.loteFinalError = true;
            let  lote = document.getElementById(id).value;
            if (lote.length < 3)
                return document.getElementById(id).value = lote.toString().padStart(3 , "0");
            else
                this.loteError = true;
            
        },
        loteUnico( checkboxId , index ){
            document.getElementById(checkboxId).checked ?
            document.getElementById('final'+index).value = document.getElementById('inicial'+index).value : '';

        }
    }
}
</script>