@extends('layouts.flujo-base', ['navTitle' => '<span class="bold">Paso 3.2:</span> Tus preferencias de arriendo'])

@section('content')
<div class="container" id="third-step-page">
    <section class="section main-section">
        <div class="columns">
            <div class="column is-7 flow-form" id="app">
                
                <div class="div">
                    <form method="POST" action="" enctype="multipart/form-data" id="registration-form">
                        {{ csrf_field() }}
                        <h1 class="form-title">Servicios de la unidad</h1>

                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_lupa.png') }}">
                                    <span>Filtar Servicios de Propiedad</span>
                                </div>
                                <div class="control">
                                    <input list="property_amenities" class="input" id="inputPropertyAmenities" autocomplete="off">
                                    <datalist id="property_amenities"></datalist>
                                </div>
                            </div>
                            @foreach ($property_amenities as $service)
                                <label class="checkbox square {{ $user->amenities->contains($service->id) ? 'active' : '' }}">
                                    <input type="checkbox" name="amenities[]" amenity="{{ $service->name }}" value="{{ $service->id }}" class="check_amenities check_property_amenity" {{ $user->amenities->contains($service->id) ? 'checked' : '' }}>
                                    {{ $service->name }} <span class="fa fa-{{ $user->amenities->contains($service->id) ? 'minus' : 'plus' }}"></span>
                                </label>
                            @endforeach

                        <h1 class="form-title">Servicios del condominio o edificio</h1>

                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_lupa.png') }}">
                                    <span>Filtar Servicios de Condominio</span>
                                </div>
                                <div class="control">
                                    <input list="common_amenities" class="input" id="inputCommonAmenities" autocomplete="off">
                                    <datalist id="common_amenities"></datalist>
                                </div>
                            </div>
                            @foreach ($common_amenities as $service)
                                <label class="checkbox square {{ $user->amenities->contains($service->id) ? 'active' : '' }}"> 
                                    <input type="checkbox" name="amenities[]" class="check_amenities  check_common_amenity" {{ $user->amenities->contains($service->id) ? 'checked' : '' }} value="{{ $service->id }}" amenity="{{ $service->name }}" 
                                    >
                                    {{ $service->name }} <span class="fa fa-{{ $user->amenities->contains($service->id) ? 'minus' : 'plus' }}"></span>
                                </label>
                            @endforeach
                    </form>
                </div>


                <div class="form-footer">
                    <a href="{{route('users.tenants.third-step.two')}}" class="button is-outlined">Atrás</a>
                    <img src="{{ asset('images/icono_guardar.png') }}" id="save-button-icon">
                    <input type="submit" class="button is-outlined is-primary" value="Continuar" form="registration-form">
                </div>
            </div>
            <div class="column side-info">
                <div class="info">
                    <img src="{{ asset('images/icono-atencion.png') }}">
                    <p>Recuerda seleccionar la mayor cantidad de atributos que buscas en tu propiedad de esta manera UHOMIE optimizará las que mejor se adapten a tus criterios.</p>
                    <div class="border"></div>
                </div>
            </div>
        </div>
    </section>
</div>
@include('components.users.common-forms.save-button.modal')
@endsection

@section('scripts')
<script src="{{ asset('js/third-step-two-2.js') }}"></script>
@endsection
