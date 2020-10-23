@extends('layouts.flujo-base', ['navTitle' => '<span class="bold">Paso 1:</span> Tu Propiedad', 'close' => route('profile.agent')])
@section('content')
<input type="hidden" name="one" id="one" value="{{$type}}"/>
<input type="hidden" name="class_type" id="class_type" value="{{$type_property}}"/>
<input type="hidden" name="is_project" id="is_project" value="{{$property->is_project}}"/>
<div class="container" id="agent-second-step-one">
    <section class="section main-section">
        <div class="columns">
            <div class="column is-7 flow-form">
                <div class="div">
                    <form method="POST" action="" enctype="multipart/form-data" id="registration-form">
						            {{ csrf_field() }}
                        @isset($property)
                            <input type="hidden" name="property_id" value="{{ $property->id }}">
                        @endisset
                        <div class="one" style="display:none">
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono-casa.png') }}">
                                    <span>Tipo de Propiedad</span>
                                </div>
                                <div class="select">
                                    <select name="property_type" id="property_type" required>
                                        @foreach($property_type as $type)
                                            <option value="{{$type->id}}" {{ isset($property) && $property->property_type_id == $type->id ? 'selected' : '' }}>{{$type->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="one-one" style="display:none">
                            <div class="project" style="display:none">
                                <div class="field">
                                    <div class="label-field">
                                        <img src="{{ asset('images/icono_ciudad.png') }}">
                                        <span>Nombre de tu Propiedad</span>
                                    </div>
                                    <div class="control">
                                        <input type="text" autocomplete="off" class="input" name="name" id="name" value="{{ isset($property) ? $property->name : '' }}" maxlength="30" required>
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="label-field">
                                        <img src="{{ asset('images/icono-estrella.png') }}">
                                        <span>Condición de la Propiedad</span>
                                    </div>
                                    <div class="control">
                                        <label class="radio">
                                            <input type="radio" name="condition" id="condition" value="1" {{ isset($property) && $property->condition == 1 ? 'checked' : '' }} required>
                                            Terminado
                                        </label>
                                        <label class="radio">
                                            <input type="radio" name="condition" id="condition" value="0" {{ isset($property) && $property->condition == 0 ? 'checked' : '' }} required>
                                            En obra
                                        </label>
                                        <label class="radio">
                                            <input type="radio" name="condition" id="condition" value="2" {{ isset($property) && $property->condition == 2 ? 'checked' : '' }} required>
                                            Otro
                                        </label>
                                    </div>
                                </div>

                                <div class="field">
                                    <div class="label-field">
                                        <img src="{{ asset('images/datepicker-icon.png') }}">
                                        <span>Propiedad disponible el:</span>
                                    </div>
                                    <!--<input type="text" class="input date" autocomplete="off" name="available_date" id="available_date" value="{{ $property && !is_null($property->available_date) ? $property->available_date : '' }}">-->
                                    <div class="control">
                                        <input type="text" class="input date" autocomplete="off" name="available_date" id="available_date" value="{{ $property && !is_null($property->available_date) ? $property->available_date : '' }}" required>
                                    </div>
                                </div>
                                @if($type_property == 1)
                                <div class="field">
                                    <div class="label-field">
                                        <span>Requiere Habilitación de local</span>
                                    </div>
                                    <div class="control">
                                        <label class="radio">
                                            <input type="radio" name="room_enablement" id="room" value="1" {{ isset($property) && $property->room_enablement == 1 ? 'checked' : '' }} required>
                                                Si
                                        </label>
                                        <label class="radio">
                                            <input type="radio" name="room_enablement" id="room" value="0" {{ isset($property) && $property->room_enablement == 0 ? 'checked' : '' }} required>
                                                No
                                        </label>
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="label-field">
                                        <span>Requiere Obra Civil</span>
                                    </div>
                                    <div class="control">
                                        <label class="radio">
                                            <input type="radio" name="civil_work" id="civil_work" value="1" {{ isset($property) && $property->civil_work == 1 ? 'checked' : '' }} required>
                                                Si
                                        </label>
                                        <label class="radio">
                                            <input type="radio" name="civil_work" id="civil_work" value="0" {{ isset($property) && $property->civil_work == 0 ? 'checked' : '' }} required>
                                                No
                                        </label>
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="label-field">
                                        <span>Elaboración de Proyecto Arquitectura</span>
                                    </div>
                                    <div class="control">
                                        <label class="radio">
                                            <input type="radio" name="arquitecture_project" id="arquitecture_project" value="1" {{ isset($property) && $property->arquitecture_project == 1 ? 'checked' : '' }} required>
                                                Si
                                        </label>
                                        <label class="radio">
                                            <input type="radio" name="arquitecture_project" id="arquitecture_project" value="0" {{ isset($property) && $property->arquitecture_project == 0 ? 'checked' : '' }} required>
                                                No
                                        </label>
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="label-field">
                                        <span>Elaboración de Plan Eléctrico / Red de Aguas</span>
                                    </div>
                                    <div class="control">
                                        <label class="radio">
                                            <input type="radio" name="work_electric_water" id="work_electric_water" value="1" {{ isset($property) && $property->work_electric_water == 1 ? 'checked' : '' }} required>
                                                Si
                                        </label>
                                        <label class="radio">
                                            <input type="radio" name="work_electric_water" id="work_electric_water" value="0" {{ isset($property) && $property->work_electric_water == 0 ? 'checked' : '' }} required>
                                                No
                                        </label>
                                    </div>
                                </div>
                                @endif

                                <div class="field">
                                    <div class="label-field">
                                        <img src="{{ asset('images/icono-empresa.png') }}">
                                        <span>Constructora</span>
                                        <small class="is-text-7">(opcional)</small>
                                    </div>
                                    <div class="control">
                                        <input class="input" type="text" name="builder" id="builder" maxlength="30" value="{{ isset($property) ? $property->builder : '' }}">
                                    </div>
                                </div>

                                <div class="field">
                                    <div class="label-field">
                                        <img src="{{ asset('images/icono_trabajo.png') }}">
                                        <span>Arquitecto</span>
                                        <small class="is-text-7">(opcional)</small>
                                    </div>
                                    <div class="control">
                                        <input class="input" type="text" name="architect" id="architect" maxlength="30" value="{{ isset($property) ? $property->architect : '' }}">
                                    </div>
                                </div>

                                <div class="field">
                                    <div class="label-field">
                                        <img src="{{ asset('images/icono_descripcion.png') }}">
                                        <span>Descripción de tu Propiedad</span>
                                    </div>
                                    <div class="control">
                                        <textarea class="textarea" name="description" id="description" rows="8" cols="80" maxlength="800" required>{{ isset($property) ? $property->description : '' }}</textarea>
                                    </div>
                                </div>

                            </div>
                            <div class="property" style="display: none">
                                <div class="form-resident" style="display:none">
                                    <div class="field">
                                        <div class="label-field">
                                            <img src="{{ asset('images/icono_ciudad.png') }}">
                                            <span>Nombre de tu Propiedad</span>
                                        </div>
                                        <div class="control">
                                            <input type="text" autocomplete="off" class="input" name="name" id="name" value="{{ isset($property) ? $property->name : '' }}" maxlength="30">
                                        </div>
                                    </div>
                                    <div class="field">
                                        <div class="label-field">
                                            <img src="{{ asset('images/icono-estrella.png') }}">
                                            <span>Condicion de la Propiedad</span>
                                        </div>
                                        <div class="control">
                                            <label class="radio">
                                                <input type="radio" name="condition" id="condition" value="1" {{ isset($property) && $property->condition == '1' ? 'checked' : '' }} required>
                                                                                    Nueva
                                            </label>
                                            <label class="radio">
                                                <input type="radio" name="condition" id="condition" value="0" {{ isset($property) && $property->condition == '0' ? 'checked' : '' }} required>
                                                Usada
                                            </label>
                                        </div>
                                    </div>
                                    @if($property->type_stay == 'LONG_STAY' && $type_property == 1)
                                    <div class="field">
                                        <div class="label-field">
                                            <span>Requiere Habilitación de local</span>
                                        </div>
                                        <div class="control">
                                            <label class="radio">
                                                <input type="radio" name="room_enablement" id="room" value="1" {{ isset($property) && $property->room_enablement == '1' ? 'checked' : '' }} required>
                                                    Si
                                            </label>
                                            <label class="radio">
                                                <input type="radio" name="room_enablement" id="room" value="0" {{ isset($property) && $property->room_enablement == '0' ? 'checked' : '' }} required>
                                                    No
                                            </label>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <div class="label-field">
                                            <span>Requiere Obra Civil</span>
                                        </div>
                                        <div class="control">
                                            <label class="radio">
                                                <input type="radio" name="civil_work" id="civil_work" value="1" {{ isset($property) && $property->civil_work == '1' ? 'checked' : '' }} required>
                                                    Si
                                            </label>
                                            <label class="radio">
                                                <input type="radio" name="civil_work" id="civil_work" value="0" {{ isset($property) && $property->civil_work == '0' ? 'checked' : '' }} required>
                                                    No
                                            </label>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <div class="label-field">
                                            <span>Elaboración de Proyecto Arquitectura</span>
                                        </div>
                                        <div class="control">
                                            <label class="radio">
                                                <input type="radio" name="arquitecture_project" id="arquitecture_project" value="1" {{ isset($property) && $property->arquitecture_project == '1' ? 'checked' : '' }} required>
                                                    Si
                                            </label>
                                            <label class="radio">
                                                <input type="radio" name="arquitecture_project" id="arquitecture_project" value="0" {{ isset($property) && $property->arquitecture_project == '0' ? 'checked' : '' }} required>
                                                    No
                                            </label>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <div class="label-field">
                                            <span>Elaboración de Plan Eléctrico / Red de Aguas</span>
                                        </div>
                                        <div class="control">
                                            <label class="radio">
                                                <input type="radio" name="work_electric_water" id="work_electric_water" value="1" {{ isset($property) && $property->work_electric_water == '1' ? 'checked' : '' }} required>
                                                    Si
                                            </label>
                                            <label class="radio">
                                                <input type="radio" name="work_electric_water" id="work_electric_water" value="0" {{ isset($property) && $property->work_electric_water == '0' ? 'checked' : '' }} required>
                                                    No
                                            </label>
                                        </div>
                                    </div>
                                    @endif
                                    <div class="field">
                                        <div class="label-field">
                                            <img src="{{ asset('images/icono_descripcion.png') }}">
                                            <span>Descripción de tu Propiedad</span>
                                        </div>
                                        <div class="control">
                                            <textarea name="description" id="description" rows="8" cols="80" maxlength="800" required>{{ isset($property) ? $property->description : '' }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-office" style="display:none">
                                    <div class="field">
                                        <div class="label-field">
                                            <img src="{{ asset('images/icono_ciudad.png') }}">
                                            <span>Nombre de tu Propiedad</span>
                                        </div>
                                        <div class="control">
                                            <input type="text" autocomplete="off" class="input" name="name" id="name" value="{{ isset($property) ? $property->name : '' }}" maxlength="30">
                                        </div>
                                    </div>
                                    <div class="field">
                                        <div class="label-field">
                                            <img src="{{ asset('images/icono-estrella.png') }}">
                                            <span>Condición de tu Propiedad</span>
                                        </div>
                                        <div class="control">
                                            <label class="radio">
                                                <input type="radio" name="condition" id="condition" value="1" {{ isset($property) && $property->condition == '1' ? 'checked' : '' }} required>
                                                                                    Nueva
                                            </label>
                                            <label class="radio">
                                                <input type="radio" name="condition" id="condition" value="0" {{ isset($property) && $property->condition == '0' ? 'checked' : '' }} required>
                                                Usada
                                            </label>
                                        </div>
                                    </div>
                                    @if($property->type_stay == 'LONG_STAY')
                                    <div class="field">
                                        <div class="label-field">
                                            <span>Requiere Habilitación de local</span>
                                        </div>
                                        <div class="control">
                                            <label class="radio">
                                                <input type="radio" name="room_enablement" id="room" value="1" {{ isset($property) && $property->room_enablement == '1' ? 'checked' : '' }} required>
                                                    Si
                                            </label>
                                            <label class="radio">
                                                <input type="radio" name="room_enablement" id="room" value="0" {{ isset($property) && $property->room_enablement == '0' ? 'checked' : '' }} required>
                                                    No
                                            </label>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <div class="label-field">
                                            <span>Requiere Obra Civil</span>
                                        </div>
                                        <div class="control">
                                            <label class="radio">
                                                <input type="radio" name="civil_work" id="civil_work" value="1" {{ isset($property) && $property->civil_work == '1' ? 'checked' : '' }} required>
                                                    Si
                                            </label>
                                            <label class="radio">
                                                <input type="radio" name="civil_work" id="civil_work" value="0" {{ isset($property) && $property->civil_work == '0' ? 'checked' : '' }} required>
                                                    No
                                            </label>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <div class="label-field">
                                            <span>Elaboración de Proyecto Arquitectura</span>
                                        </div>
                                        <div class="control">
                                            <label class="radio">
                                                <input type="radio" name="arquitecture_project" id="arquitecture_project" value="1" {{ isset($property) && $property->arquitecture_project == '1' ? 'checked' : '' }} required>
                                                    Si
                                            </label>
                                            <label class="radio">
                                                <input type="radio" name="arquitecture_project" id="arquitecture_project" value="0" {{ isset($property) && $property->arquitecture_project == '0' ? 'checked' : '' }} required>
                                                    No
                                            </label>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <div class="label-field">
                                            <span>Elaboración de Plan Eléctrico / Red de Aguas</span>
                                        </div>
                                        <div class="control">
                                            <label class="radio">
                                                <input type="radio" name="work_electric_water" id="work_electric_water" value="1" {{ isset($property) && $property->work_electric_water == '1' ? 'checked' : '' }} required>
                                                    Si
                                            </label>
                                            <label class="radio">
                                                <input type="radio" name="work_electric_water" id="work_electric_water" value="0" {{ isset($property) && $property->work_electric_water == '0' ? 'checked' : '' }} required>
                                                    No
                                            </label>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    <div class="field">
                                        <div class="label-field">
                                            <img src="{{ asset('images/icono_descripcion.png') }}">
                                            <span>Descripción de tu Propiedad</span>
                                        </div>
                                        <div class="control">
                                            <textarea name="description" id="description" rows="8" cols="80" maxlength="800" required>{{ isset($property) ? $property->description : '' }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="form-footer">
                    <a href="@route('users.agents.third-step')/{{$property->id}}" class="button is-outlined">Atrás</a>
                    <img src="{{ asset('images/icono_guardar.png') }}" id="save-button-icon">
                    <input type="submit" class="button is-outlined is-primary" value="Continuar" form="registration-form">
                </div>
            </div>
            <div class="column side-info">
                <div class="info">
                    <img src="{{ asset('images/icono-atencion.png') }}">
                    <p>{{$message1}}</p>
                    <div class="border"></div>
                </div>
            </div>
        </div>
    </section>
</div>
@include('components.users.common-forms.save-button.modal')
@endsection
@section('scripts')
<script src="{{ asset('js/property-forms/first-step-one.js') }}" charset="utf-8"></script>
@endsection
