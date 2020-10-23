@extends('layouts.flujo-base', ['navTitle' => '<span class="bold">Paso 1.2:</span> Tus datos personales'])
@section('content')

<div class="container" id="collateral-second-step">
    <section class="section main-section">
        <div class="columns">
            <div class="column is-7 flow-form">
              <div class="div">
                  <form method="POST" action="{{ route('users.collaterals.second-step') }}" enctype="multipart/form-data" id="registration-form">
                    @include('components.users.common-forms.basic-data.form2', [
                    'countries' => $countries,
                    'civil_status' => $civil_status,
                    'user' => isset($user) ? $user : null,
                    'back_url' => route('users.collaterals.first-step')
                    ])
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
    <script src="{{ asset('js/basic-data.js') }}"></script>
@endsection
