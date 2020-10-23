import $ from 'jquery';
import jQuery from 'jquery';

$(function() {

    var arrayOptionsBasicAmenity = [ ]
    var arrayCheckedBasicAmenity = [ ]
    var arrayOptionsDetailsAmenity = [ ]
    var arrayCheckedDetailsAmenity = [ ]
    var arrayOptionsRulesAmenity = [ ]
    var arrayCheckedRulesAmenity = [ ]
    var arrayOptionsPropertyAmenity = [ ]
    var arrayCheckedPropertyAmenity = [ ]
    var arrayOptionsCommonAmenity = [ ]
    var arrayCheckedCommonAmenity = [ ]
    var arrayOptionsPossessions = [ ]
    var arrayCheckedPossessions = [ ]
    $("input.check_basic_amenity").each(function(i,item){
      if ($(item).is(':checked')) {
        arrayCheckedBasicAmenity.push($(item).attr('amenity'))
      } else {
        arrayOptionsBasicAmenity.push($(item).attr('amenity'))
      }
    })
    $("input.check_details_amenity").each(function(i,item){
      if ($(item).is(':checked')) {
        arrayCheckedDetailsAmenity.push($(item).attr('amenity'))
      } else {
        arrayOptionsDetailsAmenity.push($(item).attr('amenity'))
      }
    })
    $("input.check_rules_amenity").each(function(i,item){
      if ($(item).is(':checked')) {
        arrayCheckedRulesAmenity.push($(item).attr('amenity'))
      } else {
        arrayOptionsRulesAmenity.push($(item).attr('amenity'))
      }
    })
    $("input.check_property_amenity").each(function(i,item){
      if ($(item).is(':checked')) {
        arrayCheckedPropertyAmenity.push($(item).attr('amenity'))
      } else {
        arrayOptionsPropertyAmenity.push($(item).attr('amenity'))
      }
    })
    $("input.check_common_amenity").each(function(i,item){
      if ($(item).is(':checked')) {
        arrayCheckedCommonAmenity.push($(item).attr('amenity'))
      } else {
        arrayOptionsCommonAmenity.push($(item).attr('amenity'))
      }
    })
    $("input.check_possessions").each(function(i,item){
      if ($(item).is(':checked')) {
        arrayCheckedPossessions.push($(item).attr('amenity'))
      } else {
        arrayOptionsPossessions.push($(item).attr('amenity'))
      }
    })
    localStorage.setItem("optionsListBasicAmenity",JSON.stringify(arrayOptionsBasicAmenity))
    localStorage.setItem("optionsCheckedBasicAmenity",JSON.stringify(arrayCheckedBasicAmenity))
    localStorage.setItem("optionsListDetailsAmenity",JSON.stringify(arrayOptionsDetailsAmenity))
    localStorage.setItem("optionsCheckedDetailsAmenity",JSON.stringify(arrayCheckedDetailsAmenity))
    localStorage.setItem("optionsListRulesAmenity",JSON.stringify(arrayOptionsRulesAmenity))
    localStorage.setItem("optionsCheckedRulesAmenity",JSON.stringify(arrayCheckedRulesAmenity))
    localStorage.setItem("optionsListPropertyAmenity",JSON.stringify(arrayOptionsPropertyAmenity))
    localStorage.setItem("optionsCheckedPropertyAmenity",JSON.stringify(arrayCheckedPropertyAmenity))
    localStorage.setItem("optionsListCommonAmenity",JSON.stringify(arrayOptionsCommonAmenity))
    localStorage.setItem("optionsCheckedCommonAmenity",JSON.stringify(arrayCheckedCommonAmenity))
    localStorage.setItem("optionsListPossessions",JSON.stringify(arrayOptionsPossessions))
    localStorage.setItem("optionsCheckedPossessions",JSON.stringify(arrayCheckedPossessions))
    loadDatalist( $("#basic_amenities") , JSON.parse(localStorage.getItem("optionsListBasicAmenity")) )
    loadDatalist( $("#details_amenities") , JSON.parse(localStorage.getItem("optionsListDetailsAmenity")) )
    loadDatalist( $("#rules_amenities") , JSON.parse(localStorage.getItem("optionsListRulesAmenity")) )
    loadDatalist( $("#property_amenities") , JSON.parse(localStorage.getItem("optionsListPropertyAmenity")) )
    loadDatalist( $("#common_amenities") , JSON.parse(localStorage.getItem("optionsListCommonAmenity")) )
    loadDatalist( $("#possessions") , JSON.parse(localStorage.getItem("optionsListPossessions")) )
});

