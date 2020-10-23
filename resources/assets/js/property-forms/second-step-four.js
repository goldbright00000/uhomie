import $ from 'jquery';
import jQuery from 'jquery';
import 'jquery-validation';
$(function(){
  $(".numbers").on("keypress",function(e){

    var keynum = window.event ? window.event.keyCode : e.which;
    if ((keynum == 8) || (keynum == 46))
    return true;
    return /\d/.test(String.fromCharCode(keynum));
  })

  jQuery.validator.addMethod("isCero", function(value, element) {
      if ( value == 0 ) {
        return false;
      }else {
        return true;
      }
  }, 'La cantidad minima es 1');
  jQuery.validator.addMethod("isAmount", function(value, element) {
    if ( value < 999 ) {
      return false;
    }else {
      return true;
    }
  }, 'El monto es muy bajo');
})

checkCellar()
if($("#cellar:checked").val() == "0" || $("#cellar:checked").val() == null){
  $(".cellar_description").hide();
}

if($("#furnished:checked").val() == "0" || $("#furnished:checked").val() == null){
  $(".furnished_description").hide();
}


$('#registration-form').validate({

	lang: 'es',
	rules: {
    rent:{
      required:true,
      //isAmount:true
    },
    rent_up : {
      required: true,
    },
		bedrooms:{
      required:true,
      isCero:true
    },
    bathrooms:{
      required:true,
      isCero:true
    },
    meters:{
      required:true,
      isCero:true
    },
    pool:{
      required:true
    },
    garden:{
      required:true
    },
    terrace:{
      required:true
    },
    private_parking:{
      required:true
    },
    public_parking:{
      required:true
    },
    cellar:{
      required: true
    },
    furnished:{
      required: true
    }
	},
	messages : {
    rent:{
      required:'Debe introducir un monto',
      minlength: 'El monto es muy pequeño',
    },
    rent_up:{
      required:'Debe introducir un monto',
      minlength: 'El monto es muy pequeño',
    },
    bedrooms:{
      required:'Ingrese una cantidad',
      min: 'La cantidad minima es 1'
    },
    bathrooms:{
      required:'Ingrese una cantidad',
      min: 'La cantidad minima es 1'
    },
    meters:{
      required:'Ingrese una cantidad',
      min: 'La cantidad minima es 1'
    },
    pool:{
      required:'Debe seleccionar una opcion'
    },
    garden:{
      required:'Debe seleccionar una opcion'
    },
    terrace:{
      required:'Debe seleccionar una opcion'
    },
    private_parking:{
      required:'Ingrese una cantidad',
      min: 'La cantidad minima es 1'
    },
    public_parking:{
      required:'Debe seleccionar una opcion'
    },
    cellar:{
      required: 'Campo requerido'
    },
    furnished:{
      required: 'Campo requerido'
    },
    lot_number:{
      required: 'Campo requerido'
    },
    building_name: {
      required: 'Campo requerido'
    },
    level: {
      required: 'Campo requerido'
    },
    meeting_room: {
      required: 'Campo requerido'
    },
    rooms: {
      required: 'Campo requerido'
    },
	}
})

$("#cellar").change(function(){
	checkCellar()
})

function checkCellar(){

	if($("#cellar:checked").val() == "0"){
    $(".cellar_description").hide();
	}
	if($("#cellar:checked").val() == "1"){
    $(".cellar_description").show();
	}
}

if($("#property_type").val() == "2"){
	//alert("no visit")
  $(".form-resident").remove()
  $(".form-office").remove()
  $(".form-parking").remove()
  $(".form-ground").remove()
  $(".form-cellar").remove()
  $(".form-room").css("display","block")
}

if($("#property_type").val() == "1"){
	//alert("no visit")
  $(".form-resident").remove()
  $(".form-room").remove()
  $(".form-parking").remove()
  $(".form-ground").remove()
  $(".form-cellar").remove()
  $(".form-office").css("display","block")
}

if($("#property_type").val() == "0"){
  //alert("visit")
  $(".form-office").remove()
  $(".form-room").remove()
  $(".form-parking").remove()
  $(".form-ground").remove()
  $(".form-cellar").remove()
  $(".form-resident").css("display","block")
}

if($("#property_type").val() == "3"){
  //alert("visit")
  $(".form-office").remove()
  $(".form-room").remove()
  $(".form-resident").remove()
  $(".form-ground").remove()
  $(".form-cellar").remove()
  $(".form-parking").css("display","block")
}

if($("#property_type").val() == "4"){
	//alert("no visit")
  $(".form-resident").remove()
  $(".form-office").remove()
  $(".form-parking").remove()
  $(".form-room").remove()
  $(".form-cellar").remove()
  $(".form-ground").css("display","block")
} 

if($("#property_type").val() == "5"){
	//alert("no visit")
  $(".form-resident").remove()
  $(".form-office").remove()
  $(".form-parking").remove()
  $(".form-room").remove()
  $(".form-ground").remove()
  $(".form-cellar").css("display","block")
}

if($("#property_type_stay").val() == "LONG_STAY"){
  $(".long_stay").show()
} else {
  $(".long_stay").remove()
}

if($("#property_type_stay").val() == "LONG_STAY"){
  $(".data-long-stay").css("display","block")
} else {
  $(".data-long-stay").remove()
}

$(".monto_formato_decimales").on({
  "focus": function(event) {
    $(event.target).select();
  },
  "keyup": function(event) {
    $(event.target).val(function(index, value) {
      return value.replace(/\D/g, "")
        // .replace(/([0-9])([0-9]{2})$/, '$1.$2')
        .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
    });
  }
});



$("#furnished").change(function(){
	checkFurnished()
})

function checkFurnished(){
  if($("#furnished:checked").val() == "0"){
    $(".furnished_description").hide();
	}
	if($("#furnished:checked").val() == "1"){
    $(".furnished_description").show();
  }
}
