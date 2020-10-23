import $ from 'jquery';
import jQuery from 'jquery';
import 'jquery-validation';

$(function(){

  toggleAvalInputs($("input[name=collateral]:checked").val())
})



$("input[name=collateral]").change( function(){

  toggleAvalInputs($(this).val())
})

$("#cancelOperation").on("click",function(e){
	$("input[name=collateral_email]").val("")
	$("input[name=firstname]").val("")
	$("input[name=lastname]").val("")
	$("input[name=email]").val("")
	$("input[name=confirm_email]").val("")
	$(".confirm-email").show()
	$("input").attr("readonly",false)
	$(".email_exist_case").css("display","none")
	$(".email_not_exist_case").css("display","block")
  $(".collateral").show()
})

function toggleAvalInputs( option ){
  if ( option == "0" ) {
    $("input[name=firstname]").val("").attr("disabled",true)
    $("input[name=lastname]").val("").attr("disabled",true)
    $("input[name=email]").val("").attr("disabled",true)
    $("input[name=confirm_email]").val("").attr("disabled",true)
    //$(".confirm-email").show()
    $("input").attr("readonly",true)
  }else if ( option == "1" ) {
    $("input[name=firstname]").attr("disabled",false)
    $("input[name=lastname]").attr("disabled",false)
    $("input[name=email]").attr("disabled",false)
    $("input[name=confirm_email]").attr("disabled",false)
    //$(".confirm-email").show()
    $("input").attr("readonly",false)
  }
}

function validateEmail(value){
	var global_response = ""
	$.ajax({
		async:false,
		cache:false,
		url:"/users/check-collateral-mail",
		type:'GET',
		dataType:'json',
		data:{"email":value}
	})
	.done(function(response){
		global_response = response
	})
	return global_response
}

$.validator.addMethod("email_auth", function(value, element) {
	var response_validate = validateEmail(value)
	var res = true
	if ( response_validate.exist == true && response_validate.data ==null ) {
		res = false
	}
	return this.optional(element) || res;
}, "");

$.validator.addMethod("email_exist", function(value, element) {

	var response_validate = validateEmail(value)
	var res = true

	if ( response_validate.exist == true && response_validate.data !== null ) {

	    if ($("input[name=collateral_email]").val() == response_validate.data.email) {

	    }else{
	  		$("input[name=collateral_email]").val(response_validate.data.email)
	  		$("input[name=firstname]").val(response_validate.data.firstname)
	  		$("input[name=lastname]").val(response_validate.data.lastname)
	  		$("input[name=email]").val(response_validate.data.email)
	  		$("input[name=confirm_email]").val(response_validate.data.email)
	  		$(".confirm-email").hide()
	  		$(".collateral").hide()
		    $(".email_exist_case").css("display","block")
		  	$(".email_not_exist_case").css("display","none")
		  	$("input").attr("readonly",true)
	    }

	}else{
		$("input[name=collateral_email]").attr("disabled",true)
	}

	return this.optional(element) || res;
}, "");

$('#registration-form').validate({
	lang: 'es',

	rules: {
		firstname: {
			required: true
		},
		lastname:{
			required:true
		},
		email:{
			required:true,
			email:true,
			email_auth:true,
			email_exist:true
		},
		confirm_email: {
			required: true,
			equalTo:'#email'
		}
	},

	messages : {
		firstname : {
			required:'Escriba el nombre del aval'
		},
		lastname:{
			required:'Escriba el apellido del aval'
		},
		email:{
			required:'Escriba el correo del aval',
			email:'Escriba una direccion de correo valida',
			email_auth:'La direccion de correo pertenece a su usuario de sesion'
		},
		confirm_email: {
			equalTo:'Las direcciones de correo no coinciden',
			required:'Escriba el correo del aval'
		}
	}
})
