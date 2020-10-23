
import $ from 'jquery';
import jQuery from 'jquery';
import 'jquery-validation';
import Inputmask from "inputmask";
import bulmaCalendar from 'bulma-calendar';
import 'select2';
import 'select2/dist/css/select2.css';
const { validate, clean, format } = require('rut.js')

/*var max = get18YearsBefore()
var today = getToday()
var datePicker = new bulmaCalendar( document.getElementById( 'birthdate' ), {
    dateFormat: 'DD/MM/YYYY',
    lang: 'es',
    overlay: true,
    closeOnOverlayClick: true,
    closeOnSelect: true,
    // callback functions
    onSelect: null,
    onOpen: null,
    onClose: null,
    onRender: null,
    displayMode:'default',
    changeYear:true,
    showFooter:false,
	showHeader:false,
} );*/

$("button.calendar-nav-previous").click(function(e){ e.preventDefault() })
$("button.calendar-nav-next").click(function(e){ e.preventDefault() })

$("#doc_type").on("change",function(e){
	var type_input = $("#doc_type").val();
	$("#document_number").val("")
	if(type_input == "RUT" || type_input == "RUT_PROVISIONAL") {
		$('#document_number').val(format($('#document_number').val()));
		$("#document_number").attr("placeholder","23.495.589-6");
	}else if(type_input == "PASSPORT") {
		Inputmask.remove($("#document_number"));
		$("#document_number").attr("placeholder","23495589");
	}
})

function validateDocument(value){
	var global_response = ""
	$.ajax({
		async:false,
		cache:false,
		url:"/users/check-document-number",
		type:'GET',
		dataType:'json',
		data:{"document_number":value}
	})
	.done(function(response){
		global_response = response.valid

	})
	return global_response
}

/*function isDate(string)
{ //string estará en formato dd/mm/yyyy (dí­as < 32 y meses < 13)
	var ExpReg = /^([0][1-9]|[12][0-9]|3[01])(\/|-)([0][1-9]|[1][0-2])\2(\d{4})$/
	return (ExpReg.test(string));
}*/

$('#registration-form').validate({
    //

	lang: 'es',
	rules: {
		document_number: {
			required: true,
			rut:true,
      		validate_document:true
		},
		birthdate:{
			required:true,
			date:false,
	        validate_age:true,
	        minDate:true,
	        maxDate:true,
	        isDate:true
		}
	},
	messages : {
		document_number : {
			required:'Escriba el numero de documento',
			rut:'El documento no es Valido',
			validate_document:"El número de documento ya está en uso"
		},
		birthdate:{
			required:'Seleccione una fecha de nacimiento',
			//validate_age: 'Debe ser mayor de edad',
			//isDate: 'Fecha invalida'
		}
	}
})

$(function(){
	/*$.validator.addMethod("validate_age", function(value, element) {
	 return this.optional(element) || validateBirthdate(value);
	}, 'Debe ser mayor de edad');*/

  	$.validator.addMethod("validate_document", function(value, element) {
  	 return this.optional(element) || validateDocument(value);
   	}, '');

	$.validator.addMethod("rut", function(value, element) {
	  return this.optional(element) || validateRut(value);
	}, 'RUT incorrecto');

	jQuery.validator.addMethod("venezuelanDate", function(value, element) {
	    return Date.parseExact(value, "d/M/yyyy");
	});

	jQuery.validator.addMethod("isDate", function(value, element) {
	    return isDate(value);
	});
	$.validator.addMethod("minDate", function (value, element) {
	    // new Date(-356780166)..
	    var min = new Date(new Date().setFullYear(new Date().getFullYear() - 90))

      var inputDate = parseDate(value)
	    if (inputDate < min)
	        return false;
	    return true;
	}, "Maximo 90 Años");

	/*$.validator.addMethod("maxDate", function (value, element) {
	    var max = new Date(new Date().setFullYear(new Date().getFullYear() - 18));
	    var inputDate = parseDate(value)
	    if (inputDate > max)
	        return false;
	    return true;
	}, "Minimo 18 Años");*/

	documentVerify()

    $('select').select2();

   	//Inputmask("99/99/9999").mask($("#birthdate"));


});

/*datePicker.on("date:selected",function(){

    $("#registration-form").find("input[name=birthdate]").each(function () {
        $(this).valid();
    });
})*/
$("#document_number").on("keyup", function(){
	var type_input = $("#doc_type").val();
	//if (type_input == "PASSPORT") {
		documentVerify()
	//}
});

