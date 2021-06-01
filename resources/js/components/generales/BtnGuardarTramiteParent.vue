<script>
    export default {
        name: 'BtnGuardarTramiteParent',
        props: ['tipoTramite', 'files', 'datosComplementaria', 'idUsuario', 'infoGuardadaFull', "type", "labelBtn"],
        mounted() {
          console.log("montado")
        },

        methods:{
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

            formatearCampos(campos){
                let camposFormateados = campos.map(campo =>{
                let caracteristicas = this.getCaracteristicas(campo);
                if(caracteristicas.formato == 'moneda'){
                  campo.valor = this.formatoNumero( campo.valor );
                }
                return campo;
                })
                return camposFormateados;
            },

            obtenerDatosTabs(){
              let listaSolicitantes = this.getStorage( 'listaSolicitantes', 2 );
              let tramite = this.getStorage( 'tramite' );
              let datosFormulario = this.getStorage( 'datosFormulario', 1);
              return [listaSolicitantes, tramite, datosFormulario];
            },

            formatoNumero(numberStr){
              let valor =  Number((numberStr+"").replace(/[^0-9.-]+/g,""));
              return valor;
            },

            getCaracteristicas(campo){
              if(typeof campo.caracteristicas == 'object'){
                return campo.caracteristicas;
              } else {
                let caracteristicas = {};
                try {
                  caracteristicas = JSON.parse(campo.caracteristicas + '');
                }catch(err){
                  console.log(JSON.parse(JSON.stringify(campo)));
                  console.log(err);
                }
                return caracteristicas;
              }
              
            },

            getInformacion(tramite, datosFormulario){
                let informacion = {
                  costo_final: tramite &&  tramite.detalle ? tramite.detalle.costo_final: null,
                  partidas: tramite.partidas,
                  detalle: tramite.detalle,
                  tipoTramite:this.tipoTramite
                }
                if( datosFormulario && datosFormulario.mottivoDeclaracion0 ){
                  informacion.mottivoDeclaracion0 = datosFormulario.motivoDeclaracion0
                }
                let camposObj = {};
                if( this.tipoTramite == 'normal'|| this.tipoTramite == 'declaracionEn0' ){
                  datosFormulario.campos.forEach( campo =>  {
                    //if( campo.valido ){
                      camposObj[campo.campo_id] = campo.valor ||  "";
                    //}
                  });
                  informacion.campos=camposObj;
                  informacion.camposConfigurados = datosFormulario.campos;
                  if(datosFormulario && datosFormulario.tipoPersona){
                    informacion.tipoPersona=datosFormulario.tipoPersona;
                  }
                  if(datosFormulario && datosFormulario.motivoDeclaracion0){
                    informacion.motivoDeclaracion0 = datosFormulario.motivoDeclaracion0;
                  }
                  if(datosFormulario && datosFormulario.tipo_costo_obj){
                    informacion.tipo_costo_obj = datosFormulario.tipo_costo_obj;
                  }
                } else {
                  informacion.camposComplementaria = this.datosComplementaria;
                }
                if(informacion.camposConfigurados){
                  informacion.camposConfigurados = informacion.camposConfigurados.map(campo => { 
                    if(campo.tipo == 'file' ){
                      let elfile = this.files.find( file => file.nombre == campo.nombre);
                      campo.archivoCargado = !!elfile.valor;
                    }
                    return campo;
                  });
                }
                return informacion;
            },

            formDataComplementaria( config ){
              let formData = new FormData();
              formData.append('user_id', this.idUsuario );
              //se envia enajenantes para que se cree un registro por cada complementaria
              let datosTabs = JSON.parse( JSON.stringify(this.obtenerDatosTabs() ) );
              let listaComplementarias = [];
              let listaSolicitantes = datosTabs[0];
              if(config && config.temporal){
                let informacion = {
                  datosComplementaria:this.datosComplementaria,
                  tipoTramite:this.tipoTramite
                }
                formData.append('info', JSON.stringify(informacion) );
                formData.append('enajenantes',[]);
                formData.append('solicitantes', JSON.stringify(listaSolicitantes) );
              } else {
                this.datosComplementaria.complementarias.forEach( complementaria => {
                  let inf = Object.assign({} , complementaria);
                  inf.version = '1.0.0';
                  inf.id = 0;
                  inf.tipoTramite = this.tipoTramite;
                  inf.solicitante = listaSolicitantes[0];
                  listaComplementarias.push(inf);
                });
                formData.append('info', JSON.stringify({}) );
                formData.append("enajenantes", JSON.stringify(listaComplementarias));
              }
                


              
              let tramite = datosTabs[1];

              if(tramite){
                formData.append('clave', tramite.id_seguimiento );
                formData.append('grupo_clave', tramite.id_seguimiento );
                formData.append('catalogo_id', tramite.id_tramite );
              }

              if(this.infoGuardadaFull && this.infoGuardadaFull.id > 0) {
                formData.append('id', this.infoGuardadaFull.id );
              }

              return formData;
              
            },

            buildFormData(informacion, listaSolicitantes, tramite, idEdicion, enajenantes){
              let formData = new FormData();
              if( this.files && this.files.length > 0 ){
                this.files.forEach( (file, index) => {
                    if(this.files[index].valor && this.files[index].valor.name){
                      formData.append('file['+  index +']', this.files[index].valor);
                      formData.append('descripcion['+  index +']',  this.files[index].nombre );
                    }
                });
              }
      
              formData.append('user_id', this.idUsuario );
              if(!enajenantes){
                formData.append('info', JSON.stringify(informacion) );
              } else {
                formData.append('info', JSON.stringify({}) );
                formData.append("enajenantes", JSON.stringify(enajenantes));
              }
              if( listaSolicitantes && listaSolicitantes.length > 0 ){
                formData.append('solicitantes', JSON.stringify(listaSolicitantes) );
              }
              if(tramite){
                formData.append('clave', tramite.id_seguimiento );
                formData.append('catalogo_id', tramite.id_tramite );
              }
              if(  idEdicion  ){
                formData.append('id', idEdicion );
              }

              let archivosCargados = true;
              if(informacion.camposConfigurados){
                informacion.camposConfigurados.forEach(campo => { 
                  if(campo.tipo == 'file' ){
                    archivosCargados = archivosCargados && campo.archivoCargado;
                  }
                });
              }
              if(archivosCargados){
                formData.append('required_docs', 1);  
              } else {
                formData.append('required_docs', 0);  
              }
              
              
              return formData;
            },

            getFormData(enajenantes){
                let datosTabs = JSON.parse( JSON.stringify(this.obtenerDatosTabs() ) );
                let listaSolicitantes = datosTabs[0];
                let tramite = datosTabs[1];
                let datosFormulario = datosTabs[2];

                datosFormulario.campos = this.formatearCampos(datosFormulario.campos);
                let informacion = this.getInformacion( tramite, datosFormulario );
                let idEdicion = null;
                if(  this.infoGuardadaFull && this.infoGuardadaFull.id  ){
                  idEdicion = this.infoGuardadaFull.id ;
                }
                return this.buildFormData( informacion, listaSolicitantes, tramite, idEdicion,enajenantes );
            },

            





        }
    }
</script>

