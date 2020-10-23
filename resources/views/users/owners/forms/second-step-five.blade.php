@extends('layouts.flujo-base', ['navTitle' => '<span class="bold">Paso 2.5:</span> Servicios'])

@section('content')
<div class="container" id="own-second-step-five">
    <section class="section main-section">
        <div class="columns">
            <div class="column is-7 flow-form">
                <div class="div">
                    <form method="POST" action="" enctype="multipart/form-data" id="registration-form">
                        {{ csrf_field() }}
                        @if($property->type_stay == 'SHORT_STAY' && $type_property != 1)
                        <div class="columns">
                            <div class="column">
                                <h1 class="form-title">Servicios Básicos</h1>
                                <div class="field">
                                    <div class="label-field">
                                        <img src="{{ asset('images/icono_lupa.png') }}">
                                        <span>Filtrar Servicios Básicos</span>
                                    </div>
                                    <div class="control">
                                        <input list="basic_amenities" class="input" id="inputBasicAmenities" autocomplete="off">
                                        <datalist id="basic_amenities"></datalist>
                                    </div>
                                </div>
                                @foreach ($basic_services as $service)
                                    <label class="checkbox square {{ $property->amenities->contains($service->id) ? 'active' : '' }}">
                                        <input type="checkbox" name="amenities[]" amenity="{{ $service->name }}" value="{{ $service->id }}" class="check_amenities check_basic_amenity" {{ $property->amenities->contains($service->id) ? 'checked' : '' }}>
                                        {{ $service->name }} <span class="fa fa-{{ $property->amenities->contains($service->id) ? 'minus' : 'plus' }}"></span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <div class="columns">
                            <div class="column">
                                <h1 class="form-title">Detalles de la Propiedad</h1>
                                <div class="field">
                                    <div class="label-field">
                                        <img src="{{ asset('images/icono_lupa.png') }}">
                                        <span>Filtrar Detalles de la Propiedad</span>
                                    </div>
                                    <div class="control">
                                        <input list="details_amenities" class="input" id="inputDetailsAmenities" autocomplete="off">
                                        <datalist id="details_amenities"></datalist>
                                    </div>
                                </div>
                                @foreach ($details_amenities as $service)
                                    <label class="checkbox square {{ $property->amenities->contains($service->id) ? 'active' : '' }}">
                                        <input type="checkbox" name="amenities[]" amenity="{{ $service->name }}" value="{{ $service->id }}" class="check_amenities check_details_amenity" {{ $property->amenities->contains($service->id) ? 'checked' : '' }}>
                                        {{ $service->name }} <span class="fa fa-{{ $property->amenities->contains($service->id) ? 'minus' : 'plus' }}"></span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <div class="columns">
                            <div class="column">
                                <h1 class="form-title">Reglas de la Propiedad</h1>
                                <div class="field">
                                    <div class="label-field">
                                        <img src="{{ asset('images/icono_lupa.png') }}">
                                        <span>Filtrar Reglas de la Propiedad</span>
                                    </div>
                                    <div class="control">
                                        <input list="rules_amenities" class="input" id="inputRulesAmenities" autocomplete="off">
                                        <datalist id="rules_amenities"></datalist>
                                    </div>
                                </div>
                                @foreach ($rules_amenities as $service)
                                    <label class="checkbox square {{ $property->amenities->contains($service->id) ? 'active' : '' }}">
                                        <input type="checkbox" name="amenities[]" amenity="{{ $service->name }}" value="{{ $service->id }}" class="check_amenities check_rules_amenity" {{ $property->amenities->contains($service->id) ? 'checked' : '' }}>
                                        {{ $service->name }} <span class="fa fa-{{ $property->amenities->contains($service->id) ? 'minus' : 'plus' }}"></span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        @if($type_property != 4)
                        <div class="columns">
                            <div class="column">
                                <h1 class="form-title">Servicios de la unidad</h1>

                                <div class="field">
                                    <div class="label-field">
                                        <img src="{{ asset('images/icono_lupa.png') }}">
                                        <span>Filtrar Servicios de Propiedad</span>
                                    </div>
                                    <div class="control">
                                        <input list="property_amenities" class="input" id="inputPropertyAmenities" autocomplete="off">
                                        <datalist id="property_amenities"></datalist>
                                    </div>
                                </div>
                                @foreach ($property_amenities as $service)
                                    <label class="checkbox square">
                                        <input type="checkbox" name="amenities[]" amenity="{{ $service->name }}" value="{{ $service->id }}" class="check_amenities check_property_amenity" {{ $property->amenities->contains($service->id) ? 'checked' : '' }}>
                                        {{ $service->name }} <span class="fa fa-{{ $property->amenities->contains($service->id) ? 'minus' : 'plus' }}"></span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <div class="columns">
                            <div class="column">
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
                                    <label class="checkbox square">
                                        <input type="checkbox" name="amenities[]" class="check_amenities check_common_amenity" value="{{ $service->id }}" {{ $property->amenities->contains($service->id) ? 'checked' : '' }} amenity="{{ $service->name }}">
                                        {{ $service->name }} <span class="fa fa-{{ $property->amenities->contains($service->id) ? 'minus' : 'plus' }}"></span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        @else
                        <div class="columns">
                            <div class="column">
                                <h1 class="form-title">Posesiones del Terreno</h1>

                                <div class="field">
                                    <div class="label-field">
                                        <img src="{{ asset('images/icono_lupa.png') }}">
                                        <span>Filtar Posesiones del Terreno</span>
                                    </div>
                                    <div class="control">
                                        <input list="possessions" class="input" id="inputPossessions" autocomplete="off">
                                        <datalist id="possessions"></datalist>
                                    </div>
                                </div>
                                @foreach ($possessions as $service)
                                    <label class="checkbox square">
                                        <input type="checkbox" name="amenities[]" class="check_possessions check_possession" value="{{ $service->id }}" {{ $property->amenities->contains($service->id) ? 'checked' : '' }} amenity="{{ $service->name }}">
                                        {{ $service->name }} <span class="fa fa-{{ $property->amenities->contains($service->id) ? 'minus' : 'plus' }}"></span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </form>
                </div>
                <div class="form-footer">
                    <a href="{{route('users.owners.second-step.four')}}" class="button is-outlined">Atrás</a>
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
        </div>``
    </section>
</div>
@include('components.users.common-forms.save-button.modal')
@endsection

@section('scripts')
<script src="{{ asset('js/property-forms/first-step-five.js') }}"></script>
@endsection
