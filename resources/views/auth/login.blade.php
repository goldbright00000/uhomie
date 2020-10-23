<!--<div class="modal modal-access">
    <div class="modal-background"></div>
    <div class="modal-content">
        <div class="toolbar">
            <div class="container">
                <img src="{{ asset('images/icons/logo_completo.png') }}">
                <button class="button is-outlined is-primary btn-close-modal">Cerrar</button>
            </div>
        </div>

        <div class="container">
            <div class="columns is-multiline">
                <div class="column is-12">
                    <img class="top-logo" src="{{ asset('images/icons/logo_grande.png') }}">
                    <h1 class="title top-title">Bienvenido a uHomie</h1>
                </div>
                <div class="column is-6 is-offset-3 form">
                    <form action="{{ route('login') }}" method="POST" id="form-login">
                        {{ csrf_field() }}
                        <input class="input" type="email" autocomplete="off" name="email" placeholder="Email" required>
                        <input class="input" type="password" autocomplete="off" name="password" placeholder="Contraseña" required>
                    </form>
                    <a href="#" class="recovery-link has-text-primary">¿Olvidó su contraseña?</a>
                    <hr>
                    <p>Al loguearte o registrarte aceptas los <a href="#">Términos y Condiciones de Uso</a> y <a href="#">Política de privacidad</a> de uHomie</p>
                </div>
                <div class="column is-6 is-offset-3">
                    <p class="register-option">¿No tiene una cuenta en UHOMIE? <a href="#" class="has-text-primary">Regístrese</a></p>
                    <input type="submit" value="Ingresa" form="form-login" class="button is-outlined is-primary btn-access">
                </div>
            </div>
        </div>
    </div>
</div>-->

@extends('layouts.flujo-base', ['navTitle' => '<span class="bold">Inicio de Sesión</span>'])

@section('content')
    <div class="container" id="first-step">
        <section class="section main-section">
            <div class="columns">
                <div class="column is-7">
                    <section class="hero main-title">
                        <div class="hero-body">
                            <div class="container">
                                <h1 class="title">Inicia Sesión</h1>
                            </div>
                        </div>
                    </section>
                    <span class="bold">Hola,</span>
                    <span>Tu sesion ha expirado</span>
                    <span>introduce tu datos de sesión para ingresar</span>
                    <hr>
                    @if (session('status'))
                        <span class="bold">
                            {{ session('status') }}
                        </span>
                        <hr>
                    @endif
                    <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}
                        <div class="field">
                            <div class="label-field">
                                <span>Introduce el email o telefono asociado a tu cuenta</span>
                            </div>
                            <input type="text" autocomplete="off" name="login" class="input" value="{{ old('email') }}" required>
                        </div>
                        @if ($errors->has('login'))
                            <span class="help-block">
                                <strong>{{ $errors->first('login') }}</strong>
                            </span>
                        @endif
                        
                        <div class="field">
                            <div class="label-field">
                                <span>Introduce tu contraseña</span>
                            </div>
                            <input type="password" autocomplete="off" id="password-register" name="password" class="input" required>
                        </div>
                        @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                        <div class="field input-validation-wrapper">
                            <div class="validation-block">
                                <p>La contraseña debe incluir:</p>
                                <ul>
                                    <li class="rule min max"><span class="fa fa-times"></span> 8-20 <span>Caracteres</span></li>
                                    <li class="rule uppercase"><span class="fa fa-times"></span>Al menos <span>una letra mayúscula</span></li>
                                    <li class="rule digits"><span class="fa fa-times"></span>Al menos <span>un número</span></li>
                                    <li class="rule spaces success"><span class="fa fa-check"></span>Sin espacios</li>
                                    <li class="rule symbols"><span class="fa fa-times"></span>Al menos <span>1 caracter especial</span></li>
                                </ul>
                            </div>
                        </div>
                        <!--<div class="row" style="text-align: center;max-height: 80px;" >
                            <div style="display: inline-block;" id="email_pass" data-sitekey="{{env('GOOGLE_RECAPTCHA_KEY')}}"></div>
                        </div>-->
                        <div class="has-text-centered">
                            <button type="submit" class="button is-outlined is-primary btn-continue">Ingresar</button>
                        </div>
                    </form>
                </div>
                <div class="column side-info small">
                    <div class="info small">
                        <img src="{{ asset('images/foco.png') }}">
                        <p>Recuerda llenar con información correcta.</p>
                        <div class="border"></div>
                    </div>
                    <br>
                    <div class="info small">
                        <img src="{{ asset('images/foco.png') }}">
                        <p>Si no recuerda su contraseña puede recuperarla <a href="@route('password.request')" class="recovery-link has-text-primary">aqui</a></p>
                        <div class="border"></div>
                        
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@section('scripts')
	<script type="text/javascript" src="{{ asset("js/collateral-forms/first-step.js") }}">

    </script>
    <script type="text/javascript">
        /* RE_CAPTCHA DE GOOGLE
        
        var onloadCallback = function() {
          if ( $('#email_pass').length ) {
            grecaptcha.render('email_pass', {
              'sitekey' : "{{ env('GOOGLE_RECAPTCHA_KEY') }}"
            });
          }
            
    
        };*
      </script>
@endsection
