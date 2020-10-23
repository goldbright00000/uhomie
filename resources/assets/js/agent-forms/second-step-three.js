import $ from 'jquery';
import jQuery from 'jquery';
import Slick from 'slick-carousel';
import 'select2';
import 'select2/dist/css/select2.css';
var tips = [
	"Toma foto con luz natural, abre las ventanas y persianas.",
	"Toma fotos de al menos 1024 x 683 px, cuanto más grande sean las fotos mejor.",
	"Saca fotos con orientación horizontal: cuando la orientación es vertical, siempre es más difícil apreciar cómo es el espacio.",
	"Destaca las zonas y espacios más relevantes de tu Propiedad. Tu Jardin, Tu espaciosa habitación principal.",
	"Evita tomar fotos de tus espacios con objetos, mascotas u otros aspectos que luzcan desordenados.",
	"Cuanto más fotos agregas mejor. Al menos 1 por cada espacio de tu propiedad. Muestra todos las zonas, cada detalle importa"
]

$("#btn-next-tip").click(function(){

	switch ($('input#current-step').val()) {

	  case '0':
	  	$('#modal-text').text("");
	  	$('#modal-text').text(tips[1]);
	  	$('input#current-step').val(1)
	    break;
	  case '1':
	  	$('#modal-text').text("");
	  	$('#modal-text').text(tips[2]);
	  	$('input#current-step').val(2)
	    break;
	  case '2':
	    $('#modal-text').text("");
	    $('#modal-text').text(tips[3]);
	    $('input#current-step').val(3)
	    break;
	  case '3':
	    $('#modal-text').text("");
	    $('#modal-text').text(tips[4]);
	    $('input#current-step').val(4)
	    break;
	  case '4':
	    $('#modal-text').text("");
	    $('#modal-text').text(tips[5]);
	    $('input#current-step').val(5)
	    break;
	  case '5':
	    $('#modal-text').text("");
	    $('input#current-step').val(0)
	    $('.modal-tip').removeClass('is-active');
	    break;
	}
})
$("#btn-back-tip").click(function(){

	switch ($('input#current-step').val()) {

	  case '1':
	  	$('#modal-text').text("");
	  	$('#modal-text').text(tips[0]);
	  	$('input#current-step').val(0)
	    break;
	  case '2':
	  	$('#modal-text').text("");
	  	$('#modal-text').text(tips[1]);
	  	$('input#current-step').val(1)
	    break;
	  case '3':
	    $('#modal-text').text("");
	    $('#modal-text').text(tips[2]);
	    $('input#current-step').val(2)
	    break;
	  case '4':
	    $('#modal-text').text("");
	    $('#modal-text').text(tips[3]);
	    $('input#current-step').val(3)
	  break;
	  case '5':
	    $('#modal-text').text("");
	    $('#modal-text').text(tips[4]);
	    $('input#current-step').val(4)
	  break;
	}
})

// $(".btn-close").click(function(){
// 	$('.modal-tip').removeClass('is-active');
// })

$(function(){
	
	$('.modal-tip').addClass('is-active');
	$('#modal-text').text("");
	$('#modal-text').text(tips[0]);
	$('input#current-step').val(0)
	
})

