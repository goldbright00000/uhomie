import intlTelInput from 'intl-tel-input';
import $ from 'jquery';
import jQuery from 'jquery';
import 'jquery-validation';
import VueTelInput from 'vue-tel-input';

/*
const inputPhone = document.getElementById("phone");
const intl = intlTelInput(inputPhone, {
    utilsScript: '/js/intTelInputUtils.js',
    initialCountry: 'cl'
});*/

$(function() {
    $('#register_user').submit(function(e) {
        let countryData = intl.getSelectedCountryData();
        let code = countryData.dialCode;

        $('#code').val(code);
    });
    $.validator.addMethod("phone_unique", function (value, element) {

        return validUniquePhone( value );
    }, "Numero de telefono actualmente en uso");
});

$('#register_user').validate({
	lang: 'es',
	rules: {
		phone: {
			required: true,
            number: true,
            rangelength: [9,14],
            phone_unique:true
		}

	},
	messages:{
		phone:{
			required:'Por favor introduzca su número de teléfono',
            number : 'Por favor introduzca sólo números',
            rangelength : 'Por favor introduzca un número de teléfono valido'
		}
	}
});

window.Vue.use(VueTelInput);

var uh = new Vue({
	el: '#app',
    mounted() {
        console.log('vue mounted');
	},
	data(){
		return {
			phone: '9',
			largo_chile: 9,
			porDefecto: '9',
			deshabilitar: true,
			mensajeCorto: 'none',
			code_phone: '',
			mensajeUnicidad: 'none'
		}
	},
	methods: {
		valorPorDefecto: function(){
			return ''
		},
		handle: function(number, objeto){
			
			this.code_phone = objeto.country.dialCode;
			if( objeto.valid ){
				this.deshabilitar = false;
				this.mensajeCorto = 'none';
				console.log(number);
				if( !this.validarUnicidad(number.replace(/\s/g, '')) ){
					this.mensajeUnicidad = 'flex';
					this.deshabilitar = true;
				} else {
					this.mensajeUnicidad = 'none';
					this.deshabilitar = false;
				}
				
			} else {
				this.mensajeCorto = 'flex';
				this.deshabilitar = true;
			}
		},
		validarUnicidad: function(phone){
			let global_response = ""
			$.ajax({
				async:false,
				cache:false,
				url:"/users/check-phone-number",
				type:'GET',
				dataType:'json',
				data:{"phone":phone}
			})
			.done(function(response){
				global_response = response.valid;
			})
			return global_response;
		}
	}
});

