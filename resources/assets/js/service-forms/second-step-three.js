import $ from 'jquery';
import jQuery from 'jquery';
import 'jquery-validation';
import 'select2';
import 'select2/dist/css/select2.css';

$(function(){
  localStorage.clear()
  var primaryServicesArray = [ ]
  var secondaryServicesArray = [ ]
  var secondary_services_datalist = []
  var secondaryServicesSelected = 0
  var primaryServicesSelected = 0
  $.ajax({
    async:false,
    cache:false,
    url:$("#uri_get_services_lists").val(),
    type:'GET',
    dataType:'json'
  }).done(function(response){
      for (var j = 0; j < response.all_primary_services.length; j++) {
        primaryServicesArray.push( { "id" : response.all_primary_services[j].id , "name" : response.all_primary_services[j].name } )
      }
      for (var k = 0; k < response.all_secondary_services.length; k++) {
        secondaryServicesArray.push( { "id" : response.all_secondary_services[k].id, "name" : response.all_secondary_services[k].name , "service_type_id" : response.all_secondary_services[k].service_type_id } )
      }
      //cargar primarios al usuario
      $(".service_type").each(function(i,item){
        for (var z = 0; z < response.primary_services.length; z++) {
          if ( $(item).attr("service_type_id") == response.primary_services[z].id ) {
            primaryServicesSelected++
            //eliminar el item del array
            for (var x = 0; x < primaryServicesArray.length; x++) { if (primaryServicesArray[x].id == response.primary_services[z].id) { primaryServicesArray.splice(x,1); } }
            $(item).css("display","block")
            $(item).children(".tags-services").children(".secondary-service").children(".checkbox").children("input[type=checkbox]").each(function(num,tag){
              for (var y = 0; y < response.secondary_services.length; y++) {
                for (var u = 0; u < secondaryServicesArray.length; u++) {
                  if (secondaryServicesArray[u].id == response.secondary_services[y].id) { secondaryServicesArray.splice(u,1); }
                }
              }
            })
          }
        }
      })
      $(".secondary_services_list").each(function(i,item){
        $(item).html("")
        for (var j = 0; j < secondaryServicesArray.length; j++) {
          if ( secondaryServicesArray[j].service_type_id == $(item).parents(".service_type").attr("service_type_id") ) {
            $(item).append('<option value="' + secondaryServicesArray[j].name + '" >' + secondaryServicesArray[j].name + '</option>')
            $(item).parents(".service_type").children(".tags-services").children(".secondary-service").children(".checkbox").children(".check_services").each(function(num,check){
              for (var k = 0; k < response.secondary_services.length; k++) {
                if ( $(check).val() == response.secondary_services[k].id ) {
                   
                  $(check).parents(".secondary-service").css("display","block")
                  $(check).prop('checked',true)

                }
              }
            })
          }
        }
      })
      
      $("input.check_services").each(function(i,item){
        if ($(item).is(':checked')) {
          secondaryServicesSelected++
        }
      })      

      localStorage.setItem("primaryServicesArray",JSON.stringify(primaryServicesArray))
      localStorage.setItem("secondaryServicesArray",JSON.stringify(secondaryServicesArray))
      localStorage.setItem("primaryServicesSelected",primaryServicesSelected)
      localStorage.setItem("secondaryServicesSelected",secondaryServicesSelected)
      
      loadDatalist( $(".service_type_datalist") , primaryServicesArray )
  })

  $("h1.form-title").text('Servicios disponibles : Principales (' + primaryServicesSelected + '/' + $("#main_services_limit").val() + ') | Secundarios (' + secondaryServicesSelected + '/' + $("#secondary_services_limit").val() + ')')
  
  $("select").select2()
})

$("#services_type_input").on('input', function () {
    var val = this.value;
    var primaryServicesArray = JSON.parse(localStorage.getItem("primaryServicesArray"))
    var primaryServicesSelected = localStorage.getItem("primaryServicesSelected")
    var secondaryServicesSelected = localStorage.getItem("secondaryServicesSelected")
    if($('#services_type option').filter(function(){
        return this.value.toUpperCase() === val.toUpperCase();
    }).length) {
      $('div.service_type').each(function(i,item){
        if ( $(item).attr('service_type_name') == val ) {
          if ( primaryServicesSelected < $("#main_services_limit").val() ) {
            $(item).css("display","block")
            for (var x = 0; x < primaryServicesArray.length; x++) { if (primaryServicesArray[x].name == val) { primaryServicesArray.splice(x,1); } }
            localStorage.setItem("primaryServicesArray",JSON.stringify(primaryServicesArray))
            primaryServicesSelected++
          }else{
            alert("exedio el limite de servicios principales")
          }
        }
      })
      $(this).val("")
      localStorage.setItem("primaryServicesSelected",primaryServicesSelected)
      loadDatalist( $(".service_type_datalist") , primaryServicesArray )
      $("h1.form-title").text('Servicios disponibles : Principales (' + primaryServicesSelected + '/' + $("#main_services_limit").val() + ') | Secundarios (' + secondaryServicesSelected + '/' + $("#secondary_services_limit").val() + ')')
    }
});

