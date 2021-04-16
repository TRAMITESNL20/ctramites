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
                        
                        <div id="docAlert" class="w-100">
                            <div role="alert" class="alert alert-warning alert-dismissible fade show ">Ocurrio un error al guardar el documento intente nuevamente 
                            <button type="button" data-dismiss="alert" aria-label="Close" class="close"><span aria-hidden="true">×</span></button></div>
                        </div>

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

                            <label class="custom-file-label"
                            ><span>
                                {{ fileName }}
                            </span>
                            </label>
                      
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
            filesx: '',
            fileName: 'Seleccione archivo',
            showAlert: 0
        }
    },
    mounted(){
        $('#modalDocument').appendTo("body");
         $("#docAlert").hide();
    },
    methods:{
        enviarDocumentos(){
            $('#saveDocument').addClass('spinner-border spinner-border-sm text-light');
            var url =  process.env.TESORERIA_HOSTNAME + "/save-files";
            var self = this;
                axios.post(url, this.files).then(response => {                    
                    if (response.data.code === 200) {
                        for (let i = 0; i < self.idtramites.length; i++) {

                            for (let k = 0; k < self.tramitesdoc.length; k++) {

                                if ( self.idtramites.solicitudes[i].id === self.tramitesdoc[k].id ) {
                                    var required_docs = 1;
                                    self.idtramites.solicitudes[i].push(required_docs);
                                }
                                
                            }
                            
                        }
                        
                        $('#modalDocument').modal('hide');                        
                    }else{
                        $("#docAlert").show();
                    }
                    
                }).catch(error => {
                    console.log("error al enviar los documentos");
                    console.log(error)
                }).finally( () => {
                    $('#saveDocument').removeClass('spinner-border spinner-border-sm text-light');
                });


        },
        previewFiles(id, index){
            


                console.log('/////');
                var i = $(this).prev('label').clone();
                var auxName = $('#'+id)[0].files[0].name;
                this.fileName =auxName;      

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