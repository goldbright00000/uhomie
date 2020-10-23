@extends('layouts.flujo-base', ['navTitle' => '<span class="bold">Paso 3.2:</span> Tus preferencias de arriendo'])

@section('content')
<div class="container" id="ten-third-step-two">
    <section class="section main-section">
        <div class="columns">
            <div class="column is-7 flow-form">
                <div class="div">
                    <form method="POST" action="{{-- route($form_route) --}}" enctype="multipart/form-data" id="registration-form">
                        {{ csrf_field() }}
                        <div class="field">
                            <div class="label-field">
                                <img src="{{ asset('images/icono-casa.png') }}">
                                <span>Tipo de propiedad que prefieres</span>
                            </div>
                            <div class="select">
                                <select name="property_type">
                                    @foreach ($property_types as $pt)
                                        <option {{ $user->property_type == $pt->id ? 'selected' : '' }} value="{{$pt->id}}">{{$pt->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="field">
                            <div class="label-field">
                                <img src="{{ asset('images/icono-estrella.png') }}">
                                <span>Condición</span>
                            </div>
                            <div class="select">
                                <select name="property_condition">
                                    @foreach ($property_conditions as $key => $value)
                                        <option {{ $user->property_condition == $key ? 'selected' : '' }} value="{{$key}}" >{{$value}}</option>
                                    @endforeach
                                    <option {{ $user->property_condition == null ? 'selected' : '' }} value="2" >Indiferente</option>
                                </select>
                            </div>
                        </div>
                        <div class="field">
                            <div class="label-field">
                                <img src="{{ asset('images/icono-familia.png') }}">
                                <span>Busco propiedad para</span>
                            </div>
                            <div class="select">
                                <select name="property_for">
                                    @foreach ($properties_for as $pf)
                                        <option {{ $user->property_for == $pf->id ? 'selected' : '' }} value="{{$pf->id}}">{{$pf->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="field">
                            <div class="label-field">
                                <img src="{{ asset('images/icono-amoblado.png') }}">
                                <span>Amoblado</span>
                            </div>
                            <div class="control">
                                <label class="radio">
                                    <input type="radio" name="furnished" value="1" {{ $user->furnished ? 'checked' : ''}}>
                                    Si
                                </label>
                                <label class="radio">
                                    <input type="radio" name="furnished" value="0" {{ !$user->furnished ? 'checked' : ''}}>
                                    No
                                </label>
                            </div>
                        </div>
                        <div class="field">
                            <div class="label-field">
                                <img src="{{ asset('images/icono-mascota.png') }}">
                                <span>Tienes Mascotas?</span>
                            </div>
                            <div class="control">
                                <label class="radio">
                                    <input type="radio" name="pet_preference" value="dog" {{ $user->pet_preference == 'dog' ? 'checked' : ''}}>
                                    <img src="{{ asset('images/icono-perro.png') }}">
                                </label>
                                <label class="radio">
                                    <input type="radio" name="pet_preference" value="cat" {{ $user->pet_preference == 'cat' ? 'checked' : ''}}>
                                    <img src="{{ asset('images/icono-gato.png') }}">
                                </label>
                                <label class="radio">
                                    <input type="radio" name="pet_preference" value="other" {{ $user->pet_preference == 'other' ? 'checked' : ''}}>
                                    Otro
                                </label>
                                <label class="radio">
                                    <input type="radio" name="pet_preference" value="no" {{ $user->pet_preference == 'no' ? 'checked' : ''}} {{ $user->pet_preference == null ? 'checked' : ''}}>
                                    No
                                </label>
                            </div>
                        </div>
                        <div class="field">
                            <div class="label-field">
                                <img src="{{ asset('images/icono-fumar.png') }}">
                                <span>Fumas?</span>
                            </div>
                            <div class="control">
                                <label class="radio">
                                    <input type="radio" name="smoking_allowed" value="1" {{ $user->smoking_allowed ? 'checked' : ''}}>
                                    Si
                                </label>
                                <label class="radio">
                                    <input type="radio" name="smoking_allowed" value="0" {{ !$user->smoking_allowed ? 'checked' : ''}}>
                                    No
                                </label>
                            </div>
                        </div>
                        
                    </form>
                </div>
                <div class="form-footer">
                    <a href="{{route('users.tenants.third-step.one')}}" class="button is-outlined">Atrás</a>
                    <img src="{{ asset('images/icono_guardar.png') }}" id="save-button-icon">
                    <input type="submit" class="button is-outlined is-primary" value="Continuar" form="registration-form">
                </div>
            </div>
            <div class="column side-info">
                <div class="info">
                    <img src="{{ asset('images/icono-atencion.png') }}">
                    <p>Recuerda seleccionar la mayor cantidad de </p>
                    <p>atributos que buscas en tu propiedad de </p>
                    <p>esta manera UHOMIE optimizará las que </p>
                    <p>mejor se adapten a tus criterios.</p>
                    <div class="border"></div>
                </div>

            </div>
        </div>
    </section>
</div>
@include('components.users.common-forms.save-button.modal')
@endsection

@section('scripts')
<script src="{{ asset('js/third-step-two.js') }}" charset="utf-8"></script>
@endsection
