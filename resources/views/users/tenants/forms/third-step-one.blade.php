@extends('layouts.flujo-base', ['navTitle' => '<span class="bold">Paso 3.1:</span> Tus preferencias de pago'])
@section('content')
<div class="container" id="ten-third-step-one">
    <section class="section main-section">
        <div class="columns">
            <div class="column is-7 flow-form">
                <div class="div">
                    <form method="POST" action="{{-- route($form_route) --}}" enctype="multipart/form-data" id="registration-form">
                        {{ csrf_field() }}
                        <div class="field">
                            <div class="label-field">
                                <img src="{{ asset('images/icono_dinero.png') }}">
                                <span>Límite gasto mensual arriendo</span>
                            </div>
                            <div class="control has-icons-left has-icons-right">
                                <input class="input monto_formato_decimales" type="text" autocomplete="off"  name="expenses_limit" value="{{ $user->expenses_limit ? $user->expenses_limit : '' }}">
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
                                <span>Límite para gastos comunes</span>
                            </div>
                            <div class="control has-icons-left has-icons-right">
                                <input class="input monto_formato_decimales" type="text" autocomplete="off"  name="common_expenses_limit" value="{{ $user->common_expenses_limit ? $user->common_expenses_limit : '' }}">
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
                                <img src="{{ asset('images/icono_dinero.png') }}" style="margin-right: 0.1rem;">
                                <span>¿Quieres pagar Seguro de Arriendo?</span>
                            </div>
                            <div class="control">
                                <label class="radio">
                                    <input type="radio" name="tenanting_insurance" value="1" {{ $user->tenanting_insurance ? 'checked' : ''}}>
                                    Si
                                </label>
                                <label class="radio">
                                    <input type="radio" name="tenanting_insurance" value="0" {{ !$user->tenanting_insurance ? 'checked' : ''}}>
                                    No
                                </label>
                            </div>
                        </div>
                        <div class="field">
                            <div class="label-field">
                                <img src="{{ asset('images/icono_numero.png') }}">
                                <span>¿Cuántos meses de garantía puedes dar?</span>
                            </div>
                            <div class="select">
                                <select name="warranty_months_quantity">
                                    @for($i=1;$i<=12;$i++)
                                      @if($i == 1)
                                          <option {{ $i == $user->warranty_months_quantity ? 'selected' : '' }} value="{{ $i }}">{{$i}} Mes</option>
                                      @else
                                          <option {{ $i == $user->warranty_months_quantity ? 'selected' : '' }} value="{{$i}}" >{{$i}} Meses</option>
                                      @endif
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="field">
                            <div class="label-field">
                                <img src="{{ asset('images/icono_numero.png') }}">
                                <span>¿Cuántos meses de adelanto?</span>
                            </div>
                            <div class="select">
                                <select name="months_advance_quantity">
                                    @for($i=1;$i<=12;$i++)
                                    @if($i == 1)
                                      <option {{ $i == $user->months_advance_quantity ? 'selected' : '' }} value="{{$i}}"  >{{$i}} Mes</option>
                                      @else
                                        <option {{ $i == $user->months_advance_quantity ? 'selected' : '' }} value="{{$i}}"  >{{$i}} Meses</option>
                                    @endif
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="field">
                            <div class="label-field">
                                <img src="{{ asset('images/icono-calendario-azul.png') }}">
                                <span>¿Qué día quieres mudarte?</span>
                            </div>
                            <input type="text" autocomplete="off"  class="input date" name="move_date" id="move_date" value="{{ !is_null($user->move_date) ? date('d/m/Y',strtotime($user->move_date)) : date('d/m/Y', strtotime('+1 day') ) }}">
                        </div>
                        <div class="field">
                            <div class="label-field">
                                <img src="{{ asset('images/icono_numero.png') }}">
                                <span>¿Por cuánto tiempo quieres arrendar?</span>
                            </div>
                            <div class="select">
                                <select name="tenanting_months_quantity">
                                    @for($i=1; $i<=12; $i++)
                                        @if($i == 1)
                                            <option  {{ $i == $user->tenanting_months_quantity ? 'selected' : '' }} value="{{$i}}" >{{$i}} Mes</option>
                                        @else
                                            <option {{ $i == $user->tenanting_months_quantity ? 'selected' : '' }} value="{{$i}}" >{{$i}} Meses</option>
                                        @endif
                                    @endfor
                                    <option value="18" {{ "18" == $user->tenanting_months_quantity ? 'selected' : '' }}>18 Meses</option>
                                    <option value="24" {{ "24" == $user->tenanting_months_quantity ? 'selected' : '' }}>24 Meses</option>
                                    <option value="36" {{ "36" == $user->tenanting_months_quantity ? 'selected' : '' }}>36 Meses</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="form-footer">
                    <a href="{{route('users.tenants.third-step')}}" class="button is-outlined">Atrás</a>
                    <img src="{{ asset('images/icono_guardar.png') }}" id="save-button-icon">
                    <input type="submit" class="button is-outlined is-primary" value="Continuar" form="registration-form">
                </div>
            </div>
            <div class="column side-info">
                <div class="info">
                    <img src="{{ asset('images/icono-atencion.png') }}">
                    <p>Por lo general para residentes chilenos y extranjeros con RUT el arrendado exigirá entre 1 y 2 meses de adelanto.</p>
                    <div class="border"></div>
                </div>
                <div class="info">
                    <img src="{{ asset('images/icono-atencion.png') }}">
                    <p>Para Extranjeros con pasaporte para aumentar tus probabilidades de arriendo se recomienda dar entre 3 a 4 meses por adelantado.</p>
                    <div class="border"></div>
                </div>
                <div class="info">
                    <img src="{{ asset('images/icono-atencion.png') }}">
                    <p>Uhomie te recomienda 1 mes de garantía y entre 1 y 2 meses de adelanto.</p>
                    <div class="border"></div>
                </div>
                <div class="info">
                    <img src="{{ asset('images/icono-atencion.png') }}">
                    <p>
                        Uhomie recomienda un tiempo mínimo de 3 meses para permanecer en las propiedades. A los arrendadores les encantan arrendatarios con tiempos mínimos de arriendo de 3 meses hasta 12 meses.
                    </p>
                    <div class="border"></div>
                </div>
            </div>
        </div>
    </section>
</div>
@include('components.users.common-forms.save-button.modal')
@endsection

@section('scripts')
<script src="{{ asset('js/third-step-one.js') }}" charset="utf-8"></script>

@endsection