$('input.check_basic_amenity').change(function() {

  let $label = $(this).parent('label');
  var arrayOptionsBasicAmenity = JSON.parse(localStorage.getItem("optionsListBasicAmenity"))
  var arrayCheckedBasicAmenity = JSON.parse(localStorage.getItem("optionsCheckedBasicAmenity"))
  if ($(this).is(':checked')) {
    arrayCheckedBasicAmenity.push($(this).attr('amenity'))
    var index = arrayOptionsBasicAmenity.indexOf($(this).attr('amenity'));
    if (index > -1) { arrayOptionsBasicAmenity.splice(index, 1); }
    $label.addClass('active');
    $label.find('.fa').removeClass('fa-plus').addClass('fa-minus');
    localStorage.setItem("optionsListBasicAmenity",JSON.stringify(arrayOptionsBasicAmenity))
    localStorage.setItem("optionsCheckedBasicAmenity",JSON.stringify(arrayCheckedBasicAmenity))
    loadDatalist( $("#basic_amenities") , JSON.parse(localStorage.getItem("optionsListBasicAmenity")) )
  } else {
    var index = arrayCheckedBasicAmenity.indexOf($(this).attr('amenity'));
    if (index > -1) { arrayCheckedBasicAmenity.splice(index, 1); }
    arrayOptionsBasicAmenity.push($(this).attr('amenity'))

    $label.removeClass('active');
    $label.find('.fa').removeClass('fa-minus').addClass('fa-plus');

    localStorage.setItem("optionsListBasicAmenity",JSON.stringify(arrayOptionsBasicAmenity))
    localStorage.setItem("optionsCheckedBasicAmenity",JSON.stringify(arrayCheckedBasicAmenity))
    loadDatalist( $("#basic_amenities") , JSON.parse(localStorage.getItem("optionsListBasicAmenity")) )
  }

});

$("#inputBasicAmenities").on('input', function () {
  var val = this.value;
  if($('#basic_amenities option').filter(function(){
      return this.value.toUpperCase() === val.toUpperCase();
  }).length) {

      var arrayOptionsBasicAmenity = JSON.parse(localStorage.getItem("optionsListBasicAmenity"))
      var arrayCheckedBasicAmenity = JSON.parse(localStorage.getItem("optionsCheckedBasicAmenity"))
      arrayCheckedBasicAmenity.push(val)
      var index = arrayOptionsBasicAmenity.indexOf(val);
      if (index > -1) { arrayOptionsBasicAmenity.splice(index, 1); }
      localStorage.setItem("optionsListBasicAmenity",JSON.stringify(arrayOptionsBasicAmenity))
      localStorage.setItem("optionsCheckedBasicAmenity",JSON.stringify(arrayCheckedBasicAmenity))
      $("input.check_basic_amenity").each(function(i,item){
        if ($(item).attr('amenity') == val) {
          let $label = $(item).parent('label');
          $label.addClass('active');
          $label.find('.fa').removeClass('fa-plus').addClass('fa-minus');
          $(item).prop('checked',true)
        }
      })
      loadDatalist( $("#basic_amenities") , JSON.parse(localStorage.getItem("optionsListBasicAmenity")) )
      $("#inputBasicAmenities").val("")
  }
});

