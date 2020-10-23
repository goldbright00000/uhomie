@extends('layouts.flujo-base')

@section('content')
<div class="container" id="third-step-page">
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
                            <a href="{{route('users.tenants.first-step')}}">Editar</a>
                        </div>
                    </div>
                    <div class="edit-step">
                        <div>
                            <span>Paso 2</span>
                            <span>Tus datos laborales</span>
                        </div>
                        <div class="edit-right">
                            <img src="{{ asset('images/icono-ok.png') }}">
                            <a href="{{route('users.tenants.second-step')}}">Editar</a>
                        </div>
                    </div>
                    <h1 class="title step-title">Paso 3</h1>
                    <section class="hero main-title">
                        <div class="hero-body">
                            <div class="container">
                                <h1 class="title">Tus preferencias</h1>
                                <h1 class="title">para arrendar</h1>
                            </div>
                        </div>
                    </section>
                </div>
                <div class="form-footer">
                    <a href="{{ route('users.tenants.second-step') }}" class="button">Atrás</a>
                    <a href="{{ route('users.tenants.third-step.one') }}" class="button is-outlined is-primary">Continuar</a>
                </div>
            </div>
            <div class="column side-info small">
                <div class="info small">
                    <img src="{{ asset('images/foco.png') }}">
                    <p>Todos los datos que configures te ayudarán a encontrar tus opciones propiedades en menor tiempo.</p>
                    <div class="border"></div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
