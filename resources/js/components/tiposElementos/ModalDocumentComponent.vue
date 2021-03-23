<template>
    <div class="container"> 
        <button type="button" class="btn btn-success font-weight-bolder text-uppercase px-9 py-4" data-toggle="modal" data-target="#myModal">Ingresar documentos </button>


            <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                    &times;
                    </button>
                    <h4 class="modal-title">Ingresa los documentos correspondientes</h4>
                </div>
                <div  v-for="tramiteDoc in tramitesdoc" class="modal-body">
                
                    <h3>Tramite id: {{tramiteDoc.id }}</h3>

                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span id="inputGroupFileAddon01" class="input-group-text">
                            CALCULO DEL ISR CONFORME AL 126 LISR
                            </span>
                        </div>
                        <div class="custom-file">
                            <input
                            id="126"
                            name="documento126"
                            type="file"
                            class="custom-file-input"
                            style="background-color: rgb(229, 242, 245) !important"
                            @change ="refreshFiles(event, tramiteDoc.id)"
                            />
                            <label for="documento126" class="custom-file-label"
                            ><span id="documento126-namefile">
                                Seleccione archivo
                            </span></label
                            >
                        </div>
                    </div>
                    <div class="input-group pt-10">
                         <div class="input-group-prepend">
                            <span id="inputGroupFileAddon01" class="input-group-text">
                            documento del SAT
                            </span>
                        </div>
                        <div class="custom-file">
                            <input
                            id="sat"
                            name="docSat"
                            type="file"
                            class="custom-file-input"
                            style="background-color: rgb(229, 242, 245) !important"
                            />
                            <label for="docSat" class="custom-file-label"
                            ><span id="docSat-namefile"> Seleccione archivo </span></label
                            >
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-dismiss="modal">
                    Enviar
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                    Close
                    </button>
                </div>
                </div>
        </div>
        </div>
        <!-- <code> {{tramitesdoc}}</code> -->

    </div>
</template>

<script>
export default {
	props: ['idtramites', 'tramitesdoc'],
    data(){
        return{
            files : [],
        }
    },
    methods:{
        enviarDocumentos(){
                url =  process.env.TESORERIA_HOSTNAME + "/save-files";
                axios.post(url, this.files).then(response => {
                    
                }).catch(error => {
                    console.log("error al enviar los documentos");
                    console.log(error)
                }).finally( () => {
                   
                });
        },
        refreshFiles(event, id){
            this.files.push({"ticket_id" : id ,"mensaje" : ""  , "file" : event.target.files});
        }
    }


};
</script>

<style>
</style>