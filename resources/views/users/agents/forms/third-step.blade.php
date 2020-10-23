@extends('layouts.flujo-base', ['close' => route('profile.agent')])

@section('content')
<div class="container" id="agent-third-step">
    <section class="section main-section">
        <div class="columns">
            <div class="column is-7 flow-form">
                <form method="POST" action="">
                    {{ csrf_field() }}
                    <div>
                        <!-- <div class="edit-step">
                            <div>
                                <span>Paso 1</span>
                                <span>Tus datos personales</span>
                            </div>
                            <div class="edit-right">
                                <img src="{{ asset('images/icono-ok.png') }}">
                                <a href="{{route('users.agents.first-step')}}">Editar</a>
                            </div>
                        </div> -->
                        <!-- <h1 class="title step-title">Paso 2</h1> -->
                        <section class="hero main-title">
                            <div class="hero-body">
                                <div class="container">
                                    <h1 class="title">Tu nuevo proyecto</h1>
                                </div>
                            </div>
                        </section>
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
                    </div>
                    <div class="form-footer">
                        <button type="submit" class="button is-outlined is-primary">Continuar</button>
                    </div>
                </form>
            </div>
            <div class="column side-info small">
                <div class="info small">
                    <img src="{{ asset('images/foco.png') }}">
                    <p>Recuerda llenar todos los</p>
                    <p>items de tu proyecto</p>
                    <div class="border"></div>
                </div>
                <div class="info small">
                    <img src="{{ asset('images/foco.png') }}">
                    <p>En UHOMIE puedes publicar</p>
                    <p>todos los proyectos que desees</p>
                    <div class="border"></div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
