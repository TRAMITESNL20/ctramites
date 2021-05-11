import Vue from 'vue';
export default class AdminCamposCostos {

	constructor(infoFull) {
        this.info = JSON.parse(infoFull.info);
        this.camposParaCostos = [
            Vue.prototype.$const.NOMBRES_CAMPOS.CAMPO_LOTE, 
            Vue.prototype.$const.NOMBRES_CAMPOS.CAMPO_HOJA, 
            Vue.prototype.$const.NOMBRES_CAMPOS.CAMPO_SUBSIDIO, 
            Vue.prototype.$const.NOMBRES_CAMPOS.CAMPO_VALOR_CATASTRAL, 
            Vue.prototype.$const.NOMBRES_CAMPOS.CAMPO_VALOR_OPERACION, 
            Vue.prototype.$const.NOMBRES_CAMPOS.CAMPO_DIVISAS
        ];
        let nuevaInfo = this.info;
        if(infoFull.status == Vue.prototype.$const.STATUS_FALTA_PAGO ){
            nuevaInfo = this.desabilidarCampos( this.info );
        } else  if(infoFull.status == Vue.prototype.$const.STATUS_ERROR_MUNICIPIO ){
            nuevaInfo = this.habilitarSoloMunicipio( this.info );
        }
        infoFull.info = JSON.stringify( nuevaInfo );
        return infoFull;
	}

    desabilidarCampos( info ){
    	info.camposConfigurados.map( (campo, index) => {
            let esParaCosto = this.camposParaCostos.includes( campo.nombre )
            campo.disabled = !esParaCosto;
            return campo;
        });
        return info;
    }

    habilitarSoloMunicipio( info ){
        info.camposConfigurados.map( (campo, index) => {
            let habilitar = campo.nombre.indexOf('Municipios') >= 0 ;
            campo.disabled = !habilitar;
            return campo;
        });
        return info;
    }
}