import $ from 'jquery';
import jQuery from 'jquery';
import 'jquery-validation';
import 'select2';
import 'select2/dist/css/select2.css';

$(function(){
  $('select').select2();
    if ( $("#other_type").val() == "0" ) {
      $("input[name=income]").val("0")
  		  $("input[name=income]").attr("disabled",true)

        sumar()
  	}else{
        $("input[name=income]").attr("disabled",false)

        sumar()
  	}

    if ( $("input[name=saves]:checked").val() == "0" ) {
  		  $("input[name=save_amount]").attr("disabled",true)
        $("input[name=save_amount]").val("0")

  	}else{
        $("input[name=save_amount]").attr("disabled",false)
  	}

    if ( $("input[name=invoice]:checked").val() == "0" ) {
  		  $("input[name=last_invoice_amount]").val("0")
  		  $("input[name=last_invoice_amount]").attr("disabled",true)

  	}else{
        $("input[name=last_invoice_amount]").attr("disabled",false)
    	}
    sumar()

    var save_amount = $("input[name=save_amount]").val()
    var income = $("input[name=income]").val()
    var last_invoice_amount = $("input[name=last_invoice_amount]").val()

    $("input[name=income]").val(number_format(income,0))
    $("input[name=save_amount]").val(number_format(save_amount,0))
    $("input[name=last_invoice_amount]").val(number_format(last_invoice_amount,0))
})
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
$("input[name=invoice]").change(function(){
  if ( $(this).val() == "0" ) {
    $("input[name=last_invoice_amount]").val("0")
		  $("input[name=last_invoice_amount]").attr("disabled",true)

	}else{
      $("input[name=last_invoice_amount]").val("")
      $("input[name=last_invoice_amount]").attr("disabled",false)

	}
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


function sumar(){
	$("#liquidez_total").val("")
  var sueldo = 0;
  var income = parseFloat(document.getElementById("income").value.replace(/\./g, '')) || 0;
  var save_amount = (parseFloat(document.getElementById("save_amount").value.replace(/\./g, ''))/12) || 0;
  var last_invoice_amount = (parseFloat(document.getElementById("last_invoice_amount").value.replace(/\./g, ''))/12) || 0;

  sueldo += save_amount + income + last_invoice_amount;
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

    $("input[name=income]").val(0)
    $("input[name=income]").attr("disabled",true)

    sumar()
	}else{
    $("input[name=income]").val("")
    $("input[name=income]").attr("disabled",false)
    sumar()
	}
});


$(".monto_formato_decimales").on({
  "keyup": function(event) {
    sumar()
  }
});

// position
// saves
// save_amount
// invoice
// last_invoice_amount
// afp

$('#registration-form').validate({
	lang: 'es',

	rules: {
		position: {
			required: true
		},
    saves:{
      required: true
    },
    save_amount:{
      required: true
    },
    invoice:{
      required: true
    },
    last_invoice_amount:{
      required: true
    },
    afp:{
      required: true
    }
	},
	messages : {
		position : {
			required:'Escriba el nombre del cargo'
		},
    saves:{
      required: 'Debe seleccionar una opcion'
    },
    save_amount:{
      required: 'Debe ingresar un monto'
    },
    invoice:{
      required: 'Debe seleccionar una opcion'
    },
    last_invoice_amount:{
      required: 'Debe ingresar un monto'
    },
    afp:{
      required: 'Debe seleccionar una opcion'
    }
	}
})
