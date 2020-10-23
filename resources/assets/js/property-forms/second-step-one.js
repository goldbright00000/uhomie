import $ from 'jquery';
import jQuery from 'jquery';
import 'select2';
import 'select2/dist/css/select2.css';
import bulmaCalendar from 'bulma-calendar';
import Inputmask from "inputmask";


import 'jquery-validation';

var min = getToday()

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

var available_date1 = new bulmaCalendar( document.getElementById( 'available_date1' ), {
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

var available_date2 = new bulmaCalendar( document.getElementById( 'available_date2' ), {
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

var available_date3 = new bulmaCalendar( document.getElementById( 'available_date3' ), {
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

var available_date4 = new bulmaCalendar( document.getElementById( 'available_date4' ), {
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

$("button.calendar-nav-previous").click(function(e){ e.preventDefault() })
$("button.calendar-nav-next").click(function(e){ e.preventDefault() })

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

  jQuery.validator.addMethod("isCero", function(value, element) {
      if ( value == 0 ) {
        return false;
      }else {
        return true;
      }
  }, 'El monto no puede ser 0');


	//Inputmask("99/99/9999").mask($("input[name=available_date]"));
	$('select').select2();
	checkFurnished()

	if ( $("#common_expenses_limit").prop("disabled") ) {

		$(".btn-no-posee").css("background-color","#00a2ff")
		$(".btn-no-posee").css("border-color","#00a2ff")
		$(".btn-no-posee").css("color","#fff")
    $(".btn-no-posee").text("Posee")

	}else{

		$(".btn-no-posee").css("background-color","transparent")
		$(".btn-no-posee").css("border-color","#00a2ff")
		$(".btn-no-posee").css("color","#00a2ff")
    $(".btn-no-posee").text("No posee")
	}

  var expenses_limit = $("#rent").val()
  var common_expenses_limit  = $("#common_expenses_limit").val()
  $("#rent").val(number_format(expenses_limit,0))
  $("#common_expenses_limit").val(number_format(common_expenses_limit,0))
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

$("#furnished").change(function(){
	checkFurnished()
})

$(".btn-no-posee").click(function(){
	if ( $("#common_expenses_limit").prop("disabled")  ) {

		$(".btn-no-posee").css("background-color","transparent")
		$(".btn-no-posee").css("border-color","#00a2ff")
		$(".btn-no-posee").css("color","#00a2ff")
		$("#common_expenses_limit").val("")
		$("#common_expenses_limit").attr("disabled",false)
		$("#common_expenses_limit").attr("required",true)
    $(".btn-no-posee").text("No posee")

	}else{
		$(".btn-no-posee").css("background-color","#00a2ff")
		$(".btn-no-posee").css("border-color","#00a2ff")
		$(".btn-no-posee").css("color","#fff")

		$("#common_expenses_limit").val("0")
		$("#common_expenses_limit").attr("disabled",true)
		$("#common_expenses_limit").attr("required",false)
    $(".btn-no-posee").text("Posee")
  }

})

$('#registration-form').validate({

	lang: 'es',
	rules: {
		rent: {
			required: true,
      isCero:true
    },
    rent_year_1 : {
      required:true,
      minlength:true,
    },
    rent_year_2 : {
      required:true,
      minlength:true,
    },
    rent_year_3 : {
      required:true,
      minlength:true,
    },
		available_date: {
			required: true,
		    isDate:true,
		    maxDate:true,
		    date:false
    },
    common_expenses_limit: {
			required: true,
      isCero:true
    },
    penalty_fees: {
			required: true,
      isCero:true
    },
    tenanting_insurance: {
      required: true,
    },
    furnished: {
      required: true,
    },
    number_furnished:{
      required: true,
      isCero:true
    },
    furnished_description: {
      required: true,
    },
    warranty_ticket: {
      requierd: true,
    },
    warranty_ticket_price: {
      required: true,
      isCero:true
    },
    warranty_months_quantity: {
      required: true
    },
    months_advance_quantity: {
      required: true
    },
    building_name: {
      required: true
    }
	},

	messages : {

	  rent : {
      required:'Precio mensual de arriendo ',
      minlength: 'El monto es muy pequeño',
    },
    term_year: {
      required:'Campo requerido'
    },
    rent_year_1 : {
      required:'Campo requerido',
      minlength: 'El monto es muy pequeño',
    },
    rent_year_2 : {
      required:'Campo requerido',
      minlength: 'El monto es muy pequeño',
    },
    single_beds : {
      required:'Campo requerido',
    },
    double_beds : {
      required:'Campo requerido',
    },
    number_furnished:{
      required: 'Campo Requerido'
    },
    number_furnished:{
      required: 'Campo Requerido'
    },
    rent_year_3 : {
      required:'Campo requerido',
      minlength: 'El monto es muy pequeño',
    },
		common_expenses_limit : {
			required:'Se requiere Campo'
		},
    available_date : {
			required:'¿Dia disponible?'
    },
    penalty_fees: {
			required: 'Se requiere Campo',
    },
    tenanting_insurance: {
      required: 'Se requiere Campo',
    },
    furnished:{
      required: 'Se requiere el Campo',
    },
    furnished_description: {
      required: 'Se requiere el Campo',
    },
    warranty_ticket: {
      requierd: 'Se requiere el Campo',
    },
    warranty_ticket_price: {
      required: 'Se requiere el Campo',
    },
    bedrooms: {
      required: 'Se requiere el Campo',
    },
    bathrooms: {
      required: 'Se requiere el Campo',
    },
    meters: {
      required: 'Se requiere el Campo',
    },
    pool: {
      required: 'Se requiere el Campo',
    },
    garden: {
      required: 'Se requiere el Campo',
    },
    terrace: {
      required: 'Se requiere el Campo',
    },
    private_parking: {
      required: 'Se requiere el Campo',
    },
    public_parking: {
      required: 'Se requiere el Campo',
    },
    cellar: {
      required: 'Se requiere el Campo',
    },
    warranty_months_quantity: {
      required: 'Se requiere el Campo',
    },
    months_advance_quantity: {
      required: 'Se requiere el Campo',
    },
    tenanting_months_quantity: {
      required: 'Se requiere el Campo',
    },
    collateral_require: {
      required: 'Se requiere el Campo',
    },
    level:{
      required: 'Se requiere el Campo'
    },
    rooms: {
      required: 'Se requiere el Campo'
    },
    meeting_room: {
      required: 'Se requiere el Campo'
    },
    cellar_description:{
      required: 'Se requiere el Campo'
    },
    building_name: {
      required: 'Se requiere el Campo'
    }
	}
})


available_date.on("date:selected",function(){

  $("#registration-form").find("#available_date").each(function () {
        $(this).valid();
    });
})
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

function parseDate(date){
   var parts = date.split("/");
   return new Date(parts[2], parts[1] - 1, parts[0]);
}


function checkFurnished(){

	if($("#furnished:checked").val() == "0"){

		$(".furnished_description").css("display","none")
	}
	if($("#furnished:checked").val() == "1"){

		$(".furnished_description").show()

	}
}

function getTomorrow(){
	var today = new Date(new Date().getTime() + 24 * 60 * 60 * 1000) , dd = today.getDate() , mm = today.getMonth()+1 , yy = today.getFullYear().toString().substr(-2);
  if(dd<10) { dd='0'+dd } if(mm<10) { mm='0'+mm }
  return dd+'/'+mm+'/'+yy;
}

function getToday(){
	var today = new Date() , dd = today.getDate() , mm = today.getMonth()+1 , yy = today.getFullYear().toString().substr(-2);
  if(dd<10) { dd='0'+dd } if(mm<10) { mm='0'+mm }
  return dd+'/'+mm+'/'+yy;
}
function isDate(string)
{ //string estará en formato dd/mm/yyyy (dí­as < 32 y meses < 13)
  var ExpReg = /^([0][1-9]|[12][0-9]|3[01])(\/|-)([0][1-9]|[1][0-2])\2(\d{4})$/
  return (ExpReg.test(string));
}

if($("#is_project").val() == 1){
  $(".property").remove()
  $(".project").css("display","block")
} else {
  $(".project").remove()
  $(".property").css("display","block")
}

if($("#property_type").val() == "2"){
	//alert("no visit")
  $(".form-resident").remove()
  $(".form-office").remove()
  $(".form-parking").remove()
  $(".form-cellar").remove()
  $(".form-room").css("display","block")
}

if($("#property_type").val() == "1"){
	//alert("no visit")
  $(".form-resident").remove()
  $(".form-room").remove()
  $(".form-parking").remove()
  $(".form-cellar").remove()
  $(".form-office").css("display","block")
}

if($("#property_type").val() == "0"){
  //alert("visit")
  $(".form-office").remove()
  $(".form-room").remove()
  $(".form-parking").remove()
  $(".form-cellar").remove()
  $(".form-resident").css("display","block")
}

if($("#property_type").val() == "3"){
  //alert("visit")
  $(".form-office").remove()
  $(".form-room").remove()
  $(".form-resident").remove()
  $(".form-cellar").remove()
  $(".form-parking").css("display","block")
}
if($("#property_type").val() == "5" || $("#property_type").val() == "4"){
  //alert("visit")
  $(".form-office").remove()
  $(".form-room").remove()
  $(".form-resident").remove()
  $(".form-parking").remove()
  $(".form-cellar").css("display","block")
}

if($("#property_type_stay").val() == "LONG_STAY"){
  $(".short_stay").remove()
  $(".long_stay").css("display","block")
}

if($("#property_type_stay").val() == "SHORT_STAY"){
  $(".short_stay").css("display","block")
  $(".long_stay").remove()
}

if($("#property_type_stay").val() == "LONG_STAY"){
  $(".data-long-stay").css("display","block")
} else {
  $(".data-long-stay").remove()
}



$("#warranty_ticket").change(function(){
	checkWarranty();
})

function checkWarranty(){

	if($("#warranty_ticket:checked").val() == "0"){

		$(".warranty_ticket_price").css("display","none")
	}
	if($("#warranty_ticket:checked").val() == "1"){

		$(".warranty_ticket_price").css("display","block")

	}
}