$('input.check_details_amenity').change(function() {

  let $label = $(this).parent('label');
  var arrayOptionsDetailsAmenity = JSON.parse(localStorage.getItem("optionsListDetailsAmenity"))
  var arrayCheckedDetailsAmenity = JSON.parse(localStorage.getItem("optionsCheckedDetailsAmenity"))
  if ($(this).is(':checked')) {
    arrayCheckedDetailsAmenity.push($(this).attr('amenity'))
    var index = arrayOptionsDetailsAmenity.indexOf($(this).attr('amenity'));
    if (index > -1) { arrayOptionsDetailsAmenity.splice(index, 1); }
    $label.addClass('active');
    $label.find('.fa').removeClass('fa-plus').addClass('fa-minus');
    localStorage.setItem("optionsListDetailsAmenity",JSON.stringify(arrayOptionsDetailsAmenity))
    localStorage.setItem("optionsCheckedDetailsAmenity",JSON.stringify(arrayCheckedDetailsAmenity))
    loadDatalist( $("#details_amenities") , JSON.parse(localStorage.getItem("optionsListDetailsAmenity")) )
  } else {
    var index = arrayCheckedDetailsAmenity.indexOf($(this).attr('amenity'));
    if (index > -1) { arrayCheckedDetailsAmenity.splice(index, 1); }
    arrayOptionsDetailsAmenity.push($(this).attr('amenity'))

    $label.removeClass('active');
    $label.find('.fa').removeClass('fa-minus').addClass('fa-plus');

    localStorage.setItem("optionsListDetailsAmenity",JSON.stringify(arrayOptionsDetailsAmenity))
    localStorage.setItem("optionsCheckedDetailsAmenity",JSON.stringify(arrayCheckedDetailsAmenity))
    loadDatalist( $("#details_amenities") , JSON.parse(localStorage.getItem("optionsListDetailsAmenity")) )
  }

});

$("#inputDetailsAmenities").on('input', function () {
  var val = this.value;
  if($('#details_amenities option').filter(function(){
      return this.value.toUpperCase() === val.toUpperCase();
  }).length) {

      var arrayOptionsDetailsAmenity = JSON.parse(localStorage.getItem("optionsListDetailsAmenity"))
      var arrayCheckedDetailsAmenity = JSON.parse(localStorage.getItem("optionsCheckedDetailsAmenity"))
      arrayCheckedDetailsAmenity.push(val)
      var index = arrayOptionsDetailsAmenity.indexOf(val);
      if (index > -1) { arrayOptionsDetailsAmenity.splice(index, 1); }
      localStorage.setItem("optionsListDetailsAmenity",JSON.stringify(arrayOptionsDetailsAmenity))
      localStorage.setItem("optionsCheckedDetailsAmenity",JSON.stringify(arrayCheckedDetailsAmenity))
      $("input.check_details_amenity").each(function(i,item){
        if ($(item).attr('amenity') == val) {
          let $label = $(item).parent('label');
          $label.addClass('active');
          $label.find('.fa').removeClass('fa-plus').addClass('fa-minus');
          $(item).prop('checked',true)
        }
      })
      loadDatalist( $("#details_amenities") , JSON.parse(localStorage.getItem("optionsListDetailsAmenity")) )
      $("#inputDetailsAmenities").val("")
  }
});

$('input.check_rules_amenity').change(function() {

  let $label = $(this).parent('label');
  var arrayOptionsRulesAmenity = JSON.parse(localStorage.getItem("optionsListRulesAmenity"))
  var arrayCheckedRulesAmenity = JSON.parse(localStorage.getItem("optionsCheckedRulesAmenity"))
  if ($(this).is(':checked')) {
    arrayCheckedRulesAmenity.push($(this).attr('amenity'))
    var index = arrayOptionsRulesAmenity.indexOf($(this).attr('amenity'));
    if (index > -1) { arrayOptionsRulesAmenity.splice(index, 1); }
    $label.addClass('active');
    $label.find('.fa').removeClass('fa-plus').addClass('fa-minus');
    localStorage.setItem("optionsListRulesAmenity",JSON.stringify(arrayOptionsRulesAmenity))
    localStorage.setItem("optionsCheckedRulesAmenity",JSON.stringify(arrayCheckedRulesAmenity))
    loadDatalist( $("#rules_amenities") , JSON.parse(localStorage.getItem("optionsListRulesAmenity")) )
  } else {
    var index = arrayCheckedRulesAmenity.indexOf($(this).attr('amenity'));
    if (index > -1) { arrayCheckedRulesAmenity.splice(index, 1); }
    arrayOptionsRulesAmenity.push($(this).attr('amenity'))

    $label.removeClass('active');
    $label.find('.fa').removeClass('fa-minus').addClass('fa-plus');

    localStorage.setItem("optionsListRulesAmenity",JSON.stringify(arrayOptionsRulesAmenity))
    localStorage.setItem("optionsCheckedRulesAmenity",JSON.stringify(arrayCheckedRulesAmenity))
    loadDatalist( $("#rules_amenities") , JSON.parse(localStorage.getItem("optionsListRulesAmenity")) )
  }

});