$.extend({
  uploadPreview : function (options) {

	// Options + Defaults
	var settings = $.extend({
	  input_field: ".image-input",
	  preview_box: ".image-preview",
	  label_field: ".image-label",
	  image_name: ".image-name",
	  image_id: ".image-id",
	  is_cover : ".cover",
	  entity_id: "#entity_id",
	  label_default: "Elija una foto",
	  label_selected: "Cambiar foto",
	  no_label: false,
	  success_callback : null,
	}, options);

	// Check if FileReader is available
	if (window.File && window.FileList && window.FileReader) {
	  if (typeof($(settings.input_field)) !== 'undefined' && $(settings.input_field) !== null) {
		$(settings.input_field).change(function() {
		  var files = this.files;

		  if (files.length > 0 && files.length < 2) {
			var file = files[0];
			var reader = new FileReader();

			// Load file
			reader.addEventListener("load",function(event) {
			  var loadedFile = event.target;

			  // Check format
			  if (file.type.match('image')) {
				// Image
				var Upload = function (file) {
				    this.file = file;
				};

				Upload.prototype.getType = function() {
				    return this.file.type;
				};
				Upload.prototype.getSize = function() {
				    return this.file.size;
				};
				Upload.prototype.getName = function() {
				    return this.file.name;
				};
				Upload.prototype.doUpload = function () {
				    var that = this;
				    var formData = new FormData();
					var cover = $(settings.input_field).parent(settings.preview_box).children(settings.is_cover);
					var photo_name = $(settings.input_field).parent(settings.preview_box).children(settings.image_name);
					var photo_id = $(settings.input_field).parent(settings.preview_box).children(settings.image_id);
					if (cover.val() == 0 ) {
						var space_id = $(settings.input_field).parent(settings.preview_box).next(".select-spaces").children("select");
					}else{
						var space_id = $(settings.input_field).parent(settings.preview_box).next(".select-spaces-cover").children("select");
					}
				    // add assoc key values, this will be posts values
				    formData.append($(settings.input_field).attr('name'), this.file, this.getName());
					formData.append('photo_name', photo_name.val());
					formData.append('cover', cover.val());
					formData.append('photo_id', photo_id.val());

						if ( space_id.val() == undefined ) {
							formData.append('space_id', 0);
						}else{
							formData.append('space_id', space_id.val());

						}


				    $.ajax({
				        type: "POST",
				        url: $("#photo-save").val(),
						headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						},
				        xhr: function () {
				            var myXhr = $.ajaxSettings.xhr();
				            if (myXhr.upload) {
				                myXhr.upload.addEventListener('progress', that.progressHandling, false);
				            }
				            return myXhr;
				        },
						error: function (response) {
	  					  console.log('error');
		  				},
	  				    success: function(response){
										$(settings.input_field).parent(settings.preview_box).css("background-image", "url("+response.path+")");
										$(settings.input_field).parent(settings.preview_box).css("background-size", "cover");
										$(settings.input_field).parent(settings.preview_box).css("background-position", "center center");
										$(settings.input_field).parent(settings.preview_box).children(settings.image_id).val(response.photo_id);
										$(settings.input_field).parent(settings.preview_box).children(settings.label_field).css("z-index","-1");
										$(settings.input_field).parent(settings.preview_box).children(".close-btn").html('<a class="delete"></a>');
										$(settings.input_field).parent(settings.preview_box).attr("load",1)

										if ( cover.val() == 0 ) {
											$(settings.input_field).parent(settings.preview_box).next(".select-spaces").attr("photo_id",response.photo_id)
											$(settings.input_field).parent(settings.preview_box).next(".select-spaces").attr("space_id",response.space_id)

										}else{

											$('.main-image-preview').next(".select-spaces-cover").attr("photo_id",response.photo_id)
											$('.main-image-preview').next(".select-spaces-cover").attr("space_id",response.space_id)
											$.ajax({
													url: $("#spaces_uri").val(),
													type:'GET',
													dataType:'JSON',
													success: function(response_spaces){
														$('.main-image-preview').next(".select-spaces-cover").html("")
														var arrSpaces = response_spaces.spaces
														var options_content = $("")
														options_content = '<select style="width:100%;" class="list-spaces" name="space" id="list-spaces-cover"  >'
														options_content += '<option value="" disabled selected>Que espacio representa?</option>'
														for (var j = 0; j < arrSpaces.length; j++) {
															 	if ( arrSpaces[j].id  == response.space_id ) {
																 	options_content += '<option selected="selected" value="' + arrSpaces[j].id + '">' + arrSpaces[j].name + '</option>'
																}else{
																	 	options_content += '<option value="' + arrSpaces[j].id + '">' + arrSpaces[j].name + '</option>'
																}
														}
														options_content += '</select>'
														$('.main-image-preview').parents('.cover-item-preview').children(".select-spaces-cover").append(options_content)
														$('#list-spaces-cover').select2({ minimumResultsForSearch: -1 })
													}
											 });
										}

		  				},
				        async: false,
				        data: formData,
				        cache: false,
				        contentType: false,
				        processData: false,
				        timeout: 60000
				    });
				};
				var upload = new Upload(file);
				upload.doUpload();
			  } else {
				alert("This file type is not supported yet.");
			  }
			});
			if (settings.no_label == false) {
			  // Change label
			  $(settings.input_field).closest(settings.label_field).html(settings.label_selected);

			}
			// Read the file
			reader.readAsDataURL(file);
			// Success callback function call
			if(settings.success_callback) {
			  settings.success_callback();

			}
		  } else {
			if (settings.no_label == false) {
			  // Change label
			  $(settings.input_field).closest(settings.label_field).html(settings.label_default);
			}
			// Clear background
			//$(settings.input_field).parent(settings.preview_box).css("background-image", "none");
		  }
		});
	  }
	} else {
	  alert("You need a browser with file reader support, to use this form properly.");
	  return false;
	}
  }
});
(function ($) {

$.ajax({
  url: $("#photo_uri").val(),
  type:'GET',
  dataType:'JSON',

  error: function (response) {

  },
   success: function(response){

	   if (response.cover != null) {
		   	$('.main-image-preview').css("background-image", "url("+response.cover.path+")")
		   	$('.main-image-preview').css("background-size", "cover")
		   	$('.main-image-preview').css("background-position", "center center")
		   	$('.main-image-preview').children(".image-id").val(response.cover.id)
				$('.main-image-preview').children(".image-label").css("z-index","-1");
				$('.main-image-preview').children(".close-btn").html('<a class="delete"></a>')
				$('.main-image-preview').next(".select-spaces-cover").attr("space_id",response.cover.space_id)
				$('.main-image-preview').next(".select-spaces-cover").attr("photo_id",response.cover.id)
				$.ajax({
					url: $("#spaces_uri").val(),
					type:'GET',
					dataType:'JSON',
					success: function(response_spaces){
						var arrSpaces = response_spaces.spaces
							var options_content = $("")
							options_content = '<select style="width:100%;" class="list-spaces" name="space" id="list-spaces-cover"  >'
							options_content += '<option value="" disabled selected>Que espacio representa?</option>'
							for (var j = 0; j < arrSpaces.length; j++) {
							 	if ( arrSpaces[j].id  == response.cover.space_id ) {
								 	options_content += '<option selected="selected" value="' + arrSpaces[j].id + '">' + arrSpaces[j].name + '</option>'
								}else{
								 	options_content += '<option value="' + arrSpaces[j].id + '">' + arrSpaces[j].name + '</option>'
								}
							}
							options_content += '</select>'
							$('.main-image-preview').parents('.cover-item-preview').children(".select-spaces-cover").append(options_content)
							$('#list-spaces-cover').select2({ minimumResultsForSearch: -1 })
					}
				});
	   }

	   if (response.photos != null) {
			if ($(".other-photo-main .image-preview").length == 0) {
				var cant_photos = parseInt($("#photo_limit").val()) - parseInt($(".main-image-preview").length)
		 		for (var i = 0; i < cant_photos; i++) {
					loadPreview(i)
		 		}
			}
			var cont = 1
			$.each(response.photos,function(i,itemPhoto){
				 $('.other-photo-main .image-preview[load=0]').each(function(j,item){
					 if($(item).attr("photo_number")==i){
						$(item).css("background-image", "url("+itemPhoto.path+")")
						$(item).css("background-size", "cover")
						$(item).css("background-position", "center center")
						$(item).children(".image-id").val(itemPhoto.id)
						$(item).next(".select-spaces").attr("space_id",itemPhoto.space_id)
						$(item).next(".select-spaces").attr("photo_id",itemPhoto.id)
						$(item).children(".image-label").css("z-index","-1")
						$(item).children(".close-btn").html('<a class="delete"></a>')
						$(item).attr('load',1)

					 }
				})
			})

			$('.image-upload').each(function(index, el) {
				$.uploadPreview({
					input_field: '#'+$(el).attr('id'),   // Default: .image-upload
					preview_box: ".image-preview",  // Default: .image-preview
					label_field: ".image-label",    // Default: .image-label
					image_name: ".image-name",
					image_id: ".image-id",
					is_cover : ".cover",
					entity_id: "#entity_id",
					label_default: "Elija una Foto",   // Default: Choose File
					label_selected: "Cambiar foto",  // Default: Change File
					no_label: false                 // Default: false
				});
			});
			$.ajax({
				url: $("#spaces_uri").val(),
				type:'GET',
				dataType:'JSON',
				success: function(response){
					var arrSpaces = response.spaces
					$(".other-photo-main .select-spaces").each(function(i,item){

						var options_content = $("")
						options_content = '<select style="width:100%;" class="list-spaces" name="space" id="list-spaces-' + i + '" list="' + i + '" >'
						options_content += '<option value="" disabled selected>Que espacio representa?</option>'
						for (var j = 0; j < arrSpaces.length; j++) {
						 	if ( arrSpaces[j].id  == $(item).attr("space_id") ) {
							 	options_content += '<option selected="selected" value="' + arrSpaces[j].id + '">' + arrSpaces[j].name + '</option>'
							}else{
							 	options_content += '<option value="' + arrSpaces[j].id + '">' + arrSpaces[j].name + '</option>'
							}
						}
						options_content += '</select>'
						$(item).append(options_content)
						$('#list-spaces-' + i).select2({ minimumResultsForSearch: -1 })

					})
				}
			});

	   }
  }
});
})(jQuery);

