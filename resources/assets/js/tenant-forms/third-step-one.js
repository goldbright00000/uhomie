import $ from 'jquery';
import jQuery from 'jquery';
import 'jquery-validation';
import bulmaCalendar from 'bulma-calendar';
import 'select2';
import 'select2/dist/css/select2.css';
import Inputmask from "inputmask";

var min = getTomorrow()
var moveDate = new bulmaCalendar( document.getElementById( 'move_date' ), {

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
  showHeader:false,
  minDate: moment().add(1, 'days').format("YYYY-MM-DD")
} );

$(function(){

  $("button.calendar-nav-previous").click(function(e){ e.preventDefault() })
  $("button.calendar-nav-next").click(function(e){ e.preventDefault() })

  Inputmask("99/99/9999").mask($("input[name=move_date]"));
  $('select').select2();


  jQuery.validator.addMethod("isDate", function(value, element) {
      return isDate(value);
  }, 'Fecha invalida');

  jQuery.validator.addMethod("isCero", function(value, element) {
      if ( value == 0 ) {
        return false;
      }else {
        return true;
      }
  }, 'El monto no puede ser 0');

  $.validator.addMethod("maxDate", function (value, element) {
      var max = new Date();
      var inputDate = parseDate(value)
      max.setHours(0,0,0,0)
      if (max > inputDate)
          return false;
      return true;
  }, "Seleccione una fecha a partir de hoy");

  var expenses_limit = $("input[name=expenses_limit]").val()
  var common_expenses_limit  = $("input[name=common_expenses_limit]").val()
  $("input[name=expenses_limit]").val(number_format(expenses_limit,0))
  $("input[name=common_expenses_limit]").val(number_format(common_expenses_limit,0))

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

$('#registration-form').validate({
	lang: 'es',

	rules: {
		expenses_limit: {
			required: true,
      isCero:true
		},
		common_expenses_limit: {
			required: true,
      isCero:false
		},
		move_date: {
			required: true,
      isDate:true,
      date:false,
      maxDate:true
		}
	},

	messages : {
	   expenses_limit : {
			required:'Ingrese un monto '
		},
		common_expenses_limit : {
			required:'Ingrese un monto'
		},
    move_date : {
			required:'¿Que dia quieres mudarte?'
		}
	}
})

moveDate.on("date:selected",function(){
  if($("#registration-form").valid()) {
        $("#registration-form .error").removeClass('error')
    }
})
function getTomorrow(){
  var today = new Date(new Date().getTime() + 24 * 60 * 60 * 1000) , dd = today.getDate() , mm = today.getMonth()+1 , yy = today.getFullYear().toString().substr(-2);
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
