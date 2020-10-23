const _token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const visit_from = $("#visit_from").val();
const visit_to = $("#visit_to").val();
const visit = $("#visit").val();
const schedule_range = $("#schedule_range").val();

import 'vuetify/dist/vuetify.min.css';
const fechas = new Vue({
    el: '#fechas',
    components : {
    }, 
    data: vm => ({
            visit_from: visit_from,
            visit_to: visit_to,
            visit: visit,
            schedule_range: schedule_range,
			dateFormatted: vm.formatDate(new Date().toISOString().substr(0, 10)),
            modal1: false,
            modal2: false
			
	}),
    methods: {
        formatDate (date) {
			if (!date) return null

			const [year, month, day] = date.split('-')
			return `${day}/${month}/${year}`
		},
		parseDate (date) {
			if (!date) return null

			const [day, month, year] = date.split('/')
			return `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`
        },
        computedDateFormatted (date) {
			return this.formatDate(date) 
        },
        changeVisit(){
            this.modal1 = false
            if(moment(this.visit_from).isBefore(this.visit_to) != true){
                this.visit_to = this.visit_from
            }
        }
        
    },
    mounted() {

	},
	computed: {
		
		min_date_visit(){
			var fecha = moment().add(1, 'days').format("YYYY-MM-DD")
			
			return fecha
        },
        min_date_to(){
            var fecha = this.visit_from
            return fecha
        },
        max_date_to(){
            var fecha = moment(this.visit_from).add(21, 'days').format("YYYY-MM-DD")
            return fecha
        },
	},
	watch: {
		date (val) {
		this.dateFormatted = this.formatDate(this.date)
		}
	},
});



import $ from 'jquery';
import jQuery from 'jquery';
import bulmaCalendar from 'bulma-calendar';
import Inputmask from "inputmask";
import 'jquery-validation';
//var today = getToday()

/*var from_date = new bulmaCalendar( document.getElementById( 'visit_from_date' ), {

  dateFormat: 'DD/MM/YYYY',
  lang: 'es',
  overlay: false,
  closeOnOverlayClick: true,
  closeOnSelect: true,
  // callback functions
  onSelect: null,
  onOpen: null,
  onClose: null,
  onRender: null,
  displayMode:'default',
  showFooter:false,
  showHeader:false,
  //minDate:today
} );

// var new_from_date = from_date.split("/").reverse().join("-");
var to_date = new bulmaCalendar( document.getElementById( 'visit_to_date' ), {

dateFormat: 'DD/MM/YYYY',
lang: 'es',
overlay: false,
closeOnOverlayClick: true,
closeOnSelect: true,
// callback functions
onSelect: null,
onOpen: null,
onClose: null,
onRender: null,
displayMode:'default',
showFooter:false,
showHeader:false,
//minDate:today
} );

$(function(){
$("button.calendar-nav-previous").click(function(e){ e.preventDefault() })
$("button.calendar-nav-next").click(function(e){ e.preventDefault() })
	checkFurnished()
    Inputmask("99/99/9999").mask($("input[name=visit_from_date]"));

    Inputmask("99/99/9999").mask($("input[name=visit_to_date]"));
    jQuery.validator.addMethod("isDate", function(value, element) {
            return isDate(value);
        }, 'Fecha invalida');


    $.validator.addMethod("minToDate", function (value, element) {

        var from_date = document.getElementById( 'visit_from_date' ).value;
        var to_date = document.getElementById( 'visit_to_date' ).value;

        var parse_from_date = parseDate(from_date)
        var parse_to_date = parseDate(to_date)

        var sum=parse_from_date.getTime()+(19*24*60*60*1000);

        if (parse_to_date > sum)
            return false;
        return true;
    }, "Debe tener como maximo tres(3) semanas a partir de la fecha de inicio");

    $.validator.addMethod("maxToDate", function (value, element) {

        var from_date = document.getElementById( 'visit_from_date' ).value;
        var to_date = document.getElementById( 'visit_to_date' ).value;

        var parse_from_date = parseDate(from_date)
        var parse_to_date = parseDate(to_date)

        if (parse_to_date < parse_from_date)
            return false;
        return true;
    }, "No debe ser inferior a la fecha de inicio");

    $.validator.addMethod("maxDate", function (value, element) {
        var min = getToday();
        var inputDate = document.getElementById( 'visit_from_date' ).value;
        var parse_min_date = parseDate(min)
        var parse_input_date = parseDate(inputDate)

        // console.log( inputDate + ' ** ' + min )
        // console.log(  parse_min_date )

        if (parse_input_date < parse_min_date)
            return false;
        return true;
    }, "No puede ser menor a hoy");

})*/

$("#visit").change(function(){
	checkFurnished()
})

function checkFurnished(){

	if($("#visit:checked").val() == "0"){
    //alert("no visit")
		$(".no-visit").css("display","block")
		$(".with-visit").css("display","none")
		//$("input[name=visit_from_date]").attr("disabled",true)
		//$("input[name=visit_to_date]").attr("disabled",true)
		$("#schedule_range").attr("disabled",true)
	}
	if($("#visit:checked").val() == "1"){
    //alert("visit")
		$(".no-visit").css("display","none")
		$(".with-visit").css("display","block")
		//$("input[name=visit_from_date]").attr("disabled",false)
		//$("input[name=visit_to_date]").attr("disabled",false)
		$("#schedule_range").attr("disabled",false)
	}
}

function getTomorrow(){
  var today = new Date(new Date().getTime() + 3 * 24 * 60 * 60 * 1000) , dd = today.getDate() , mm = today.getMonth()+1 , yy = today.getFullYear().toString().substr(-2);
  if(dd<10) { dd='0'+dd } if(mm<10) { mm='0'+mm }
  return dd+'/'+mm+'/'+yy;
}

function getToday(){
	var today = new Date() , dd = today.getDate() , mm = today.getMonth()+1 , yy = today.getFullYear().toString().substr(-2);
  if(dd<10) { dd='0'+dd } if(mm<10) { mm='0'+mm }
  return dd+'/'+mm+'/'+yy;
}


$('#registration-form').validate({
    lang: 'es',

    rules: {

        /*visit_from_date: {
            required: true,
            isDate:true,
            date:false,
            // minDate:true,
            maxDate:true
        },
        visit_to_date: {
            required: true,
          isDate:true,
          minToDate:true,
          date:false,
          maxToDate:true
          // maxDate:true,
          // minDate:true
        },*/
        schedule_range:{
          required:true
        }
    },

    messages : {

        /*visit_from_date : {
            required:'Debe seleccionar una fecha'
        },
        visit_to_date : {
            required:'Debe seleccionar una fecha'
        },*/
        schedule_range:{
          required:'Debe seleccionar un rango de hora'
        }
    }
})

/*
from_date.on("date:selected",function(){

    $("#registration-form").find("input[name=visit_from_date]").each(function () {
        $(this).valid();
    });
})
to_date.on("date:selected",function(){

    $("#registration-form").find("input[name=visit_to_date]").each(function () {
        $(this).valid();
    });
})*/
function parseDate(date){
   var parts = date.split("/");
   return new Date(parts[2], parts[1] - 1, parts[0]);
}
function isDate(string)
{ //string estará en formato dd/mm/yyyy (dí­as < 32 y meses < 13)
  var ExpReg = /^([0][1-9]|[12][0-9]|3[01])(\/|-)([0][1-9]|[1][0-2])\2(\d{4})$/
  return (ExpReg.test(string));
}



