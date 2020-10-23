import $ from 'jquery';
import jQuery from 'jquery';
import 'jquery-validation'
import passwordValidator from 'password-validator';

$(function() {

	const schema = new passwordValidator();
	schema
		.is().min(8)
		.is().max(20)
		.has().uppercase()
		.has().digits()
		.has().not().spaces()
		.has().symbols();

	$('body').on('input', '#password-register', function() {
		let validation = schema.validate($(this).val(), { list: true });

		$('.validation-block .rule').each(function(index, rule) {
			$(rule).addClass('success');
			$(rule).children('.fa').removeClass(function (index, className) {
				return (className.match (/fa-[a-z]+/g) || []).join(' ');
			}).addClass('fa-check');
		});

		validation.forEach(rule => {
			$('.validation-block .'+rule).removeClass('success');
			$('.validation-block .'+rule).children('.fa').removeClass(function (index, className) {
				return (className.match (/fa-[a-z]+/g) || []).join(' ');
			}).addClass('fa-times')
		});
	});

	$('body').on('focusin', '#password-register', function() {
		$('.validation-block').addClass('is-active');
	});

	$('body').on('focusout', '#password-register', function() {
		$('.validation-block').removeClass('is-active');
	});

	$('#registration-form').validate({
		lang: 'es',
		rules: {
			first_name: {
				required: true
			},
			last_name: {
				required: true
			},
			email: {
				required: true,
				email:true
			},
			password : {
				required : true,
			}
		},
		messages:{
			first_name:{
				required:''
			},
			last_name:{
				required:''
			},
			email:{
				required:'',
				email:'Introduzca un correo electronico valido',
				remote:"Correo electrónico ya está en uso"
			}
		}
	});
});
function forceLower(strInput){
strInput.value=strInput.value.toLowerCase();
}
