<link rel="stylesheet"  href="/css/login-buttons.css">
<style>
    .slide {
	float: left;
	position: absolute;
	width: 100%;
	height: 100%;
	opacity: 0;
	transition: opacity 3s linear;
    }

    .slider-wrapper > .slide:first-child {
        opacity: 1;
    }

    .contenido-hovereable{
        z-index: 999;
        /*background-color: rgba(134,133,133, 0.25);*/
    }
</style>

<div class="modal modal-access" id="login-modal">
    <div class="modal-background slider-wrapper">
            <!--
            <div class="slide columns  fondo-difuminado" style="background-color: white;background-image: url('/images/imagen_uhomie.jpg'); background-size: cover;">
                <div class="column is-6 is-offset-6 contenido-relativo">
                    <div class="columns " style="padding-top: 3rem;">
                        <div class="column is-12 contenido-hovereable">
                        <section class="hero main-title" id="seventh-title">
                            <div class="hero-body">
                            <div class="container">
                                <h1 class="title" style="font-size: 1.5rem;">Publicita con nosotros</h1>
                                <h1 class="title" style="font-size: 1.5rem;">Tu marca podría estar aquí</h1>
                            </div>
                            </div>
                        </section>
                        </div>
                    </div>
                </div>
            </div>
            -->
            <div class="slide columns is-vcentered" style="background-color: white;background-image: url('/images/login-slides/login_wp_1.jpg'); background-size: cover;">
                <div class="column is-6 is-offset-5 contenido-relativo">
                    <div class="columns is-centered">
                        <div class="column is-12 contenido-hovereable">
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="slide columns is-vcentered" style="background-color: white;background-image: url('/images/login-slides/login_wp_2.jpg'); background-size: cover;">
                <div class="column is-6 is-offset-5 contenido-relativo">
                    <div class="columns is-centered">
                        <div class="column is-12 contenido-hovereable">
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="slide columns is-vcentered" style="background-color: white;background-image: url('/images/login-slides/login_wp_3.jpg'); background-size: cover;">
                <div class="column is-6 is-offset-5 contenido-relativo">
                    <div class="columns is-centered">
                        <div class="column is-12 contenido-hovereable">
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="slide columns is-vcentered" style="background-color: white;background-image: url('/images/login-slides/login_wp_4.jpg'); background-size: cover;">
                <div class="column is-6 is-offset-5 contenido-relativo">
                    <div class="columns is-centered">
                        <div class="column is-12 contenido-hovereable">
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="slide columns is-vcentered" style="background-color: white;background-image: url('/images/login-slides/login_wp_5.jpg'); background-size: cover;">
                <div class="column is-6 is-offset-5 contenido-relativo">
                    <div class="columns is-centered">
                        <div class="column is-12 contenido-hovereable">
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="slide columns is-vcentered" style="background-color: white;background-image: url('/images/login-slides/login_wp_6.jpg'); background-size: cover;">
                <div class="column is-6 is-offset-5 contenido-relativo">
                    <div class="columns is-centered">
                        <div class="column is-12 contenido-hovereable">
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="slide columns is-vcentered" style="background-color: white;background-image: url('/images/login-slides/login_wp_7.jpg'); background-size: cover;">
                <div class="column is-6 is-offset-5 contenido-relativo">
                    <div class="columns is-centered">
                        <div class="column is-12 contenido-hovereable">
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="slide columns is-vcentered" style="background-color: white;background-image: url('/images/login-slides/login_wp_8.jpg'); background-size: cover;">
                <div class="column is-6 is-offset-5 contenido-relativo">
                    <div class="columns is-centered">
                        <div class="column is-12 contenido-hovereable">
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="slide columns is-vcentered" style="background-color: white;background-image: url('/images/login-slides/login_wp_9.jpg'); background-size: cover;">
                <div class="column is-6 is-offset-5 contenido-relativo">
                    <div class="columns is-centered">
                        <div class="column is-12 contenido-hovereable" >
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="slide columns is-vcentered" style="background-color: white;background-image: url('/images/login-slides/login_wp_10.jpg'); background-size: cover;">
                <div class="column is-6 is-offset-5 contenido-relativo">
                    <div class="columns is-centered">
                        <div class="column is-12 contenido-hovereable">
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="slide columns is-vcentered" style="background-color: white;background-image: url('/images/login-slides/login_wp_11.jpg'); background-size: cover;">
                <div class="column is-6 is-offset-5 contenido-relativo">
                    <div class="columns is-centered">
                        <div class="column is-12 contenido-hovereable">
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="slide columns is-vcentered" style="background-color: white;background-image: url('/images/login-slides/login_wp_12.jpg'); background-size: cover;">
                <div class="column is-6 is-offset-5 contenido-relativo">
                    <div class="columns is-centered">
                        <div class="column is-12 contenido-hovereable">
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="slide columns is-vcentered" style="background-color: white;background-image: url('/images/login-slides/login_wp_13.jpg'); background-size: cover;">
                <div class="column is-6 is-offset-5 contenido-relativo">
                    <div class="columns is-centered">
                        <div class="column is-12 contenido-hovereable">
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="slide columns is-vcentered" style="background-color: white;background-image: url('/images/login-slides/login_wp_14.jpg'); background-size: cover;">
                <div class="column is-6 is-offset-5 contenido-relativo">
                    <div class="columns is-centered">
                        <div class="column is-12 contenido-hovereable">
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="slide columns is-vcentered" style="background-color: white;background-image: url('/images/login-slides/login_wp_15.jpg'); background-size: cover;">
                <div class="column is-6 is-offset-5 contenido-relativo">
                    <div class="columns is-centered">
                        <div class="column is-12 contenido-hovereable">
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="slide columns is-vcentered" style="background-color: white;background-image: url('/images/login-slides/login_wp_16.jpg'); background-size: cover;">
                <div class="column is-6 is-offset-5 contenido-relativo">
                    <div class="columns is-centered">
                        <div class="column is-12 contenido-hovereable">
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="slide columns is-vcentered" style="background-color: white;background-image: url('/images/login-slides/login_wp_17.jpg'); background-size: cover;">
                <div class="column is-6 is-offset-5 contenido-relativo">
                    <div class="columns is-centered">
                        <div class="column is-12 contenido-hovereable">
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="slide columns is-vcentered" style="background-color: white;background-image: url('/images/login-slides/login_wp_18.jpg'); background-size: cover;">
                <div class="column is-6 is-offset-5 contenido-relativo">
                    <div class="columns is-centered">
                        <div class="column is-12 contenido-hovereable">
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="slide columns is-vcentered" style="background-color: white;background-image: url('/images/login-slides/login_wp_19.jpg'); background-size: cover;">
                <div class="column is-6 is-offset-5 contenido-relativo">
                    <div class="columns is-centered">
                        <div class="column is-12 contenido-hovereable">
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="slide columns is-vcentered" style="background-color: white;background-image: url('/images/login-slides/login_wp_20.jpg'); background-size: cover;">
                <div class="column is-6 is-offset-5 contenido-relativo">
                    <div class="columns is-centered">
                        <div class="column is-12 contenido-hovereable">
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="slide columns is-vcentered" style="background-color: white;background-image: url('/images/login-slides/login_wp_21.jpg'); background-size: cover;">
                <div class="column is-6 is-offset-5 contenido-relativo">
                    <div class="columns is-centered">
                        <div class="column is-12 contenido-hovereable">
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="slide columns is-vcentered" style="background-color: white;background-image: url('/images/login-slides/login_wp_22.jpg'); background-size: cover;">
                <div class="column is-6 is-offset-5 contenido-relativo">
                    <div class="columns is-centered">
                        <div class="column is-12 contenido-hovereable">
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="slide columns is-vcentered" style="background-color: white;background-image: url('/images/login-slides/login_wp_23.jpg'); background-size: cover;">
                <div class="column is-6 is-offset-5 contenido-relativo">
                    <div class="columns is-centered">
                        <div class="column is-12 contenido-hovereable">
                            
                        </div>
                    </div>
                </div>
            </div>
    </div>
    <div class="modal-content" style="height:100%; max-height: none;">
        <div class="toolbar">
            <div class="container">
                <img src="{{ asset('images/icons/logo_completo.png') }}">
                <button class="button is-outlined is-primary btn-close-modal">Cerrar</button>
            </div>
        </div>

        <div class="container">
            <div class="columns is-multiline">
                <div class="column is-3 form" style="opacity: 0.95; padding-bottom: 1.5rem;border-radius: 25px;box-shadow: 0 0 12px 0 rgba(0,0,0,0.1),0 10px 30px 0 rgba(0,0,0,0.2);padding-left: 2.5rem;padding-right: 2.5rem;padding-top: 0px;margin-top: 30px;">
                    <img class="top-logo" src="{{ asset('images/icons/logo_grande.png') }}" width="width: 25px;">
                    <h2 class="title top-title">Bienvenido a uHomie</h2>
                    <form action="{{ route('login') }}" method="POST" id="form-login"  autocomplete="off">
                        {{ csrf_field() }}
                        
                        <input class="input" type="text" autocomplete="off" name="login" placeholder="Email o Nro. Teléfono" style="text-transform:lowercase;" required>
                        <input class="input" type="password" autocomplete="off" name="password" placeholder="Contraseña" required>
                        
                        
                    
                    <a href="@route('password.request')" style="margin-bottom: 0rem;" class="recovery-link has-text-primary">¿Olvidó su contraseña?</a>
                    <!--
                    <br>
                    <div class="columns">
                        <div class="column is-12" style="max-height: 80px;padding-top: 0px;" >
                            <div style="display: inline-block;" id="captcha_element_login" data-sitekey="{{env('GOOGLE_RECAPTCHA_KEY')}}"></div>
                        </div>
                    </div>
                    -->
                    </form>
                    <hr>
                    <p style="padding: 0 0rem;font-size: 12px;">Al loguearte o registrarte aceptas los <a href="@route('get-terms')">Términos y Condiciones de Uso</a> y <a href="@route('get-terms')">Política de privacidad</a> de uHomie</p>
                    <br>
                    <p style="font-size: 12px;" class="register-option">¿No tiene una cuenta en UHOMIE? <a href="#" class="has-text-primary link-register">Regístrese</a></p>
                    <input type="submit" value="Ingresa" form="form-login" class="button is-outlined is-primary btn-access">
                    <br>
                    <a href="{{url('auth/facebook')}}" style="margin-top: 20px;" type="button" class="button is-small is-facebook">
                        <span class="icon">
                          <i class="fa fa-facebook"></i>
                        </span>
                        <span>Ingresar con facebook</span>
                    </a>
                    <button  onclick="smsLogin();" style="margin-top: 15px;background-color: #07ca00;"  type="button" class="button is-small is-github">
                        <span class="icon">
                          <i class="fa fa-mobile"></i>
                        </span>
                        <span>Ingresar con celular</span>
                    </button>
                </div>
            </div>
            
        </div>
    </div>
