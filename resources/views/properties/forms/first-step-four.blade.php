@extends('layouts.flujo-base', ['navTitle' => '<span class="bold">Paso 1.4:</span> Detalles de Propiedad', 'close' => route('profile.owner')])
@section('content')
<input type="hidden" name="property_type" id="property_type" value="{{$type_property}}"/>
<input type="hidden" name="property_type_stay" id="property_type_stay" value="{{$property->type_stay}}"/>
<div class="container" id="own-second-step-four">
    <section class="section main-section">
        <div class="columns">
            <div class="column is-7 flow-form">
                <div class="div">
                    <form method="POST" enctype="multipart/form-data" id="registration-form">
                        {{ csrf_field() }}
                        @if($property->is_project == 1)
                        <div class="form-office" style="display:none">
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_ciudad.png') }}">
                                    <span>Nombre de Torre u Edificio</span>
                                </div>
                                <div class="control">
                                    <input type="text" autocomplete="off" class="input" name="building_name" id="building_name" value="{{ isset($property) ? $property->building_name : '' }}" maxlength="30" required>
                                </div>
                            </div>
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_habitacion.png') }}">
                                    <span>Nivel / Planta</span>
                                </div>
                                <div class="control">
                                    <input class="input numbers" type="number" min="1" max="100"  placeholder="" name="level" id="level" value="{{ !is_null($property) ? $property->level : '' }}" required >
                                </div>
                            </div>
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_habitacion.png') }}">
                                    <span>Cantidad de privados u oficinas que se pueden habilitar</span>
                                </div>
                                <div class="control">
                                    <input class="input numbers" type="number" min="0" max="100"  placeholder="" name="rooms" id="rooms" value="{{ !is_null($property) ? $property->rooms : '' }}" required >
                                </div>
                            </div>
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_trabajo.png') }}">
                                    <span>Cantidad de salas de reuniones</span>
                                </div>
                                <div class="control">
                                    <input class="input numbers" type="number" min="0" max="100"  placeholder="" name="meeting_room" id="meeting_room" value="{{ !is_null($property) ? $property->meeting_room : '' }}" required >
                                </div>
                            </div>
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_banera.png') }}">
                                    <span>Baños</span>
                                </div>
                                <div class="control">
                                    <input class="input numbers" type="number" min="1" max="100"  placeholder="" name="bathrooms" id="bathrooms" value="{{ !is_null($property) ? $property->bathrooms : '' }}" required >
                                </div>
                            </div>
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_superficie.png') }}">
                                    <span>Metros cuadrados</span>
                                </div>
                                <div class="control">
                                    <input class="input numbers" type="number" min="1" placeholder="" name="meters" id="meters" value="{{ !is_null($property) ? $property->meters : '' }}" required >
                                </div>
                            </div>
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_piscina.png') }}">
                                    <span>Piscina</span>
                                </div>
                                <div class="control">
                                    <label class="radio">
                                        <input type="radio" required name="pool" id="pool" {{ !is_null($property) &&  $property->pool == "1" ? 'checked' : '' }} value="1">
                                                                        Si
                                    </label>
                                    <label class="radio">
                                        <input type="radio" required name="pool" id="pool" {{ !is_null($property) &&  $property->pool == "0" ? 'checked' : '' }} value="0">
                                        No
                                    </label>
                                </div>
                            </div>
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_jardin.png') }}">
                                    <span>Jardín</span>
                                </div>
                                <div class="control">
                                    <label class="radio">
                                        <input type="radio" required name="garden" id="garden"  {{ !is_null($property) &&  $property->garden == "1" ? 'checked' : '' }} value="1"> Si
                                    </label>
                                    <label class="radio">
                                        <input type="radio" required name="garden" id="garden"  {{ !is_null($property) &&  $property->garden == "0" ? 'checked' : '' }} value="0"> No
                                    </label>
                                </div>
                            </div>
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_terraza.png') }}">
                                    <span>Terraza</span>
                                </div>
                                <div class="control">
                                    <label class="radio">
                                        <input type="radio" required name="terrace" id="terrace"  {{ !is_null($property) &&  $property->terrace == "1" ? 'checked' : '' }} value="1">
                                                                        Si
                                    </label>
                                    <label class="radio">
                                        <input type="radio" required name="terrace" id="terrace"  {{ !is_null($property) &&  $property->terrace == "0" ? 'checked' : '' }} value="0">
                                        No
                                    </label>
                                </div>
                            </div>
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_garage.png') }}">
                                    <span>Puestos de estacionamiento propio</span>
                                </div>
                                <div class="control">
                                    <input class="input numbers" type="number" min="0" max="100"  placeholder="" name="private_parking" id="private_parking" value="{{ !is_null($property) ? $property->private_parking : '' }}" required >
                                </div>
                            </div>
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_estacionamiento.png') }}">
                                    <span>Puestos de estacionamiento público</span>
                                </div>
                                <div class="control">
                                    <label class="radio">
                                        <input type="radio" required name="public_parking" id="public_parking"  {{ !is_null($property) &&  $property->public_parking == "1" ? 'checked' : '' }} value="1">
                                                                        Si
                                    </label>
                                    <label class="radio">
                                        <input type="radio" required name="public_parking" id="public_parking"  {{ !is_null($property) &&  $property->public_parking == "0" ? 'checked' : '' }} value="0">
                                        No
                                    </label>
                                </div>
                            </div>
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icons/bodega.png') }}">
                                    <span>Bodega</span>
                                </div>
                                <div class="control">
                                    <label class="radio">
                                        <input type="radio" name="cellar" id="cellar" value="1" {{ $property && $property->cellar == '1' ? 'checked' : '' }} required >
                                        Si
                                    </label>
                                    <label class="radio">
                                        <input type="radio" name="cellar" id="cellar" value="0" {{ $property && $property->cellar == '0' ? 'checked' : '' }} required >
                                        No
                                    </label>
                                </div>
                            </div>
                            <!--<div class="field cellar_description">
                                <div class="label-field">
                                    <span>Identificación de Bodega</span>
                                </div>
                                <input type="text" class="input" name="cellar_description" value="{{ $property && !is_null($property->cellar_description) ? $property->cellar_description : '' }}" maxlength="200" required/>
                            </div>-->
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_amoblado.png') }}">
                                    <span>Amoblado</span>
                                </div>
                                <div class="control">
                                    <label class="radio">
                                        <input type="radio" name="furnished" id="furnished" value="1" {{ $property && $property->furnished == '1' ? 'checked' : '' }} required >
                                        Si
                                    </label>
                                    <label class="radio">
                                        <input type="radio" name="furnished" id="furnished" value="0" {{ $property && $property->furnished == '0' ? 'checked' : '' }} required >
                                        No
                                    </label>
                                </div>
                            </div>

                            <!--<div class="field furnished_description">
                                <div class="label-field">
                                    <span>Descripción de los muebles</span>
                                </div>
                                <div class="control">
                                    <input type="text" class="input" name="furnished_description" id="furnished_description" value="{{ $property && !is_null($property->furnished_description) ? $property->furnished_description : '' }}" maxlength="200" required>
                                </div>
                            </div>-->
                        </div>
                        <div class="form-resident" style="display:none">
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_habitacion.png') }}">
                                    <span>Habitaciones</span>
                                </div>
                                <div class="control">
                                    <input class="input numbers" type="number" min="1" max="100"  placeholder="" name="bedrooms" id="bedrooms" value="{{ !is_null($property) ? $property->bedrooms : '' }}" required >
                                </div>
                            </div>
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_banera.png') }}">
                                    <span>Baños</span>
                                </div>
                                <div class="control">
                                    <input class="input numbers" type="number" min="1" max="100"  placeholder="" name="bathrooms" id="bathrooms" value="{{ !is_null($property) ? $property->bathrooms : '' }}" required >
                                </div>
                            </div>
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_superficie.png') }}">
                                    <span>Metros cuadrados</span>
                                </div>
                                <div class="control">
                                    <input class="input numbers" type="number" min="1" placeholder="" name="meters" id="meters" value="{{ !is_null($property) ? $property->meters : '' }}" required >
                                </div>
                            </div>
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_piscina.png') }}">
                                    <span>Piscina</span>
                                </div>
                                <div class="control">
                                    <label class="radio">
                                        <input type="radio" required name="pool" id="pool" {{ !is_null($property) &&  $property->pool == "1" ? 'checked' : '' }} value="1">
                                                                        Si
                                    </label>
                                    <label class="radio">
                                        <input type="radio" required name="pool" id="pool" {{ !is_null($property) &&  $property->pool == "0" ? 'checked' : '' }} value="0">
                                        No
                                    </label>
                                </div>
                            </div>
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_jardin.png') }}">
                                    <span>Jardín</span>
                                </div>
                                <div class="control">
                                    <label class="radio">
                                        <input type="radio" required name="garden" id="garden"  {{ !is_null($property) &&  $property->garden == "1" ? 'checked' : '' }} value="1"> Si
                                    </label>
                                    <label class="radio">
                                        <input type="radio" required name="garden" id="garden"  {{ !is_null($property) &&  $property->garden == "0" ? 'checked' : '' }} value="0"> No
                                    </label>
                                </div>
                            </div>
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_terraza.png') }}">
                                    <span>Terraza</span>
                                </div>
                                <div class="control">
                                    <label class="radio">
                                        <input type="radio" required name="terrace" id="terrace"  {{ !is_null($property) &&  $property->terrace == "1" ? 'checked' : '' }} value="1">
                                                                        Si
                                    </label>
                                    <label class="radio">
                                        <input type="radio" required name="terrace" id="terrace"  {{ !is_null($property) &&  $property->terrace == "0" ? 'checked' : '' }} value="0">
                                        No
                                    </label>
                                </div>
                            </div>
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_garage.png') }}">
                                    <span>Puestos de estacionamiento propio</span>
                                </div>
                                <div class="control">
                                    <input class="input numbers" type="number" min="0" max="100"  placeholder="" name="private_parking" id="private_parking" value="{{ !is_null($property) ? $property->private_parking : '' }}" required >
                                </div>
                            </div>
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_estacionamiento.png') }}">
                                    <span>Puestos de estacionamiento público</span>
                                </div>
                                <div class="control">
                                    <label class="radio">
                                        <input type="radio" required name="public_parking" id="public_parking"  {{ !is_null($property) &&  $property->public_parking == "1" ? 'checked' : '' }} value="1">
                                                                        Si
                                    </label>
                                    <label class="radio">
                                        <input type="radio" required name="public_parking" id="public_parking"  {{ !is_null($property) &&  $property->public_parking == "0" ? 'checked' : '' }} value="0">
                                        No
                                    </label>
                                </div>
                            </div>
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icons/bodega.png') }}">
                                    <span>Bodega</span>
                                </div>
                                <div class="control">
                                    <label class="radio">
                                        <input type="radio" name="cellar" id="cellar" value="1" {{ $property && $property->cellar == '1' ? 'checked' : '' }} required >
                                        Si
                                    </label>
                                    <label class="radio">
                                        <input type="radio" name="cellar" id="cellar" value="0" {{ $property && $property->cellar == '0' ? 'checked' : '' }} required >
                                        No
                                    </label>
                                </div>
                            </div>
                            <!--<div class="field cellar_description">
                                <div class="label-field">
                                    <span>Identificación de Bodega</span>
                                </div>
                                <input type="text" class="input" name="cellar_description" value="{{ $property && !is_null($property->cellar_description) ? $property->cellar_description : '' }}" maxlength="200" required/>
                            </div>-->
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_amoblado.png') }}">
                                    <span>Amoblado</span>
                                </div>
                                <div class="control">
                                    <label class="radio">
                                        <input type="radio" name="furnished" id="furnished" value="1" {{ $property && $property->furnished == '1' ? 'checked' : '' }} required >
                                        Si
                                    </label>
                                    <label class="radio">
                                        <input type="radio" name="furnished" id="furnished" value="0" {{ $property && $property->furnished == '0' ? 'checked' : '' }} required >
                                        No
                                    </label>
                                </div>
                            </div>

                            <!--<div class="field furnished_description">
                                <div class="label-field">
                                    <span>Descripción de los muebles</span>
                                </div>
                                <div class="control">
                                    <input type="text" class="input" name="furnished_description" id="furnished_description" value="{{ $property && !is_null($property->furnished_description) ? $property->furnished_description : '' }}" maxlength="200" required>
                                </div>
                            </div>-->
                        </div>

                        <div class="form-parking" style="display:none">
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_superficie.png') }}">
                                    <span>Metros cuadrados</span>
                                </div>
                                <div class="control">
                                    <input class="input numbers" type="number" min="1" placeholder="" name="meters" id="meters" value="{{ !is_null($property) ? $property->meters : '' }}" required >
                                </div>
                            </div>
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_garage.png') }}">
                                    <span>Puestos de estacionamiento propio</span>
                                </div>
                                <div class="control">
                                    <input class="input numbers" type="number" min="0" max="100"  placeholder="" name="private_parking" id="private_parking" value="{{ !is_null($property) ? $property->private_parking : '' }}" required >
                                </div>
                            </div>
                        </div>
                        <div class="form-ground" style="display:none">
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_superficie.png') }}">
                                    <span>Metros cuadrados</span>
                                </div>
                                <div class="control">
                                    <input class="input numbers" type="number" min="1" placeholder="" name="meters" id="meters" value="{{ !is_null($property) ? $property->meters : '' }}" required >
                                </div>
                            </div>
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_numero.png') }}">
                                    <span>Numero de terreno / parcela / lote</span>
                                </div>
                                <div class="control">
                                    <input class="input" type="text" placeholder="" name="lot_number" id="lot_number" value="{{ !is_null($property) ? $property->lot_number : '' }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-cellar" style="display: none">
                            <input type="hidden" name="cellar" id="cellar" value="1"/>
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icons/bodega.png') }}">
                                    <span>Identificación de Bodega</span>
                                </div>
                                <div class="control">
                                    <input type="text" class="input" name="cellar_description" value="{{ $property && !is_null($property->cellar_description) ? $property->cellar_description : '' }}" required/>
                                </div>
                            </div>
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_superficie.png') }}">
                                    <span>Metros cuadrados</span>
                                </div>
                                <div class="control">
                                    <input class="input numbers" type="number" min="1" placeholder="" name="meters" id="meters" value="{{ !is_null($property) ? $property->meters : '' }}" required >
                                </div>
                            </div>
                        </div>
                        @else 
                        <div class="form-resident" style="display: none;">
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_habitacion.png') }}">
                                    <span>Habitaciones</span>
                                </div>
                                <div class="control">
                                    <input class="input numbers" type="number" min="1" max="100"  placeholder="" name="bedrooms" id="bedrooms" value="{{ !is_null($property) ? $property->bedrooms : '' }}" required >
                                </div>
                            </div>
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_banera.png') }}">
                                    <span>Baños</span>
                                </div>
                                <div class="control">
                                    <input class="input numbers" type="number" min="1" max="100"  placeholder="" name="bathrooms" id="bathrooms" value="{{ !is_null($property) ? $property->bathrooms : '' }}" required >
                                </div>
                            </div>
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_superficie.png') }}">
                                    <span>Metros cuadrados</span>
                                </div>
                                <div class="control">
                                    <input class="input numbers" type="number" min="1" placeholder="" name="meters" id="meters" value="{{ !is_null($property) ? $property->meters : '' }}" required >
                                </div>
                            </div>
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_piscina.png') }}">
                                    <span>Piscina</span>
                                </div>
                                <div class="control">
                                    <label class="radio">
                                        <input type="radio" required name="pool" id="pool" {{ !is_null($property) &&  $property->pool == "1" ? 'checked' : '' }} value="1">
                                                                        Si
                                    </label>
                                    <label class="radio">
                                        <input type="radio" required name="pool" id="pool" {{ !is_null($property) &&  $property->pool == "0" ? 'checked' : '' }} value="0">
                                        No
                                    </label>
                                </div>
                            </div>
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_jardin.png') }}">
                                    <span>Jardín</span>
                                </div>
                                <div class="control">
                                    <label class="radio">
                                        <input type="radio" required name="garden" id="garden"  {{ !is_null($property) &&  $property->garden == "1" ? 'checked' : '' }} value="1"> Si
                                    </label>
                                    <label class="radio">
                                        <input type="radio" required name="garden" id="garden"  {{ !is_null($property) &&  $property->garden == "0" ? 'checked' : '' }} value="0"> No
                                    </label>
                                </div>
                            </div>
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_terraza.png') }}">
                                    <span>Terraza</span>
                                </div>
                                <div class="control">
                                    <label class="radio">
                                        <input type="radio" required name="terrace" id="terrace"  {{ !is_null($property) &&  $property->terrace == "1" ? 'checked' : '' }} value="1">
                                                                        Si
                                    </label>
                                    <label class="radio">
                                        <input type="radio" required name="terrace" id="terrace"  {{ !is_null($property) &&  $property->terrace == "0" ? 'checked' : '' }} value="0">
                                        No
                                    </label>
                                </div>
                            </div>
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_garage.png') }}">
                                    <span>Puestos de estacionamiento propio</span>
                                </div>
                                <div class="control">
                                    <input class="input numbers" type="number" min="0" max="100"  placeholder="" name="private_parking" id="private_parking" value="{{ !is_null($property) ? $property->private_parking : '' }}" required >
                                </div>
                            </div>
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_estacionamiento.png') }}">
                                    <span>Puestos de estacionamiento público</span>
                                </div>
                                <div class="control">
                                    <label class="radio">
                                        <input type="radio" required name="public_parking" id="public_parking"  {{ !is_null($property) &&  $property->public_parking == "1" ? 'checked' : '' }} value="1">
                                                                        Si
                                    </label>
                                    <label class="radio">
                                        <input type="radio" required name="public_parking" id="public_parking"  {{ !is_null($property) &&  $property->public_parking == "0" ? 'checked' : '' }} value="0">
                                        No
                                    </label>
                                </div>
                            </div>
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icons/bodega.png') }}">
                                    <span>Bodega</span>
                                </div>
                                <div class="control">
                                    <label class="radio">
                                        <input type="radio" name="cellar" id="cellar" value="1" {{ $property && $property->cellar == '1' ? 'checked' : '' }} required >
                                        Si
                                    </label>
                                    <label class="radio">
                                        <input type="radio" name="cellar" id="cellar" value="0" {{ $property && $property->cellar == '0' ? 'checked' : '' }} required >
                                        No
                                    </label>
                                </div>
                            </div>
                            
                            @if($property->type_stay == 'SHORT_STAY')
                            <div class="field">
                                <div class="label-field">
                                    <i style="color:#6cd4fc" class="fa fa-bed"></i>
                                    <span>Cantidad de camas invididuales</span>
                                </div>
                                <div class="control">
                                    <input class="input numbers" type="number" min="1" max="100"  placeholder="" name="single_beds" id="single_beds" value="{{ !is_null($property) ? $property->single_beds : '' }}" required >
                                </div>
                            </div>

                            <div class="field">
                                <div class="label-field">
                                    <i style="color:#6cd4fc" class="fa fa-bed"></i>
                                    <span>Cantidad de camas matrimoniales</span>
                                </div>
                                <div class="control">
                                    <input class="input numbers" type="number" min="1" max="100"  placeholder="" name="double_beds" id="double_beds" value="{{ !is_null($property) ? $property->double_beds : '' }}" required >
                                </div>
                            </div>
                            @endif

                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_amoblado.png') }}">
                                    <span>Amoblado</span>
                                </div>
                                <div class="control">
                                    <label class="radio">
                                        <input type="radio" name="furnished" id="furnished" value="1" {{ $property && $property->furnished == '1' ? 'checked' : '' }} required >
                                        Si
                                    </label>
                                    <label class="radio">
                                        <input type="radio" name="furnished" id="furnished" value="0" {{ $property && $property->furnished == '0' ? 'checked' : '' }} required >
                                        No
                                    </label>
                                </div>
                            </div>
                            <div class="field furnished_description" style="display: none;">
                                <div class="label-field">
                                    <i style="color:#6cd4fc" class="fa fa-bed"></i>
                                    <span>Cantidad de Muebles</span>
                                </div>
                                <div class="control">
                                    <input class="input numbers" type="number" min="1" max="100"  placeholder="" name="number_furnished" id="number_furnished" value="{{ !is_null($property) ? $property->number_furnished : '' }}" required >
                                </div>
                            </div>
                            <div class="field furnished_description" style="display: none;">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_descripcion.png') }}">
                                    <span>Por favor indique los muebles que posee en su propiedad</span>
                                </div>
                                <div class="control">
                                    <textarea name="furnished_description" id="furnished_description" class="description" cols="70" rows="8">{{ $property && !is_null($property->furnished_description) ? $property->furnished_description : '' }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="form-room" style="display: none;">
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_habitacion.png') }}">
                                    <span>Habitaciones</span>
                                </div>
                                <div class="control">
                                    <label>1</label>
                                    <input class="input numbers" type="hidden" min="1" max="100"  placeholder="" name="bedrooms" id="bedrooms" value="{{ !is_null($property) ? $property->bedrooms : '' }}" required >
                                </div>
                            </div>    
                            @if($property->type_stay == 'SHORT_STAY')
                            <div class="field">
                                <div class="label-field">
                                    <i style="color:#6cd4fc" class="fa fa-bed"></i>
                                    <span>Cantidad de camas invididuales</span>
                                </div>
                                <div class="control">
                                    <input class="input numbers" type="number" max="10"  placeholder="" name="single_beds" id="single_beds" value="{{ !is_null($property) ? $property->single_beds : '' }}" required >
                                </div>
                            </div>

                            <div class="field">
                                <div class="label-field">
                                    <i style="color:#6cd4fc" class="fa fa-bed"></i>
                                    <span>Cantidad de camas matrimoniales</span>
                                </div>
                                <div class="control">
                                    <input class="input numbers" type="number" max="10"  placeholder="" name="double_beds" id="double_beds" value="{{ !is_null($property) ? $property->double_beds : '' }}" required >
                                </div>
                            </div>
                            @endif
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_banera.png') }}">
                                    <span>Baños</span>
                                </div>
                                <div class="control">
                                    <input class="input numbers" type="number" min="1" max="100"  placeholder="" name="bathrooms" id="bathrooms" value="{{ !is_null($property) ? $property->bathrooms : '' }}" required >
                                </div>
                            </div>
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_superficie.png') }}">
                                    <span>Metros cuadrados</span>
                                </div>
                                <div class="control">
                                    <input class="input numbers" type="number" min="1" placeholder="" name="meters" id="meters" value="{{ !is_null($property) ? $property->meters : '' }}" required >
                                </div>
                            </div>
                            
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_amoblado.png') }}">
                                    <span>Amoblado</span>
                                </div>
                                <div class="control">
                                    <label class="radio">
                                        <input type="radio" name="furnished" id="furnished" value="1" {{ $property && $property->furnished == '1' ? 'checked' : '' }} required >
                                        Si
                                    </label>
                                    <label class="radio">
                                        <input type="radio" name="furnished" id="furnished" value="0" {{ $property && $property->furnished == '0' ? 'checked' : '' }} required >
                                        No
                                    </label>
                                </div>
                            </div>
                            <div class="field furnished_description" style="display: none;">
                                <div class="label-field">
                                    <i style="color:#6cd4fc" class="fa fa-bed"></i>
                                    <span>Cantidad de Muebles</span>
                                </div>
                                <div class="control">
                                    <input class="input numbers" type="number" min="1" max="100"  placeholder="" name="number_furnished" id="number_furnished" value="{{ !is_null($property) ? $property->number_furnished : '' }}" required >
                                </div>
                            </div>
                            <div class="field furnished_description" style="display: none;">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_descripcion.png') }}">
                                    <span>Por favor indique los muebles que posee en su propiedad</span>
                                </div>
                                <div class="control">
                                    <textarea name="furnished_description" id="furnished_description" cols="70" rows="8">{{ $property && !is_null($property->furnished_description) ? $property->furnished_description : '' }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="form-parking" style="display: none">
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_superficie.png') }}">
                                    <span>Metros cuadrados</span>
                                </div>
                                <div class="control">
                                    <input class="input numbers" type="number" min="1" placeholder="" name="meters" id="meters" value="{{ !is_null($property) ? $property->meters : '' }}" required >
                                </div>
                            </div>
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_garage.png') }}">
                                    <span>Puestos de estacionamiento propio</span>
                                </div>
                                <div class="control">
                                    <input class="input numbers" type="number" min="0" max="100"  placeholder="" name="private_parking" id="private_parking" value="{{ !is_null($property) ? $property->private_parking : '' }}" required >
                                </div>
                            </div>
                        </div>

                        <div class="form-office" style="display: none;">
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_ciudad.png') }}">
                                    <span>Nombre de Torre u Edificio</span>
                                </div>
                                <div class="control">
                                    <input type="text" autocomplete="off" class="input" name="building_name" id="building_name" value="{{ isset($property) ? $property->building_name : '' }}" maxlength="30" required>
                                </div>
                            </div>
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_habitacion.png') }}">
                                    <span>Nivel / Planta</span>
                                </div>
                                <div class="control">
                                    <input class="input numbers" type="number" min="1" max="100"  placeholder="" name="level" id="level" value="{{ !is_null($property) ? $property->level : '' }}" required >
                                </div>
                            </div>
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_habitacion.png') }}">
                                    <span>Cantidad de privados u oficinas que se pueden habilitar</span>
                                </div>
                                <div class="control">
                                    <input class="input numbers" type="number" min="0" max="100"  placeholder="" name="rooms" id="rooms" value="{{ !is_null($property) ? $property->rooms : '' }}" required >
                                </div>
                            </div>
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_trabajo.png') }}">
                                    <span>Cantidad de salas de reuniones</span>
                                </div>
                                <div class="control">
                                    <input class="input numbers" type="number" min="0" max="100"  placeholder="" name="meeting_room" id="meeting_room" value="{{ !is_null($property) ? $property->meeting_room : '' }}" required >
                                </div>
                            </div>
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_banera.png') }}">
                                    <span>Baños</span>
                                </div>
                                <div class="control">
                                    <input class="input numbers" type="number" min="0" max="100"  placeholder="" name="bathrooms" id="bathrooms" value="{{ !is_null($property) ? $property->bathrooms : '' }}" required >
                                </div>
                            </div>
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_superficie.png') }}">
                                    <span>Metros cuadrados</span>
                                </div>
                                <div class="control">
                                    <input class="input numbers" type="number" min="1" placeholder="" name="meters" id="meters" value="{{ !is_null($property) ? $property->meters : '' }}" required >
                                </div>
                            </div>
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_garage.png') }}">
                                    <span>Puestos de estacionamiento propio</span>
                                </div>
                                <div class="control">
                                    <input class="input numbers" type="number" min="0" max="100"  placeholder="" name="private_parking" id="private_parking" value="{{ !is_null($property) ? $property->private_parking : '' }}" required >
                                </div>
                            </div>
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_estacionamiento.png') }}">
                                    <span>Puestos de estacionamiento público</span>
                                </div>
                                <div class="control">
                                    <label class="radio">
                                        <input type="radio" required name="public_parking" id="public_parking"  {{ !is_null($property) &&  $property->public_parking == "1" ? 'checked' : '' }} value="1">
                                                                        Si
                                    </label>
                                    <label class="radio">
                                        <input type="radio" required name="public_parking" id="public_parking"  {{ !is_null($property) &&  $property->public_parking == "0" ? 'checked' : '' }} value="0">
                                        No
                                    </label>
                                </div>
                            </div>
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icons/bodega.png') }}">
                                    <span>Bodega</span>
                                </div>
                                <div class="control">
                                    <label class="radio">
                                        <input type="radio" name="cellar" id="cellar" value="1" {{ $property && $property->cellar == '1' ? 'checked' : '' }} required >
                                        Si
                                    </label>
                                    <label class="radio">
                                        <input type="radio" name="cellar" id="cellar" value="0" {{ $property && $property->cellar == '0' ? 'checked' : '' }} required >
                                        No
                                    </label>
                                </div>
                            </div>
                            <div class="field cellar_description">
                                <div class="label-field">
                                    <span>Identificación de Bodega</span>
                                </div>
                                <div class="control">
                                    <input type="text" class="input" name="cellar_description" id="cellar_description" value="{{ $property && !is_null($property->cellar_description) ? $property->cellar_description : '' }}" required/>
                                </div>
                            </div>
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_descripcion.png') }}">
                                    <span>Exclusiones que no incluye el alquiler</span>
                                </div>
                                <div class="control">
                                    <textarea name="exclusions" id="exclusions" rows="8" cols="80" maxlength="800" required>{{ isset($property) ? $property->exclusions : '' }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-cellar" style="display: none">
                            <input type="hidden" name="cellar" id="cellar" value="1"/>
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icons/bodega.png') }}">
                                    <span>Identificación de Bodega</span>
                                </div>
                                <div class="control">
                                    <input type="text" class="input" name="cellar_description" id="cellar_description" value="{{ $property && !is_null($property->cellar_description) ? $property->cellar_description : '' }}" required/>
                                </div>
                            </div>
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_superficie.png') }}">
                                    <span>Metros cuadrados</span>
                                </div>
                                <div class="control">
                                    <input class="input numbers" type="number" min="1" placeholder="" name="meters" id="meters" value="{{ !is_null($property) ? $property->meters : '' }}" required >
                                </div>
                            </div>
                        </div>
                        @endif
                    </form>
                </div>
                <div class="form-footer">
                    <a href="{{route('properties.first-step.three',['id' => $property->id])}}" class="button is-outlined">Atrás</a>
                    <img src="{{ asset('images/icono_guardar.png') }}" id="save-button-icon">
                    <input type="submit" class="button is-outlined is-primary" value="Continuar" form="registration-form">
                </div>
            </div>
            <div class="column side-info">
                <div class="info">
                    <img src="{{ asset('images/icono-atencion.png') }}">
                    <p>Esta información es importante, para conocer a detalle toda la información de la propiedad y sea más fácil para los arrendatarios encontrar tu propiedad.</p>
                    <div class="border"></div>
                </div>
            </div>
        </div>
    </section>
</div>
@include('components.users.common-forms.save-button.modal')
@endsection

@section('scripts')
<!--<script src="{{ asset('js/property-forms/second-step-one.js') }}" charset="utf-8"></script>-->
<script src="{{ asset('js/property-forms/first-step-four.js') }}" charset="utf-8"></script>

@endsection
