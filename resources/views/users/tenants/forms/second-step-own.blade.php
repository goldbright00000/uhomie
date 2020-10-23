@extends('layouts.flujo-base', ['navTitle' => '<span class="bold">Paso 2:</span> Tus datos laborales'])
@section('content')
<div class="container" id="second-step-page">
    <section class="section main-section">
        <div class="columns">
            <div class="column is-7 flow-form">
                <div class="div">
                    <form method="POST" action="{{-- route($form_route) --}}" enctype="multipart/form-data" id="registration-form">
                        {{ csrf_field() }}
                        <h1 class="form-title">Ingresos personales</h1>
                        <div class="field">
                            <div class="label-field">
                                <img src="{{ asset('images/icono-empleo.png') }}">
                                <span>A qué te dedicas</span>
                            </div>
                            <input type="text" autocomplete="off" name="position" id="position" class="input" value="{{$user->checkEmploymentOwner() ? $user->position : '' }}" required>
                        </div>
                        <div class="field">
                            <div class="label-field">
                                <img src="{{ asset('images/icono-chancho.png') }}">
                                <span>¿Posee ahorros?</span>
                            </div>
                            <div class="control">
                                <label class="radio">
                                    <input type="radio" name="saves" id="saves" value="1" {{ $user->checkEmploymentOwner() && $user->saves ? 'checked' : '' }} required>
                                    Si
                                  </label>
                                <label class="radio">
                                    <input type="radio" name="saves" id="saves" value="0" {{ $user->checkEmploymentOwner() && !$user->saves ? 'checked' : '' }} required>
                                    No
                                </label>
                            </div>
                        </div>
                        <div class="field">
                            <div class="label-field">
                                <img src="{{ asset('images/icono_dinero.png') }}">
                                <span>Indique monto ahorrado</span>
                            </div>
                            <div class="control has-icons-left has-icons-right">
                                <input class="input monto_formato_decimales" type="text" autocomplete="off" name="save_amount" id="save_amount" value="{{ $user->checkEmploymentOwner() && $user->saves ? $user->save_amount : '' }}" required>
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
                                <img src="{{ asset('images/icono-doc.png') }}">
                                <span>¿Posee Boletas de Honorarios o facturas de pago por tus servicios?</span>
                            </div>
                            <div class="control">
                                <label class="radio">
                                    <input type="radio" name="invoice" id="invoice" value="1" {{ $user->checkEmploymentOwner() && $user->invoice ? 'checked' : is_null($user->invoice) ? 'checked' : '' }} >
                                    Si
                                </label>
                                <label class="radio">
                                    <input type="radio" name="invoice" id="invoice" value="0" {{ $user->checkEmploymentOwner() && !$user->invoice ? 'checked' : is_null($user->invoice) ? 'checked' : '' }} >
                                    No
                                </label>
                            </div>
                        </div>
                        <div class="field">
                            <div class="label-field">
                                <img src="{{ asset('images/icono_dinero.png') }}">
                                <span>Indique monto de su ultima boleta</span>
                            </div>
                            <div class="control has-icons-left has-icons-right">
                                <input class="input monto_formato_decimales" type="text" autocomplete="off" name="last_invoice_amount" id="last_invoice_amount" value="{{ $user->checkEmploymentOwner() ? $user->last_invoice_amount : '' }}" required>
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
                                <img src="{{ asset('images/icono_id.png') }}">
                                <span>Cotizas en AFP</span>
                            </div>
                            <div class="control">
                                <label class="radio">
                                    <input type="radio" name="afp" id="afp" value="1" {{ $user->checkEmploymentOwner() && $user->afp ? 'checked' : is_null($user->invoice) ? 'checked' : '' }} required>
                                    Si
                                </label>
                                <label class="radio">
                                    <input type="radio" name="afp" id="afp" value="0" {{ $user->checkEmploymentOwner() && !$user->afp ? 'checked' : is_null($user->invoice) ? 'checked' : '' }} required>
                                    No
                                </label>
                            </div>
                        </div>
                        <h1 class="form-title">Ingreso mensual adicional</h1>
                        <div class="field">
                            <div class="label-field">
                                <img src="{{ asset('images/icono_dinero.png') }}">
                                <span>Tipo de ingreso</span>
                            </div>
                            <div class="select">
                                <select name="other_type" id="other_type">
                                    @foreach ($other_income_type as $key => $value)
                                        <option value="{{ $key }}" {{ $user->checkEmploymentOwner() && $user->other_income_type == $key ? 'selected' : ''  }}> {{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="field row-income">
                            <div class="label-field">
                                <img src="{{ asset('images/icono_dinero.png') }}">
                                <span>Monto adicional</span>
                            </div>
                            <div class="control has-icons-left has-icons-right">
                                <input class="input monto_formato_decimales" type="text" autocomplete="off" name="income" id="income" value="{{ $user->checkEmploymentOwner() ? $user->other_income_amount : '' }}">
                                <span class="icon is-small is-left">
                                    $
                                </span>
                                <span class="icon is-small is-right">
                                    CLP
                                </span>
                            </div>
                        </div>
                        <h1 class="form-title">Sumario financiero</h1>
                        <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_dinero.png') }}">
                                    <span>Liquidez total</span>
                                    <span>(Ingresos mensuales + Ingresos adicionales)</span>
                                </div>
                                <div class="control has-icons-left has-icons-right">
                                    <input class="input monto_formato_decimales" type="text" autocomplete="off" name="liquidez_total" id="liquidez_total">
                                    <span class="icon is-small is-left">
                                        $
                                    </span>
                                    <span class="icon is-small is-right">
                                        CLP
                                    </span>
                                </div>
                            </div>
                    </form>
                </div>
                <div class="form-footer">
                    <a href="{{route('users.tenants.second-step')}}" class="button is-outlined">Atrás</a>
                    <img src="{{ asset('images/icono_guardar.png') }}" id="save-button-icon">
                    <input type="submit" class="button is-outlined is-primary" value="Continuar" form="registration-form">
                </div>
            </div>
            <div class="column side-info">
                <div class="info">
                    <img src="{{ asset('images/icono-atencion.png') }}">
                    <p>Si eres Chileno o Extranjero con RUT vigente, recuerda adjuntar tus tres últimas liquidaciones, el historial de un año de AFP y el certificado de trabajo.</p>
                    <div class="border"></div>
                </div>
                <div class="info">
                    <img src="{{ asset('images/icono-atencion.png') }}">
                    <p>Si eres extranjero con pasaporte deberás anexar tu contrato laboral u oferta laboral vigente preferiblemente notariada.</p>
                    <div class="border"></div>
                </div>
            </div>
        </div>
    </section>
</div>
@include('components.users.common-forms.save-button.modal')
@endsection

@section('scripts')
<script src="{{ asset('js/second-step-own.js') }}" charset="utf-8"></script>
@endsection
