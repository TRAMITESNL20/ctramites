
export default class AdminCamposCostos {

	constructor(infoFull) {
        this.info = JSON.parse(infoFull.info);
        this.camposParaCostos = ["Lote", "Hoja", "Subsidio", "Valor catastral", "Valor de operacion", "Cambio de divisas"];
        let nuevaInfo = this.info;
        if(infoFull.status == 8){
            nuevaInfo = this.desabilidarCampos( this.info );
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
}