$(".datalist_services_list").on('input', function () {
    var val = this.value;
    var secondaryServicesArray = JSON.parse(localStorage.getItem("secondaryServicesArray"))
    var secondaryServicesSelected = localStorage.getItem("secondaryServicesSelected")
    var primaryServicesSelected = localStorage.getItem("primaryServicesSelected")
    $(this).parents(".service_type").children(".tags-services").children(".secondary-service").children(".checkbox").children(".check_services").each(function(i,item){
      if ( $(item).attr("service_name") == val ) {
        if ( secondaryServicesSelected < $("#secondary_services_limit").val() ) {
          for (var x = 0; x < secondaryServicesArray.length; x++) { if (secondaryServicesArray[x].name == val) { secondaryServicesArray.splice(x,1); } }
          localStorage.setItem("secondaryServicesArray",JSON.stringify(secondaryServicesArray))
          $(item).parents(".secondary-service").css("display","block")
          $(item).prop('checked',true)
          
        }else{
          alert("exedio el limite de servicios secundarios")
        }
      }
    })
    $(".secondary_services_list").each(function(i,item){
      $(item).html("")
      var secondaryServicesArray = JSON.parse(localStorage.getItem("secondaryServicesArray"))
      for (var j = 0; j < secondaryServicesArray.length; j++) {
        if ( secondaryServicesArray[j].service_type_id == $(item).parents(".service_type").attr("service_type_id") ) {
          $(item).append('<option value="' + secondaryServicesArray[j].name + '" >' + secondaryServicesArray[j].name + '</option>')         
        }
      }
    })
    var secondary_selected = 0
    $("input.check_services").each(function(i,item){
      if ($(item).is(':checked')) {
        secondary_selected++
      }
    })
    secondaryServicesSelected = secondary_selected
    localStorage.setItem("secondaryServicesSelected",secondaryServicesSelected)
    $(this).val("")
    $("h1.form-title").text('Servicios disponibles : Principales (' + primaryServicesSelected + '/' + $("#main_services_limit").val() + ') | Secundarios (' + secondaryServicesSelected + '/' + $("#secondary_services_limit").val() + ')')
});

$('input.check_services').change(function() {
    var secondaryServicesArray = JSON.parse(localStorage.getItem("secondaryServicesArray"))
    var secondaryServicesSelected = localStorage.getItem("secondaryServicesSelected")
    var primaryServicesSelected = localStorage.getItem("primaryServicesSelected")
    if (!$(this).is(':checked')) {
      secondaryServicesArray.push({ "id" : $(this).val(), "name" : $(this).attr('service_name') , "service_type_id" : $(this).parents(".service_type").attr("service_type_id") })
      localStorage.setItem("secondaryServicesArray",JSON.stringify(secondaryServicesArray))
      $(this).prop("checked",false)
      $(this).parents(".secondary-service").css("display","none")
      secondaryServicesSelected = secondaryServicesSelected - 1 
      localStorage.setItem("secondaryServicesSelected",secondaryServicesSelected)
    }
    $(".secondary_services_list").each(function(i,item){
      $(item).html("")
      var secondaryServicesArray = JSON.parse(localStorage.getItem("secondaryServicesArray"))
      for (var j = 0; j < secondaryServicesArray.length; j++) {
        if ( secondaryServicesArray[j].service_type_id == $(item).parents(".service_type").attr("service_type_id") ) {
          $(item).append('<option value="' + secondaryServicesArray[j].name + '" >' + secondaryServicesArray[j].name + '</option>')         
        }
      }
    })
    $("h1.form-title").text('Servicios disponibles : Principales (' + primaryServicesSelected + '/' + $("#main_services_limit").val() + ') | Secundarios (' + secondaryServicesSelected + '/' + $("#secondary_services_limit").val() + ')')

});

$(".close_main_service").on("click",function(e){

  var primaryServicesArray = JSON.parse(localStorage.getItem("primaryServicesArray"))
  var primaryServicesSelected = localStorage.getItem("primaryServicesSelected")
  var secondaryServicesArray = JSON.parse(localStorage.getItem("secondaryServicesArray"))
  var secondaryServicesSelected = localStorage.getItem("secondaryServicesSelected")
  var service_type_id = $(this).parents(".service_type").attr("service_type_id")
  primaryServicesArray.push({ "id" : $(this).parents(".service_type").attr("service_type_id"), "name" : $(this).parents(".service_type").attr('service_type_name') })
  primaryServicesSelected = primaryServicesSelected - 1

  $(".check_services").each(function(i,item){
    console.log($(item))
    if ( $(item).parents(".service_type").attr("service_type_id") == service_type_id ) {
      if ( $(item).prop('checked') ) {
        secondaryServicesArray.push({ "id" : $(item).val(), "name" : $(item).attr('service_name') , "service_type_id" : $(item).parents(".service_type").attr("service_type_id") })
        localStorage.setItem("secondaryServicesArray",JSON.stringify(secondaryServicesArray))
        $(item).prop("checked",false)
        $(item).parents(".secondary-service").css("display","none")
        secondaryServicesSelected = secondaryServicesSelected - 1 
        localStorage.setItem("secondaryServicesSelected",secondaryServicesSelected)
      }
    }
  })

  localStorage.setItem("primaryServicesArray",JSON.stringify(primaryServicesArray))
  localStorage.setItem("primaryServicesSelected",primaryServicesSelected)
  loadDatalist( $(".service_type_datalist") , primaryServicesArray )
  $("h1.form-title").text('Servicios disponibles : Principales (' + primaryServicesSelected + '/' + $("#main_services_limit").val() + ') | Secundarios (' + secondaryServicesSelected + '/' + $("#secondary_services_limit").val() + ')')
  $(this).parents(".service_type").css("display","none")
})

$("input[type=submit]").on('click',function(e){
  e.preventDefault()
  
  localStorage.clear()
  $('form').submit();
})

$('#registration-form').validate({

	lang: 'es',
	rules: {
		description:{
      required:true
    }
	},
	messages : {
    description:{
      required:'Ingrese una breve descripción'
    }
	}
})

function loadDatalist( el, options ){
  el.html('')
  $.each(options,function(i,item){
    el.append('<option value="' + item.name + '" >' + item.name + '</option>')
  })
};
