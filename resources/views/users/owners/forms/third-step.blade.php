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
                            <a href="{{ route('users.owners.first-step') }}">Editar</a>
                        </div>
                    </div>
                    <div class="edit-step">
                        <div>
                            <span>Paso 2</span>
                            <span>Configuración</span>
                        </div>
                        <div class="edit-right">
                            <img src="{{ asset('images/icono-ok.png') }}">
                            <a href="{{ route('users.owners.second-step') }}">Editar</a>
                        </div>
                    </div>

                    <h1 class="title step-title">Paso 3</h1>
                    <section class="hero main-title">
                        <div class="hero-body">
                            <div class="container">
                                <h1 class="title">{{$message1}}</h1>
                            </div>
                        </div>
                    </section>
                </div>
                <div class="form-footer">
                    <a href="{{ route($back) }}" class="button">Atrás</a>
                    <a href="{{ route('users.owners.third-step.one') }}" class="button is-outlined is-primary">Continuar</a>
                </div>
            </div>
            <div class="column side-info small">
                <div class="info small">
                    <img src="{{ asset('images/foco.png') }}">
                    <p>{{$message2}}</p>
                    <div class="border"></div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
