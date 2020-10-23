@extends('layouts.flujo-base', ['navTitle' => '<span class="bold">Paso 1.3:</span> Tu aval'])

@section('content')
<div class="container" id="ten-first-step-three">
    <section class="section main-section">
        <div class="columns">
            <div class="column is-7 flow-form">
                <div class="div">
                    <form method="POST" action="{{-- route($form_route) --}}" enctype="multipart/form-data" id="registration-form">
                        {{ csrf_field() }}
                        <input type="hidden" name="collateral_email" value="{{ $user->collateral ? $user->collateral->email : '' }}">
                        <div class="field collateral">
                            <div class="label-field">
                                <span>¿Tienes aval?</span>
                            </div>
                            <div class="control">
                                <label class="radio">
                                    <input type="radio" name="collateral" value="1" {{ $user->collateral ? 'checked' : '' }}>
                                    Si
                                </label>
                                <label class="radio">
                                    <input type="radio" name="collateral" value="0" {{ !$user->collateral ? 'checked' : '' }}>
                                    No
                                </label>

                            </div>
                        </div>
                        <div class="field">
                            <div class="label-field">
                                <span>Nombre</span>
                            </div>
                            <input type="text" autocomplete="off" class="input" name="firstname" id="firstname" placeholder="Nombre de tu aval" value="{{ $user->collateral ? $user->collateral->firstname : '' }}">
                        </div>
                        <div class="field">
                            <div class="label-field">
                                <span>Apellido</span>
                            </div>
                            <input type="text" autocomplete="off" class="input" name="lastname" id="lastname" placeholder="Apellido de tu aval" value="{{ $user->collateral ? $user->collateral->lastname : '' }}">
                        </div>
                        <div class="field">
                            <div class="label-field">
                                <span>E-mail</span>
                            </div>
                            <input type="email" autocomplete="off" class="input" name="email" id="email" placeholder="Email de tu aval" value="{{ $user->collateral ? $user->collateral->email : '' }}">
                        </div>
                        <div class="field confirm-email">
                            <div class="label-field">
                                <span>Confirmar E-mail</span>
                            </div>
                            <input type="email" autocomplete="off" class="input" name="confirm_email" id="confirm_email" placeholder="Email de tu aval" value="{{ $user->collateral ? $user->collateral->email : '' }}">
                        </div>
                        <div class="info email_exist_case" style="display:none">

                            <p>La direccion de correo electronico suministrada pertenece a un usuario existente dentro de la plataforma , ¿desea que este usuario le sirva como aval?.</p>
                            <a href="#" class="button is-outlined" id="cancelOperation">Cancelar</a>
                            <input type="submit" class="button is-outlined is-primary" value="Aceptar y Continuar" form="registration-form">
                            <div class="border"></div>
                        </div>
                    </form>
                </div>
                <div class="form-footer email_not_exist_case" >
                    <a href="{{ route( 'users.tenants.first-step.two') }}" class="button is-outlined" >Atrás</a>

                    <img src="{{ asset('images/icono_guardar.png') }}" id="save-button-icon">
                    <input type="submit"  class="button is-outlined is-primary" value="Continuar" form="registration-form" >

                </div>
            </div>
            <div class="column side-info">
                <div class="info">
                    <img src="{{ asset('images/icono-atencion.png') }}">
                    <p>Notificaremos a tu aval para su proceso de registro, recuerda notificarle a tu aval que revise su bandeja de entrada en su correo electrónico para iniciar el proceso.</p>
                </div>
            </div>
        </div>
    </section>
</div>
@include('components.users.common-forms.save-button.modal')
@endsection

@section('scripts')
<script src="{{asset('js/first-step-three.js')}}" charset="utf-8"></script>
@endsection


{{-- @php
	$header = ["title"=>"PASO 3:  " , "subtitle" => "  Descripcion paso 3"];
@endphp
@extends('layouts.multi-form')
@section('custom-css')
@endsection
@section('content')
	<!-----Content section----->
	    <div class="ndiv Content-section">
	      <div class="ndiv DashTheme ThemeProgressbar Step-1">
	        <!-- progress bar -->
	      </div>
	      <div class="ndiv dash-inner-bracketv2">
	        <div class="ndiv page-container-v2">
	          <div class="ndiv page-home-container">
	            <div class="ndiv entry-page">
	              <div class="ndiv ep-section-one">
	                <div class="ndiv fields-base">

	                  <div class="ndiv field-yes-no-holder">
	                  <p class="pmzero ndiv qus-yn">
	                    ¿Tienes aval?

	                  </p>

	                  <div class="ndiv answer-radio-holder">
	                    <a href="{{route('users.tenants.fourth-step-check',1)}}">
<label class="container-docname radiov2">Si
    <input type="radio" name="radio" value="1">
    <span class="checkmark"></span>
</label>
</a>
<a href="{{route('users.tenants.fourth-step-check',0)}}">
    <label class="container-docname radiov2">No
        <input type="radio" name="radio" value="0">
        <span class="checkmark"></span>
    </label>
</a>
</div>
</div>

</div>

<div class="ndiv page-nav-button-holder">
    <a href="{{route('users.tenants.third-step')}}" class="DashTheme ThemeButton ThemeMedium ThemeGreay">
        <span class="but-icon back-icon">
        </span>
        ATRÁS
    </a>
</div>
</div>
<div class="ndiv ep-section-two">

    <div class="ndiv ep-inner-holder">

        <div class="ndiv ThemeTipBox TipBlue TipBgLarge">
            <ul class="ndiv bullet_free">
                <li>Recuerda que al no tener un aval quedará en el propietario los meses adelantado que pedirá.</li>
            </ul>
        </div>

    </div>

</div>
</div>
</div>
</div>
</div>
</div>
<!----/Content section----->
@endsection
@section('custom-js')
@endsection --}}
