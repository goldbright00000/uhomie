@extends('layouts.flujo-base')

@section('content')
<div class="container" id="service-second-step">
    <section class="section main-section">
        <div class="columns">
            <div class="column is-7 flow-form">
                <div>

                    <div class="edit-step">
                        <div>
                            <span>Paso 1</span>
                            <span>Tus datos personales</span>
                        </div>
                        <div class="edit-right">
                            <img src="{{ asset('images/icono-ok.png') }}">
                            <a href="{{route('users.services.first-step')}}">Editar</a>
                        </div>
                    </div>
                    <h1 class="title step-title">Paso 2</h1>
                    <section class="hero main-title">
                        <div class="hero-body">
                            <div class="container">
                                <h1 class="title">Tu servicio</h1>
                            </div>
                        </div>
                    </section>
                    <span>Cuentanos que tipo y</span><br>
                    <span>caractersticas posee </span><br>
                    <span>el servicio que quieres</span><br>
                    <span>publicar.</span>
                </div>

                <div class="form-footer">
                    <a href="{{ route('users.services.first-step.two') }}" class="button">Atrás</a>
                    <a href="{{ route('users.services.second-step.one') }}" class="button is-outlined is-primary">Continuar</a>
                </div>
            </div>
            <div class="column side-info small">
                <div class="info small">
                    <img src="{{ asset('images/foco.png') }}">
                    <p>Recuerda llenar todos los</p>
                    <p>items de tu servicios</p>
                    <div class="border"></div>
                </div>
                <div class="info small">
                    <img src="{{ asset('images/foco.png') }}">
                    <p>En UHOMIE puedes publicar</p>
                    <p>todos los servicios que desees</p>
                    <div class="border"></div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
