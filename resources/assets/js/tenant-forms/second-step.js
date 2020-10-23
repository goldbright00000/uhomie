import $ from 'jquery';
import jQuery from 'jquery';
import 'jquery-validation';

$('#registration-form').validate({
	lang: 'es',

	rules: {
		employment_type: {
			required: true
		}
	},

	messages : {
		employment_type : {
			required:'Se requiere que escoja una opción'
		},
	}
})