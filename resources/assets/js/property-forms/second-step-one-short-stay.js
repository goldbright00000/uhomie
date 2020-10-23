import $ from 'jquery';
import jQuery from 'jquery';
import 'select2';
import 'select2/dist/css/select2.css';
import bulmaCalendar from 'bulma-calendar';
import Inputmask from "inputmask";

import 'jquery-validation';

var min = getToday()
/*
var available_date = new bulmaCalendar( document.getElementById( 'available_date' ), {
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
    changeYear:false,
    showFooter:false,
    showHeader:false,
    minDate: moment().add(1, 'days').format("YYYY-MM-DD")
} );
*/
$("button.calendar-nav-previous").click(function(e){ e.preventDefault() })
$("button.calendar-nav-next").click(function(e){ e.preventDefault() })

$(function(){
	$.validator.addMethod("isDate", function(value, element) {
	            return isDate(value);
	        }, 'Fecha invalida');
  $.validator.addMethod("maxDate", function (value, element) {
      var max = new Date();
      var inputDate = parseDate(value)
      max.setHours(0,0,0,0)
      if (max > inputDate)
          return false;
      return true;
  }, "Seleccione una fecha a partir de hoy");

  jQuery.validator.addMethod("isCero", function(value, element) {
      if ( value == 0 ) {
        return false;
      }else {
        return true;
      }
  }, 'El monto no puede ser 0');


	//Inputmask("99/99/9999").mask($("input[name=available_date]"));
	$('select').select2();
	checkFurnished()

	if ( $("input[name=common_expenses_limit]").prop("disabled") ) {

		$(".btn-no-posee").css("background-color","#00a2ff")
		$(".btn-no-posee").css("border-color","#00a2ff")
		$(".btn-no-posee").css("color","#fff")
    $(".btn-no-posee").text("Posee")

	}else{

		$(".btn-no-posee").css("background-color","transparent")
		$(".btn-no-posee").css("border-color","#00a2ff")
		$(".btn-no-posee").css("color","#00a2ff")
    $(".btn-no-posee").text("No posee")
	}

  var expenses_limit = $("input[name=rent]").val()
  var common_expenses_limit  = $("input[name=common_expenses_limit]").val()
  //$("input[name=rent]").val(number_format(expenses_limit,0))
  //$("input[name=common_expenses_limit]").val(number_format(common_expenses_limit,0))
})




/*
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
*/

$("input[name=furnished]").change(function(){
	checkFurnished()
})

$(".btn-no-posee").click(function(){
	if ( $("input[name=common_expenses_limit]").prop("disabled")  ) {

		$(".btn-no-posee").css("background-color","transparent")
		$(".btn-no-posee").css("border-color","#00a2ff")
		$(".btn-no-posee").css("color","#00a2ff")
		$("input[name=common_expenses_limit]").val("")
		$("input[name=common_expenses_limit]").attr("disabled",false)
		$("input[name=common_expenses_limit]").attr("required",true)
    $(".btn-no-posee").text("No posee")

	}else{
		$(".btn-no-posee").css("background-color","#00a2ff")
		$(".btn-no-posee").css("border-color","#00a2ff")
		$(".btn-no-posee").css("color","#fff")

		$("input[name=common_expenses_limit]").val("0")
		$("input[name=common_expenses_limit]").attr("disabled",true)
		$("input[name=common_expenses_limit]").attr("required",false)
    $(".btn-no-posee").text("Posee")
  }

})

$('#registration-form').validate({

	lang: 'es',
	rules: {
		rent: {
			required: true,
      //isCero:true
		},
		common_expenses_limit: {
			required: true,
      isCero:true
		},
		available_date: {
			required: true,
		    isDate:true,
		    maxDate:true,
		    date:false
		}
	},

	messages : {
	   rent : {
      required:'Precio mensual de arriendo ',
      minlength: 'El monto es muy pequeño',
		},
		common_expenses_limit : {
			required:'Limite para gastos comunes'
		},
    available_date : {
			required:'¿Que dia quieres mudarte?'
		}
	}
})

/*
available_date.on("date:selected",function(){

  $("#registration-form").find("input[name=available_date]").each(function () {
        $(this).valid();
    });
})
*/
function number_format (number, decimals, dec_point, thousands_sep) {
  number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
  var n = !isFinite(+number) ? 0 : +number,
      prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
      sep = (typeof thousands_sep === 'undefined') ? '.' : thousands_sep,
      dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
      s = '',
      toFixedFix = function (n, prec) {
          var k = Math.pow(10, prec);
          return '' + Math.round(n * k) / k;
      };
  // Fix for IE parseFloat(0.55).toFixed(0) = 0;
  s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
  if (s[0].length > 3) {
      s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
  }
  if ((s[1] || '').length < prec) {
      s[1] = s[1] || '';
      s[1] += new Array(prec - s[1].length + 1).join('0');
  }
  return s.join(dec);
}

function parseDate(date){
   var parts = date.split("/");
   return new Date(parts[2], parts[1] - 1, parts[0]);
}


function checkFurnished(){

	if($("input[name=furnished]:checked").val() == "0"){

		$(".furnished_description").css("display","none")
	}
	if($("input[name=furnished]:checked").val() == "1"){

		$(".furnished_description").css("display","block")

	}
}

function getTomorrow(){
	var today = new Date(new Date().getTime() + 24 * 60 * 60 * 1000) , dd = today.getDate() , mm = today.getMonth()+1 , yy = today.getFullYear().toString().substr(-2);
  if(dd<10) { dd='0'+dd } if(mm<10) { mm='0'+mm }
  return dd+'/'+mm+'/'+yy;
}

