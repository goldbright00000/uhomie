@extends('layouts.flujo-base', ['navTitle' => '<span class="bold">Paso 4:</span> Detalles de la Propiedad', 'close' => route('profile.agent')])
@section('content')
<input type="hidden" name="property_type" id="property_type" value="{{$type_property}}"/>
<input type="hidden" name="property_type_stay" id="property_type_stay" value="{{$property->type_stay}}"/>
<div class="container" id="agent-third-step-four">
    <section class="section main-section">
        <div class="columns">
            <div class="column is-7 flow-form">
                <div class="div">
                    <form method="POST" action="" enctype="multipart/form-data" id="registration-form">
                        {{ csrf_field() }}
                        @if($property->is_project == 1)
                        <div class="form-office" style="display:none">
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_dinero.png') }}">
                                    <span>Precio de la propiedad desde</span>
                                </div>
                                <div class="control has-icons-left">
                                    <input class="input monto_formato_decimales" type="text" autocomplete="off" name="rent" id="rent" value="{{ $property && !is_null($property->rent) ? $property->rent : '' }}" required minlength="2">
                                    <span class="icon is-small is-left">
                                        UF
                                    </span>
                                </div>
                            </div>
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_dinero.png') }}">
                                    <span>Precio de la propiedad hasta </span>
                                </div>
                                <div class="control has-icons-left">
                                    <input class="input monto_formato_decimales" type="text" autocomplete="off" name="rent_up" id="rent_up" value="{{ $property && !is_null($property->rent_up) ? $property->rent_up : '' }}" required minlength="2">
                                    <span class="icon is-small is-left">
                                        UF
                                    </span>
                                </div>
                            </div>
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
                                    <img src="{{ asset('images/icono_dinero.png') }}">
                                    <span>Precio de la propiedad desde</span>
                                </div>
                                <div class="control has-icons-left">
                                    <input class="input monto_formato_decimales" type="text" autocomplete="off" name="rent" id="rent" value="{{ $property && !is_null($property->rent) ? $property->rent : '' }}" required minlength="2">
                                    <span class="icon is-small is-left">
                                        UF
                                    </span>
                                </div>
                            </div>
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_dinero.png') }}">
                                    <span>Precio de la propiedad hasta </span>
                                </div>
                                <div class="control has-icons-left">
                                    <input class="input monto_formato_decimales" type="text" autocomplete="off" name="rent_up" id="rent_up" value="{{ $property && !is_null($property->rent_up) ? $property->rent_up : '' }}" required minlength="2">
                                    <span class="icon is-small is-left">
                                        UF
                                    </span>
                                </div>
                            </div>
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
                                    <img src="{{ asset('images/icono_dinero.png') }}">
                                    <span>Precio de la propiedad desde</span>
                                </div>
                                <div class="control has-icons-left">
                                    <input class="input monto_formato_decimales" type="text" autocomplete="off" name="rent" id="rent" value="{{ $property && !is_null($property->rent) ? $property->rent : '' }}" required minlength="2">
                                    <span class="icon is-small is-left">
                                        UF
                                    </span>
                                </div>
                            </div>
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_dinero.png') }}">
                                    <span>Precio de la propiedad hasta </span>
                                </div>
                                <div class="control has-icons-left">
                                    <input class="input monto_formato_decimales" type="text" autocomplete="off" name="rent_up" id="rent_up" value="{{ $property && !is_null($property->rent_up) ? $property->rent_up : '' }}" required minlength="2">
                                    <span class="icon is-small is-left">
                                        UF
                                    </span>
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
                        </div>
                        <div class="form-ground" style="display:none">
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_dinero.png') }}">
                                    <span>Precio de la propiedad desde</span>
                                </div>
                                <div class="control has-icons-left">
                                    <input class="input monto_formato_decimales" type="text" autocomplete="off" name="rent" id="rent" value="{{ $property && !is_null($property->rent) ? $property->rent : '' }}" required minlength="2">
                                    <span class="icon is-small is-left">
                                        UF
                                    </span>
                                </div>
                            </div>
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_dinero.png') }}">
                                    <span>Precio de la propiedad hasta </span>
                                </div>
                                <div class="control has-icons-left">
                                    <input class="input monto_formato_decimales" type="text" autocomplete="off" name="rent_up" id="rent_up" value="{{ $property && !is_null($property->rent_up) ? $property->rent_up : '' }}" required minlength="2">
                                    <span class="icon is-small is-left">
                                        UF
                                    </span>
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
                                    <img src="{{ asset('images/icono_numero.png') }}">
                                    <span>Numero de terreno / parcela / lote</span>
                                </div>
                                <div class="control">
                                    <input class="input" type="text" placeholder="" name="lot_number" id="lot_number" value="{{ !is_null($property) ? $property->meters : '' }}" required>
                                </div>
                            </div>

                        </div>
                        <div class="form-cellar" style="display: none">
                            <input type="hidden" name="cellar" id="cellar" value="1"/>
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_dinero.png') }}">
                                    <span>Precio de la propiedad desde</span>
                                </div>
                                <div class="control has-icons-left">
                                    <input class="input monto_formato_decimales" type="text" autocomplete="off" name="rent" id="rent" value="{{ $property && !is_null($property->rent) ? $property->rent : '' }}" required minlength="2">
                                    <span class="icon is-small is-left">
                                        UF
                                    </span>
                                </div>
                            </div>
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_dinero.png') }}">
                                    <span>Precio de la propiedad hasta </span>
                                </div>
                                <div class="control has-icons-left">
                                    <input class="input monto_formato_decimales" type="text" autocomplete="off" name="rent_up" id="rent_up" value="{{ $property && !is_null($property->rent_up) ? $property->rent_up : '' }}" required minlength="2">
                                    <span class="icon is-small is-left">
                                        UF
                                    </span>
                                </div>
                            </div>
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
                            <div class="data-long-stay" style="display: none; margin-bottom: 2rem">
                                <div class="field">
                                    <div class="label-field">
                                        <img src="{{ asset('images/icono_dinero.png') }}">
                                        <span>Precio Mensual de arriendo</span>
                                    </div>
                                    <div class="control has-icons-left has-icons-right">
                                        <input class="input monto_formato_decimales" type="text" autocomplete="off" name="rent" id="rent" value="{{ $property && !is_null($property->rent) ? $property->rent : '' }}" minlength="3" required>
                                        <span class="icon is-small is-left">
                                            $
                                        </span>
                                        <span class="icon is-small is-right">
                                            CLP
                                        </span>
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="label-field">
                                        <img src="{{ asset('images/icono_dinero.png') }}">
                                        <span>Monto de Gastos Comunes</span>
                                    </div>
                                    <div class="control has-icons-left has-icons-right">
                                        <input
                                            class="input monto_formato_decimales"
                                            {{ !is_null($property) && $property->common_expenses_limit == 0 ? 'disabled' : '' }}
                                            type="text" autocomplete="off"
                                            name="common_expenses_limit"
                                            id="common_expenses_limit"
                                            value="{{ !is_null($property) ? $property->common_expenses_limit : '' }}" required>
                                        <span class="icon is-small is-left">
                                            $
                                        </span>
                                        <span class="icon is-small is-right">
                                            CLP
                                        </span>
                                    </div>
                                    <button type="button" class="button btn-no-posee" >No posee</button>
                                </div>
                                <div class="field">
                                    <div class="label-field">
                                        <img src="{{ asset('images/icono_dinero.png') }}" style="margin-right: 0.1rem;">
                                        <span>¿Requieres Seguro de Arriendo?</span>
                                    </div>
                                    <div class="control">
                                        <label class="radio">
                                            <input type="radio" name="tenanting_insurance" value="1" {{ $property->tenanting_insurance ? 'checked' : ''}}>
                                            Si
                                        </label>
                                        <label class="radio">
                                            <input type="radio" name="tenanting_insurance" value="0" {{ !$property->tenanting_insurance ? 'checked' : ''}}>
                                            No
                                        </label>
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="label-field">
                                        <img src="{{ asset('images/icono_numero.png') }}">
                                        <span>¿Cuantos meses de garantia exiges?</span>
                                    </div>
                                    <div class="select">
                                        <select name="warranty_months_quantity" required>
                                            @for($i=1;$i<=12;$i++)
                                            @if($i == 1)
                                                <option {{ $property && $property->warranty_months_quantity == $i ? 'selected' : '' }}  value="{{ $i }}">{{$i}} Mes</option>
                                            @else
                                                <option {{ $property && $property->warranty_months_quantity == $i ? 'selected' : '' }} value="{{$i}}" >{{$i}} Meses</option>
                                            @endif
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="label-field">
                                        <img src="{{ asset('images/icono_numero.png') }}">
                                        <span>¿Cuantos meses de adelanto exiges?</span>
                                    </div>
                                    <div class="select">
                                        <select name="months_advance_quantity" required>
                                            @for($i=1;$i<=12;$i++)
                                            @if($i == 1)
                                            <option value="{{$i}}" {{ $property && $property->months_advance_quantity == $i ? 'selected' : '' }}  >{{$i}} Mes</option>
                                            @else
                                                <option value="{{$i}}" {{ $property && $property->months_advance_quantity == $i ? 'selected' : '' }}  >{{$i}} Meses</option>
                                            @endif
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="label-field">
                                        <img src="{{ asset('images/datepicker-icon.png') }}">
                                        <span>Fecha disponible para arrendar</span>
                                    </div>
                                    <!--<input type="text" class="input date" autocomplete="off" name="available_date" id="available_date" value="{{ $property && !is_null($property->available_date) ? $property->available_date : '' }}">-->
                                    <div class="control">
                                        <input type="text" class="input date" autocomplete="off" name="available_date" id="available_date" value="{{ $property && !is_null($property->available_date) ? $property->available_date : '' }}">
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="label-field">
                                        <img src="{{ asset('images/icono_numero.png') }}">
                                        <span>Tiempo mínimo de arriendo</span>
                                    </div>
                                    <div class="select">
                                        <select name="tenanting_months_quantity" required>
                                            @for($i=1; $i<=12; $i++)
                                                @if($i == 1)
                                                    <option  value="{{$i}}" {{ $property && $property->tenanting_months_quantity == $i ? 'selected' : '' }}  >{{$i}} Mes</option>
                                                @else
                                                    <option value="{{$i}}" {{ $property && $property->tenanting_months_quantity == $i ? 'selected' : '' }}  >{{$i}} Meses</option>
                                                @endif
                                            @endfor
                                            <option {{ $property && $property->tenanting_months_quantity == '18' ? 'selected' : '' }} value="18" >18 Meses</option>
                                            <option {{ $property && $property->tenanting_months_quantity == '24' ? 'selected' : '' }} value="24" >24 Meses</option>
                                            <option {{ $property && $property->tenanting_months_quantity == '36' ? 'selected' : '' }} value="36" >36 Meses</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="label-field">
                                        <img src="{{ asset('images/icono_aval.png') }}">
                                        <span>¿Exijes Aval?</span>
                                    </div>
                                    <div class="control">
                                        <label class="radio">
                                            <input type="radio" name="collateral_require"  value="1" {{ $property && $property->collateral_require == '1' ? 'checked' : '' }} required>
                                            Si
                                        </label>
                                        <label class="radio">
                                            <input type="radio" name="collateral_require"  value="0" {{ $property && $property->collateral_require == '0' ? 'checked' : '' }} required>
                                            No
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
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
                                    <textarea name="furnished_description" id="furnished_description" cols="70" rows="8">{{ $property && !is_null($property->furnished_description) ? $property->furnished_description : '' }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="form-room" style="display: none;">
                            <div class="long_stay" style="display: none; margin-bottom: 2rem;">
                                <div class="field">
                                    <div class="label-field">
                                        <img src="{{ asset('images/icono_dinero.png') }}">
                                        <span>Precio Mensual de arriendo</span>
                                    </div>
                                    <div class="control has-icons-left has-icons-right">
                                        <input class="input monto_formato_decimales" type="text" autocomplete="off" name="rent" id="expenses_limit" value="{{ $property && !is_null($property->rent) ? $property->rent : '' }}" required>
                                        <span class="icon is-small is-left">
                                            $
                                        </span>
                                        <span class="icon is-small is-right">
                                            CLP
                                        </span>
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="label-field">
                                        <img src="{{ asset('images/icono_dinero.png') }}">
                                        <span>Monto de Gastos Comunes</span>
                                    </div>
                                    <div class="control has-icons-left has-icons-right">
                                        <input
                                            class="input monto_formato_decimales"
                                            {{ !is_null($property) && $property->common_expenses_limit == 0 ? 'disabled' : '' }}
                                            type="text" autocomplete="off"
                                            name="common_expenses_limit"
                                            id="common_expenses_limit"
                                            value="{{ !is_null($property) ? $property->common_expenses_limit : '' }}" required>
                                        <span class="icon is-small is-left">
                                            $
                                        </span>
                                        <span class="icon is-small is-right">
                                            CLP
                                        </span>
                                    </div>
                                    <button type="button" class="button btn-no-posee" >No posee</button>
                                </div>
                                <div class="field">
                                    <div class="label-field">
                                        <img src="{{ asset('images/icono_dinero.png') }}" style="margin-right: 0.1rem;">
                                        <span>¿Requieres Seguro de Arriendo?</span>
                                    </div>
                                    <div class="control">
                                        <label class="radio">
                                            <input type="radio" name="tenanting_insurance" value="1" {{ $property->tenanting_insurance ? 'checked' : ''}}>
                                            Si
                                        </label>
                                        <label class="radio">
                                            <input type="radio" name="tenanting_insurance" value="0" {{ !$property->tenanting_insurance ? 'checked' : ''}}>
                                            No
                                        </label>
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="label-field">
                                        <img src="{{ asset('images/icono_numero.png') }}">
                                        <span>¿Cuantos meses de garantia exiges?</span>
                                    </div>
                                    <div class="select">
                                        <select name="warranty_months_quantity" required>
                                            @for($i=1;$i<=12;$i++)
                                            @if($i == 1)
                                                <option {{ $property && $property->warranty_months_quantity == $i ? 'selected' : '' }}  value="{{ $i }}">{{$i}} Mes</option>
                                            @else
                                                <option {{ $property && $property->warranty_months_quantity == $i ? 'selected' : '' }} value="{{$i}}" >{{$i}} Meses</option>
                                            @endif
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="label-field">
                                        <img src="{{ asset('images/icono_numero.png') }}">
                                        <span>¿Cuantos meses de adelanto exiges?</span>
                                    </div>
                                    <div class="select">
                                        <select name="months_advance_quantity" required>
                                            @for($i=1;$i<=12;$i++)
                                            @if($i == 1)
                                            <option value="{{$i}}" {{ $property && $property->months_advance_quantity == $i ? 'selected' : '' }}  >{{$i}} Mes</option>
                                            @else
                                                <option value="{{$i}}" {{ $property && $property->months_advance_quantity == $i ? 'selected' : '' }}  >{{$i}} Meses</option>
                                            @endif
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="label-field">
                                        <img src="{{ asset('images/datepicker-icon.png') }}">
                                        <span>Fecha disponible para arrendar</span>
                                    </div>
                                    <!--<input type="text" class="input date" autocomplete="off" name="available_date" id="available_date" value="{{ $property && !is_null($property->available_date) ? $property->available_date : '' }}">-->
                                    <div class="control">
                                        <input type="text" class="input date" autocomplete="off" name="available_date" id="available_date2" value="{{ $property && !is_null($property->available_date) ? $property->available_date : '' }}">
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="label-field">
                                        <img src="{{ asset('images/icono_numero.png') }}">
                                        <span>Tiempo mínimo de arriendo</span>
                                    </div>
                                    <div class="select">
                                        <select name="tenanting_months_quantity" required>
                                            @for($i=1; $i<=12; $i++)
                                                @if($i == 1)
                                                    <option  value="{{$i}}" {{ $property && $property->tenanting_months_quantity == $i ? 'selected' : '' }}  >{{$i}} Mes</option>
                                                @else
                                                    <option value="{{$i}}" {{ $property && $property->tenanting_months_quantity == $i ? 'selected' : '' }}  >{{$i}} Meses</option>
                                                @endif
                                            @endfor
                                            <option {{ $property && $property->tenanting_months_quantity == '18' ? 'selected' : '' }} value="18" >18 Meses</option>
                                            <option {{ $property && $property->tenanting_months_quantity == '24' ? 'selected' : '' }} value="24" >24 Meses</option>
                                            <option {{ $property && $property->tenanting_months_quantity == '36' ? 'selected' : '' }} value="36" >36 Meses</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="label-field">
                                        <img src="{{ asset('images/icono_aval.png') }}">
                                        <span>¿Exijes Aval?</span>
                                    </div>
                                    <div class="control">
                                        <label class="radio">
                                            <input type="radio" name="collateral_require"  value="1" {{ $property && $property->collateral_require == '1' ? 'checked' : '' }} required>
                                            Si
                                        </label>
                                        <label class="radio">
                                            <input type="radio" name="collateral_require"  value="0" {{ $property && $property->collateral_require == '0' ? 'checked' : '' }} required>
                                            No
                                        </label>
                                    </div>
                                </div>
                            </div>
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
                            <div class="long_stay" style="display:none; margin-bottom: 2rem">
                                <div class="field">
                                    <div class="label-field">
                                        <img src="{{ asset('images/icono_dinero.png') }}">
                                        <span>Precio Mensual de arriendo</span>
                                    </div>
                                    <div class="control has-icons-left has-icons-right">
                                        <input class="input monto_formato_decimales" type="text" autocomplete="off" name="rent" id="expenses_limit" value="{{ $property && !is_null($property->rent) ? $property->rent : '' }}" required>
                                        <span class="icon is-small is-left">
                                            $
                                        </span>
                                        <span class="icon is-small is-right">
                                            CLP
                                        </span>
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="label-field">
                                        <img src="{{ asset('images/icono_dinero.png') }}">
                                        <span>Monto de Gastos Comunes</span>
                                    </div>
                                    <div class="control has-icons-left has-icons-right">
                                        <input
                                            class="input monto_formato_decimales"
                                            {{ !is_null($property) && $property->common_expenses_limit == 0 ? 'disabled' : '' }}
                                            type="text" autocomplete="off"
                                            name="common_expenses_limit"
                                            id="common_expenses_limit"
                                            value="{{ !is_null($property) ? $property->common_expenses_limit : '' }}" required>
                                        <span class="icon is-small is-left">
                                            $
                                        </span>
                                        <span class="icon is-small is-right">
                                            CLP
                                        </span>
                                    </div>
                                    <button type="button" class="button btn-no-posee" >No posee</button>
                                </div>
                                <div class="field">
                                    <div class="label-field">
                                        <img src="{{ asset('images/icono_dinero.png') }}" style="margin-right: 0.1rem;">
                                        <span>¿Requieres Seguro de Arriendo?</span>
                                    </div>
                                    <div class="control">
                                        <label class="radio">
                                            <input type="radio" name="tenanting_insurance" value="1" {{ $property->tenanting_insurance ? 'checked' : ''}}>
                                            Si
                                        </label>
                                        <label class="radio">
                                            <input type="radio" name="tenanting_insurance" value="0" {{ !$property->tenanting_insurance ? 'checked' : ''}}>
                                            No
                                        </label>
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="label-field">
                                        <img src="{{ asset('images/icono_numero.png') }}">
                                        <span>¿Cuantos meses de garantia exiges?</span>
                                    </div>
                                    <div class="select">
                                        <select name="warranty_months_quantity" required>
                                            @for($i=1;$i<=12;$i++)
                                            @if($i == 1)
                                                <option {{ $property && $property->warranty_months_quantity == $i ? 'selected' : '' }}  value="{{ $i }}">{{$i}} Mes</option>
                                            @else
                                                <option {{ $property && $property->warranty_months_quantity == $i ? 'selected' : '' }} value="{{$i}}" >{{$i}} Meses</option>
                                            @endif
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="label-field">
                                        <img src="{{ asset('images/icono_numero.png') }}">
                                        <span>¿Cuantos meses de adelanto exiges?</span>
                                    </div>
                                    <div class="select">
                                        <select name="months_advance_quantity" required>
                                            @for($i=1;$i<=12;$i++)
                                            @if($i == 1)
                                            <option value="{{$i}}" {{ $property && $property->months_advance_quantity == $i ? 'selected' : '' }}  >{{$i}} Mes</option>
                                            @else
                                                <option value="{{$i}}" {{ $property && $property->months_advance_quantity == $i ? 'selected' : '' }}  >{{$i}} Meses</option>
                                            @endif
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="label-field">
                                        <img src="{{ asset('images/datepicker-icon.png') }}">
                                        <span>Fecha disponible para arrendar</span>
                                    </div>
                                    <!--<input type="text" class="input date" autocomplete="off" name="available_date" id="available_date" value="{{ $property && !is_null($property->available_date) ? $property->available_date : '' }}">-->
                                    <div class="control">
                                        <input type="text" class="input date" autocomplete="off" name="available_date" id="available_date3" value="{{ $property && !is_null($property->available_date) ? $property->available_date : '' }}">
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="label-field">
                                        <img src="{{ asset('images/icono_numero.png') }}">
                                        <span>Tiempo mínimo de arriendo</span>
                                    </div>
                                    <div class="select">
                                        <select name="tenanting_months_quantity" required>
                                            @for($i=1; $i<=12; $i++)
                                                @if($i == 1)
                                                    <option  value="{{$i}}" {{ $property && $property->tenanting_months_quantity == $i ? 'selected' : '' }}  >{{$i}} Mes</option>
                                                @else
                                                    <option value="{{$i}}" {{ $property && $property->tenanting_months_quantity == $i ? 'selected' : '' }}  >{{$i}} Meses</option>
                                                @endif
                                            @endfor
                                            <option {{ $property && $property->tenanting_months_quantity == '18' ? 'selected' : '' }} value="18" >18 Meses</option>
                                            <option {{ $property && $property->tenanting_months_quantity == '24' ? 'selected' : '' }} value="24" >24 Meses</option>
                                            <option {{ $property && $property->tenanting_months_quantity == '36' ? 'selected' : '' }} value="36" >36 Meses</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="label-field">
                                        <img src="{{ asset('images/icono_aval.png') }}">
                                        <span>¿Exijes Aval?</span>
                                    </div>
                                    <div class="control">
                                        <label class="radio">
                                            <input type="radio" name="collateral_require"  value="1" {{ $property && $property->collateral_require == '1' ? 'checked' : '' }} required>
                                            Si
                                        </label>
                                        <label class="radio">
                                            <input type="radio" name="collateral_require"  value="0" {{ $property && $property->collateral_require == '0' ? 'checked' : '' }} required>
                                            No
                                        </label>
                                    </div>
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
                        </div>

                        <div class="form-office" style="display: none;">
                            <div class="long_stay" style="display: none; margin-bottom: 2rem;">
                                <div class="field">
                                    <div class="label-field">
                                        <img src="{{ asset('images/icono_dinero.png') }}">
                                        <span>Precio Mensual de arriendo</span>
                                    </div>
                                    <div class="control has-icons-left has-icons-right">
                                        <input class="input monto_formato_decimales" type="text" autocomplete="off" name="rent" id="expenses_limit" value="{{ $property && !is_null($property->rent) ? $property->rent : '' }}" minlength="6">
                                        <span class="icon is-small is-left">
                                            $
                                        </span>
                                        <span class="icon is-small is-right">
                                            CLP
                                        </span>
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="label-field">
                                        <img src="{{ asset('images/icono-calendario-azul.png') }}">
                                        <span>Plazo minimo de arriendo (años)</span>
                                    </div>
                                    <div class="control">
                                        <input class="input numbers" v-model="term_year" type="number" min="0" max="5"  placeholder="" name="term_year" id="term_year" value="{{ !is_null($property) ? $property->term_year : '' }}" required >
                                    </div>
                                </div>
                                
                                <div class="field">
                                    <div class="label-field">
                                        <img src="{{ asset('images/icono_dinero.png') }}">
                                        <span>Precio arriendo año 1</span>
                                    </div>
                                    <div class="control has-icons-right">
                                        <input class="input monto_formato_decimales" type="text" autocomplete="off" name="rent_year_1" value="{{ !is_null($property) ? $property->rent_year_1 : '' }}">
                                        <span class="icon is-small is-right">
                                            UF
                                        </span>
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="label-field">
                                        <img src="{{ asset('images/icono_dinero.png') }}">
                                        <span>Precio arriendo año 2</span>
                                    </div>
                                    <div class="control has-icons-right">
                                        <input class="input monto_formato_decimales" type="text" autocomplete="off" name="rent_year_2" value="{{ !is_null($property) ? $property->rent_year_2 : '' }}">
                                        <span class="icon is-small is-right">
                                            UF
                                        </span>
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="label-field">
                                        <img src="{{ asset('images/icono_dinero.png') }}">
                                        <span>Precio arriendo año 3</span>
                                    </div>
                                    <div class="control has-icons-right">
                                        <input class="input monto_formato_decimales" type="text" autocomplete="off" name="rent_year_3" value="{{ !is_null($property) ? $property->rent_year_3 : '' }}">
                                        <span class="icon is-small is-right">
                                            UF
                                        </span>
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="label-field">
                                        <img src="{{ asset('images/icono_dinero.png') }}">
                                        <span>Precio mensual de gastos comunes</span>
                                    </div>
                                    <div class="control has-icons-right">
                                        <input class="input monto_formato_decimales" type="text" autocomplete="off" name="common_expenses_limit" id="common_expenses_limit" value="{{ $property && !is_null($property->common_expenses_limit) ? $property->common_expenses_limit : '' }}" required>
                                        <span class="icon is-small is-right">
                                            UF
                                        </span>
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="label-field">
                                        <img src="{{ asset('images/icono_dinero.png') }}">
                                        <span>Multa por morosidad de no pago</span>
                                    </div>
                                    <div class="control has-icons-right">
                                        <input class="input monto_formato_decimales" type="text" autocomplete="off" name="penalty_fees" id="penalty_fees" value="{{ $property && !is_null($property->penalty_fees) ? $property->penalty_fees : '' }}" required>
                                        <span class="icon is-small is-right">
                                            UF
                                        </span>
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="label-field">
                                        <img src="{{ asset('images/icono_dinero.png') }}" style="margin-right: 0.1rem;">
                                        <span>¿Requieres Seguro de Arriendo?</span>
                                    </div>
                                    <div class="control">
                                        <label class="radio">
                                            <input type="radio" name="tenanting_insurance" value="1" {{ $property->tenanting_insurance ? 'checked' : ''}}>
                                            Si
                                        </label>
                                        <label class="radio">
                                            <input type="radio" name="tenanting_insurance" value="0" {{ !$property->tenanting_insurance ? 'checked' : ''}}>
                                            No
                                        </label>
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="label-field">
                                        <img src="{{ asset('images/icono_numero.png') }}">
                                        <span>¿Cuantos meses de garantia exiges?</span>
                                    </div>
                                    <div class="select">
                                        <select name="warranty_months_quantity" required>
                                            @for($i=1;$i<=12;$i++)
                                            @if($i == 1)
                                                <option {{ $property && $property->warranty_months_quantity == $i ? 'selected' : '' }}  value="{{ $i }}">{{$i}} Mes</option>
                                            @else
                                                <option {{ $property && $property->warranty_months_quantity == $i ? 'selected' : '' }} value="{{$i}}" >{{$i}} Meses</option>
                                            @endif
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="label-field">
                                        <img src="{{ asset('images/icono_numero.png') }}">
                                        <span>¿Cuantos meses de adelanto exiges?</span>
                                    </div>
                                    <div class="select">
                                        <select name="months_advance_quantity" required>
                                            @for($i=1;$i<=12;$i++)
                                            @if($i == 1)
                                            <option value="{{$i}}" {{ $property && $property->months_advance_quantity == $i ? 'selected' : '' }}  >{{$i}} Mes</option>
                                            @else
                                                <option value="{{$i}}" {{ $property && $property->months_advance_quantity == $i ? 'selected' : '' }}  >{{$i}} Meses</option>
                                            @endif
                                            @endfor
                                        </select>
                                    </div>
                                </div>

                                <div class="field">
                                    <div class="label-field">
                                        <img src="{{ asset('images/datepicker-icon.png') }}">
                                        <span>Fecha disponible para arrendar</span>
                                    </div>
                                    <div class="control">
                                        <!--<input type="text" class="input date" autocomplete="off" name="available_date" id="available_date" value="{{ $property && !is_null($property->available_date) ? $property->available_date : '' }}">-->
                                        <input type="text" class="input date" autocomplete="off" name="available_date" id="available_date1" value="{{ $property && !is_null($property->available_date) ? $property->available_date : '' }}">
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
                                        <img src="{{ asset('images/icono_descripcion.png') }}">
                                        <span>Por favor indique los muebles que posee en su propiedad</span>
                                    </div>
                                    <div class="control">
                                        <textarea name="furnished_description" id="furnished_description" cols="70" rows="8">{{ $property && !is_null($property->furnished_description) ? $property->furnished_description : '' }}</textarea>
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="label-field">
                                        <img src="{{ asset('images/icono_dinero.png') }}">
                                        <span>Requiere boleta de garantia</span>
                                    </div>
                                    <div class="control">
                                        <label class="radio">
                                            <input type="radio" name="warranty_ticket" id="warranty_ticket" value="1" {{ $property && $property->warranty_ticket == '1' ? 'checked' : '' }} required >
                                            Si
                                        </label>
                                        <label class="radio">
                                            <input type="radio" name="warranty_ticket" id="warranty_ticket" value="0" {{ $property && $property->warranty_ticket == '0' ? 'checked' : '' }} required >
                                            No
                                        </label>
                                    </div>
                                </div>
                                <div class="warranty_ticket_price" style="display: none;">
                                    <div class="field">
                                        <div class="label-field">
                                            <img src="{{ asset('images/icono_dinero.png') }}">
                                            <span>Monto de la boleta de garantia</span>
                                        </div>
                                        <div class="control has-icons-right">
                                            <input class="input monto_formato_decimales" type="text" autocomplete="off" name="warranty_ticket_price" id="expenses_limit" value="{{ $property && !is_null($property->warranty_ticket_price) ? $property->warranty_ticket_price : '' }}" required>
                                            <span class="icon is-small is-right">
                                                UF
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                                    <input type="text" class="input" name="cellar_description" value="{{ $property && !is_null($property->cellar_description) ? $property->cellar_description : '' }}" required/>
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
                                    <img src="{{ asset('images/icono_dinero.png') }}">
                                    <span>Precio Mensual de arriendo</span>
                                </div>
                                <div class="control has-icons-left has-icons-right">
                                    <input class="input monto_formato_decimales" type="text" autocomplete="off" name="rent" id="expenses_limit" value="{{ $property && !is_null($property->rent) ? $property->rent : '' }}" required>
                                    <span class="icon is-small is-left">
                                        $
                                    </span>
                                    <span class="icon is-small is-right">
                                        CLP
                                    </span>
                                </div>
                            </div>
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_dinero.png') }}">
                                    <span>Monto de Gastos Comunes</span>
                                </div>
                                <div class="control has-icons-left has-icons-right">
                                    <input
                                        class="input monto_formato_decimales"
                                        {{ !is_null($property) && $property->common_expenses_limit == 0 ? 'disabled' : '' }}
                                        type="text" autocomplete="off"
                                        name="common_expenses_limit"
                                        id="common_expenses_limit"
                                        value="{{ !is_null($property) ? $property->common_expenses_limit : '' }}" required>
                                    <span class="icon is-small is-left">
                                        $
                                    </span>
                                    <span class="icon is-small is-right">
                                        CLP
                                    </span>
                                </div>
                                <button type="button" class="button btn-no-posee" >No posee</button>
                            </div>
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_dinero.png') }}" style="margin-right: 0.1rem;">
                                    <span>¿Requieres Seguro de Arriendo?</span>
                                </div>
                                <div class="control">
                                    <label class="radio">
                                        <input type="radio" name="tenanting_insurance" value="1" {{ $property->tenanting_insurance ? 'checked' : ''}}>
                                        Si
                                    </label>
                                    <label class="radio">
                                        <input type="radio" name="tenanting_insurance" value="0" {{ !$property->tenanting_insurance ? 'checked' : ''}}>
                                        No
                                    </label>
                                </div>
                            </div>
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_numero.png') }}">
                                    <span>¿Cuantos meses de garantia exiges?</span>
                                </div>
                                <div class="select">
                                    <select name="warranty_months_quantity" required>
                                        @for($i=1;$i<=12;$i++)
                                        @if($i == 1)
                                            <option {{ $property && $property->warranty_months_quantity == $i ? 'selected' : '' }}  value="{{ $i }}">{{$i}} Mes</option>
                                        @else
                                            <option {{ $property && $property->warranty_months_quantity == $i ? 'selected' : '' }} value="{{$i}}" >{{$i}} Meses</option>
                                        @endif
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_numero.png') }}">
                                    <span>¿Cuantos meses de adelanto exiges?</span>
                                </div>
                                <div class="select">
                                    <select name="months_advance_quantity" required>
                                        @for($i=1;$i<=12;$i++)
                                        @if($i == 1)
                                        <option value="{{$i}}" {{ $property && $property->months_advance_quantity == $i ? 'selected' : '' }}  >{{$i}} Mes</option>
                                        @else
                                            <option value="{{$i}}" {{ $property && $property->months_advance_quantity == $i ? 'selected' : '' }}  >{{$i}} Meses</option>
                                        @endif
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/datepicker-icon.png') }}">
                                    <span>Fecha disponible para arrendar</span>
                                </div>
                                <div class="control">
                                    <input type="text" class="input date" autocomplete="off" name="available_date" id="available_date4" value="{{ $property && !is_null($property->available_date) ? $property->available_date : '' }}">
                                </div>
                            </div>
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_numero.png') }}">
                                    <span>Tiempo mínimo de arriendo</span>
                                </div>
                                <div class="select">
                                    <select name="tenanting_months_quantity" required>
                                        @for($i=1; $i<=12; $i++)
                                            @if($i == 1)
                                                <option  value="{{$i}}" {{ $property && $property->tenanting_months_quantity == $i ? 'selected' : '' }}  >{{$i}} Mes</option>
                                            @else
                                                <option value="{{$i}}" {{ $property && $property->tenanting_months_quantity == $i ? 'selected' : '' }}  >{{$i}} Meses</option>
                                            @endif
                                        @endfor
                                        <option {{ $property && $property->tenanting_months_quantity == '18' ? 'selected' : '' }} value="18" >18 Meses</option>
                                        <option {{ $property && $property->tenanting_months_quantity == '24' ? 'selected' : '' }} value="24" >24 Meses</option>
                                        <option {{ $property && $property->tenanting_months_quantity == '36' ? 'selected' : '' }} value="36" >36 Meses</option>
                                    </select>
                                </div>
                            </div>
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_aval.png') }}">
                                    <span>¿Exijes Aval?</span>
                                </div>
                                <div class="control">
                                    <label class="radio">
                                        <input type="radio" name="collateral_require"  value="1" {{ $property && $property->collateral_require == '1' ? 'checked' : '' }} required>
                                        Si
                                    </label>
                                    <label class="radio">
                                        <input type="radio" name="collateral_require"  value="0" {{ $property && $property->collateral_require == '0' ? 'checked' : '' }} required>
                                        No
                                    </label>
                                </div>
                            </div>
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
                        @endif
                    </form>
                </div>
                <div class="form-footer">
                    <a href="@route('users.agents.second-step.three')" class="button is-outlined">Atrás</a>
                    <img src="{{ asset('images/icono_guardar.png') }}" id="save-button-icon">                    <input type="submit" class="button is-outlined is-primary" value="Continuar" form="registration-form">
                </div>
            </div>
            <div class="column side-info">
                <div class="info">
                    <img src="{{ asset('images/icono-atencion.png') }}">
                    <p>Esta información es importante, para confeccionar la visualización y los parámetros de búsqueda de tu propiedad.</p>
                    <div class="border"></div>
                </div>
            </div>
        </div>
    </section>
</div>
@include('components.users.common-forms.save-button.modal')
@endsection

@section('scripts')
@if($property->is_project == 0)
<script src="{{ asset('js/property-forms/second-step-one.js') }}" charset="utf-8"></script>
@else
<script src="{{ asset('js/property-forms/second-step-four.js') }}" charset="utf-8"></script>
@endif
@endsection
