@extends('layouts.flujo-base')

@section('content')
<div class="container" id="first-step">
    <section class="section main-section">
        <div class="columns">
            <div class="column is-7">
                <h1 class="title step-title">Paso 1</h1>
                <section class="hero main-title">
                    <div class="hero-body">
                        <div class="container">
                            <h1 class="title">Tus Datos</h1>
                            <h1 class="title">Personales</h1>
                        </div>
                    </div>
                </section>
                <span class="bold">Hola {{$user->fullname}},</span>
                <span>Vamos a ayudarte a configurar</span>
                <span>la publicación de tu servicio</span>
                <a href="{{ route('users.services.first-step.one') }}" class="button is-outlined is-primary btn-continue">Continuar</a>
            </div>
            <div class="column side-info small">
                <div class="info small">
                    <img src="{{ asset('images/foco.png') }}">
                    <p>Podrás publicar todos los servicios que desees.</p>
                    <p>Vive la experiencia UHOMIE</p>
                    <div class="border"></div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
