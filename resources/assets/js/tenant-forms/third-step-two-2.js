import $ from 'jquery';
import jQuery from 'jquery';

$(function() {
    var arrayOptionsPropertyAmenity = [ ]
    var arrayCheckedPropertyAmenity = [ ]
    var arrayOptionsCommonAmenity = [ ]
    var arrayCheckedCommonAmenity = [ ]

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

    localStorage.setItem("optionsListPropertyAmenity",JSON.stringify(arrayOptionsPropertyAmenity))
    localStorage.setItem("optionsCheckedPropertyAmenity",JSON.stringify(arrayCheckedPropertyAmenity))
    localStorage.setItem("optionsListCommonAmenity",JSON.stringify(arrayOptionsCommonAmenity))
    localStorage.setItem("optionsCheckedCommonAmenity",JSON.stringify(arrayCheckedCommonAmenity))

    loadDatalist( $("#property_amenities") , JSON.parse(localStorage.getItem("optionsListPropertyAmenity")) )
    loadDatalist( $("#common_amenities") , JSON.parse(localStorage.getItem("optionsListCommonAmenity")) )
    console.log($('input.check_common_amenity:checked').length)
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

$("input[type=submit]").on('click',function(e){
  e.preventDefault()

  localStorage.removeItem("optionsListPropertyAmenity");
  localStorage.removeItem("optionsCheckedPropertyAmenity");
  localStorage.removeItem("optionsListCommonAmenity");
  localStorage.removeItem("optionsCheckedCommonAmenity");

  $('form').submit();
})


function loadDatalist( el, options ){
  el.html('')
  var arr = options
  $.each(arr,function(i,item){
    el.append('<option value="' + item + '" >' + item + '</option>')
  })
};
