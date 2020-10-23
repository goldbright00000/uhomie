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
})
checkCellar()
if($("#cellar:checked").val() == "0" || $("#cellar:checked").val() == null){
  $(".cellar_description").hide();
}

checkFurnished()
if($("#furnished:checked").val() == "0" || $("#furnished:checked").val() == null){
  $(".furnished_description").hide();
}

$('#registration-form').validate({

	lang: 'es',
	rules: {
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
    building_name:{
      required:true
    },
    level:{
      required:true
    },
    rooms:{
      required:true
    },
    meeting_room:{
      required:true
    }
	},
	messages : {
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
      required:'Debe seleccionar una opción'
    },
    garden:{
      required:'Debe seleccionar una opción'
    },
    terrace:{
      required:'Debe seleccionar una opción'
    },
    private_parking:{
      required:'Ingrese una cantidad'
    },
    public_parking:{
      required:'Debe seleccionar una opción'
    },
    cellar:{
      required:'Debe selecionar una opción'
    },
    building_name:{
      required:'Ingrese Nombre de Torre u Edificio'
    },
    level:{
      required:'Ingrese Nivel / Planta'
    },
    rooms:{
      required:'Se requiere este campo'
    },
    meeting_room:{
      required:'Se requiere este campo'
    },
    exclusions:{
      required:'Se requiere este campo'
    },
    furnished:{
      required: 'Campo requerido'
    },
    number_furnished:{
      required:'Campo requerido'
    },
    furnished_description:{
      required:'Campo requerido'
    }
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


