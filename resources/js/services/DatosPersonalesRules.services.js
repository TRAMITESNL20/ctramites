import Vue from 'vue';
import { required, helpers } from 'vuelidate/lib/validators';
class DatosPersonalesRulesService {
  
  constructor() {

  }

  getRulesMexicanPF(  ){
    let curpPattern = this.getPatterCurp();
    let rfcPattern = helpers.regex("mob", /^[A-ZÑ&]{3,4}\d{6}(?:[A-Z\d]{3})?$/);
  
    return {
      nacionalidad:{required},
      tipoPersona:{required},
      curp:{ required, curpPattern},
      rfc:{ required, rfcPattern},
      tipoPropietario: {  required },
      nombre: { required },
      apPat: {required},
      apMat: {required:false},
      fechaNacimiento:{ required },
      genero:{ required },
      estado:{ required }
    }
  }

  getRulesMexicanPM(  ){
    let rfcPattern = helpers.regex("mob", /^[A-ZÑ&]{3,4}\d{6}$/);
    return {
      nacionalidad:{required},
      tipoPersona:{required},
      rfc:{ required, rfcPattern},
      razonSocial:{ required },
      tipoPropietario: {  required }
    }
  }

  getRulesExtranjero(  ){
    return {
      nacionalidad:{required},
      tipoPersona:{ required:false },
      nombre: { required },
      tipoPropietario: {  required },
      apPat: {required},
      apMat: {required:false},
      fechaNacimiento:{ required },
      genero:{ required:false },
      estado:{ required:false },
    }
  }


  getPatterCurp(){
    return helpers.regex("mob", /^[A-Z]{1}[AEIOU]{1}[A-Z]{2}[0-9]{2}(0[1-9]|1[0-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1])[HM]{1}(AS|BC|BS|CC|CS|CH|CL|CM|DF|DG|GT|GR|HG|JC|MC|MN|MS|NT|NL|OC|PL|QT|QR|SP|SL|SR|TC|TS|TL|VZ|YN|ZS|NE)[B-DF-HJ-NP-TV-Z]{3}[0-9A-Z]{1}[0-9]{1}$/);
  }
}
export default new DatosPersonalesRulesService();