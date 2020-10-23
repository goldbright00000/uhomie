@extends('layouts.flujo-base', ['navTitle' => '<span class="bold">Paso 3.3:</span> Tus documentos de soporte'])

@section('content')
<div class="container" id="ten-third-step-four">
    <section class="section main-section">
        <div class="columns">
            <div class="column is-7 flow-form">
                <div class="div">
                    <form method="POST" action="{{-- route($form_route) --}}" enctype="multipart/form-data" id="registration-form">
                        {{ csrf_field() }}
                        <div class="form-info">
                            <div class="text">
                                <span>Solicitamos opcionalmente</span>
                                <span>el Reporte de DICOM.</span>
                                <span>Informe de Arriendo</span>
                            </div>
                            <img src="{{ asset('images/icono-atencion.png') }}">
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

                        @if ($user->checkEmploymentOwner())
                            @include('users.tenants.forms.employment.own-files')
                        @elseif ($user->checkEmploymentEmployee())
                            @include('users.tenants.forms.employment.employee-files')
                        @elseif ($user->checkEmploymentUnemployed())
                            @include('users.tenants.forms.employment.unemployed-files')
                        @endif

                        @include('components.common.files', [
                            'title' => "Certificado de DICOM",
                            'name' => 'dicom',
                            'mimes' => 'application/pdf',
                            'file' => $user->files()->where('name', 'dicom')->first()
                         ])
                        @if ($user->other_income_amount > 0)
                            @include('components.common.files', [
                                'title' => "Certificado de otros ingresos mensuales",
                                'name' => 'other_income',
                                'mimes' => 'application/pdf',
                                'file' => $user->files()->where('name', 'other_income')->first()
                             ])
                        @endif
                    </form>
                </div>
                <div class="form-footer">
                    <a href="{{route('users.tenants.third-step.three')}}" class="button is-outlined">Atrás</a>
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
