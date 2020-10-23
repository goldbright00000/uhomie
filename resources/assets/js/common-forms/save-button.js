import $ from 'jquery';
import jQuery from 'jquery';

$("#save-button-icon").on("click",function(e){
	// Get all the inputs into an array...
	var $inputs = $('#registration-form :input');

	// An array of just the ids...
	var input = {};

	$inputs.each(function (index)
	{
		input[$(this).attr('name')] = $(this).val();
	});
	
	$.ajax({
	  url:"",
	  type:'POST',
	  dataType:'JSON',
	  data:input,

	  error: function (response) {
		  $("#modal-title").html("");
		  $("#modal-title").append("Ups.");
		  $("#modal-text").html("");
		  $("#modal-text").append("Ha ocurrido un error guardando los cambios, intenta nuevamente.");
	  },
	   success: function(response){
		if (response.success==true) {
		  $("#modal-title").html("");
		  $("#modal-title").append("Muy bien,");
		  $("#modal-text").html("");
		  $("#modal-text").append("Tus cambios se han guardado correctamente, sientete libre de navegar");
		}else{
		  $("#modal-title").html("");
		  $("#modal-title").append("Ups..");
		  $("#modal-text").html("");
		  $("#modal-text").append("Ha ocurrido un error guardando los cambios, intenta nuevamente.");
		}
	  }
  });
  $("#save-modal").show();
})
