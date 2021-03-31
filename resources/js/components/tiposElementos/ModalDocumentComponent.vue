<template>
    <div> 
        <button type="button" class="btn btn-sm  btn-success font-weight-bolder text-uppercase text-white mt-2" data-toggle="modal" data-target="#modalDocument">Ingresar documentos </button>


            <div class="modal fade" id="modalDocument" role="dialog">
            <div class="modal-dialog  modal-dialog-centered   modal-dialog-scrollable modal-lg">
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                    &times;
                    </button>
                    <h4 class="modal-title">Ingresa los documentos correspondientes</h4>
                </div>
                <div  v-for="(tramiteDoc, index) in tramitesdoc" class="modal-body">
                
                    <h3>Tramite id: {{tramiteDoc.id}} </h3>

                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span id="inputGroupFileAddon01" class="input-group-text">
                            CALCULO DEL ISR CONFORME AL 126 LISR
                            </span>
                        </div>
                        <div class="custom-file">
                            <input 
                            type="file" 
                            :id="tramiteDoc.id" 
                            ref="myFiles" 
                            class="custom-file-input" 
                            accept=".pdf"
                            @change="previewFiles(tramiteDoc.id , index)" >

                            <label for="documento126" class="custom-file-label"
                            ><span id="documento126-namefile">
                                {{  "Seleccione archivo" }}
                            </span></label>
                        </div>
                    </div>      
                </div>
             

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                    Close
                    </button>
                    <button v-on:click="enviarDocumentos()"  type="button" class="btn btn-success">
                   <span id="saveDocument" role="status" aria-hidden="true"></span>
                    Guardar
                    </button>
                  
                </div>
                </div>
        </div>
        </div>

    </div>
</template>

<script>
export default {
	props: ['idtramites', 'tramitesdoc'],
    data(){
        return{
            files : [],
            fileById: '',
            filesx: ''
        }
    },
    methods:{
        enviarDocumentos(){
            $('#saveDocument').addClass('spinner-border spinner-border-sm text-light');

            var url =  process.env.TESORERIA_HOSTNAME + "/save-files";
                axios.post(url, this.files).then(response => {
                    console.log(response);
                    $('#modalDocument').modal('hide');
                }).catch(error => {
                    console.log("error al enviar los documentos");
                    console.log(error)
                }).finally( () => {
                    $('#saveDocument').removeClass('spinner-border spinner-border-sm text-light');
                });


        },
        previewFiles(id, index){

            function getBase64(file) {
            return new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = () => resolve(reader.result);
                reader.onerror = error => reject(error);
            });
            }

            this.fileById = document.getElementById(id).files[0];
            getBase64(this.fileById).then(data =>{
              console.log(data);
                this.filesx = {"ticket_id" : id  ,"mensaje" : ""  , "file" : data };
                  var indexFile = this.files.findIndex(x => x.ticket_id === id);
            
                if( indexFile != -1 ){
                    console.log('este es el mismo id actualizacion buscar id en array');
                    this.files[indexFile] = this.filesx;
                }else{
                    console.log('este es añadido nuevo');
                    this.files.push(this.filesx);
                }
            
            });

          
        },

     
    }


};
</script>

<style>
</style>