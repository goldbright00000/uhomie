@extends('layouts.flujo-base', ['close' => route('profile.owner')])

@section('content')
<div class="container" id="third-step-page">
    <section class="section main-section">
        <div class="columns">
            <div class="column is-7 flow-form">
                <div>
                    <div class="edit-step">
                        <div>
                            <span>Paso 1</span>
                            <span>Configura tu Propiedad</span>
                        </div>
                        <div class="edit-right">
                            <img src="{{ asset('images/icono-ok.png') }}">
                            <a href="{{ route('properties.first-step', isset($property) ? $property->id : null) }}">Editar</a>
                        </div>
                    </div>

                    <h1 class="title step-title">Paso 2</h1>
                    <section class="hero main-title">
                        <div class="hero-body">
                            <div class="container">
                                <h1 class="title">{{$message1}}</h1>
                            </div>
                        </div>
                    </section>
                </div>
                <div class="form-footer">
                    <a href="{{ route($back,['id' => $property->id]) }}" class="button">Atrás</a>
                    <a href="{{ route('properties.second-step.one',['id' => $property->id]) }}" class="button is-outlined is-primary">Continuar</a>
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