function getToday(){
	var today = new Date() , dd = today.getDate() , mm = today.getMonth()+1 , yy = today.getFullYear().toString().substr(-2);
  if(dd<10) { dd='0'+dd } if(mm<10) { mm='0'+mm }
  return dd+'/'+mm+'/'+yy;
}
function isDate(string)
{ //string estará en formato dd/mm/yyyy (dí­as < 32 y meses < 13)
  var ExpReg = /^([0][1-9]|[12][0-9]|3[01])(\/|-)([0][1-9]|[1][0-2])\2(\d{4})$/
  return (ExpReg.test(string));
}

const tenant = new Vue({
  el: '#profiles',
  mounted: function(){
    this.nro_os = parseInt(this.$el.attributes.oferta_semanal.value);
    this.nro_om = parseInt(this.$el.attributes.oferta_mensual.value);
    this.hr_hl = parseInt(this.$el.attributes.hora_llegada.value);
    this.nm = parseInt(this.$el.attributes.noches_minimas.value);
    this.renta_base = parseInt(this.$el.attributes.renta.value.replace('.',''));
    this.limpieza = parseInt(this.$el.attributes.tarifa_limpieza.value.replace('.',''));
    const calendar = bulmaCalendar.attach(this.$refs.calendarTrigger, {
        startDate: moment().add(1, 'days').format("YYYY-MM-DD"),
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
        changeYear:false,
        showFooter:false,
        showHeader:false,
        minDate: moment().add(1, 'days').format("YYYY-MM-DD")
      });
      //[0]calendar.on('date:selected', e => (this.date = e.start || null));
  },
  props: { algo: String},
  components: {

  },
  data() {
    return {
      nro_os: 0,
      nro_om: 0,
      help_oferta_semanal: '',
      help_oferta_mensual: '',
      renta_base: 0,
      hr_hl: 9,
      hr_hs: 9,
      nm: 1,
      date: new Date(),
      limpieza: 0,
    }
  },
  methods: {
    poesia: function(sumando){
      if( (this.nro_os + sumando) <= 100 && (this.nro_os + sumando) >= 0 )
      this.nro_os+= sumando;
    },
    poesiam: function(sumando){
      if( (this.nro_om + sumando) <= 100 && (this.nro_om + sumando) >= 0 )
      this.nro_om+= sumando;
    },
    poesiahl: function(sumando){
      console.log('poesiahl');
      if( (this.hr_hl+sumando) >= 0 && (this.hr_hl+sumando) <= 23 )
      this.hr_hl+= sumando;
    },
    poesiahs: function(sumando){
      console.log('poesiahs');
      if( (this.hr_hs+sumando) >= 0 && (this.hr_hs+sumando) <= 23 )
      this.hr_hs+= sumando;
    },
    poesianm: function(sumando){
      if( (this.nm+sumando) >= 1 && (this.nm+sumando) <= 30 )
      this.nm+= sumando;
    },
    onlyNumber ($event) {
      //console.log($event.keyCode); //keyCodes value
      let keyCode = ($event.keyCode ? $event.keyCode : $event.which);
      if ((keyCode < 48 || keyCode > 57) ) { // 46 is dot
        $event.preventDefault();
      }
    }
  },
  computed: {
    campo_oferta_semanal: function(){
      return this.nro_os+' %';
    },
    campo_oferta_mensual: function(){
      return this.nro_om+' %';
    },
    campo_hora_llegada: function(){
      if( this.hr_hl <= 12 ){
        return this.hr_hl+' AM';
      }else{
        return (this.hr_hl-12)+' PM';
      }
    },
    campo_hora_salida: function(){
      if( this.hr_hs <= 12 ){
        return this.hr_hs+' AM';
      }else{
        return (this.hr_hs-12)+' PM';
      }
    },
    campo_noches_minimas: function(){
      return this.nm;
    }
  },
  watch: {
    campo_oferta_semanal: function(){
      let precio_arriendo_base = parseInt(this.renta_base);
      this.help_oferta_semanal = '('+precio_arriendo_base.toLocaleString('de-DE')+' CLP x 7 dias) - '+this.nro_os+'% = '+((precio_arriendo_base*7)*((100-this.nro_os))/100).toLocaleString('de-DE')+' CLP';
    },
    campo_oferta_mensual: function(){
      let precio_arriendo_base = parseInt(this.renta_base);
      this.help_oferta_mensual = '('+precio_arriendo_base.toLocaleString('de-DE')+' CLP x 30 dias) - '+this.nro_om+'% = '+((precio_arriendo_base*30)*((100-this.nro_om))/100).toLocaleString('de-DE')+' CLP';
    },
    renta_base: function(){
      let precio_arriendo_base = parseInt(this.renta_base);
      this.help_oferta_semanal = '('+precio_arriendo_base.toLocaleString('de-DE')+' CLP x 7 dias) - '+this.nro_os+'% = '+((precio_arriendo_base*7)*((100-this.nro_os))/100).toLocaleString('de-DE')+' CLP';
      this.help_oferta_mensual = '('+precio_arriendo_base.toLocaleString('de-DE')+' CLP x 30 dias) - '+this.nro_om+'% = '+((precio_arriendo_base*30)*((100-this.nro_om))/100).toLocaleString('de-DE')+' CLP';
    },
  }
});

if(document.getElementById('is_project').value == 1){
  $(".property").remove()
  $(".project").css("display","block")
} else {
  $(".project").remove()
  $(".property").css("display","block")
}

if(document.getElementById('property_type_stay').value == "SHORT_STAY"){
  $(".short_stay").css("display","block")
  $(".long_stay").remove()
}

if(document.getElementById('property_type_stay').value == "LONG_STAY"){
  $(".short_stay").remove()
  $(".long_stay").css("display","block")
}
