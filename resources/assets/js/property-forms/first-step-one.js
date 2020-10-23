import $ from 'jquery';
import jQuery from 'jquery';
import 'jquery-validation';
import 'select2';
import 'select2/dist/css/select2.css';

import bulmaCalendar from 'bulma-calendar';

var available_date = new bulmaCalendar( document.getElementById( 'available_date' ), {
    dateFormat: 'DD/MM/YYYY',
    lang: 'es',
    overlay: false,
    closeOnOverlayClick: true,
    closeOnSelect: true,
    // callback functions
    onSelect: null,
    onOpen: null,
    onClose: null,
    onRender: null,
    displayMode:'default',
    changeYear:false,
    showFooter:false,
    showHeader:false,
    minDate: moment().add(1, 'days').format("YYYY-MM-DD")
} );

available_date.on("date:selected",function(){

	$("#registration-form").find("input[name=available_date]").each(function () {
		  $(this).valid();
	  });
})

$(function(){
	$.validator.addMethod("isDate", function(value, element) {
	            return isDate(value);
	        }, 'Fecha invalida');
  $.validator.addMethod("maxDate", function (value, element) {
      var max = new Date();
      var inputDate = parseDate(value)
      max.setHours(0,0,0,0)
      if (max > inputDate)
          return false;
      return true;
  }, "Seleccione una fecha a partir de hoy");
})

function parseDate(date){
   var parts = date.split("/");
   return new Date(parts[2], parts[1] - 1, parts[0]);
}


$(function(){ $('select').select2(); })
$('#registration-form').validate({

	lang: 'es',
	rules: {
		property_type:{
			required:true
		},
		name: {
			required: true
		},
		description: {
			required: true
		},
		condition:{
			required: true
		},
		room:{
			required: true
		},
		civil_work:{
			required: true
		},
		architecture_project:{
			required: true
		},
		work_electric_water:{
			required: true
		},
		available_date: {
			required: true,
			isDate:true,
		    maxDate:true,
		    date:false
		}
	},

	messages : {
		property_type:{
			required:'Seleccione un tipo de propiedad'
		},
		name : {
			required:'Escriba nombre de la propiedad'
		},
		description : {
			required:'Descripción de la propiedad'
		},
		condition:{
			required:'Debe seleccionar una opción'
		},
		available_date : {
			required:'¿Que dia estara disponible?'
		},
		room:{
			required: 'Debe seleccionar una opción'
		},
		civil_work:{
			required: 'Debe seleccionar una opción'
		},
		arquitecture_project:{
			required: 'Debe seleccionar una opción'
		},
		work_electric_water:{
			required: 'Debe seleccionar una opción'
		},
		room_enablement: {
			required: 'Debe seleccionar una opción'
		}

	}
});

$('.fproj').validate({

	lang: 'es',
	rules: {
		name: {
			required: true
		},
		description: {
			required: true
		},
		condition:{
			required: true
		}
	},

	messages : {
		
		name : {
			required:'Escriba el nombre del proyecto'
		},
		description : {
			required:'Descripción del proyecto'
		},
		condition:{
			required:'Debe seleccionar una opción'
		}
	}
})

if($("#is_project").val() == "1"){
	//alert("visit")
	$(".property").remove()
	$(".project").css("display","block")
}
if($("#is_project").val() == "0"){
	//alert("visit")
	$(".project").remove()
	$(".property").css("display","block")
}

if($("#one").val() == "1"){
	//alert("visit")
	$(".one-one").remove()
	$(".one").css("display","block")
}
if($("#one").val() == "0"){
	//alert("visit")
	$(".one").remove()
	$(".one-one").css("display","block")
}

if($("#class_type").val() == "1"){
	//alert("visit")
	$(".form-resident").remove()
	$(".form-office").css("display","block")
}

if($("#class_type").val() == "0"){
	//alert("visit")
	$(".form-office").remove()
	$(".form-resident").css("display","block")
}


function isDate(string)
{ //string estará en formato dd/mm/yyyy (dí­as < 32 y meses < 13)
  var ExpReg = /^([0][1-9]|[12][0-9]|3[01])(\/|-)([0][1-9]|[1][0-2])\2(\d{4})$/
  return (ExpReg.test(string));
}

