@extends('layouts.flujo-base')

@section('content')
<div class="container" id="fifth-step">
    <section class="section main-section">
        <div class="columns">
            <div class="column is-7 flow-form">
                <div>
                    <form method="POST" action="{{ route('users.tenants.second-step') }}" enctype="multipart/form-data" id="registration-form">
                        {{ csrf_field() }}
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
                        <h1 class="title step-title">Paso 2</h1>
                        <section class="hero main-title">
                            <div class="hero-body">
                                <div class="container">
                                    <h1 class="title">Tus Datos</h1>
                                    <h1 class="title">Laborales</h1>
                                </div>
                            </div>
                        </section>
                        <span class="question">¿De qué trabajas?</span>
                        <div class="control">
                            <label class="radio">
                                <input type="radio" name="employment_type" value="1" {{ $user->checkEmploymentEmployee() ? 'checked' : '' }} required="required">
                                Empleado
                            </label>
                            <label class="radio">
                                <input type="radio" name="employment_type" value="2" {{ $user->checkEmploymentOwner() ? 'checked' : '' }} required="required">
                                Cuenta propia
                            </label>
                            <label class="radio">
                                <input type="radio" name="employment_type" value="3" {{ $user->checkEmploymentUnemployed() ? 'checked' : '' }} required="required">
                                Desempleado
                            </label>
                        </div>
                    </form>
                </div>
                <div class="form-footer">
                    <a href="{{route('users.tenants.first-step.three')}}" class="button">Atrás</a>
                    <input type="submit" class="button is-outlined is-primary" value="Continuar" form="registration-form">
                </div>
            </div>
            <div class="column side-info">
                <div class="info small">
                    <img src="{{ asset('images/foco.png') }}">
                    <p>Recuerda que esto ayudará a tener una mejor evaluación cuando aplicas.</p>
                    <div class="border"></div>
                </div>
                <div class="info">
                    <img src="{{ asset('images/icono-atencion.png') }}">
                    <p>Te recomendamos tener a la mano tus documentos de respaldo como Liquidaciones de Sueldo. Contrato de Trabajo. Certificados de AFP entre otros según tu Tipo de Trabajo.</p>
                    <div class="border"></div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/second-step.js') }}" charset="utf-8"></script>
@endsection

