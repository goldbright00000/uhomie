/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
var VueTheMask = require('vue-the-mask');

window.Vue = require("vue");
window.Vue.use(VueTheMask)

window.VueRouter = require("vue-router").default;
window.globals = {
  asset: function(relative_path) {
    if (typeof this.url == "undefined") {
      this.url = document
        .querySelector('meta[name="assets-url"]')
        .getAttribute("content");

      if (typeof this.url == "undefined") return null;
    }

    return relative_path ? this.url + relative_path : this.url;
  },
  filters: {
    moneyFormat: function(n, c, d, t) {
      var c = isNaN((c = Math.abs(c))) ? 0 : c,
        d = d == undefined ? "," : d,
        t = t == undefined ? "." : t,
        s = n < 0 ? "-" : "",
        i = String(parseInt((n = Math.abs(Number(n) || 0).toFixed(c)))),
        j = (j = i.length) > 3 ? j % 3 : 0;

      return (
        s +
        (j ? i.substr(0, j) + t : "") +
        i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) +
        (c
          ? d +
            Math.abs(n - i)
              .toFixed(c)
              .slice(2)
          : "")
      );
    }
  }
};

import "./bootstrap";

import Vuetify from 'vuetify'
Vue.use(Vuetify)



Vue.options.delimiters = ["{[", "]}"];

$(function() {
  $("body").on("click", ".link-login", function(e) {
    e.preventDefault();
    $(this)
      .parents(".modal")
      .removeClass("is-active");
    $("#login-modal").addClass("is-active");
  });

  $("body").on("click", ".btn-close-modal", function() {
    $(".modal").removeClass("is-active");
  });

  $("body").on("click", ".link-register", function(e) {
    e.preventDefault();
    $(this)
      .parents(".modal")
      .removeClass("is-active");
    $("#register-modal").addClass("is-active");
  });

  $("body").on("click", ".modal-close", function() {
    $(this)
      .parents(".modal")
      .removeClass("is-active");
  });

  $(".navbar-burger").click(function() {
    $(".navbar-burger").toggleClass("is-active");
    $(".navbar-menu").toggleClass("is-active");
  });

  $("body").on("click", "#arrow-down", function() {
    $("html, body").animate(
      {
        scrollTop: $(".second-section").offset().top
      },
      1000
    );
  });
});
$('body').on('click', '#profile-link', function(e) {
  e.preventDefault();
  const roles = [
    {
      "id" : 1,
      "name" : "Arrendatario",
      "default_uri" : "/users/profile/tenant" ,
      "image_static_path" : $("#profile-modal .container input#tenant-image-static-path").val() ,
      "image_active_path" : $("#profile-modal .container input#tenant-image-active-path").val()
    },
    {
      "id" : 2,
      "name" : "Arrendador",
      "default_uri" : "/users/profile/owner" ,
      "image_static_path" : $("#profile-modal .container input#agent-image-static-path").val() ,
      "image_active_path" : $("#profile-modal .container input#agent-image-active-path").val()
    },
    {
      "id" : 3,
      "name" : "Agente",
      "default_uri" : "/users/profile/agent" ,
      "image_static_path" : $("#profile-modal .container input#owner-image-static-path").val() ,
      "image_active_path" : $("#profile-modal .container input#owner-image-active-path").val()
    },
    {
      "id" : 4,
      "name" : "Servicios",
      "default_uri" : "/users/profile/service" ,
      "image_static_path" : $("#profile-modal .container input#service-image-static-path").val() ,
      "image_active_path" : $("#profile-modal .container input#service-image-active-path").val()
    },
    {
      "id" : 5,
      "name" : "Aval",
      "default_uri" : "/users/profile/collateral" ,
      "image_static_path" : $("#profile-modal .container input#collateral-image-static-path").val() ,
      "image_active_path" : $("#profile-modal .container input#collateral-image-active-path").val()
    },
  ]
  // peticion ajax
  $.ajax({
		url: $("#roles_uri").val(),
		type:'GET',
		dataType:'JSON',
		success: function(response){
      if ( response.length > 1 ) {
          $(".roles-user").html("")
          var items = []
          $.each(response,function(i,item){
            $.each(roles,function(j,roleItem){ if(item.id == roleItem.id){ items.push(createItem(roleItem)) } })
          })
          for (var i = 0; i < items.length; i++) { $(".roles-user").append(items[i]) }
          $(this).parents('.modal').removeClass('is-active');
          $('#profile-modal').addClass('is-active');
      }else{
          var role_name = "";
          $.each(roles,function(i,item){
            if ( item.id == response[0].id ) {
              role_name = item.default_uri;
              return false;
            }
          })
          var redirect_to = $('#profile_uri').val() + role_name
          location.href = redirect_to
      }
    }
  });

  function createItem(role){
    var content = '<div class="column is-one-fifth item-role" id="' + role.name.toLowerCase() + '" >'
    content += '<figure class="image is-128x128">'
    content += '<img class="is-rounded static-img" src="'+role.image_static_path+'">'
    content += '<img style="display:none" class="is-rounded active-img" src="'+role.image_active_path+'">'
    content += '<span>'+role.name.toUpperCase()+'</span>'
    content += '</figure>'
    content += '<input type="hidden" id="default_uri" value="'+ $('#profile_uri').val() + role.default_uri+'"/>'
    content += '</div>'
    return content
  }

  $('body').on('mouseover','div.item-role',function(e){
    $(this).children('figure').children('.static-img').css('display','none')
    $(this).children('figure').children('.active-img').css('display','block')
  })

  $('body').on('mouseout','div.item-role',function(e) {
    $(this).children('figure').children('.static-img').css('display','block')
    $(this).children('figure').children('.active-img').css('display','none')
  })

  $('body').on('click','div.item-role',function(e) {
    location.href = $(this).children('#default_uri').val()
  })

});