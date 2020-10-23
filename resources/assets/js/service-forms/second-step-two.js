import $ from 'jquery';
import jQuery from 'jquery';
import 'jquery-validation';

$(function(){
checkSii()
})

$("input[name=personal_publish]").change(function(){
	checkSii()
})
function checkSii(){

	if($("input[name=personal_publish]:checked").val() == "1"){
		$("input#particular").attr("disabled",false)
		$("input#empresa").attr("disabled",true)
		$(".row-sii").css("display","none")
		$("input[name=sii]").attr("disabled",true)
	}
	if($("input[name=personal_publish]:checked").val() == "0"){
		$("input#empresa").attr("disabled",false)
		$("input#particular").attr("disabled",true)
		$(".row-sii").css("display","block")
		$("input[name=sii]").attr("disabled",false)
	}
}

$('#registration-form').validate({
    //
	lang: 'es',
	rules: {

	},
	messages : {

	}
})
