@extends('layouts.flujo-base', ['navTitle' => '<span class="bold">Paso 1.1:</span> Verifica tus Datos'])
@section('styles')
	<link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endsection
@section('content')
	<div class="container" id="collateral-second-step">
	    <section class="section main-section">
	        <div class="columns">
	            <div class="column is-7 flow-form">
	                <div class="div">
	                    <form method="POST" action="" enctype="multipart/form-data" id="registration-form">
							{{ csrf_field() }}
							<div class="field">
	                            <div class="label-field">

	                                <span>Nombre</span>
	                            </div>
	                            <input type="text" autocomplete="off" class="input" name="firstname" value="{{$user ? $user->firstname : ''}}" required>
	                        </div>
							<div class="field">
	                            <div class="label-field">

	                                <span>Apellido</span>
	                            </div>
	                            <input type="text" autocomplete="off" class="input" name="lastname" value="{{$user ? $user->lastname : ''}}" required>
	                        </div>
							<div class="field">
								<div class="label-field">

									<span>Correo Electronico</span>
								</div>
								<input type="email" autocomplete="off" class="input" name="email" value="{{$user ? $user->email : ''}}" readonly>
							</div>
							@if ($user->created_by_reference)
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
							@endif
	                </div>
	                <div class="form-footer">
	                    <a href="" class="button is-outlined">Atrás</a>
	                    <img src="{{ asset('images/icono_guardar.png') }}" id="save-button-icon">
	                    <input type="submit" class="button is-outlined is-primary" value="Continuar" form="registration-form">
	                </div>
	            </div>
	            <div class="column side-info">
	                <div class="info">
	                    <img src="{{ asset('images/icono-atencion.png') }}">
	                    <p>En el caso que elijas RUT, en la última etapa deberás adjuntar las fotos de anverso y reverso de tu carnet.</p>
	                    <div class="border"></div>
	                </div>
	                <div class="info">
	                    <img src="{{ asset('images/icono-atencion.png') }}">
	                    <p>En el caso que elijas Pasaporte, en la última etapa deberás adjuntar las fotos de la página con tus datos y la página de tu último visado.</p>
	                    <div class="border"></div>
	                </div>
	                <div class="info">
	                    <img src="{{ asset('images/icono-atencion.png') }}">
	                    <p>Estos datos sólo serán compartidos por ti en cada postulación en las propiedades que desees. UHOMIE empleará estos datos para confeccionar el contrato de arriendos digital. En UHOMIE tu tienes el control!</p>
	                    <div class="border"></div>
	                </div>
	            </div>
	        </div>
	    </section>
	</div>
	@include('components.users.common-forms.save-button.modal')
@endsection
@section('scripts')
	<script type="text/javascript" src="{{ asset("js/collateral-forms/first-step.js") }}">

	</script>
@endsection
