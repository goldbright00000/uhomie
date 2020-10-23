import $ from 'jquery';
import jQuery from 'jquery';
import 'jquery-validation';
import Inputmask from "inputmask";
import 'select2';
import 'select2/dist/css/select2.css';
$(function(){
	$('select').select2();
})

/*$("select").on("change",function(e){
	var option = $( "select#city option:selected" ).text()
	$("#address").val(option + ", ")
})*/
// VALIDATIONS
$('#registration-form').validate({

    lang: 'es',
    rules: {
        address: { required: true },
        //Comentado a peticion
        //address_details: { required: true }
    },
    messages : {
    	address : { required:'Escriba su direccion' },
    	//address_details : { required:'Escriba su direccion exacta' }
    }
})
