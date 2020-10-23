import $ from 'jquery';
import jQuery from 'jquery';
import bulmaCalendar from 'bulma-calendar';
import Inputmask from "inputmask";
import 'jquery-validation';
$(function() {
  // Inputmask("999-999-9999").mask($("#cell_phone"));
  jQuery.validator.addMethod("isCero", function(value, element) {
      if ( value == 0 ) {
        return false;
      }else {
        return true;
      }
  }, 'El valor no puede ser 0');
});
$("input[name=firstname]").on("keypress",function(e){
  return specialCharsValid(e)
})
$("input[name=lastname]").on("keypress",function(e){
  return specialCharsValid(e)
})
function specialCharsValid(e){
  var key = e.keyCode || e.which;
  var tecla = String.fromCharCode(key).toLowerCase();
  var letras = " áéíóúabcdefghijklmnñopqrstuvwxyz";
  var especiales = "8-37-39-46";
  var tecla_especial = false
  for(var i in especiales){
       if(key == especiales[i]){
           tecla_especial = true;
           break;
       }
   }
   if(letras.indexOf(tecla)==-1 && !tecla_especial){
       return false;
   }
}
const min = getTomorrow()


const furnished_date = bulmaCalendar.attach(document.getElementById( 'furnished_date'), {
  dateFormat: 'DD/MM/YYYY',
  lang: 'es',
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
  minDate: min
});

furnished_date.on('select', date => {
  $('#city-modal #caja2').css('display','block')
  $('#city-modal #caja1').css('display','none')
  $('#city-modal #cajaFooter').css('display','block')
  $('#city-modal input[name=step]').val(2)
  $('#modal-title').html('Tipo de propiedad')
});
$("button.calendar-nav-previous").click(function(e){ e.preventDefault() })
$("button.calendar-nav-next").click(function(e){ e.preventDefault() })

function getTomorrow(){
  var today = new Date(new Date().getTime() + 24 * 60 * 60 * 1000) , dd = today.getDate() , mm = today.getMonth()+1 , yy = today.getFullYear().toString().substr(-2);
  if(dd<10) { dd='0'+dd } if(mm<10) { mm='0'+mm }
  return dd+'/'+mm+'/'+yy;
}

$('body').on('click', '.city-title', function(e) {
  e.preventDefault();
  $(this).parents('.modal').removeClass('is-active');
  // $('#furnished_date').click()
  furnished_date.show()
  $('#city-modal input[name=city]').val($(this).attr('city'));
  $('#city-modal #cajaFooterTwo').css('display','none')
  $('#modal-title').html('¿Cuándo te quieres mudar?')
  $('#city-modal').addClass('is-active');
});

$('body').on('click', 'input[name=change-step]', function(e) {
  e.preventDefault();

  switch ($('#city-modal input[name=step]').val()) {

    case '1':

      if ($("#form-city").valid()) {
        $('#city-modal #caja2').css('display','block')
        $('#city-modal #caja1').css('display','none')
        $('#city-modal #cajaFooter').css('display','block')
        $('#city-modal input[name=step]').val(2)
        $('#modal-title').html('Tipo de propiedad')
      }else{
        $('#city-modal input[name=step]').val(1)

      }
      break;
    case '2':

      if ($("#form-city").valid()) {
        $('#city-modal #caja2').css('display','none')
        $('#city-modal #caja3').css('display','block')
        $('#city-modal #cajaFooter').css('display','block')
        $('#city-modal input[name=step]').val(3)
        $('#modal-title').html('Tus Datos')
      }else{
        $('#city-modal input[name=step]').val(2)
      }
      break;
    case '3':

      if ($("#form-city").valid()) {
        //send form
        $.ajax({
      		async:false,
      		cache:false,
      		url:"/newsletter",
      		type:'POST',
      		dataType:'json',
      		data:$("#form-city").serialize()
      	})
      	.done(function(response){
          $('#city-modal #caja3').css('display','none')
          $('#city-modal #caja4').css('display','block')
          $('#city-modal #cajaFooter').css('display','none')
          $('#city-modal #cajaFooterTwo').css('display','block')
          $('#city-modal input[name=step]').val(4)
          $('#modal-title').html('Registrado correctamente')
          $('#form-city input[type=text]').val('')
          $('#form-city input[type=email]').val('')
          furnished_date.refresh()
        })
      }else{
        $('#city-modal input[name=step]').val(3)
      }
      break;
  }
});
$('body').on('click', 'input[name=back-step]', function(e) {
  e.preventDefault();
  switch ($('#city-modal input[name=step]').val()) {

    case '2':
      $('#modal-title').html('¿Cuándo te quieres mudar?')
      $('#city-modal #caja1').css('display','block')
      $('#city-modal #caja2').css('display','none')
      $('#city-modal #cajaFooter').css('display','none')
      furnished_date.show()
      $('#city-modal input[name=step]').val(1)
      break;
    case '3':
      $('#modal-title').html('Tipo de propiedad')
      $('#city-modal #caja2').css('display','block')
      $('#city-modal #caja3').css('display','none')
      $('#city-modal #cajaFooter').css('display','block')
      $('#city-modal input[name=step]').val(2)
      break;
  }
});

$('body').on('click', '.btn-close-modal', function() {
    $('#city-modal input[name=step]').val(1)
    $('#city-modal #caja1').css('display','block')
    $('#city-modal #caja2').css('display','none')
    $('#city-modal #caja3').css('display','none')
    $('#city-modal #caja4').css('display','none')
    $('#city-modal #cajaFooter').css('display','none')
    $('#city-modal #cajaFooterTwo').css('display','none')
    $('.modal').removeClass('is-active');
});
$('body').on('click', '.btn-register-two', function() {
    $('#city-modal input[name=step]').val(1)
    $('#city-modal #caja1').css('display','block')
    $('#city-modal #caja2').css('display','none')
    $('#city-modal #caja3').css('display','none')
    $('#city-modal #caja4').css('display','none')
    $('#city-modal #cajaFooter').css('display','none')
    $('#city-modal #cajaFooterTwo').css('display','none')
    $(this).parents('.modal').removeClass('is-active');
    $('#register-modal').addClass('is-active');
});

$(".number-format").on({
  "focus": function(event) {
    $(event.target).select();
  },
  "keyup": function(event) {
    $(event.target).val(function(index, value) {
      return value.replace(/\D/g, "");
    });
  }
});
$(".money").on({
  "focus": function(event) {
    $(event.target).select();
  },
  "keyup": function(event) {
    $(event.target).val(function(index, value) {
      return value.replace(/\D/g, "")
        .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
    });
  }
});

$("#form-city").validate({

	lang: 'es',
	rules: {
		bathrooms: {
			required: true,
      isCero: true
		},
		bedrooms: {
			required: true,
      isCero: true
		},
		price: {
			required: true,
      isCero: true
		},
		firstname: {
			required: true
		},
		lastname: {
			required: true
		},
		other_email: {
			required: true,
      email:true
		},
		cell_phone: {
			required: true
      // number:true
		}
	},

	messages : {
    bathrooms: {
			required: 'Campo requerido'
      // number:'Solo se admiten numeros'
		},
		bedrooms: {
			required: 'Campo requerido'
      // number:'Solo se admiten numeros'
		},
		price: {
			required: 'Campo requerido'
		},
		firstname: {
			required: 'Campo requerido'
		},
		lastname: {
			required: 'Campo requerido'
		},
		other_email: {
			required: 'Campo requerido',
      email:'Formato de email incorrecto'
		},
		cell_phone: {
			required: 'Campo requerido'
      // number:'Solo se admiten numeros'
		}
	}
})
