import Vue from 'vue';
export default class TramiteCar5ISRCtrl {

	constructor() {
	  	this.json = {};
	}
 
  	create( tramite, solicitud ) {

  		this.json.nombre = tramite.tramite;
	  	this.json.id_seguimiento = solicitud.grupo_clave ? Number( solicitud.grupo_clave ) + "" : "";
	  	this.json.id_tipo_servicio = tramite.tramite_id;//397;//
	  	this.json.idSolicitante = solicitud.id; 
	  	this.json.id_tramite = solicitud.id;
	  	this.json.calveTemp = solicitud.grupo_clave ? solicitud.grupo_clave : solicitud.clave;
	  	this.json.claveIndividual = solicitud.clave;
	  	this.json.auxiliar_1 = this.getAuxiliar1(solicitud);
	  	this.json.auxiliar_2 = "";
	    this.json.auxiliar_3 = "";
	    this.json.importe_tramite = this.getImporte(solicitud);
	    this.json.datos_solicitante = this.obtenerDatosSolicitante(solicitud);
	    this.json.datos_factura = this.json.datos_solicitante;
        this.json.isComplemento = solicitud.info.complementoDe;
        this.json.isAgrupable = this.getIsAgrupable(solicitud, tramite);
	    this.json.detalle = this.getDetalle(solicitud);

	  	return this.json;
  	}

    getIsAgrupable( solicitud, tramite ){
        if(solicitud.info.campos.Distrito == undefined){
            return !solicitud.info.complementoDe && process.env.TRAMITE_5_ISR != tramite.tramite_id;
        }else{
            return !solicitud.info.complementoDe && process.env.TRAMITE_5_ISR != tramite.tramite_id && solicitud.info.campos.Distrito.clave == 1 ;
        }
    }

  	getAuxiliar1(solicitud){

  		if(solicitud.info.hasOwnProperty('enajenante') && (solicitud.info.hasOwnProperty('solicitante') ) ){
			let solicitanteInfo = solicitud.info.solicitante;
            let auxiliar_1 = '';
            if( solicitanteInfo ){
			     auxiliar_1  = (solicitanteInfo.nombreSolicitante || '') + " " + (solicitanteInfo.apPat || '' )+ " " + (solicitanteInfo.apMat || '');
            }
			let usuario = window.user;
			if(usuario && usuario.notary){
				return auxiliar_1 = auxiliar_1 + " - Notaria " + usuario.notary.notary_number;
			}
	  	} /*else if( solicitud.info.hasOwnProperty('complementoDe') ){
            return "COMPLEMENTARIO: " + solicitud.info.complementoDe ;
        } */else {
	  		return "";
	  	}
  	}

  	obtenerDatosSolicitante(solicitud){
        if(solicitud.info.hasOwnProperty('enajenante')){
            return this.extraerDatosPersonalesEnajentante(solicitud.info.enajenante);
        } else if(solicitud.info.hasOwnProperty('solicitante')){
            return this.extraerDatosPersonalesSolicitante(solicitud.info.solicitante);
        } else {
            return {};
        }
    }


    extraerDatosPersonalesEnajentante(enajenante){
        let datos_solicitante = {
            "nombre": enajenante.tipoPersona == "pm" ? "" : enajenante.datosPersonales.nombre || "",
            "apellido_paterno": enajenante.tipoPersona == "pm" ? "" : enajenante.datosPersonales.apPat || "",
            "apellido_materno": enajenante.tipoPersona == "pm" ? "" : enajenante.datosPersonales.apMat || "",
            "razon_social": enajenante.tipoPersona == "pm" ? enajenante.datosPersonales.razonSocial : "",
            "rfc": enajenante.datosPersonales.rfc,
            "curp": enajenante.tipoPersona == "pm" ? "" : enajenante.datosPersonales.curp  || "",
            "email": "-",
            "calle": "-",
            "colonia": "-",
            "numexterior": "-",
            "numinterior": "-",
            "municipio":  "-",
            "codigopostal":"-",
        }
        return datos_solicitante;
    }

