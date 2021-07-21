<template>
	<div>
		<br>
		<button type="button" class="btn btn-success font-weight-bolder text-uppercase px-9 py-4 mobil-font" v-on:click="buscar()" :disabled="buscando"> {{ labelBtn }}
	        <div id="spinner-buscarActa" class="spinner-border spinner-border-sm float-right" role="status" v-if="buscando" >
	            <span class="sr-only">Buscando...</span>
	        </div>
	    </button>
	    <br><br><br>
	    <div v-if="actaEncontradaNac" class="col-md-6 col-sm-6 col-xs-6 col-md-12 col-sm-12 col-xs-12" >
	    	<br>
	    	<h3> Informacion del Acta </h3>
	    	<table id="tblActaNac" class="table">
	    		<tr><td> Oficialia  </td><td> Libro </td><td> Acta No. </td></tr>
	    		<tr><td> {{ acta.oficialia }} </td><td> {{ acta.libro }} </td> <td> {{ acta.actano }} </td></tr>
	    	</table>
	    	<h3> Información del Ciudadano </h3>
	    	<table id="tblActaNac2" class="table">
	    		<tr><td> Nombre (s) </td><td>&nbsp;</td><td> Apellido Paterno </td><td>&nbsp;</td><td> Apellido Materno </td><td>&nbsp;</td></tr>
	    		<tr><td> {{ acta.nombre }}  </td><td>&nbsp;</td><td> {{ acta.apaterno }} </td><td>&nbsp;</td><td> {{ acta.amaterno }} </td><td>&nbsp;</td></tr>
	    		<tr><td> Fecha de Nacimiento </td><td>&nbsp;</td><td> Lugar de Nacimiento </td><td>&nbsp;</td><td> CURP </td><td> Sexo </td></tr>
	    		<tr><td> {{ acta.fechanac }} </td><td>&nbsp;</td><td> {{ acta.lugarnac }} </td><td>&nbsp;</td><td> {{ acta.curp }} </td><td> {{ acta.genero }} </td></tr>
	    		<tr><td> Fecha de Registro </td><td>&nbsp;</td><td>Estado de Registro</td><td>&nbsp;</td><td>Municipio de Registro</td><td>&nbsp;</td></tr>
	    		<tr><td> {{ acta.fechareg }} </td><td>&nbsp;</td><td> {{ acta.estadoreg }} </td><td>&nbsp;</td><td> {{ acta.munreg }} </td></tr>
	    		<tr><td> &nbsp; </td><td> &nbsp; </td><td> &nbsp; </td><td> &nbsp; </td><td> &nbsp; </td><td> &nbsp; </td></tr>	    		
	    	</table>
	    </div>
	    <div v-if="actaEncontradaDef" class="col-md-6 col-sm-6 col-xs-6 col-md-12 col-sm-12 col-xs-12" >
	    	<h3> Informacion del Acta </h3>
	    	<table id="tblActaDef" class="table">
	    		<tr><td> OFICIALIA </td><td> LIBRO </td><td> ACTA </td><td> TOMO </td></tr>
	    		<tr><td> {{ actadef.oficialia }} </td><td> {{ actadef.libro }} </td><td> {{ actadef.actano }} </td><td> {{ actadef.tomo }} </td></tr>	    		
	    	</table>
	    	<h3>Información del Finado</h3>
	    	<table id="tblActaDef2" class="table">
	    		<tr><td> Nombre (s): </td><td> {{ actadef.nombre }} </td><td> Apellido Paterno: </td><td> {{ actadef.apaterno }} </td><td> Apellido Materno: </td><td> {{ actadef.amaterno }} </td></tr>
	    		<tr><td>Sexo:</td><td> {{ actadef.genero }} </td><td>Estado Civil: </td><td> {{ actadef.civil }} </td><td>Pais de Nacimiento: </td><td> {{ actadef.paisnc }} </td></tr>
	    		<tr><td>Localidad de Nacimiento:</td><td> {{ actadef.entnac }} </td><td>Municipio de Nacimiento: </td><td> {{ actadef.munnac }} </td><td>Entidad de Nacimiento: </td><td> {{ actadef.entnac }} </td></tr>
	    		<tr><td>Nombre del Conyuge:</td><td> {{ actadef.nombrecony }} </td><td>Nombre del Padre: </td><td> {{ actadef.npadre + " " + actadef.appadre }} </td><td>Nombre de la Madre: </td><td> {{ actadef.nmadre + " " + actadef.apmadre }} </td></tr>
	    	</table>
	    	<h3>Datos del Fallecimiento</h3>
	    	<table id="tblActaDef3" class="table">
	    		<tr><td>TESTIGO 1: </td><td> {{ actadef.testigo1 }} </td><td>TESTIGO 2: </td><td> {{ actadef.testigo2 }} </td></tr>
	    		<tr><td>NOMBRE MEDICO: </td><td> {{ actadef.nombremed }} </td><td>DECLARADO: </td><td> {{ actadef.nombredecl }} </td></tr>
	    		<tr><td>Fecha de la Defunción: </td><td> {{ actadef.fechareg }} </td></tr>
	    		<tr><td>Causa de la Defunción: </td><td> {{ actadef.causa }} </td></tr>
	    	</table>
	    </div>
	    <div v-if="actaEncontradaMat" class="col-md-6 col-sm-6 col-xs-6 col-md-12 col-sm-12 col-xs-12" >
	    	<br>
	    	<h3> Detalle del Acta </h3>
	    	<table id="tblActaMat" class="table">
	    		<tr><td> Foja: </td><td>{{ actamat.foja }}</td><td> Libro: </td><td> {{ actamat.libro }} </td><td> Oficialia: </td><td> {{ actamat.oficialia }} </td></tr>
	    		<tr><td> Nombre Oficialia: </td><td> {{ actamat.nomoficialia }} </td><td> Oficialia No: </td><td> {{ actamat.oficialia }} </td><td> Regimen: </td><td> {{ actamat.nomregimen }} </td></tr>
	    		<tr><td> Fecha de Registro: </td><td> {{ actamat.fechareg }} </td><td> Entidad: </td><td> {{ actamat.nomentreg }} </td><td> Municipio: </td><td> {{ actamat.nommunreg }} </td></tr>
	    	</table>

	    	<h3> Detalle de los Contrayentes </h3>
	    	<table id="tblActaMat2" class="table">
	    		<tr><td colspan="6"> Primer Contrayente</td></tr>
	    		<tr><td> Nombre(s): </td><td>{{ actamat.nomesposo }}</td><td> Apellido Paterno: </td><td>{{ actamat.appesposo }}</td><td> Apellido Materno </td><td>{{ actamat.apmesposo }}</td></tr>
	    		<tr><td> Pais:</td><td> {{ actamat.paisesposo }} </td><td> Entidad: </td><td> {{ actamat.entesposo }} </td><td> Municipio: </td><td> {{ actamat.munesposo }} </td></tr>
	    		<tr><td> Edad: </td><td>{{ actamat.edadesposo }}</td><td> Nacionalidad: </td><td> {{ actamat.nacesposo }} </td><td> CURP: </td><td> {{ actamat.curpesposo }} </td></tr>
	    		<tr><td> Sexo: </td><td> {{ actamat.sexoesposo }} </td></tr>
	    		<tr><td> Nombre Padre: </td><td>{{ actamat.nompesposo }}</td><td>Nacionalidad: </td><td> {{ actamat.nacpesposo }} </td></tr>
	    		<tr><td> Nombre Madre: </td><td> {{ actamat.nommesposo }} </td><td>Nacionalidad: </td><td> {{ actamat.nacmesposo }} </td></tr>
	    		<tr><td colspan="6">&nbsp;</td></tr>
	    		<tr><td colspan="6"> Segundo Contrayente</td></tr>
	    		<tr><td> Nombre(s): </td><td>{{ actamat.nomesposa }}</td><td> Apellido Paterno: </td><td>{{ actamat.appesposa }}</td><td> Apellido Materno </td><td>{{ actamat.apmesposa }}</td></tr>
	    		<tr><td> Pais:</td><td> {{ actamat.paisesposa }} </td><td> Entidad: </td><td> {{ actamat.entesposa }} </td><td> Municipio: </td><td> {{ actamat.munesposa }} </td></tr>
	    		<tr><td> Edad: </td><td>{{ actamat.edadesposa }}</td><td> Nacionalidad: </td><td> {{ actamat.nacesposa }} </td><td> CURP: </td><td> {{ actamat.curpesposa }} </td></tr>
	    		<tr><td> Sexo: </td><td> {{ actamat.sexoesposa }} </td></tr>
	    		<tr><td> Nombre Padre: </td><td>{{ actamat.nompesposa }}</td><td>Nacionalidad: </td><td> {{ actamat.nacpesposa }} </td></tr>
	    		<tr><td> Nombre Madre: </td><td> {{ actamat.nommesposa }} </td><td> Nacionalidad: </td><td> {{ actamat.nacmesposa }} </td></tr>
	    	</table>
	    	<h3> Detalle de los Testigos </h3>
	    	<table id="tblActaMat3" class="table">
	    		<tr><td colspan="6">Primer Contrayente</td></tr>
	    		<tr><td>Nombre Testigo 1:</td><td> {{ actamat.t1esposo }} </td><td>Edad: </td><td> {{ actamat.edt1esposo }} </td><td> Nacionalidad: </td><td> {{ actamat.nact1esposo }} </td></tr>
	    		<tr><td>Nombre Testigo 2:</td><td> {{ actamat.t2esposo }} </td><td>Edad: </td><td> {{ actamat.edt2esposo }} </td><td> Nacionalidad: </td><td> {{ actamat.nact2esposo }} </td></tr>
	    		<tr><td colspan="6">Segundo Contrayente</td></tr>
	    		<tr><td>Nombre Testigo 1:</td><td> {{ actamat.t1esposa }} </td><td>Edad: </td><td> {{ actamat.edt1esposa }} </td><td> Nacionalidad: </td><td> {{ actamat.nact1esposa }} </td></tr>
	    		<tr><td>Nombre Testigo 2:</td><td> {{ actamat.t2esposa }} </td><td>Edad: </td><td> {{ actamat.edt2esposa }} </td><td> Nacionalidad: </td><td> {{ actamat.nact2esposa }} </td></tr>
	    	</table>
	    </div>
    </div>	
