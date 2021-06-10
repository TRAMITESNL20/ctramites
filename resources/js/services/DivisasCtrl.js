import Vue from 'vue';
class DivisasCtrl {

	constructor() {

	}

    getSymbol(campoDivisa){
        let divisa = Vue.prototype.$const.DIVISAS.find( divisa => campoDivisa.clave == divisa.CLAVE );
        if(divisa){
            return divisa;
        } else {
            return "";
        }
    }
}

export default new DivisasCtrl();