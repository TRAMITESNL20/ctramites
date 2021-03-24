require('./bootstrap');

import Vue from 'vue'
import UUID from "vue-uuid";
import Vuetify from 'vuetify';
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
// import 'bootstrap/dist/css/bootstrap.css'
// import 'bootstrap-vue/dist/bootstrap-vue.css'

Vue.use(UUID);
Vue.use(Vuetify);
Vue.use(BootstrapVue)
Vue.use(IconsPlugin)


document.getElementById("close-header").addEventListener("click", () => {
    let sideBar = document.getElementById("kt-header-sidebar");
    let content = document.getElementById("kt_content");
    let fas = document.getElementById("close-header").querySelector(".fas");
    if (!sideBar.classList.contains("close")) {
        fas.classList.remove("fa-chevron-left")
        fas.classList.add("fa-chevron-right")
        sideBar.classList.add("close");
        content.classList.add("header-closed");
    } else {
        fas.classList.remove("fa-chevron-right")
        fas.classList.add("fa-chevron-left")
        sideBar.classList.remove("close");
        content.classList.remove("header-closed");
    }
})

Vue.component('listado-tramites-component', () => import ( /* webpackChunkName: "js/components/listado-tramites-component" */ './components/ListadoTramitesComponent.vue'));
Vue.component('tramite-component', () => import ( /* webpackChunkName: "js/components/tramite-component" */ './components/TramiteComponent.vue'));
Vue.component('wizard-component', () => import ( /* webpackChunkName: "js/components/wizard-component" */ './components/WizardComponent.vue?v=1.0.0'));


Vue.component('campos-tramite-component', () => import(/* webpackChunkName: "js/components/campos-tramite-component" */ './components/CamposTramiteComponent.vue'));
Vue.component('solicitantes-component', () => import(/* webpackChunkName: "js/components/solicitantes-component" */ './components/SolicitantesComponent.vue'));
Vue.component('resumen-tramite-component', () => import(/* webpackChunkName: "js/components/resumen-tramite-component" */ './components/ResumenTramiteComponent.vue'));

Vue.component('car-shop-component', () => import ( /* webpackChunkName: "js/components/car-shop-component" */ './components/carShop/CarShopComponent.vue?v=1.0.0'));
Vue.component('metodos-pago-component', () => import ( /* webpackChunkName: "js/components/metodos-pago-component" */ './components/carShop/MetodosDePagoComponent.vue'));

Vue.component('item-solictud-carshop-component', () => import ( /* webpackChunkName: "js/components/item-solictud-carshop-component" */ './components/carShop/ItemSolicitudCarShopComponent.vue'));
Vue.component("detalle-pago-component", () => import ( /* webpackChunkName: "js/components/detalle-pago-component" */ './components/carShop/DetallePagoComponent.vue'));
Vue.component("usuarios-component", () => import ( /* webpackChunkName: "js/components/usuarios-component" */ './components/Usuarios.vue'));
Vue.component("listado-tramites", () => import ( /* webpackChunkName: "js/components/listado-tramites" */ './components/listadoTramites.vue' ))
Vue.component("pagination-component", () => import ( /* webpackChunkName: "js/components/pagination-component" */ './components/paginationComponent.vue' ));
Vue.component("table-component", () => import ( /* webpackChunkName: "js/components/pagination-component" */ './components/TableComponent.vue' ));

Vue.component("radio-option-component", () => import ( /* webpackChunkName: "js/components/generales/radio-option-component" */ './components/generales/radioOptionComponent.vue' ));
Vue.component("formulario-complementaria-component" , () => import ( /* webpackChunkName: "js/components/formulario-complementaria-component" */ './components/FormularioComplementariaComponent.vue' ));


