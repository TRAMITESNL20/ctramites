<template>
    <div> 
            <div class="col-lg-12 col-sm-12">
		

                            <button type="button" class="btn btn-sm  btn-light font-weight-bolder text-uppercase  mt-2" style="color:black" data-toggle="modal" data-target="#modalDocument">Ingresar documentos </button>


                            <div class="modal fade" id="modalDocument" role="dialog">
                                <div class="modal-dialog  modal-dialog-centered   modal-dialog-scrollable modal-lg">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">
                                        &times;
                                        </button>
                                        <h4 class="modal-title">Ingresa los documentos correspondientes</h4>
                                    </div>
                                    <div id="docAlert" class="w-90">
                                                <div role="alert" class="alert alert-warning alert-dismissible fade show ">Ocurrio un error al guardar el documento intente nuevamente 
                                                <button type="button" data-dismiss="alert" aria-label="Close" class="close"><span aria-hidden="true">×</span></button></div>
                                    </div>
                                    <div  v-for="(tramiteDoc, index) in newTramites" :key="tramiteDoc.id"  class="modal-body" v-if="tramiteDoc.required_docs != 1">
                                        
                                        <div>
                                            <div style="display:flex;justify-content: space-between;">
                                                <h6> <strong>Folio de pago:</strong>  {{tramiteDoc.folio}}</h6>
                                                <h6> <strong>FSE:</strong> {{tramiteDoc.id_transaccion}}</h6>
                                            </div>
                                            <div style="display:flex;justify-content: space-between;">
                                                <h6> <strong>Fecha de Escritura o minuta:</strong>  {{tramiteDoc.info.camposConfigurados[1].valor}}</h6>
                                                <h6 v-for="(campoConfigurado , index )  in  tramiteDoc.info.camposConfigurados" :key="index" v-if="campoConfigurado.nombre == 'Escritura' "   > <strong>No. Escritura:</strong> {{campoConfigurado.valor}} </h6>
                                            </div>
                                             <div style="display:flex;justify-content: space-between;">
                                                <h6 v-for="(campoConfigurado , index )  in  tramiteDoc.info.camposConfigurados" :key="index" v-if="campoConfigurado.nombre == 'Expedientes' "   ><strong>Expediente Catastral:</strong>  {{campoConfigurado.valor.expedientes[0].expediente}}</h6>
                                                <h6> <strong>Enajenantes:</strong>  {{tramiteDoc.cantidadEnajenantes}}</h6>
                                            </div>


                                            <div class="input-group"  style="padding-bottom:7px">

                                                <div class="input-group-prepend">
                                                    <span id="inputGroupFileAddon01" style="font-size:10px" class="input-group-text">
                                                    CALCULO DEL ISR CONFORME AL 126 LISR O COMPROBANTE DE LA EXENCIÓN
                                                    </span>
                                                </div>
                                                <div class="custom-file">
                                                    <input 
                                                    type="file" 
                                                    :id="tramiteDoc.id" 
                                                    ref="myFiles" 
                                                    class="custom-file-input" 
                                                    accept=".pdf"
                                                    @change="previewFiles(tramiteDoc.id , index, tramiteDoc.clave)" >

                                                    <label class="custom-file-label" data-browse="Buscar"
                                                    ><span>
                                                        {{  fileName[index] ? fileName[index] : 'Seleccione el archivo' }}
                                                    </span>
                                                    </label>
                                            
                                                </div>
                                            </div>

                                                     
                                            <div class="">
                                                <b-table hover Outlined small caption-top  striped responsive  thead-class="height-detalle-modal" tbody-class="padding-detalle-modal"   :items="[tramiteDoc.info.enajenante]" :fields="camposEnajenantes" ref="table"  class="text-center">
                                                    <template #cell(status)="data">
                                                        <transition name="slide-fade">
                                                            <div v-if="data.item.detalle && typeof data.item.detalle == 'object'">
                                                                <b-link title="Click para ver detalles" @click="data.toggleDetails" class="mr-2 btn btn-link">
                                                                    {{!data.detailsShowing ? "Ver detalle " : "Ocultar detalle "}}
                                                                </b-link>
                                                            </div>

                                                            <div v-else-if="actualizandoDatos">
                                                                <b-spinner small  type="grow" label="Loading..."></b-spinner>
                                                            </div>
                                                        </transition>
                                                    </template> 
                                                    <template #row-details="data" #title="Detalle">
                                                        <transition name="slide-fade">
                                                        <b-card no-body v-if="data">
                                                            <b-card-body id="nav-scroller"ref="content"style="position:relative; height:400px; overflow-y:scroll;">
                                                                <b-row v-for="(salida, key) in data.item.detalle.Salidas" :key="key">
                                                                    <b-col class="text-left" style="width: 70%" >
                                                                        <strong>{{ key }}</strong>
                                                                    </b-col>
                                                                    <b-col class="text-right" >
                                                                        <span class="text-muted">   {{ currencyFormat(key, salida) }} </span>
                                                                    </b-col>
                                                                </b-row>
                                                            </b-card-body> 
                                                        </b-card>
                                                        </transition>
                                                    </template>      
                                                </b-table>
                                            </div>
                                            <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-5 bg-gray-200"></div>
                                            <div class="separator separator-dashed mt-8 mb-5"></div>


                                        </div>   
                                    </div>

                                     
                                

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">
                                        Cerrar
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
        
    </div>
