import $ from 'jquery';
import jQuery from 'jquery';
import 'jquery-validation';
import 'select2';
import 'select2/dist/css/select2.css';
$(function(){
  $('select').select2()
})
$(".numbers").on("keypress",function(e){

    var keynum = window.event ? window.event.keyCode : e.which;
    if ((keynum == 8) || (keynum == 46))
    return true;
    return /\d/.test(String.fromCharCode(keynum));
  })


$('#registration-form').validate({

	lang: 'es',
	rules: {
		account_number: {
			required: true,
      number:true
		},
    bank:{
      required:true
    },
    account_type:{
      required:true
    }
	},
	messages : {
		account_number: {
			required:'Escriba el numero de cuenta bancaria',
			number:'no se permiten letras'
		},
    bank:{
      required:'Seleccione una opcion'
    },
    account_type:{
      required:'Seleccione una opcion'
    }
	},
	ignore: "input[type='file']"
})