$("#inputRulesAmenities").on('input', function () {
  var val = this.value;
  if($('#rules_amenities option').filter(function(){
      return this.value.toUpperCase() === val.toUpperCase();
  }).length) {

      var arrayOptionsRulesAmenity = JSON.parse(localStorage.getItem("optionsListRulesAmenity"))
      var arrayCheckedRulesAmenity = JSON.parse(localStorage.getItem("optionsCheckedRulesAmenity"))
      arrayCheckedRulesAmenity.push(val)
      var index = arrayOptionsRulesAmenity.indexOf(val);
      if (index > -1) { arrayOptionsRulesAmenity.splice(index, 1); }
      localStorage.setItem("optionsListRulesAmenity",JSON.stringify(arrayOptionsRulesAmenity))
      localStorage.setItem("optionsCheckedRulesAmenity",JSON.stringify(arrayCheckedRulesAmenity))
      $("input.check_rules_amenity").each(function(i,item){
        if ($(item).attr('amenity') == val) {
          let $label = $(item).parent('label');
          $label.addClass('active');
          $label.find('.fa').removeClass('fa-plus').addClass('fa-minus');
          $(item).prop('checked',true)
        }
      })
      loadDatalist( $("#rules_amenities") , JSON.parse(localStorage.getItem("optionsListRulesAmenity")) )
      $("#inputRulesAmenities").val("")
  }
});

$('input.check_property_amenity').change(function() {

    let $label = $(this).parent('label');
    var arrayOptionsPropertyAmenity = JSON.parse(localStorage.getItem("optionsListPropertyAmenity"))
    var arrayCheckedPropertyAmenity = JSON.parse(localStorage.getItem("optionsCheckedPropertyAmenity"))
    if ($(this).is(':checked')) {
      arrayCheckedPropertyAmenity.push($(this).attr('amenity'))
      var index = arrayOptionsPropertyAmenity.indexOf($(this).attr('amenity'));
      if (index > -1) { arrayOptionsPropertyAmenity.splice(index, 1); }
      $label.addClass('active');
      $label.find('.fa').removeClass('fa-plus').addClass('fa-minus');
      localStorage.setItem("optionsListPropertyAmenity",JSON.stringify(arrayOptionsPropertyAmenity))
      localStorage.setItem("optionsCheckedPropertyAmenity",JSON.stringify(arrayCheckedPropertyAmenity))
      loadDatalist( $("#property_amenities") , JSON.parse(localStorage.getItem("optionsListPropertyAmenity")) )
    } else {
      var index = arrayCheckedPropertyAmenity.indexOf($(this).attr('amenity'));
      if (index > -1) { arrayCheckedPropertyAmenity.splice(index, 1); }
      arrayOptionsPropertyAmenity.push($(this).attr('amenity'))

      $label.removeClass('active');
      $label.find('.fa').removeClass('fa-minus').addClass('fa-plus');

      localStorage.setItem("optionsListPropertyAmenity",JSON.stringify(arrayOptionsPropertyAmenity))
      localStorage.setItem("optionsCheckedPropertyAmenity",JSON.stringify(arrayCheckedPropertyAmenity))
      loadDatalist( $("#property_amenities") , JSON.parse(localStorage.getItem("optionsListPropertyAmenity")) )
    }

});

