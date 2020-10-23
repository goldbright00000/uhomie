@extends('layouts.flujo-base', ['navTitle' => '<span class="bold">Recuperación de contraseña:</span> Tu email'])

@section('content')
    <div class="container" id="first-step">
        <section class="section main-section">
            <div class="columns">
                <div class="column is-7">
                    <section class="hero main-title">
                        <div class="hero-body">
                            <div class="container">
                                <h1 class="title">Tu</h1>
                                <h1 class="title">Email</h1>
                            </div>
                        </div>
                    </section>
                    <span class="bold">Hola,</span>
                    <span>Vamos a ayudarte a recuperar</span>
                    <span>el acceso a tu cuenta.</span>
                    <hr>
                    @if (session('status'))
                        <span class="bold">
                            {{ session('status') }}
                        </span>
                        <hr>
                    @endif
                    <form class="form-horizontal" method="POST" action="{{ route('password.email') }}">
                        {{ csrf_field() }}
                        <div class="field">
                            <div class="label-field">
                                <span>Introduce el email asociado a tu cuenta</span>
                            </div>
                            <input type="email" autocomplete="off" name="email" class="input" value="{{ old('email') }}" required>
                        </div>

                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif

                        <br>
                        <div class="row" style="text-align: center;max-height: 80px;" >
                            <div style="display: inline-block;" id="captcha_element_olvida_password" data-sitekey="{{env('GOOGLE_RECAPTCHA_KEY')}}"></div>
                        </div>
                        
                        <button type="submit" class="button is-outlined is-primary btn-continue">Continuar</button>
                    </form>
                </div>
                <div class="column side-info small">
                    <div class="info small">
                        <img src="{{ asset('images/foco.png') }}">
                        <p>Recuerda llenar con información correcta.</p>
                        <div class="border"></div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@section('flow-scripts')
 <!-- CAPTCHA -->
 <script type="text/javascript">
    var onloadCallback = function() {
      if ( $('#captcha_element_olvida_password').length ) {
        grecaptcha.render('captcha_element_olvida_password', {
          'sitekey' : "{{ env('GOOGLE_RECAPTCHA_KEY') }}"
        });
      }
        

    };
  </script>
@endsection
