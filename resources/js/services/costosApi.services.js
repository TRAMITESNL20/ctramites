import Vue from 'vue';
class CostosApi {
  
  constructor() {

  }
 
  async getCostoISRNormal( params, headers ) {
  	let url = process.env.APP_URL +'/getcostoImpuesto';
  	let data = {
  		id_seguimiento : params.id_seguimiento,
      tipoPersona : params.tipoPersona,
      multa_correccion_fiscal:  Vue.filter('toNumber')(params.multa_correccion_fiscal +""),
      monto_operacion:  Vue.filter('toNumber')(params.monto_operacion +""),
      pago_provisional_lisr: Vue.filter('toNumber')(params.pago_provisional_lisr +""),
      ganancia_obtenida: Vue.filter('toNumber')(params.ganancia_obtenida +""),
      fecha_escritura: params.fecha_escritura
  	}
    try {
        let response = await axios.post(url, data, {
          headers
        });
        return response
    } catch (error) {
        console.log(error);
    }
  }

  async getCostoISRComplementaria( params, headers){
    let url = process.env.APP_URL +'/getComplementaria';
    
    let data = {
      fecha_escritura: params.fecha_escritura,
      ganancia_obtenida: Vue.filter('toNumber')(params.ganancia_obtenida +""),
      monto_operacion: Vue.filter('toNumber')(params.monto_operacion +""),
      multa_correccion_fiscal: Vue.filter('toNumber')(params.multa_correccion_fiscal +""),
      pago_provisional_lisr: Vue.filter('toNumber')(params.pago_provisional_lisr +""),
      folio_anterior:  params.folio_anterior
    }
    try {
        let response = await axios.post(url, data, {
          headers
        });
        return response
    } catch (error) {
        console.log(error);
    }
  }

  async getCosto(params, headers){
  	let url = process.env.APP_URL +'/getcostoTramite';
    let data = params;
    try {
      let response = await axios.post(url, data, {
        headers
      });
      return response
    } catch (error) {
        console.log(error);
    }
  }

  getISRRequest(params, headers){
    let data = {
      fecha_escritura: params.fecha_escritura,
      ganancia_obtenida: Vue.filter('toNumber')(params.ganancia_obtenida +""),
      monto_operacion: Vue.filter('toNumber')(params.monto_operacion +""),
      multa_correccion_fiscal: Vue.filter('toNumber')(params.multa_correccion_fiscal +""),
      pago_provisional_lisr: Vue.filter('toNumber')(params.pago_provisional_lisr +""),
    }
    let url = process.env.APP_URL +'/getcostoImpuesto';
    return axios.post(url, data, {headers});
  }
}
export default new CostosApi();