$("body").on("change",".other-photo-main .item-preview .select-spaces select",function(){

	if ( $(this).parents(".select-spaces").attr("photo_id") !== undefined ) {
		var photo_id = $(this).parents(".select-spaces").attr("photo_id")
		var space_id = $(this).val()
		var data = {
			"space_id" : space_id,
			"photo_id" : photo_id
		}
		$.ajax({
			url: $("#change_space_uri").val(),
			type:'GET',
			dataType:'JSON',
			data:data,
			success: function(response){

				$(this).parents(".select-spaces").attr("photo_id",response.photo_id)
			}
		});
	}
})

$("body").on("change",".cover-item-preview .select-spaces-cover select",function(){

	if ( $(this).parents(".select-spaces-cover").attr("photo_id") !== undefined ) {
		var photo_id = $(this).parents(".select-spaces-cover").attr("photo_id")
		var space_id = $(this).val()
		var data = {
			"space_id" : space_id,
			"photo_id" : photo_id
		}
		$.ajax({
			url: $("#change_space_uri").val(),
			type:'GET',
			dataType:'JSON',
			data:data,
			success: function(response){

				$(this).parents(".select-spaces-cover").attr("photo_id",response.photo_id)
			}
		});
	}
})


$("body").on("click",".close-btn a",function(e){
		var el = $(this)
		var photo_id = $(this).parents(".image-preview").children(".image-id").val()
		var data = {
			"photo_id" : photo_id
		}

		$.ajax({
			url: $("#delete_photo_uri").val(),
			type:'POST',
			dataType:'JSON',
			data:data,
			headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						},
			success: function(response){
				if (response.operation == true) {
					el.parents('.image-preview').css("background", "transparent")
					el.parents('.image-preview').children(".close-btn").html('');
					el.parents('.image-preview').children("label").css("z-index", "5")
				}
			}
		});
})

function loadPreview(cant_items){

	$('.other-photo-main').append(
		'<div class="column is-one-third item-preview" >'+
			'<div class="image-preview" load="0" photo_number="'+cant_items+'" >'+
				'<div class="close-btn"></div>'+
				'<label for="image-upload" class="image-label">Elija una Foto</label>'+
				'<input type="file"  name="image' + cant_items + '" id="image-upload-' + cant_items + '" class="image-upload" accept="image/*" />'+
				'<input type="hidden" class="image-id">'+
				'<input type="hidden" class="cover" value="0">'+
				'<input type="hidden" class="image-name" value="other_property_image">'+
			'</div>'+
			'<div class="select-spaces" space_id="">'+
			'</div>'+
		'</div>'
	);
	$.uploadPreview({
		input_field: "#image-upload-" + cant_items,   // Default: .image-upload
		preview_box: ".image-preview",  // Default: .image-preview
		label_field: ".image-label",    // Default: .image-label
		image_name: ".image-name",
		image_id: ".image-id",
		is_cover : ".cover",
		entity_id: "#entity_id",
		label_default: "Elija una Foto",   // Default: Choose File
		label_selected: "Elija una Foto",  // Default: Change File
		no_label: false                 // Default: false
	});
}