    extraerDatosPersonalesSolicitante(solicitante){
        let datos_solicitante = {
	        "nombre": solicitante.tipoPersona == "pm" ? "" : solicitante.nombreSolicitante || "",
	        "apellido_paterno": solicitante.tipoPersona == "pm" ? "" : solicitante.apPat || "",
	        "apellido_materno": solicitante.tipoPersona == "pm" ? "" : solicitante.apMat || "",
	        "razon_social": solicitante.tipoPersona == "pm" ? solicitante.razonSocial : "",
	        "rfc": solicitante.rfc,
	        "curp": solicitante.curp || "",
	        "email": solicitante.email|| "",
	        "calle": solicitante.calle|| "",
	        "colonia":solicitante.colonia|| "",
	        "numexterior": solicitante.numexterior|| "",
	        "numinterior": solicitante.numinterior|| "",
	        "municipio": solicitante.municipio|| "",
	        "codigopostal": solicitante.codigopostal|| "",
        }
        return datos_solicitante;
    }

    getImporte(solicitud){
    	let info = (typeof solicitud.info) == 'string' ? JSON.parse(solicitud.info) : solicitud.info;
        if( info.tipoTramite == 'complementaria' && info.detalle && info.detalle.Complementaria){
            return info.detalle.Complementaria['Cantidad a cargo'] ;
        } else {
            return info.detalle && info.detalle.Salidas ?  info.detalle.Salidas['Importe total'] : info.costo_final ;
        }
    }


    getDetalle(solicitud){
    	let precision = Vue.prototype.$const.PRECISION;
    	let info = (typeof solicitud.info) == 'string' ? JSON.parse(solicitud.info) : solicitud.info;
    	let detalle = [];
    	if(info && info.detalle && info.detalle.Salidas && info.tipoTramite != 'complementaria'){              
    		detalle[0] = {
                concepto : 'Impuesto correspondiente a la entidad federativa',
                partida: 56754,
                importe_concepto: Number(Number(info.detalle.Salidas['Impuesto correspondiente a la entidad federativa']).toFixed(precision))      
            }
            detalle[1] = {
                concepto : 'Parte actualizada del impuesto',
                partida: 57910,
                importe_concepto:  Number(Number(info.detalle.Salidas['Parte actualizada del impuesto']).toFixed(precision))  
            }

            detalle[2] = {
                concepto : 'Recargos',
                partida: 57612,
                importe_concepto: Number(Number(info.detalle.Salidas['Recargos']).toFixed(precision))  
            }
            detalle[3] = {
                concepto : 'Multa corrección fiscal',
                partida: 57505,
                importe_concepto: Number(Number(info.detalle.Salidas['Multa corrección fiscal']).toFixed(precision))     
            }
        } else {
            detalle[0] = { 
                concepto : info.partidas ? info.partidas[0].descripcion : this.json.nombre,
                partida: info.partidas ? info.partidas[0].id_partida : null,
                importe_concepto:this.json.importe_tramite         
            }

            if( solicitud.info.hasOwnProperty('complementos')){
                detalle[0].concepto = detalle[0].concepto + " COMPLEMENTARIO:  " + ( solicitud.info.complementos );
            } else if (  solicitud.info.hasOwnProperty('complementoDe')  ){
                detalle[0].concepto = detalle[0].concepto + " COMPLEMENTARIO:  " + ( solicitud.info.complementoDe );
            }

            let descuentosAplicados = [];
            if(info.detalle && info.detalle.descuentos && Array.isArray(info.detalle.descuentos )  && info.detalle.descuentos.length > 0  ){
                let losdescuentos = info.detalle.descuentos.filter( descuento => descuento.concepto_descuento != "No aplica" );   
                losdescuentos = info.detalle.descuentos.filter( descuento => descuento.concepto_descuento != "El numero de oficio no coincide con el trámite" );    
                if( losdescuentos && losdescuentos.length > 0 ){
                  	info.detalle.descuentos.forEach( descuento => {
	                    let descuentoAplicado =  {
	                        concepto_descuento: descuento.concepto_descuento,
	                        importe_descuento: descuento.importe_subsidio,
	                        partida_descuento: descuento.partida_descuento
	                    }
	                    descuentosAplicados.push( descuentoAplicado )
	                });
                	detalle[0].descuentos = descuentosAplicados;               
                }
            }
        }
        return detalle;

    }


}
//export default new TramiteCar5ISRCtrl();