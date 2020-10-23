import $ from 'jquery';
import jQuery from 'jquery';
import 'jquery-validation';
import bulmaCalendar from 'bulma-calendar';
import 'select2';
import 'select2/dist/css/select2.css';
import Inputmask from "inputmask";

var today = getToday()

var worked_from_date = new bulmaCalendar( document.getElementById( 'worked_from_date' ), {

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
  showFooter:false,
  showHeader:false

} );


// var new_worked_from_date = worked_from_date.split("/").reverse().join("-");
var worked_to_date = new bulmaCalendar( document.getElementById( 'worked_to_date' ), {

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
showFooter:false,
showHeader:false
} );

$(function(){
  Inputmask("99/99/9999").mask($("input[name=worked_from_date]"));
  Inputmask("99/99/9999").mask($("input[name=worked_to_date]"));
  $('select').select2();
  $("button.calendar-nav-previous").click(function(e){ e.preventDefault() })
  $("button.calendar-nav-next").click(function(e){ e.preventDefault() })
  if ( $("input[name=saves]:checked").val() == "0" ) {
      $("input[name=save_amount]").attr("disabled",true)
      $("input[name=save_amount]").val("0")

  }else{
      $("input[name=save_amount]").attr("disabled",false)
  }

  if ( $("#other_type").val() == "0" ) {
		  $("input[name=income]").attr("disabled",true)
      $("input[name=income]").val("0")
      sumar()
	}else{
      $("input[name=income]").attr("disabled",false)
      sumar()
	}


  jQuery.validator.addMethod("isDate", function(value, element) {
          return isDate(value);
      }, 'Fecha invalida');

  $.validator.addMethod("minDate", function (value, element) {
      // new Date(-356780166)..
      var min = new Date(new Date().setFullYear(new Date().getFullYear() - 90))
      var inputDate = new Date(value);
      if (inputDate < min)
          return false;
      return true;
  }, "Maximo 90 Años");

  $.validator.addMethod("minToDate", function (value, element) {
      // new Date(-356780166)..
      var worked_from_date = $("input[name=worked_from_date]").val();
      var min = parseDate(worked_from_date)
      var inputDate = parseDate(value)

      if (inputDate < min)
          return false;
      return true;
  }, "No puede ser menor a la fecha de inicio");

  $.validator.addMethod("maxDate", function (value, element) {
        var min = new Date();
        var inputDate = parseDate(value);

        if (inputDate > min)
            return false;
        return true;
    }, "No puede ser mayor a hoy");


  var save_amount = $("input[name=save_amount]").val()
  var income = $("input[name=income]").val()
  var amount = $("input[name=amount]").val()

  $("input[name=income]").val(number_format(income,0))
  $("input[name=save_amount]").val(number_format(save_amount,0))
  $("input[name=amount]").val(number_format(amount,0))
})

$(".monto_formato_decimales").on({
  "focus": function(event) {
    $(event.target).select();
  },
  "keyup": function(event) {
    $(event.target).val(function(index, value) {
      return value.replace(/\D/g, "")
        // .replace(/([0-9])([0-9]{2})$/, '$1.$2')
        .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
    });
  }
});

$("input[name=saves]").change(function(){
  if ( $(this).val() == "0" ) {
      $("input[name=save_amount]").val("0")
      $("input[name=save_amount]").attr("disabled",true)

      sumar()
  }else{
      $("input[name=save_amount]").val("")
      $("input[name=save_amount]").attr("disabled",false)
      sumar()
  }
})

$(".monto_formato_decimales").on({
  "keyup": function(event) {
    sumar()
  }
});

function sumar(){
	$("#liquidez_total").val("")
  var sueldo = 0;

  var income = parseFloat(document.getElementById("income").value.replace(/\./g, '')) || 0;

  var saves = (parseFloat(document.getElementById("save_amount").value.replace(/\./g, ''))/12) || 0;
  
  sueldo += income + saves;
  $("#liquidez_total").val(number_format(sueldo,0));
}
function number_format (number, decimals, dec_point, thousands_sep) {
  number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
  var n = !isFinite(+number) ? 0 : +number,
      prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
      sep = (typeof thousands_sep === 'undefined') ? '.' : thousands_sep,
      dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
      s = '',
      toFixedFix = function (n, prec) {
          var k = Math.pow(10, prec);
          return '' + Math.round(n * k) / k;
      };
  // Fix for IE parseFloat(0.55).toFixed(0) = 0;
  s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
  if (s[0].length > 3) {
      s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
  }
  if ((s[1] || '').length < prec) {
      s[1] = s[1] || '';
      s[1] += new Array(prec - s[1].length + 1).join('0');
  }
  return s.join(dec);
}


$("#other_type").on("change", function(){
	if ( $(this).val() == "0" ) {

    $("input[name=income]").attr("disabled",true)
    $("input[name=income]").val("0")
    sumar()
	}else{
    $("input[name=income]").val("")
    $("input[name=income]").attr("disabled",false)
    sumar()
	}
});

$('#registration-form').validate({
	lang: 'es',

	rules: {
		position: {
			required: true
		},
		company: {
			required: true
		},
		price: {
			required: true
		},
		worked_from_date: {
			required: true,
      date:false,
      isDate:true,
      maxDate:true
		},
		worked_to_date: {
			required: true,
      isDate:true,
      date:false,
      minToDate:true,
      maxDate:true
		},
    amount:{
      required:true
    },
    saves:{
      required:true
    },
    save_amount:{
      required:true
    },
    afp:{
      required:true
    }
	},

	messages : {
		position : {
			required:'Escriba el nombre del cargo'
		},
		company : {
			required:'Escriba el nombre de la empresa'
		},
    price : {
			required:'Escriba la cantidad de dinero recibida'
		},
    worked_from_date : {
      required:'Debe seleccionar una fecha'
    },
		worked_to_date : {
			required:'Debe seleccionar una fecha'
		},
    amount:{
      required:'Debe ingresar un monto'
    },
    saves:{
      required:'Debe seleccionar una opcion'
    },
    save_amount:{
      required:'Debe ingresar un monto'
    },
    afp:{
      required:'Debe seleccionar una opcion'
    }
	}
})

worked_from_date.on("date:selected",function(worked_to_date){

    $("#registration-form").find("input[name=worked_from_date]").each(function () {
        $(this).valid();
    });

})
worked_to_date.on("date:selected",function(){

    $("#registration-form").find("input[name=worked_to_date]").each(function () {
        $(this).valid();
    });
})

function getToday(){
  var today = new Date() , dd = today.getDate() , mm = today.getMonth()+1 , yy = today.getFullYear().toString().substr(-2);
  if(dd<10) { dd='0'+dd } if(mm<10) { mm='0'+mm }
  return dd+'/'+mm+'/'+yy;
}

function parseDate(date){
   var parts = date.split("/");
   return new Date(parts[2], parts[1] - 1, parts[0]);
}
function isDate(string)
{ //string estará en formato dd/mm/yyyy (dí­as < 32 y meses < 13)
  var ExpReg = /^([0][1-9]|[12][0-9]|3[01])(\/|-)([0][1-9]|[1][0-2])\2(\d{4})$/
  return (ExpReg.test(string));
}

function getTomorrow(){
  var today = new Date(new Date().getTime() + 24 * 60 * 60 * 1000) , dd = today.getDate() , mm = today.getMonth()+1 , yy = today.getFullYear().toString().substr(-2);
  if(dd<10) { dd='0'+dd } if(mm<10) { mm='0'+mm }
  return dd+'/'+mm+'/'+yy;
}
