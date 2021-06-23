export default class TramiteCarStrategy  {	
	constructor() {
	  	this.strategy = "";
	}
};
 
TramiteCarStrategy.prototype = {
	setStrategy: function(strategy) {
        this.strategy = strategy;
    }, 
    create: function(tramite, solicitud) {
    	return this.strategy.create(tramite, solicitud);
    }
}
