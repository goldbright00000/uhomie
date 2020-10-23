@extends('layouts.flujo-base', ['navTitle' => '<span class="bold">Paso 5.1:</span> Documentos'])
@section('content')

    <div class="container" id="own-fifth-step-one">
        <section class="section main-section">
            <div class="columns">
                <div class="column is-7 flow-form">
                    <div class="div">
                        <form method="POST" action="" enctype="multipart/form-data" id="registration-form">
                            {{ csrf_field() }}
                            <p style="font-style: italic;font-weight: 400;" ><i class="fa fa-info-circle has-text-info"></i> Por favor indique numero</p>
                            <p style="font-style: italic;font-weight: 400;" > de cuenta Bancaria en la cual</p>
                            <p style="font-style: italic;font-weight: 400;" > UHOMIE depositara el dinero</p>
                            <p style="font-style: italic;font-weight: 400;" > Una vez se concrete el arriendo</p>
                            <p style="font-style: italic;font-weight: 100;">(Recuerda que debes ser el titular de la cuenta)</p>
                            <br>

                            <div class="field">
                                <div class="label-field">
                                    <span>Banco</span>
                                </div>
                                <div class="select">
                                    <select name="bank" id="bank" required>
                                        <option selected disabled>Seleccione</option>
                                            @foreach($banks as $bank)
                                                <option value="{{$bank->id}}" @if($user && $user->bank && $user->bank->id == $bank->id) selected="selected" @endif>{{$bank->name}}</option>
                                            @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="field">
                                <div class="label-field">
                                    <span>Tipo de Cuenta</span>
                                </div>
                                <div class="select">
                                    <select name="account_type" id="account_type">

                                        <option value="Cuenta de Ahorro" {{ isset($user) && $user->account_type == 'Cuenta de Ahorro' ? 'selected' : '' }} >Cuenta de Ahorro</option>
                                        <option value="Cuenta Corriente" {{ isset($user) && $user->account_type == 'Cuenta Corriente' ? 'selected' : '' }} >Cuenta Corriente</option>
                                        <option value="Cuenta RUT" {{ isset($user) && $user->account_type == 'Cuenta RUT' ? 'selected' : '' }} >Cuenta RUT</option>
                                    </select>
                                </div>
                            </div>
                            <div class="field">
                                <div class="label-field">
                                    <span>Numero de Cuenta</span>
                                </div>
                                <input type="text" name="account_number" class="input numbers" value="{{ isset($user) ? $user->account_number : '' }}" required>
                            </div>
                            <h1 class="form-title">Documentos personales <span class="form-subtitle">(Documentos formato JPG/PNG)</span></h1>
                            <article class="message is-info is-small">
                                <div class="message-body">
                                        <i class="fa fa-info-circle has-text-info"></i> Los documentos de identidad son requeridos ya que se utilizaran para verificar identidad y para ser anexado en el contrato.
                                </div>
                            </article>
                            @include('components.common.files', [
                                'title' => "Rut (Foto anverso) / Pasaporte (Foto Hoja Principal)",
                                'name' => 'id_front',
                                'mimes' => 'image/*',
                                'file' => $user->files()->where('name', 'id_front')->first()
                             ])
                             @include('components.common.files', [
                                 'title' => "Rut (Foto reverso) / Pasaporte (Foto Visado)",
                                 'name' => 'id_back',
                                 'mimes' => 'image/*',
                                 'file' => $user->files()->where('name', 'id_back')->first()
                              ])

                            <h1 class="form-title">Documentos de tu propiedad <span class="form-subtitle">(Documentos formato JPG/PNG)</span></h1>
                            @if($property_type == 0)
                            @include('components.common.files', [
                                'title' => "Ultimo recibo de Luz",
                                'name' => 'last_electricity_bill',
                                'mimes' => 'image/*,application/pdf',
                                'file' => $property ? $property->files()->where('name', 'last_electricity_bill')->first() : null
                             ])
                            @include('components.common.files', [
                                'title' => "Ultimo Recibo de Agua",
                                'name' => 'last_water_bill',
                                'mimes' => 'image/*,application/pdf',
                                'file' => $property ? $property->files()->where('name', 'last_water_bill')->first() : null
                            ])
                            @include('components.common.files', [
                                'title' => "Ultimo Recibo de Gastos Comunes",
                                'name' => 'common_expense_receipt',
                                'mimes' => 'image/*,application/pdf',
                                'file' => $property ? $property->files()->where('name', 'common_expense_receipt')->first() : null
                            ])
                            @include('components.common.files', [
                                'title' => "Certificado de Propiedad o Certificado de Origen",
                                'name' => 'property_certificate',
                                'mimes' => 'application/pdf',
                                'file' => $property ? $property->files()->where('name', 'property_certificate')->first() : null
                            ])
                            @else
                            @include('components.common.files', [
                                'title' => "Contrato de copropiedad del edificio",
                                'name' => 'building_property_contract',
                                'mimes' => 'image/*,application/pdf',
                                'file' => $property ? $property->files()->where('name', 'building_property_contract')->first() : null
                            ])
                            @include('components.common.files', [
                                'title' => "Condiciones de habilitación de local",
                                'name' => 'property_room_conditions',
                                'mimes' => 'image/*,application/pdf',
                                'file' => $property ? $property->files()->where('name', 'property_room_conditions')->first() : null
                            ])
                            @include('components.common.files', [
                                'title' => "Reglamento del edificio / local",
                                'name' => 'regulation_rules',
                                'mimes' => 'image/*,application/pdf',
                                'file' => $property ? $property->files()->where('name', 'regulation_rules')->first() : null
                            ])
                            @endif
                        </div>
                        <div class="form-footer">
                            <a href="{{route('users.owners.fifth-step')}}" class="button is-outlined">Atrás</a>
                            <img src="{{ asset('images/icono_guardar.png') }}" id="save-button-icon">                            <input type="submit" class="button is-outlined is-primary" value="Continuar" form="registration-form">
                        </div>
                    </div>
                    <div class="column side-info">
                        <div class="info">
                            <img src="{{ asset('images/icono-atencion.png') }}">
                            <p>Los documentos de soporte son muy</p>
                            <p>importantes, ayudan a certificar tu</p>
                            <p>propiedad y darle un mayor Scoring UHOMIE.</p>
                            <p>los arrendatarios alquilan mas rapido</p>
                            <p>cuando mas puntaje tenga tu propiedad</p>
                            <div class="border"></div>
                        </div>
                        <div class="info">
                            <img src="{{ asset('images/icono-atencion.png') }}">
                            <p>Si no posees todos los documentos no te</p>
                            <p>preocupes podras cargarlos hasta 48 horas</p>
                            <p>despues de publicado tu aviso.</p>
                            <div class="border"></div>
                        </div>
                        <div class="info">
                            <img src="{{ asset('images/icono-atencion.png') }}">
                            <p>Recuerda los documentos como Certificados de</p>
                            <p>Servicios, Luz, Agua, Gas, Internet deberan</p>
                            <p>tener maximo 30 dias de antiguedad.</p>
                            <div class="border"></div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        @include('components.users.common-forms.save-button.modal')
        <div class="modal">
            <div class="modal-background"></div>
            <div class="modal-card">
                <header class="modal-card-head">
                    <p class="modal-card-title">Modal title</p>
                    <button class="delete" aria-label="close"></button>
                </header>
                <section class="modal-card-body">
                    <!-- Content ... -->
                </section>
                <footer class="modal-card-foot">
                    <button class="button">Cancel</button>
                </footer>
            </div>
        </div>
    @endsection

    @section('scripts')
        <script src="{{ asset('js/property-forms/four-step-one.js') }}" charset="utf-8"></script>
        <script src="{{ asset('js/third-step-three.js') }}"></script>

    @endsection
