@extends('layouts.flujo-base', ['navTitle' => '<span class="bold">Paso 2:</span> Tus datos laborales'])

@section('content')
<div class="container" id="second-step-page">
    <section class="section main-section">
        <div class="columns">
            <div class="column is-7 flow-form">
                <div class="div">
                    <form method="POST" action="" enctype="multipart/form-data" id="registration-form">
                        {{ csrf_field() }}
                        <input type="hidden" value="{{ $employment_type }}" name="employment_type" required>
                        <h1 class="form-title">Trabajo Actual</h1>
                        <div class="field">
                            <div class="label-field">
                                <img src="{{ asset('images/icono-empleo.png') }}">
                                <span>Cargo</span>
                            </div>
                            <input type="text" autocomplete="off" name="position" class="input" value="{{ $user->checkEmploymentEmployee() ? $user->position : '' }}" required>
                        </div>
                        <div class="field">
                            <div class="label-field">
                                <img src="{{ asset('images/icono-empresa.png') }}">
                                <span>Empresa</span>
                            </div>
                            <input type="text" autocomplete="off" name="company" class="input" value="{{ $user->checkEmploymentEmployee() ? $user->company : '' }}" required>
                        </div>
                        <div class="field">
                            <div class="label-field">
                                <img src="{{ asset('images/icono_trabajo.png') }}">
                                <span>Tipo de trabajo</span>
                            </div>
                            <div class="select">
                                <select name="job_type" required>
                                    <option value="FullTime" {{ $user->checkEmploymentEmployee() && $user->job_type == 'FullTime' ? 'selected' : ''  }}>Full Time</option>
                                    <option value="PartTime" {{ $user->checkEmploymentEmployee() && $user->job_type == 'PartTime' ? 'selected' : ''  }}>Part Time</option>
                                    <option value="Indefinido" {{ $user->checkEmploymentEmployee() && $user->job_type == 'Indefinido' ? 'selected' : ''  }}>Indefinido</option>
                                    <option value="Por Proyecto" {{ $user->checkEmploymentEmployee() && $user->job_type == 'Por Proyecto' ? 'selected' : ''  }}>Por Proyecto</option>
                                    <option value="Por Honorarios" {{ $user->checkEmploymentEmployee() && $user->job_type == 'Por Honorarios' ? 'selected' : ''  }}>Por Honorarios</option>
                                    <option value="Freelancer" {{ $user->checkEmploymentEmployee() && $user->job_type == 'Freelancer' ? 'selected' : ''  }}>Freelancer</option>
                                </select>
                            </div>
                        </div>
                        <div class="field">
                            <div class="label-field">
                                <img src="{{ asset('images/icono-calendario-azul.png') }}">
                                <span>De</span>
                                <input type="text" autocomplete="off" name="worked_from_date" id="worked_from_date"  class="input date" value="{{ $user->checkEmploymentEmployee() && !is_null($user->worked_from_date) ? date('d/m/Y',strtotime($user->worked_from_date)) : date('d/m/Y') }}" required>
                            </div>
                                <div class="label-field">
                                    <span>Hasta</span>
                                    Actualidad
                                </div>
                        </div>
                        <div class="field">
                            <div class="label-field">
                                <img src="{{ asset('images/icono_dinero.png') }}">
                                <span>Salario líquido percibido</span>
                            </div>
                            <div class="control has-icons-left has-icons-right">
                                <input class="input monto_formato_decimales" type="text" name="amount" id="amount" value="{{ $user->checkEmploymentEmployee() ? $user->amount : '' }}" required onkeyup="" onkeypress="">
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
                                <img src="{{ asset('images/icono-chancho.png') }}">
                                <span>¿Posee ahorros?</span>
                            </div>
                            <div class="control">
                                <label class="radio">
                                    <input type="radio" name="saves" id="saves" value="1" {{ $user->checkEmploymentEmployee() && $user->saves ? 'checked' : '' }} required>
                                    Si
                                  </label>
                                <label class="radio">
                                    <input type="radio" name="saves" id="saves" value="0" {{ $user->checkEmploymentEmployee() && !$user->saves ? 'checked' : '' }} required>
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
                                <input class="input monto_formato_decimales" type="text" autocomplete="off" name="save_amount" id="save_amount" value="{{ $user->checkEmploymentEmployee() && $user->saves ? $user->save_amount : '' }}" required>
                                <span class="icon is-small is-left">
                                    $
                                </span>
                                <span class="icon is-small is-right">
                                    CLP
                                </span>
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
                                        <option value="{{ $key }}" {{ $user->checkEmploymentEmployee() && $user->other_income_type == $key ? 'selected' : ''  }}> {{ $value }}</option>
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
                                <input class="input monto_formato_decimales" type="text" autocomplete="off" name="income" id="income" value="{{ $user->checkEmploymentEmployee() ? $user->other_income_amount : '' }}">
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
                                <input class="input" type="text" autocomplete="off" readonly name="liquidez_total" id="liquidez_total">
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
<script src="{{ asset('js/second-step-employee.js') }}" charset="utf-8"></script>
@endsection
