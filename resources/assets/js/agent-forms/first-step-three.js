import $ from 'jquery';
import jQuery from 'jquery';
import 'jquery-validation';
import Inputmask from "inputmask";

(function ($) {
  loadPreviewLogo()
})(jQuery);
$("body").on("click",".close-btn a",function(e){
  	$(this).parents('.image-preview').css("background", "transparent")
	  $(this).parents('.image-preview').children(".close-btn").html('');
	  $(this).parents('.image-preview').css("z-index", "5")
})
$(function(){
  Inputmask("999-999-9999").mask($("#phone"));
  Inputmask("999 999-9999").mask($("#cell_phone"));
  Inputmask("99.999.999-[*]").mask($("#rut"));
  $.validator.addMethod("rut", function(value, element) {
    return this.optional(element) || validateRut(value);
  }, 'RUT incorrecto');
  $('input[type=file]').change(function() {
      $(this).siblings('.file-name').text($(this).prop('files')[0].name);
      $(this).parent('label').siblings('.file-show').children('img').remove();
      $(this).parent('label').siblings('.file-show').prepend('<img src="/images/icono-tilde-azul.png">')
  });
  checkInvoiceInput()
})
function loadPreviewLogo(){
  $.ajax({
    url: $("#photo_uri").val(),
    type:'GET',
    dataType:'JSON',
    error: function (response) { },
    success: function(response){

  	   if (response.logo != null) {
          console.log(response.logo.path)
  		   	$('.image-preview').css("background-image", "url("+response.logo.path+")")
  		   	$('.image-preview').css("background-size", "cover")
  		   	$('.image-preview').css("background-position", "center center")
  			  $('.image-preview').children("#image-label").css("z-index", "-1");
  			  $('.image-preview').children(".close-btn").html('<a class="delete"></a>');
  	   }else{
         $('.image-preview').html('<div class="close-btn"></div><label for="image-upload" id="image-label">Choose File</label><input type="file" name="image_logo" id="image-upload" /><input type="hidden" name="logo" value="1" />')
       }
    }
  });
}


$("input[name=invoice]").on("change",function(){
  checkInvoiceInput()
})

function checkInvoiceInput(  ){

  if ( $("input[name=invoice]:checked").val() == "0" ) {
      $("input[name=rut]").val("").attr('placeholder','Sin Factura').attr("disabled",true)
      $("input[name=name]").val("").attr('placeholder','Sin Factura').attr("disabled",true)
      $("input[name=giro]").val("").attr('placeholder','Sin Factura').attr("disabled",true)
      $("input[name=id_front]").attr("disabled",true)
      $("input[name=id_back]").attr("disabled",true)
  }
  if ( $("input[name=invoice]:checked").val() == "1" ) {
      $("input[name=rut]").attr("disabled",false)
      $("input[name=name]").attr("disabled",false)
      $("input[name=giro]").attr("disabled",false)
      $("input[name=id_front]").attr("disabled",false)
      $("input[name=id_back]").attr("disabled",false)
  }
}
$('#registration-form').validate({
    //
	lang: 'es',
	rules: {
		rut: {
			required: true,
			rut:true
		},
		invoice:{
			required: true
		},
		name:{
			required: true
		},
		giro:{
			required: true
		},
		phone:{
			required: false
		},
		cell_phone:{
			required: false
		},
		email:{
			required: true,
			email:true
		},
		website:{
			required: true
		}
	},
	messages : {
		rut: {
			required: "Escriba un numero de RUT",
			rut:"RUT Invalido"
		},
		invoice:{
			required: "Seleccione una opcion"
		},
		name:{
			required: "Razon social requerido"
		},
		giro:{
			required: "Giro requerido"
		},
		phone:{
			required: "Telefono requerido"
		},
		cell_phone:{
			required: "Celular requerido"
		},
		email:{
			required: "Correo electronico requerido",
			email:'Escriba una direccion de correo valida'
		},
		website:{
			required: "Sitio web requerido"
		}
	},
	ignore: "input[type='file']"
})

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
