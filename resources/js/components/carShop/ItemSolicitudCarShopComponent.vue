<template>
    <div class="col-md-7 col-lg-9 col-xl-9">
        <div>
            <div class="d-flex justify-content-between">
                <div>
                    <h5> {{ solicitud.nombre }} </h5>
                    <p class="mb-3 text-muted text-uppercase small">
                        <span v-if="solicitud.datos_solicitante && solicitud.datos_solicitante.razon_social">
                            Razón Social: {{ solicitud.datos_solicitante.razon_social }}
                        </span>
                        <span v-if="solicitud.datos_solicitante && !solicitud.datos_solicitante.razon_social">
                            Nombre : {{ solicitud.datos_solicitante.nombre }} {{ solicitud.datos_solicitante.apellido_paterno  }} {{ solicitud.datos_solicitante.apellido_materno }}
                        </span>
                    </p>
                    <p class="mb-3 text-muted text-uppercase small">
                        RFC: 

                        <span v-if="solicitud.datos_solicitante && solicitud.datos_solicitante.rfc">
                            {{ solicitud.datos_solicitante.rfc }}
                        </span>
                    </p>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <button  type='button' class='card-link-secondary small text-uppercase mr-3' v-on:click="eliminar()" >
                        <i class='fas fa-trash-alt mr-1'></i>Eliminar 
                        <div :id="`spinner-pago-solicitud-${solicitud.idSolicitante}`"  class="spinner-border spinner-border-sm float-right" role="status" style="display: none;">
                            <span class="sr-only">Loading...</span> 
                        </div>
                    </button>
                </div>
                <p class="mb-0">
                    <span>
                        <strong v-if="!isNaN(solicitud.importe_tramite)"> 
                            $<span v-html="solicitud.importe_tramite"> </span>
                        </strong>
                        <strong v-if="isNaN(solicitud.importe_tramite)"> 
                            <div class="spinner-border spinner-border-sm float-right" role="status" >
                                <span class="sr-only">Loading...</span> 
                            </div>
                        </strong>
                    </span>
                </p>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['solicitud', 'index'],

        methods: {
            eliminar(){
                $("#spinner-pago-solicitud-" + this.solicitud.idSolicitante ).show();
                let url = process.env.TESORERIA_HOSTNAME +  "/solicitudes-discard/" + this.solicitud.idSolicitante;
                let elItem = this;
                axios.put(url, {tipo:"u"}).then(response => {
                    let data = { response, index: elItem.index };
                    this.$emit('updatingParent', data);
                    let totalCarritoActual = parseInt( $("#totalTramitesCarrito" ).text( ));
                    $("#totalTramitesCarrito" ).text( totalCarritoActual - 1  );
                }).catch(error => {
                    console.log("no fue posible eliminar la solicitud");
                    console.log(error)
                }).finally( () => {
                    $("#spinner-pago-solicitud-" + this.solicitud.idSolicitante ).hide();
                });
            },


        }
    }
</script>