</div>
<script>
    (function() {
	
	function Slideshow( element ) {
		this.el = document.querySelector( element );
		this.init();
	}
	
	Slideshow.prototype = {
		init: function() {
			this.wrapper = this.el.querySelector( ".slider-wrapper" );
			this.slides = this.el.querySelectorAll( ".slide" );
            this.hovereables = this.el.querySelectorAll(".contenido-hovereable");
			this.previous = this.el.querySelector( ".slider-previous" );
			this.next = this.el.querySelector( ".slider-next" );
			this.index = 0;
			this.total = this.slides.length;
			this.timer = null;
			this.action();
			this.stopStart();	
		},
		_slideTo: function( slide ) {
			var currentSlide = this.slides[slide];
			currentSlide.style.opacity = 1;
			
			for( var i = 0; i < this.slides.length; i++ ) {
				var slide = this.slides[i];
				if( slide !== currentSlide ) {
					slide.style.opacity = 0;
				}
			}
		},
		action: function() {
			var self = this;
			self.timer = setInterval(function() {
				self.index++;
				if( self.index == self.slides.length ) {
					self.index = 0;
				}
				self._slideTo( self.index );
				
			}, 15000);
		},
		stopStart: function() {
			var self = this;
            for(let i=0; i<self.hovereables.length; i++){
                self.hovereables[i].addEventListener( "mouseover", function() {
                    clearInterval( self.timer );
                    self.timer = null;
                    
                }, false);
                self.hovereables[i].addEventListener( "mouseout", function() {
                    self.action();
                    
                }, false);
            }
			
		}
		
		
	};
	
	document.addEventListener( "DOMContentLoaded", function() {
		
		var slider = new Slideshow( "#login-modal" );
		var slider2 = new Slideshow( "#register-modal" );
	});
	
	
})();

</script>

<script>
  

  // login callback
  function loginCallback(response) {
    if (response.status === "PARTIALLY_AUTHENTICATED") {
      var code = response.code;
      var csrf = response.state;
      console.log('parcialmente autenticado');
      
      var url = "{{url('toolkit-login')}}";
      var form = $('<form action="' + url + '" method="post">' +
        '<input type="text" name="_token" value="' + csrf + '" />' +
        '<input type="text" name="code" value="' + code + '" />' +
        '</form>');
      $('body').append(form);
      form.submit();
      // Send code to server to exchange for access token
    }
    else if (response.status === "NOT_AUTHENTICATED") {
      // handle authentication failure
    }
    else if (response.status === "BAD_PARAMS") {
      // handle bad parameters
    }
  }

  // phone form submission handler
  function smsLogin() {
    AccountKit.login(
      'PHONE',
      {countryCode: '+56'},
      loginCallback
    );
  }

</script>

    
