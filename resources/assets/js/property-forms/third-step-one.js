import $ from 'jquery';
import jQuery from 'jquery';

$(function() {

    $('input.check_amenities[type=checkbox]').change(function() {
        let $label = $(this).parent('label');
        if ($(this).is(':checked')) {
            $label.addClass('active');
            $label.find('.fa').removeClass('fa-plus').addClass('fa-minus');
            var amenities = []
            $("input.check_amenities:checked").each(function () { amenities.push(parseInt($(this).val())); })
            if (amenities.length >= 1 && amenities.length <= 2  ) {
              
            }else{
              alert("Debe seleccionar uno o dos tipos de arrendatario")
              $(this).prop('checked', false);
              $label.removeClass('active');
              $label.find('.fa').removeClass('fa-minus').addClass('fa-plus');
              
            }
        } else {
            $label.removeClass('active');
            $label.find('.fa').removeClass('fa-minus').addClass('fa-plus');
        }
    });

    $('input.check_work[type=checkbox]').change(function() {
        let $label = $(this).parent('label');
        if ($(this).is(':checked')) {
            $label.addClass('active');
            $label.find('.fa').removeClass('fa-plus').addClass('fa-minus');
            var amenities = []
            $("input.check_work:checked").each(function () { amenities.push(parseInt($(this).val())); })
            if (amenities.length >= 1 && amenities.length <= 10) {
              
            }else{
              alert("Debe seleccionar uno o mas tipos de arrendatario")
              $(this).prop('checked', false);
              $label.removeClass('active');
              $label.find('.fa').removeClass('fa-minus').addClass('fa-plus');
              
            }
        } else {
            $label.removeClass('active');
            $label.find('.fa').removeClass('fa-minus').addClass('fa-plus');
        }
    });

    $('input[type=checkbox]').each(function(i,item) {
        let $label = $(this).parent('label');
        if ($(this).is(':checked')) {
            $label.addClass('active');
            $label.find('.fa').removeClass('fa-plus').addClass('fa-minus');

        } else {
            $label.removeClass('active');
            $label.find('.fa').removeClass('fa-minus').addClass('fa-plus');

        }
    });
});

if($("#type").val() == "0"){
  $("#submit").on("click", function(e){
      e.preventDefault()
      var amenities = []
      $("input.check_amenities:checked").each(function () { amenities.push(parseInt($(this).val())); })
      if (amenities.length >= 1 && amenities.length <= 2  ) {
        $("form").submit();
      }else{
        alert("Debe seleccionar uno o dos tipos de arrendatario")
        return false;
      }
  });
}

if($("#type").val() == "1"){
  $("#submit").on("click", function(e){
    e.preventDefault()
       var amenities = []
       $("input.check_work:checked").each(function () { amenities.push(parseInt($(this).val())); })
       if (amenities.length >= 1 && amenities.length <= 10) {
         $("form").submit();
       }else{
         alert("Debe seleccionar uno o mas tipos de arrendatario")
         return false;
       }
  });
}



$(".btn-close").click(function(){
	$('.modal-info').removeClass('is-active');
});

if($("#type").val() == "1"){
	//alert("no visit")
	$(".form-resident").remove()
  $(".form-office").css("display","block")
}

if($("#type").val() == "0"){
  //alert("visit")
  $(".form-resident").css("display","block")
  $(".form-office").remove()
}