$("#inputPropertyAmenities").on('input', function () {
    var val = this.value;
    if($('#property_amenities option').filter(function(){
        return this.value.toUpperCase() === val.toUpperCase();
    }).length) {

        var arrayOptionsPropertyAmenity = JSON.parse(localStorage.getItem("optionsListPropertyAmenity"))
        var arrayCheckedPropertyAmenity = JSON.parse(localStorage.getItem("optionsCheckedPropertyAmenity"))
        arrayCheckedPropertyAmenity.push(val)
        var index = arrayOptionsPropertyAmenity.indexOf(val);
        if (index > -1) { arrayOptionsPropertyAmenity.splice(index, 1); }
        localStorage.setItem("optionsListPropertyAmenity",JSON.stringify(arrayOptionsPropertyAmenity))
        localStorage.setItem("optionsCheckedPropertyAmenity",JSON.stringify(arrayCheckedPropertyAmenity))
        $("input.check_property_amenity").each(function(i,item){
          if ($(item).attr('amenity') == val) {
            let $label = $(item).parent('label');
            $label.addClass('active');
            $label.find('.fa').removeClass('fa-plus').addClass('fa-minus');
            $(item).prop('checked',true)
          }
        })
        loadDatalist( $("#property_amenities") , JSON.parse(localStorage.getItem("optionsListPropertyAmenity")) )
        $("#inputPropertyAmenities").val("")
    }
});

$('input.check_common_amenity').change(function() {

    let $label = $(this).parent('label');
    var arrayOptionsCommonAmenity = JSON.parse(localStorage.getItem("optionsListCommonAmenity"))
    var arrayCheckedCommonAmenity = JSON.parse(localStorage.getItem("optionsCheckedCommonAmenity"))
    if ($(this).is(':checked')) {
      arrayCheckedCommonAmenity.push($(this).attr('amenity'))
      var index = arrayOptionsCommonAmenity.indexOf($(this).attr('amenity'));
      if (index > -1) { arrayOptionsCommonAmenity.splice(index, 1); }
      $label.addClass('active');
      $label.find('.fa').removeClass('fa-plus').addClass('fa-minus');
      localStorage.setItem("optionsListCommonAmenity",JSON.stringify(arrayOptionsCommonAmenity))
      localStorage.setItem("optionsCheckedCommonAmenity",JSON.stringify(arrayCheckedCommonAmenity))
      loadDatalist( $("#common_amenities") , JSON.parse(localStorage.getItem("optionsListCommonAmenity")) )
    } else {
      var index = arrayCheckedCommonAmenity.indexOf($(this).attr('amenity'));
      if (index > -1) { arrayCheckedCommonAmenity.splice(index, 1); }
      arrayOptionsCommonAmenity.push($(this).attr('amenity'))

      $label.removeClass('active');
      $label.find('.fa').removeClass('fa-minus').addClass('fa-plus');

      localStorage.setItem("optionsListCommonAmenity",JSON.stringify(arrayOptionsCommonAmenity))
      localStorage.setItem("optionsCheckedCommonAmenity",JSON.stringify(arrayCheckedCommonAmenity))
      loadDatalist( $("#common_amenities") , JSON.parse(localStorage.getItem("optionsListCommonAmenity")) )
    }

});

$("#inputCommonAmenities").on('input', function () {
    var val = this.value;
    if($('#common_amenities option').filter(function(){
        return this.value.toUpperCase() === val.toUpperCase();
    }).length) {

        var arrayOptionsCommonAmenity = JSON.parse(localStorage.getItem("optionsListCommonAmenity"))
        var arrayCheckedCommonAmenity = JSON.parse(localStorage.getItem("optionsCheckedCommonAmenity"))
        arrayCheckedCommonAmenity.push(val)
        var index = arrayOptionsCommonAmenity.indexOf(val);
        if (index > -1) { arrayOptionsCommonAmenity.splice(index, 1); }
        localStorage.setItem("optionsListCommonAmenity",JSON.stringify(arrayOptionsCommonAmenity))
        localStorage.setItem("optionsCheckedCommonAmenity",JSON.stringify(arrayCheckedCommonAmenity))
        $("input.check_common_amenity").each(function(i,item){
          if ($(item).attr('amenity') == val) {
            let $label = $(item).parent('label');
            $label.addClass('active');
            $label.find('.fa').removeClass('fa-plus').addClass('fa-minus');
            $(item).prop('checked',true)
          }
        })
        loadDatalist( $("#common_amenities") , JSON.parse(localStorage.getItem("optionsListCommonAmenity")) )
        $("#inputCommonAmenities").val("")
    }
});

