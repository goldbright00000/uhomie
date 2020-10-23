import $ from 'jquery';
import jQuery from 'jquery';
import 'jquery-validation';
import 'select2';
import 'select2/dist/css/select2.css';

$(function(){
  togglePersonalAddressInput( $("input[name=personal_address]:checked").val() )
})

$("input[name=personal_address]").on("change",function(){
  togglePersonalAddressInput($(this).val())
})


function togglePersonalAddressInput( option ){
  if ( option == "1" ) {

  	$.ajax({
  		async:false,
  		cache:false,
  		url:"/users/get-data-auth",
  		type:'GET',
  		dataType:'json'
  	})
  	.done(function(response){
      $('select#city').val(null).trigger('change.select2');
      $('select#city').val(response.data.city_id).trigger('change.select2');

      $("select[name=city]").attr("disabled",true)

      $("input[name=address]").val(response.data.address).attr("disabled",true)
      $("input[name=address_details]").val(response.data.address_details).attr("disabled",true)
      $("input[name=latitude]").val(response.data.latitude).attr("disabled",true)
      $("input[name=longitude]").val(response.data.longitude).attr("disabled",true)
      $("input[type=submit]").attr("disabled",false)
  	})

  }else if ( option == "0" ) {
    $("select[name=city]").val("").attr("disabled",false)
    $("input[name=address]").val("").attr("disabled",false)
    $("input[name=address_details]").val("").attr("disabled",false)
    $("input[name=latitude]").val("").attr("disabled",false)
    $("input[name=longitude]").val("").attr("disabled",false)
    $("input[type=submit]").attr("disabled",true)
  }
}
