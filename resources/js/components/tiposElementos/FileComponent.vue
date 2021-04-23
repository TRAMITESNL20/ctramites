<template>
  <div class=" fv-plugins-icon-container">
    <div class="input-group">
      <div class="input-group-prepend">
      <span class="input-group-text" id="inputGroupFileAddon01">
          {{ campo.nombre }}  {{JSON.parse(this.campo.caracteristicas + '').required == 'true' ? '*' : '' }}
      </span>
      </div>
      <div class="custom-file">
        <input  
          :id="[[campo.campo_id]] + '-' + [[campo.relationship]]"
          :name="[[campo.campo_id]] + '-' + [[campo.relationship]]" 
          class="custom-file-input"  style="background-color: #e5f2f5 !important"
          ref="fileInput"
          type="file" @change="validar"  />
        <label class="custom-file-label" :for="[[campo.campo_id]] + '-' + [[campo.relationship]]">
          <span :id="[[campo.campo_id]]+ '-' + [[campo.relationship]]+'-namefile'">  
            {{ campo.attach || 'Seleccione archivo' }}
          </span>
        </label>
      </div>
    </div>
      <small  v-if="campo.mensajes && campo.mensajes.length > 0 && ( showMensajes || estadoFormulario > 0)" class="position-absolute">
        <p v-for="mensaje in campo.mensajes" class="form-text text-danger">
          {{ mensaje.mensajeStr }}
        </p>
    </small>
  </div>
</template>

<script>
    const getFile = (url, nombreArchivo, campo) => {
      return new Promise((resolve, reject) => {
        axios({
            method: "get",
            url,
            responseType: "ArrayBuffer",
            headers: {
          'nombreArchivo': nombreArchivo,
          campo_id: campo.campo_id + "-" + campo.relationship,
          campo_nombre:campo.nombre
        }
        })
          .then(data => {
            resolve(data);
          })
          .catch(error => {
            reject(error.toString());
          });
      });
    }
    export default {
      props: ['campo', 'estadoFormulario', 'showMensajes'],
      created() {
      },
      mounted(){
        let promises = [];
        if(this.campo.nombreArchivoGuardado){
          let urlFile = process.env.TESORERIA_HOSTNAME + '/download/' + this.campo.nombreArchivoGuardado;

          promises.push(getFile( urlFile, this.campo.nombreArchivoGuardado, this.campo ));


          Promise.all(promises).then(( respuestas ) => {
            respuestas.forEach( (res) => {
                const blob = new Blob([res.data], { type: res.headers['content-type'] });
                var fileNew = new File([blob], res.config.headers.nombreArchivo , {
                  type: res.headers['content-type'], 
                  lastModified: Date.now()
                });
                let headers = res.config.headers;

                this.campo.valor = fileNew;
                this.campo.valido =  true;
                $("#"+ this.campo.campo_id + '-' + this.campo.relationship + '-namefile' ).text(  this.campo.nombreArchivoGuardado );
                this.$emit('updateForm', this.campo);

              })
            }).catch(errors => {

          }).finally(() => {

          });

        } else {
          this.validar();
        }
      },
      methods: {

        validar(){
          let requeridoValido = false;
          let extensionvalida = true;
          let caracteristicas = {};
          var caracteristicasStr = this.campo.caracteristicas;
          this.campo.mensajes = [];
            
          try {
            caracteristicas = JSON.parse(this.campo.caracteristicas + '');
          }catch(err){
            console.log(err);
          }


          var fileInput = document.getElementById(this.campo.campo_id +  "-" + this.campo.relationship );

          if( fileInput && fileInput.files.length > 0  ){
            requeridoValido = true;
            $("#"+ this.campo.campo_id + '-' + this.campo.relationship + '-namefile' ).text(   fileInput.files[0].name  );
          } else {
            requeridoValido = false;
          }
          if(caracteristicas.hasOwnProperty('required') && caracteristicas.required !== 'true')
            requeridoValido = true;

          if( !requeridoValido ){
            let mensaje = { 
              tipo:'required',
              mensajeStr: "El archivo " + this.campo.nombre + " es requerido"
            }
            this.campo.mensajes.push( mensaje );
          } else{
            var fileInput =  document.getElementById(this.campo.campo_id +  "-" + this.campo.relationship );;
            let file = fileInput.files[0];
            this.campo.valor = file;
          }
          if(caracteristicas.accept){
            let extensionesPermitidas = caracteristicas.accept.split(",").map( ext =>{
                return ext;
            });
            if(this.campo.valor && !extensionesPermitidas.includes(fileInput.value.split(".")[1])){

                extensionvalida = false;
                fileInput.value = '';
                let mensaje = { 
                  tipo:'required',
                  mensajeStr: "Extension de archivo no permitida"
                }
                this.campo.mensajes.push( mensaje );
            }
          }
          this.$forceUpdate();
          this.campo.valido =  requeridoValido && extensionvalida;
          this.$emit('updateForm', this.campo);
        }
      }
    }
</script>