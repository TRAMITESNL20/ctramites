<template>
    <div class="container"> 
        <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Open Modal</button>


            <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                    &times;
                    </button>
                    <h4 class="modal-title">Ingresa los documentos correspondientes</h4>
                </div>
                <div class="modal-body">
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
                        />
                        <label for="documento126" class="custom-file-label"
                        ><span id="documento126-namefile">
                            Seleccione archivo
                        </span></label
                        >
                    </div>

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
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                    Close
                    </button>
                </div>
                </div>
        </div>
        </div>
    </div>
</template>

<script>
export default {};
</script>

<style>
</style>