Vue.component("input-component" , () => import ( /* webpackChunkName: "js/components/tiposElementos/input-component" */ './components/tiposElementos/InputComponent.vue' ));
Vue.component("select-component" , () => import ( /* webpackChunkName: "js/components/tiposElementos/select-component" */ './components/tiposElementos/SelectComponent.vue' ));
Vue.component("option-component" , () => import ( /* webpackChunkName: "js/components/tiposElementos/option-component" */ './components/tiposElementos/OptionComponent.vue' ));
Vue.component("textbox-component" , () => import ( /* webpackChunkName: "js/components/tiposElementos/textbox-component" */ './components/tiposElementos/TextBoxComponent.vue' ));
Vue.component("checkbox-component" , () => import ( /* webpackChunkName: "js/components/tiposElementos/checkbox-component" */ './components/tiposElementos/CheckboxComponent.vue' ));
Vue.component("expediente-excel-component" , () => import ( /* webpackChunkName: "js/components/tiposElementos/expediente-excel-component" */ './components/tiposElementos/ExpedienteExcelComponent.vue' ));
Vue.component("file-component" , () => import ( /* webpackChunkName: "js/components/tiposElementos/file-component" */ './components/tiposElementos/FileComponent.vue' ));
Vue.component("results-component" , () => import ( /* webpackChunkName: "js/components/tiposElementos/results-component" */ './components/tiposElementos/ResultsComponent.vue' ));
Vue.component("firma-electronica-component" , () => import ( /* webpackChunkName: "js/components/tiposElementos/results-component" */ './components/tiposElementos/FirmaElectronicaComponent.vue' ));

Vue.component("btn-guardar-tramite-parent" , () => import ( /* webpackChunkName: "js/components/generales/btn-guardar-tramite-parent" */ './components/generales/BtnGuardarTramiteParent.vue' ));

Vue.component("btn-guardar-tramite-component" , () => import ( /* webpackChunkName: "js/components/generales/btn-guardar-tramite-component" */ './components/generales/BtnGuardarTramiteComponent.vue' ));
Vue.component("fecha-component" , () => import ( /* webpackChunkName: "js/components/tiposElementos/fecha-component" */ './components/tiposElementos/FechaComponent.vue' ));
Vue.component("enajenantes-component" , () => import ( /* webpackChunkName: "js/components/enajenantes-component" */ './components/EnajenantesComponente.vue' ));

Vue.component("modal-component" , () => import ( /* webpackChunkName: "js/components/modal-component" */ './components/ModalComponent.vue' ));
Vue.component("listado-expedientes-5-i-s-r" , () => import ( /* webpackChunkName: "js/components/listado-expedientes-5-i-s-r" */ './components/ListadoExpedientes5ISR.vue' ));
Vue.component("modal-expedientes-component" , () => import ( /* webpackChunkName: "js/components/modal-expedientes-component" */ './components/ModalExpedientesComponent.vue' ));
Vue.component('resumen-tramite-5-isr-component', () => import(/* webpackChunkName: "js/components/resumen-tramite-5-isr-component" */ './components/ResumenTramite5ISR.vue'));

Vue.component('calculo-costo-tramite-5-isr-component', () => import(/* webpackChunkName: "js/components/calculo-costo-tramite-5-isr-component" */ './components/costos/CalculoCosto5ISRComponent.vue'));

Vue.component('template-datos-personales-component', () => import(/* webpackChunkName: "js/components/template-datos-personales-component" */ './components/generales/TemplateDatosPersonalesComponent.vue'))
Vue.component('tree-component', () => import(/* webpackChunkName: "js/components/generales/tree-component" */ './components/generales/TreeComponent.vue'));


Vue.component('agrupacion-items-carrrito-component', () => import(/* webpackChunkName: "js/components/generales/agrupacion-items-carrrito-component" */ './components/carShop/AgrupacionItemsCarrritoComponent.vue'));
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
Vue.filter("capitalize", function(value) {
    if (!value)
        return '';
    value = value.toString();
    return value.charAt(0).toUpperCase() + value.slice(1);
});

Vue.filter('toCurrency', function (value) {
    if (isNaN(Number(value))) {
        return value;
    }
    var formatter = new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'MXN',
        minimumFractionDigits: 2
    });
    return formatter.format(value);
});

Vue.filter('toNumber', function (value) {
    return Number((value).replace(/[^0-9.-]+/g,""));   
});

Vue.prototype.$const = {
   PRECISION: 3
}

const app = new Vue({
    el: '#app',
});