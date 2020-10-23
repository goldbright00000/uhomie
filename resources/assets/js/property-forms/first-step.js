import $ from 'jquery';
import jQuery from 'jquery';
import 'select2';
import 'select2/dist/css/select2.css';
$(function(){ $('select').select2(); })

$('.btn-edit-property').click(function(){
  var url = document.location.origin + '/properties/registration/first-step/one?id=' + $("select[name=property]").val()
  location.href = url
})