</template>
<script>
	// import BtnGuardarTramiteParent from './BtnGuardarTramiteParent'
	export default {
		props: ['labelBtn'],
      	// extends: BtnGuardarTramiteParent, //heredamos del componente BtnGuardarTramiteParent!
      	mounted(){
      	},
      	data() {
      		return {
      			actaEncontradaNac: false,
      			acta:[],
      			actaEncontradaDef: false,
      			actadef:[],
      			actaEncontradaMat: false,
      			actamat:[],
      			endpoint: '/deployed-main-ws-registro-civil',
      			buscando: false,
      			TRAMITE_ACTANAC:process.env.TRAMITE_ACTANAC,
      			TRAMITE_ACTAMAT:process.env.TRAMITE_ACTAMAT,
      			TRAMITE_ACTADEF:process.env.TRAMITE_ACTADEF
      		}
      	},
      	methods: {
      		async buscar() {
      			/* Obtencion de datos del Storage */
      			let datosTab = this.getStorage('datosFormulario',1)
      			let datosTra = this.getStorage('tramite');
      			let datos = JSON.parse(JSON.stringify(datosTab));
      			let tram = JSON.parse(JSON.stringify(datosTra));
      			let idtr = tram.id_tramite;
      			
      			/* Asignacion de valores del formulario */
      			let nombre = datos.campos.find(campo => campo.nombre == 'Nombre(s)');
      			let apaterno = datos.campos.find(campo => campo.nombre == 'Primer apellido');
      			let amaterno = datos.campos.find(campo => campo.nombre == 'Segundo apellido');
      			let fecha = datos.campos.find(campo => campo.nombre == 'Fecha de Nacimiento');
      			let curp = datos.campos.find(campo => campo.nombre == 'CURP');
      			let fechadef = datos.campos.find(campo => campo.nombre == 'Fecha de Defunción');
      			let fechamat = datos.campos.find(campo => campo.nombre == 'Fecha de Matrimonio');
      			let nomc1 = datos.campos.find(campo => campo.nombre == 'Nombre(s) Primer Contrayente');
      			let apc1 = datos.campos.find(campo => campo.nombre == 'Primer Apellido Primer Contrayente');
      			let amc1 = datos.campos.find(campo => campo.nombre == 'Segundo Apellido Primer Contrayente');
      			let nomc2 = datos.campos.find(campo => campo.nombre == 'Nombre(s) Segundo Contrayente');
      			let apc2 = datos.campos.find(campo => campo.nombre == 'Primer Apellido Segundo Contrayente');
      			let amc2 = datos.campos.find(campo => campo.nombre == 'Segundo Apellido Segundo Contrayente');
      			
      			this.buscando = true;
      			let url = process.env.TESORERIA_HOSTNAME + this.endpoint;
      			// let url = process.env.TESORERIA_HOSTNAME;
    			let params = '';
    			let paramsdef = [];
    			let paramsmat = []; 
	      		let response = '';
	      		let info = '';
      			try {
	      			
					if(tram.id_tramite == this.TRAMITE_ACTAMAT) {		/* ACTA DE MATRIMONIO */						
						paramsdef = {
							nc1:nomc1.valor,
							apc1:apc1.valor,
							amc1:amc1.valor,
							nc2:nomc2.valor,
							apc2:apc2.valor,
							amc2:amc2.valor,
							fechareg:fechamat.valor
						}
						try {
							response = await axios.get(url + '/wsrc-actamat',{params:paramsdef}, {headers:{"Content-type":"application/json"}});
							if(response.status === 200)
							{
								info = response.data[0];
								this.actamat.fechareg = info.fecha_registro;
								this.actamat.nomentreg = info.nombre_entidad_registro;
								this.actamat.nommunreg = info.nombre_municipio_registro;
								this.actamat.oficialia = info.oficialia;
								this.actamat.nomoficialia = info.nombre_oficialia;
								this.actamat.anioreg = info.anio_registro;
								this.actamat.libro = info.libro;
								this.actamat.acta = info.acta;
								this.actamat.foja = info.foja;
								this.actamat.nomregimen =  info.nombre_regimen;
								this.actamat.bis = info.bis;
								this.actamat.tomo = info.tomo;
								/// DATOS CONTRAYENTE 1 
								this.actamat.nomesposo = info.nombre_esposo;
								this.actamat.appesposo = info.apellido_paterno_esposo;
								this.actamat.apmesposo = info.apellido_materno_esposo;
								this.actamat.edadesposo = info.edad_esposo;
								this.actamat.curpesposo = info.curp_el;
								this.actamat.paisesposo = info.pais_esposo;
								this.actamat.entesposo = info.entidad_esposo;
								this.actamat.munesposo = info.municipio_esposo;
								this.actamat.nacesposo = info.nacionalidad_esposo;
								this.actamat.sexoesposo = info.sexo_esposo;
								/// DATOS CONTRAYENTE 2
								this.actamat.nomesposa = info.nombre_esposa;
								this.actamat.appesposa = info.apellido_paterno_esposa;
								this.actamat.apmesposa = info.apellido_materno_esposa;
								this.actamat.edadesposa = info.edad_esposa;
								this.actamat.curpesposa = info.curp_ella;
								this.actamat.paisesposa = info.pais_esposa;
								this.actamat.entesposa = info.entidad_esposa;
								this.actamat.munesposa = info.municipio_esposa;
								this.actamat.nacesposa = info.nacionalidad_esposa;
								this.actamat.sexoesposa = info.sexo_esposa;
								// /// DATOS FAMILIARES Y TESTIGOS
								this.actamat.nompesposo = info.nombre_padre_esposo;
								this.actamat.nacpesposo = info.nacionalidad_padre_esposo;
								this.actamat.nommesposo = info.nombre_madre_esposo;
								this.actamat.nacmesposo = info.nacionalidad_madre_esposo;
								this.actamat.t1esposo = info.testigo1_esposo;
								this.actamat.edt1esposo = info.edad_testigo1_esposo;
								this.actamat.nact1esposo = info.nacionalidad_testigo1_esposo;
								this.actamat.t2esposo = info.testigo2_esposo;
								this.actamat.edt2esposo = info.edad_testigo2_esposo;
								this.actamat.nact2esposo = info.nacionalidad_testigo2_esposo;
								
								this.actamat.nompesposa = info.nombre_padre_esposa;
								this.actamat.nacpesposa = info.nacionalidad_padre_esposa;
								this.actamat.nommesposa = info.nombre_madre_esposa;
								this.actamat.nacmesposa = info.nacionalidad_madre_esposa;
								this.actamat.t1esposa = info.testigo1_esposa;
								this.actamat.edt1esposa = info.edad_testigo1_esposa;
								this.actamat.nact1esposa = info.nacionalidad_testigo1_esposa;
								this.actamat.t2esposa = info.testigo2_esposa;
								this.actamat.edt2esposa = info.edad_testigo2_esposa;
								this.actamat.nact2esposa = info.nacionalidad_testigo2_esposa;
								this.actaEncontradaMat = true;
							}
							else
								Command: toastr.warning("Aviso !!","No se encuentra acta con información proporcionada");
						}
						catch (error) {
							console.log(error);
						    Command: toastr.warning("Error !!","Ocurrio algo al realizar la solicitud de información");
						}
					}
					else if(tram.id_tramite == this.TRAMITE_ACTADEF) {	/* ACTA DE DEFUNCION */
						
						paramsdef = {
							ndef:nombre.valor,
							apdef:apaterno.valor,
							amdef:amaterno.valor,
							fedef:fechadef.valor,
							curp:curp.valor
						}
						try {
							response = await axios.get(url + '/wsrc-actadef',{params:paramsdef}, {headers:{"Content-type":"application/json"}});
							
							if(response.status === 200) {
								info = response.data[0];
								this.actadef.oficialia = info.OFICIALIA;
								this.actadef.libro = info.LIBRO;
								this.actadef.actano = info.ACTA;						
								this.actadef.tomo = info.TOMO;
								
								this.actadef.anioreg = info.ANIO_REGI;
								this.actadef.entreg = info.ENTIDAD_REGI;
								this.actadef.munreg = info.MUNICIPIO_REGI;
								
								this.actadef.nombre = info.NOMBRE;
								this.actadef.apaterno = info.APELLIDO_PATERNO;
								this.actadef.amaterno = info.APELLIDO_MATERNO;
								this.actadef.edadfinado = info.EDAD_FINADO;
								this.actadef.civil = info.ESTADO_CIVIL;
								this.actadef.nombrecony = info.NOMBRE_CONYUGE;
								this.actadef.curp = info.CURP;
								this.actadef.nac = info.NACIONALIDAD;
								this.actadef.fechareg = info.FECHA_REGI;
								this.actadef.horadef = info.HORA_DEFUNCION;
								this.actadef.causa = info.CAUSA_DEFUNCION_A;
								this.actadef.dom = info.DOMICILIO_FALLECIO;
								this.actadef.munfall = info.MUN_FALLECIO;
								this.actadef.tipodef = info.TIPO_DEFUNCION;
								this.actadef.destino = info.DESTINO_CADAVER;
								this.actadef.entfall = info.ENTIDAD_FALLECIO;
								
								this.actadef.entnac = info.ENTIDAD_NACI;
								this.actadef.munnac = info.MUNICIPIO_NACI;
								this.actadef.genero = info.SEXO_1;
								this.actadef.paisnc = info.PAIS_NACI;
								
								this.actadef.nombredecl = info.NOMBRE_DECLARA;
								this.actadef.nombremed = info.NOMBRE_MEDICO;
								this.actadef.testigo1 = info.NOMBRE_TESTIGO1;
								this.actadef.testigo2 = info.NOMBRE_TESTIGO2;
								this.actadef.npadre = info.NOM_PADRE;
								this.actadef.appadre = info.PAT_PADRE;
								this.actadef.nmadre = info.NOM_MADRE;
								this.actadef.apmadre = info.PAT_MADRE;
								this.actaEncontradaDef = true;	
							}
							else
								Command: toastr.warning("Aviso !!","No se encuentra acta con información proporcionada");
						}
						catch (error) {
						    console.log(error);
						    Command: toastr.warning("Error !!","Ocurrio algo al realizar la solicitud de información");
						}						
					}
					else if(tram.id_tramite == this.TRAMITE_ACTANAC) {  	/* ACTA DE NACIMIENTO */						
						try {
							params = nombre.valor + "/" + apaterno.valor + "/" + amaterno.valor + "/" + moment(fecha.valor).format("DD-MM-YYYY")
							response = await axios.get(url + "/wsrc-actanac/" + params);
							info = response.data[0];
							
							this.acta.nombre = info.NOMBRE;
							this.acta.apaterno = info.APELLIDO_PATERNO;
							this.acta.amaterno = info.APELLIDO_MATERNO;
							this.acta.fechanac = info.FECHA_NACIMIENTO;
							this.acta.lugarnac = info.LUGAR_DE_NACIMIENTO;
							this.acta.genero = info.SEXO;
							this.acta.curp = info.CURP;
							
							this.acta.estadoreg = info.ENTIDAD_REGISTRO;
							this.acta.fechareg = info.FECHA_DE_REGISTRO;
							this.acta.munreg = info.MUNICIPIO_REGISTRO;
							this.acta.nombreM = info.NOMBRE_MADRE;
							this.acta.nombreP = info.NOMBRE_PADRE;
							this.acta.oficialia = info.OFICIALIA;
							this.acta.libro = info.LIBRO;
							this.acta.actano = info.NUMERO_DE_ACTA;
							this.acta.notas = info.NOTAS_MARGINAL;
							this.actaEncontradaNac = true;
						}
						catch (error) {
							console.log(error);
							Command: toastr.warning("Error !!","Ocurrio algo al realizar la solicitud de información");
						}
					}
					else {
						Command: toastr.warning("Aviso!!","No se encontro acta con información proporcionada");
						this.actaEncontradaNac = false;
						this.actaEncontradaDef = false;
						this.actaEncontradaMat = false;
					}    				
      			} catch (error) {
      				console.log(error);
      				Command: toastr.warning("Aviso!!","Servicio no disponible por el momento");
      				this.actaEncontradaNac = false;
					this.actaEncontradaDef = false;
					this.actaEncontradaMat = false;
      			}
      			this.buscando = false;
      		},
      		getStorage(key, goTab){
              if (localStorage.getItem(key)) {
                  try {
                    return JSON.parse(localStorage.getItem(key));
                  } catch(e) {
                    localStorage.removeItem(key);
                    if(  goTab ){
                      //agreger emit to
                        //goTo(goTab);
                        return false;
                    }
                  
                  }
              }
            },
        },
	}
</script>