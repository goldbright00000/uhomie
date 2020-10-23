@extends('layouts.flujo-base', ['navTitle' => '<span class="bold">Paso 2:</span> Tus documentos'])
@section('content')
<div class="container">
	<section class="section main-section">
        <div class="columns">
            <div class="column is-7 flow-form">
                <div class="div">
                    <form method="POST" action="{{-- route($form_route) --}}" enctype="multipart/form-data" id="registration-form">
                        {{ csrf_field() }}
						<div class="form-info">
							<div class="text">
								<img src="{{ asset('images/icono-atencion.png') }}">
								<span>Solicitamos opcionalmente</span>
								<span>el Reporte de DICOM.</span>
								<span>Informe de Arriendo</span>
							</div>
						</div>
						<div class="download-link">
							<img src="{{ asset('images/icono-descargar.png') }}">
							<a href="https://soluciones.equifax.cl/" target="_blank">lo puedes descargar de aquí</a>
						</div>
						<h1 class="form-title">Paso 1. Tu identidad <span class="form-subtitle">(Documentos formato JPG/PNG)</span></h1>
						@include('components.common.files', [
							'title' => "Rut (Foto anverso) / Pasaporte (Foto Hoja Principal)",
							'name' => 'id_front',
							'mimes' => 'image/*',
							'file' => $user->files()->where('name', 'id_front')->first()
						 ])
						 @include('components.common.files', [
							 'title' => "Rut (Foto reverso) / Pasaporte (Foto Visado)",
							 'name' => 'id_back',
							 'mimes' => 'image/*',
							 'file' => $user->files()->where('name', 'id_back')->first()
						  ])
                        <h1 class="form-title">Paso 2. Tus datos laborales <span class="form-subtitle">(Documentos en PDF)</span></h1>
                            @include('users.tenants.forms.employment.employee-files')
							@include('components.common.files', [
	                            'title' => "Certificado de DICOM",
	                            'name' => 'dicom',
	                            'mimes' => 'application/pdf',
	                            'file' => $user->files()->where('name', 'dicom')->first()
	                         ])
                    </form>
                </div>
                <div class="form-footer">
                    <a href="{{route('users.collaterals.fourth-step-start')}}" class="button is-outlined">Atrás</a>
                    <img src="{{ asset('images/icono_guardar.png') }}" id="save-button-icon">
                    <input type="submit" class="button is-outlined is-primary" value="Continuar" form="registration-form">
                </div>
            </div>
            <div class="column side-info">
                <div class="info">
                    <img src="{{ asset('images/icono-atencion.png') }}">
                    <p>Si no posees todos los documentos, no te preocupes podrás siempre actualizarlos desde tu perfil al completar el proceso de registro. Puedes continuar y luego cargar los documentos que te falten.</p>
                    <div class="border"></div>
                </div>
                <div class="info">
                    <img src="{{ asset('images/icono-atencion.png') }}">
                    <p>Se recomienda cargar los documentos actualizados y vigentes en un plazo máximo de 48 hs para poder incrementar tu SCORING UHOMIE.</p>
                    <div class="border"></div>
                </div>
                <div class="info">
                    <img src="{{ asset('images/icono-atencion.png') }}">
                    <p>Te recomendamos documentos vigentes y de un plazo máximo de 30 días desde su emisión.</p>
                    <div class="border"></div>
                </div>
            </div>
        </div>
    </section>
</div>
@include('components.users.common-forms.save-button.modal')
@endsection
@section('scripts')
<script src="{{ asset('js/third-step-three.js') }}"></script>
@endsection