</template>

<script>
import Vue from 'vue'

export default {
	props: ['idtramites', 'tramitesdoc'],
    data(){
        return{
            files : [],
            fileById: '',
            filesx: '',
            fileName: [],
            showAlert: 0,
            lastClave: [],
            newTramites: [],
            camposEnajenantes : [{ key: 'status', label:"Acciones" }],
            trampa: []
        }
    },
    mounted(){

        $('#modalDocument').appendTo("body");
         $("#docAlert").hide();
        var last=[];

        for (let i = 0; i < this.tramitesdoc.length; i++) {

            var cantidadEnajenantes = this.tramitesdoc.filter((v) => (v.clave == this.tramitesdoc[i].clave  ) ).length;
            this.tramitesdoc[i]['cantidadEnajenantes'] = cantidadEnajenantes;
            
        
            if (!last.includes(this.tramitesdoc[i].clave)) {
                
                
                if( this.idtramites[0].solicitudes[i].id == this.tramitesdoc[i].id){
                    this.tramitesdoc[i]['folio'] = this.idtramites[0].solicitudes[i].folio;
                }


                this.newTramites.push( _.cloneDeep( this.tramitesdoc[i] )  );
            }
            last.push(this.tramitesdoc[i].clave);
            
        }
        //     for (let i = 0; i < self.idtramites[0].solicitudes.length; i++) {
        //         for (let k = 0; k < self.tramitesdoc.length; k++) {
        //                 if ( self.idtramites[0].solicitudes[i].id === self.tramitesdoc[k].id  &&  this.tramitesdoc[k].required_docs == 0) {
        //                     self.idtramites[0].solicitudes[i].required_docs = 1;
        //                 }
        //     }
        //     console.log('Guardado desde el modal');
        //     this.$emit('updatedTramites', self.idtramites);
        // }  
    },
    created(){
         $("#docAlert").hide();
    },
    methods:{
        enviarDocumentos(){
            var self = this;
            $('#saveDocument').addClass('spinner-border spinner-border-sm text-light');
          
            console.log();
            var url =  process.env.TESORERIA_HOSTNAME + "/save-files";
                axios.post(url, this.files).then(response => {                    
                    if (response.data.code === 200) {
                        for (let i = 0; i < self.idtramites[0].solicitudes.length; i++) {
                            for (let k = 0; k < self.tramitesdoc.length; k++) {
                                    if ( self.idtramites[0].solicitudes[i].id === self.tramitesdoc[k].id  && self.tramitesdoc[k].required_docs == 3) {
                                        self.idtramites[0].solicitudes[i].required_docs = 1;
                                        self.tramitesdoc[k].required_docs =1;
                                        this.$forceUpdate();
                                    }else{
                                        self.idtramites[0].solicitudes[i].required_docs = 0;
                                    }
                            }
                            console.log('Guardado desde el modal');
                            this.$emit('updatedTramites', self.idtramites);
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
        previewFiles(id, index, clave){
        
        for (let i = 0; i < this.tramitesdoc.length; i++) {
            this.tramitesdoc[i].clave == clave ?  this.tramitesdoc[i].required_docs = 3  : '';
        }

        this.tramitesdoc[index].required_docs = 3
                // var i = $(this).prev('label').clone();
                var auxName = $('#'+id)[0].files[0].name;
                this.fileName[index] = auxName;   
                this.$forceUpdate();
                console.log(auxName);
                console.log(this.fileName[index]);
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
            //   console.log(data);
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
        currencyFormat(campoName, salida){
            let arr = ["Ganancia Obtenida","Monto obtenido conforme al art 127 LISR",
                        "Pago provisional conforme al art 126 LISR","Impuesto correspondiente a la entidad federativa",
                        "Parte actualizada del impuesto", "Recargos", "Multa corrección fiscal", "Importe total", "Cantidad a cargo",
                        "Monto pagado en la declaracion inmediata anterior", "Pago en exceso", "Diferencia de Impuesto correspondiente a la Entidad Federativa", "Importe total a pagar"];
            if(arr.includes(campoName)){
                let text = Vue.filter('toCurrency')(salida);
                return text;
            } else{
                return salida;
            }
        },
     
    }
};
</script>