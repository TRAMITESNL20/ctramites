import Vue from 'vue';
class AvisoEnajenacionService {
  
  constructor() {

  }

  async gettInfoCatastro( expediente ){
  	let url = process.env.TESORERIA_HOSTNAME +'/insumos-catastro-consulta/' + expediente;
    try {
      let response = await axios.get(url);
      return response
    } catch (error) {
      console.log(error);
    }
  }
}
export default new AvisoEnajenacionService();