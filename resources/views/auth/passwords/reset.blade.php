@extends('layouts.flujo-base', ['navTitle' => '<span class="bold">Recuperación de contraseña:</span> Tu nueva Contraseña'])

@section('content')
    <div class="container" id="first-step">
        <section class="section main-section">
            <div class="columns">
                <div class="column is-7">
                    <section class="hero main-title">
                        <div class="hero-body">
                            <div class="container">
                                <h1 class="title">Tu</h1>
                                <h1 class="title">Nuevo Acceso</h1>
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
                    <form class="form-horizontal" method="POST" action="{{ route('password.request') }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="token" value="{{ $token }}">
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
                        <div class="field">
                            <div class="label-field">
                                <span>Introduce tu nueva contraseña</span>
                            </div>
                            <input type="password" autocomplete="off" id="password-register" name="password" class="input" required>
                        </div>
                        @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                        <div class="field">
                            <div class="label-field">
                                <span>Confirmanos tu contraseña</span>
                            </div>
                            <input type="password" autocomplete="off" name="password_confirmation" class="input" required>
                        </div>
                        @if ($errors->has('password_confirmation'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                            </span>
                        @endif




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
@section('scripts')
	<script type="text/javascript" src="{{ asset("js/collateral-forms/first-step.js") }}">

	</script>
@endsection
