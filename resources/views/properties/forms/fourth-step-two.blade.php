@extends('layouts.flujo-base', ['navTitle' => '<span class="bold">Paso 4.2:</span> Gestion de Propiedad', 'close' => route('profile.owner')])
@section('content')

    <div class="container" id="own-fifth-step-one">
        <section class="section main-section">
            <div class="columns">
                <div class="column is-7 flow-form">
                    <div class="div">
                        <form method="POST" action="" enctype="multipart/form-data" id="registration-form">
                            {{ csrf_field() }}
                            <div class="columns">
                                <div class="column">
                                    <div style="margin-left: 20px;">
                                        <ol>
                                            <li value="1">Recaudación y liquidación de arriendo</li>
                                            <li value="2">Control mensual de pagos de cuentas de servicios y gastos comunes</li>
                                            <li value="3">Gestionar Mantención de preventiva de propiedades</li>
                                            <li value="4">Reparaciones de propiedad</li>
                                            <li value="5">Asesoria y cobranza judicial</li>
                                            <li value="6">Visitas periodicas para revisar el estado del inmueble</li>
                                            <li value="7">Recepcion de la propiedad</li>
                                            <li value="8">Liquidación de la garantia</li>
                                            <li value="9">Finiquito de arriendo</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                            <div class="columns">
                                <div class="column">
                                    <article class="message is-success">
                                        <div class="message-body">
                                            <strong>6%</strong> del canon mensual del arriendo.
                                        </div>
                                    </article> 
                                </div>
                            </div>
                            <div class="columns">
                                <div class="column">
                                    <div class="label">
                                        <span>¿Deseas que tus propiedades las gestione Uhomie?</span>
                                    </div>
                                    <div class="control">
                                        <label class="radio">
                                            <input type="radio" name="manage" id="manage" value="1" {{ $property && $property->manage == '1' ? 'checked' : ''}}>
                                            Si
                                        </label>
                                        <label class="radio">
                                            <input type="radio" name="manage" id="manage" value="0" {{ $property && $property->manage == '0' ? 'checked' : is_null($property->manage) ? 'checked' : '' }} >
                                            No
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-footer">
                            <a href="{{route($back,['id' => $property->id])}}" class="button is-outlined">Atrás</a>
                            <img src="{{ asset('images/icono_guardar.png') }}" id="save-button-icon">                            <input type="submit" class="button is-outlined is-primary" value="Continuar" form="registration-form">
                        </div>
                    </div>
                    <div class="column side-info">
                        <div class="info">
                            <img src="{{ asset('images/icono-atencion.png') }}">
                            <p>En UHOMIE te hacemos la vida mas facil</p>
                            <div class="border"></div>
                        </div>
                        <div class="info">
                            <img src="{{ asset('images/icono-atencion.png') }}">
                            <p>Al permitir que Uhomie gestione su propiedad</p>
                            <p>ud no se preocupara por tratar con los arrandatarios</p>
                            <p>todo los gastos vendran por parte de el cobro del canon.</p>
                            <div class="border"></div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        @include('components.users.common-forms.save-button.modal')
    @endsection

    @section('scripts')
        <script src="{{ asset('js/property-forms/four-step-one.js') }}" charset="utf-8"></script>
        <script src="{{ asset('js/third-step-three.js') }}"></script>
    @endsection
