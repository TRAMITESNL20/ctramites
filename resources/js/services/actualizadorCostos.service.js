import Vue from 'vue';

import costosApi from './costosApi.services.js';
class ActualizadorCostos {
  

  async getRequestCosto( solicitud , indexTramite, idTramite) {
    let isISR = solicitud.info.detalle.Salidas;
    let tipoTramite = solicitud.info.tipoTramite;

    if(isISR && tipoTramite == 'normal'){

      let datosParaDeterminarImpuesto = solicitud.info.enajenante.datosParaDeterminarImpuesto;

      let params = {
        id_seguimiento: solicitud.clave,
        tipoPersona:solicitud.info.tipoPersona,
        multa_correccion_fiscal: datosParaDeterminarImpuesto.multaCorreccion ,
        monto_operacion: datosParaDeterminarImpuesto.montoOperacion,
        pago_provisional_lisr: datosParaDeterminarImpuesto.pagoProvisional,
        ganancia_obtenida: datosParaDeterminarImpuesto.gananciaObtenida ,
        fecha_escritura: solicitud.info.campos['Fecha de escritura o minuta']
      }

      let headers = {
        idSolicitud: solicitud.id,
        indexTramite
      }
      return costosApi.getCostoISRNormal(params, headers);
    } else if(isISR && tipoTramite == 'complementaria'){
      let params = solicitud.info.camposComplementaria;
      let headers = {
        idSolicitud: solicitud.id,
        indexTramite
      }
      return costosApi.getCostoISRComplementaria(params, headers);
    } else if(isISR && tipoTramite == 'declaracionEn0'){
      return false;
    } else {
      let tipo_costo_obj = solicitud.info.tipo_costo_obj;
      let params = {
          id_seguimiento: solicitud.clave,
          tipoPersona:solicitud.info.tipoPersona,
          tramite_id:idTramite
      }
      if ( tipo_costo_obj.tipo_costo == '1' && (tipo_costo_obj.tipoCostoRadio == 'hoja'|| tipo_costo_obj.tipoCostoRadio == 'lote') ){
        params.tipo_costo = tipo_costo_obj.tipo_costo;
        params.tipoCostoRadio = tipo_costo_obj.tipoCostoRadio;
        params.hojaInput = tipo_costo_obj.hojaInput;
      } else {
        let campoLote           = this.getCampoByName(Vue.prototype.$const.NOMBRES_CAMPOS.CAMPO_LOTE, solicitud.info);
        let campoHoja           = this.getCampoByName(Vue.prototype.$const.NOMBRES_CAMPOS.CAMPO_HOJA, solicitud.info);
        let campoSubsidio       = this.getCampoByName(Vue.prototype.$const.NOMBRES_CAMPOS.CAMPO_SUBSIDIO, solicitud.info);
        let campoCatastral      = this.getCampoByName(Vue.prototype.$const.NOMBRES_CAMPOS.CAMPO_VALOR_CATASTRAL, solicitud.info);
        let campoValorOperacion = this.getCampoByName(Vue.prototype.$const.NOMBRES_CAMPOS.CAMPO_VALOR_OPERACION, solicitud.info);  

        if( campoCatastral ){
          params.valor_catastral = Vue.filter('toNumber')(campoCatastral.valor +"")
        }
        if(campoSubsidio){                            
          if( campoSubsidio.tipo == 'select'  ){ 
            params.subsidio = campoSubsidio.valor.clave;
          } else {
            params.subsidio = campoSubsidio.valor;  
          } 
        }
        if(campoValorOperacion ){
          params.valor_operacion = Vue.filter('toNumber')(campoValorOperacion.valor +"")
        }

        if( campoHoja ){
          params.hoja = campoHoja.valor; 
        }
        
        if( campoLote ){
            params.lote = campoLote.valor
        }
      }
      let campoDivisas              = this.getCampoByName(Vue.prototype.$const.NOMBRES_CAMPOS.CAMPO_DIVISAS, solicitud.info);
      if( campoDivisas ){
        params.divisa = campoDivisas.valor.clave;
      } 
      let headers = {
        idSolicitud: solicitud.id,
        indexTramite
      } 
      return costosApi.getCosto(params, headers);
    }
  }

  getCampoByName( nameCampo, solicitud ){
    return solicitud.camposConfigurados.find( campo => campo.nombre.toLowerCase()  === nameCampo.toLowerCase() );
  }

}
export default new ActualizadorCostos();