<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12" style="padding-bottom: 9px; margin-top: 5px;">
                <div class="text-dark-50 line-height-sm">
                    <div :class="classText" v-if="datosPersonales.razonSocial ||  datosPersonales.razon_social">
                        <div>
                            <strong>{{datosPersonales.razonSocial ||  datosPersonales.razon_social}}</strong>
                        </div>
                        <div class="font-weight-10">
                           <strong>RFC:</strong> <i class="ni location_pin mr-2"></i>{{ datosPersonales.rfc }}
                        </div>
                    </div>
                    <div :class="classText" v-if="datosPersonales.nombre">
                        <div>
                            <strong>{{datosPersonales.nombre }} {{ datosPersonales.apPat }} {{ datosPersonales.apMat }}</strong>
                        </div>
                        <div class="font-weight-10">
                           <strong>RFC:</strong> <i class="ni location_pin mr-2"></i>{{ datosPersonales.rfc }}
                        </div>
                        <div class="font-weight-10">
                          <strong>CURP:</strong> <i class="ni business_briefcase-24 mr-2"></i>{{ datosPersonales.curp }}
                        </div>                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['datosPersonales', 'classText'],
        created() {
            this.classText = this.classText ? this.classText : 'text-center';
        },

    }
</script>
