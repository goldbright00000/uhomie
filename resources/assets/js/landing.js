import $ from 'jquery';
import jQuery from 'jquery';

$(function() {
  var bodytop = $('body').offset().top;
  $(window).on('scroll', function() {
    var stop = Math.round($(window).scrollTop());
    if (stop > bodytop) {
      $('#nav-header').addClass('navbar-solid');
    } else {
      $('#nav-header').removeClass('navbar-solid');
    }
  });

  $(window).scroll(function() {
    if($(this).scrollTop() > 1) {

        $("#search_input_navbar").css("display","block")
        $("#search_icon_navbar").css("display","block")
    } else if( $(this).scrollTop() == 0 ){

        $("#search_input_navbar").css("display","none")
        $("#search_icon_navbar").css("display","none")
    }
  }); 

  //animate
  function animateCSS(element, animationName, callback) {
    const node = document.querySelector(element)
    node.classList.add('animated', animationName)

    function handleAnimationEnd() {
        node.classList.remove('animated', animationName)
        node.removeEventListener('animationend', handleAnimationEnd)

        if (typeof callback === 'function') callback()
    }

    node.addEventListener('animationend', handleAnimationEnd)
  }

  $('#home-init .tabs li').click(function() {
    //Toggle tabs

    if(!$(this).hasClass('is-active')) {
      //tabs
      $('#home-init .tabs .is-active').removeClass('is-active');
      $(this).addClass('is-active');

      //container
      var tab = $(this).attr('tab');
      animateCSS('#home-init .tab-container.is-active', 'slideOutLeft', function() {
        $('#home-init .tab-container.is-active').removeClass('is-active');
        $('#'+tab).addClass('is-active');
        animateCSS('#'+tab, 'slideInRight');
      })
    }
  })
});