$('input.check_possession').change(function() {

  let $label = $(this).parent('label');
  var arrayOptionsPossessions = JSON.parse(localStorage.getItem("optionsListPossessions"))
  var arrayCheckedPossessions = JSON.parse(localStorage.getItem("optionsCheckedPossessions"))
  if ($(this).is(':checked')) {
    arrayCheckedPossessions.push($(this).attr('amenity'))
    var index = arrayOptionsPossessions.indexOf($(this).attr('amenity'));
    if (index > -1) { arrayOptionsPossessions.splice(index, 1); }
    $label.addClass('active');
    $label.find('.fa').removeClass('fa-plus').addClass('fa-minus');
    localStorage.setItem("optionsListPossessions",JSON.stringify(arrayOptionsPossessions))
    localStorage.setItem("optionsCheckedPossessions",JSON.stringify(arrayCheckedPossessions))
    loadDatalist( $("#possessions") , JSON.parse(localStorage.getItem("optionsListPossessions")) )
  } else {
    var index = arrayCheckedPossessions.indexOf($(this).attr('amenity'));
    if (index > -1) { arrayCheckedPossessions.splice(index, 1); }
    arrayOptionsPossessions.push($(this).attr('amenity'))

    $label.removeClass('active');
    $label.find('.fa').removeClass('fa-minus').addClass('fa-plus');

    localStorage.setItem("optionsListPossessions",JSON.stringify(arrayOptionsPossessions))
    localStorage.setItem("optionsCheckedPossessions",JSON.stringify(arrayCheckedPossessions))
    loadDatalist( $("#possessions") , JSON.parse(localStorage.getItem("optionsListPossessions")) )
  }

});

$("#inputPossessions").on('input', function () {
  var val = this.value;
  if($('#possessions option').filter(function(){
      return this.value.toUpperCase() === val.toUpperCase();
  }).length) {

      var arrayOptionsPossessions = JSON.parse(localStorage.getItem("optionsListPossessions"))
      var arrayCheckedPossessions = JSON.parse(localStorage.getItem("optionsCheckedPossessions"))
      arrayCheckedPossessions.push(val)
      var index = arrayOptionsPossessions.indexOf(val);
      if (index > -1) { arrayOptionsPossessions.splice(index, 1); }
      localStorage.setItem("optionsListPossessions",JSON.stringify(arrayOptionsPossessions))
      localStorage.setItem("optionsCheckedPossessions",JSON.stringify(arrayCheckedPossessions))
      $("input.check_possession").each(function(i,item){
        if ($(item).attr('amenity') == val) {
          let $label = $(item).parent('label');
          $label.addClass('active');
          $label.find('.fa').removeClass('fa-plus').addClass('fa-minus');
          $(item).prop('checked',true)
        }
      })
      loadDatalist( $("#possessions") , JSON.parse(localStorage.getItem("optionsListPossessions")) )
      $("#inputPossessions").val("")
  }
});

function loadDatalist( el, options ){
  el.html('')
  var arr = options
  $.each(arr,function(i,item){
    el.append('<option value="' + item + '" >' + item + '</option>')
  })
};



$("input[type=submit]").on('click',function(e){
  e.preventDefault()
  
  localStorage.removeItem("optionsListRulesAmenity");
  localStorage.removeItem("optionsCheckedRulesAmenity");
  localStorage.removeItem("optionsListBasicAmenity");
  localStorage.removeItem("optionsCheckedBasicAmenity");
  localStorage.removeItem("optionsListDetailsAmenity");
  localStorage.removeItem("optionsCheckedDetailsAmenity");
  localStorage.removeItem("optionsListPropertyAmenity");
  localStorage.removeItem("optionsCheckedPropertyAmenity");
  localStorage.removeItem("optionsListCommonAmenity");
  localStorage.removeItem("optionsCheckedCommonAmenity");
  localStorage.removeItem("optionsListPossessions");
  localStorage.removeItem("optionsCheckedPossessions");
  
  $('form').submit();
})