function documentVerify(){
	var doc = $("#doc_type option:selected").val();
	if (doc == "RUT" || doc == "RUT_PROVISIONAL"){
		//Inputmask("99.999.999-[*]").mask($("#document_number"));
		$('#document_number').val(format($('#document_number').val()));
		/*
		$("#document_number").rut({
			formatOn: 'keyup',
			minimumLength: 8, // validar largo mínimo; default: 2
			validateOn: null // si no se quiere validar, pasar null
		});
		*/
		$.validator.addMethod("rut", function(value, element) {
		  return this.optional(element) || validateRut(value);
		}, 'RUT incorrecto');
	}else if (doc == "PASSPORT"){
		//Inputmask.remove($("#document_number"));
		$.validator.addMethod("rut", function(value, element) {
		  return true
		}, 'No message');
	}
}
function validateRut(campo){
	if ( campo.length == 0 ){ return false; }
	if ( campo.length < 8 ){ return false; }
	campo = campo.replace('-','')
	campo = campo.replace(/\./g,'')
	var suma = 0;
	var caracteres = "1234567890kK";
	var contador = 0;
	var u;
	for (var i=0; i < campo.length; i++){
		u = campo.substring(i, i + 1);
		if (caracteres.indexOf(u) != -1)
		contador ++;
	}
	if ( contador==0 ) { return false }
	var rut = campo.substring(0,campo.length-1)
	var drut = campo.substring( campo.length-1 )
	var dvr = '0';
	var mul = 2;
	var dvi = null;
	var res = null;
	for (i= rut.length -1 ; i >= 0; i--) {
		suma = suma + rut.charAt(i) * mul
				if (mul == 7)   mul = 2
				else    mul++
	}
	res = suma % 11
	if (res==1)     dvr = 'k'
				else if (res==0) dvr = '0'
	else {
		dvi = 11-res
		dvr = dvi + ""
	}
	if ( dvr != drut.toLowerCase() ) { return false; }
	else { return true; }
	}



/*function parseDate(date){
   var parts = date.split("/");
   return new Date(parts[2], parts[1] - 1, parts[0]);
}

function validateBirthdate(value){
	var minDate = new Date();
	var inputDate = parseDate(value);
	minDate = minDate.setFullYear(minDate.getFullYear()-18);
	if (inputDate <= minDate) { return true; }else{ return false; }
}
function getToday(){
	var today = new Date() , dd = today.getDate() , mm = today.getMonth()+1 , yy = today.getFullYear().toString().substr(-2);
  if(dd<10) { dd='0'+dd } if(mm<10) { mm='0'+mm }
  return dd+'/'+mm+'/'+yy;
}
function get18YearsBefore(){
	var today = new Date(new Date().setFullYear(new Date().getFullYear() - 18)) , dd = today.getDate() , mm = today.getMonth()+1 , yy = today.getFullYear().toString().substr(-2);
  if(dd<10) { dd='0'+dd } if(mm<10) { mm='0'+mm }
  return dd+'/'+mm+'/'+yy;
}
function getTomorrow(){
	var today = new Date(new Date().getTime() + 24 * 60 * 60 * 1000) , dd = today.getDate() , mm = today.getMonth()+1 , yy = today.getFullYear().toString().substr(-2);
  if(dd<10) { dd='0'+dd } if(mm<10) { mm='0'+mm }
  return dd+'/'+mm+'/'+yy;
}*/

const _token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const birthdate = $("#birthdate").val();
import 'vuetify/dist/vuetify.min.css';
const formulario = new Vue({
    el: '#formulario',
    components : {
    }, 
    data: vm => ({
            date: birthdate ? birthdate : moment().subtract(18, 'years').format("YYYY-MM-DD"),
			dateFormatted: vm.formatDate(new Date().toISOString().substr(0, 10)),
			modal: false
			
	}),
    methods: {
        formatDate (date) {
			if (!date) return null

			const [year, month, day] = date.split('-')
			return `${day}/${month}/${year}`
		},
		parseDate (date) {
			if (!date) return null

			const [day, month, year] = date.split('/')
			return `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`
		},
    },
    mounted() {

	},
	computed: {
		computedDateFormatted () {
			return this.formatDate(this.date)
		},
		max_date(){
			var fecha = moment().subtract(18, 'years').format("YYYY-MM-DD")
			
			return fecha
		}
	},
	watch: {
		date (val) {
		this.dateFormatted = this.formatDate(this.date)
		}
	},
})


