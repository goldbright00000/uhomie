@extends('layouts.flujo-base')

@section('content')
<div class="container" id="own-second-step-page">
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

                    <form method="POST">
                        {{ csrf_field() }}
                        <div class="columns">
                            <div class="column">
                                <h1 class="title step-title">Paso 1</h1>
                                <section class="hero main-title">
                                    <div class="hero-body">
                                        <div class="container">
                                            <h1 class="title">Configuración</h1>
                                            <h1 class="title">de tu propiedad</h1>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>
                        <div class="columns is-vcentered">
                            <div class="column">
                                <div>Cuentanos que tipo y</div>
                                <div>caractersticas posee </div>
                                <div>la propiedad que quieres</div>
                                <div>publicar.</div>
                            </div>
                            <div class="column">
                                <div class="control">
                                    <label class="radio">
                                        <input type="radio" name="is_project" id="is_project" value="0" {{ isset($project) && $project->is_project == '0' ? 'checked' : '' }} required>
                                        Propiedad para arrendar
                                    </label>
                                    <br>
                                    <label class="radio">
                                        <input type="radio" name="is_project" id="is_project" value="1" {{ isset($project) && $project->is_project == '1' ? 'checked' : '' }} required>
                                        Propiedad para vender
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-footer">
                            <a href="{{ route('users.owners.first-step.two') }}" class="button">Atrás</a>
                            <button type="submit" class="button is-outlined is-primary">Continuar</button>
                        </div>
                    </form>

                </div>
            </div>
            <div class="column side-info small">
                <div class="info small">
                    <img src="{{ asset('images/foco.png') }}">
                    <p>Esto te permitira destacar tu</p>
                    <p>propiedad. Cuanto mas</p>
                    <p>especifico seas mejor.</p>
                    <div class="border"></div>
                </div>
                <br><br>
                <div class="info small">
                    <img src="{{ asset('images/icono_info.png') }}">
                    <p>Te recomendamos tener a la mano tus</p>
                    <p>certificados de servicios como luz,</p>
                    <p>Agua y certificado de Dominio Vigente.</p>
                    <div class="border"></div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
@section('scripts')
  <script src="{{ asset('js/property-forms/first-step.js') }}" charset="utf-8"></script>
@endsection
