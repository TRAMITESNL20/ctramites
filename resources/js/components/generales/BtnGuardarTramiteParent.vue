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
              let caracteristicas = {};
              try {
                caracteristicas = JSON.parse(campo.caracteristicas + '');
              }catch(err){
                console.log(err);
              }
              return caracteristicas;
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
                  informacion.tipoPersona=datosFormulario.tipoPersona,
                  //informacion.declararEn0 = this.declararEn0,
                  informacion.motivoDeclaracion0 = datosFormulario.motivoDeclaracion0,
                  informacion.tipo_costo_obj = datosFormulario.tipo_costo_obj
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
              formData.append('user_id', user.id );
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

