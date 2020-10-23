@extends('layouts.flujo-base', ['navTitle' => '<span class="bold">Paso 2.1:</span> Tu publicacion'])
@section('content')

    <div class="container" id="service-second-step-two">
        <section class="section main-section">
            <div class="columns">
                <div class="column is-7 flow-form">
                    <div class="div">
                        <form method="POST" action="" enctype="multipart/form-data" id="registration-form">
                            {{ csrf_field() }}
                          <div class="field">
                          	<div class="label-field">
                          		<span>¿La publicacion la hara con una empresa registrada o a titulo personal?</span>
                          	</div>
                          	<div class="control">
                          		<label class="radio">
                          			<input type="radio" id="particular" name="personal_publish" value="1" {{ $user->getServiceCompany() && !$user->getServiceCompany()->invoice ? 'checked' : '' }}  >
                          			Particular
                          		</label>
                          		<label class="radio">
                          			<input type="radio" id="empresa" name="personal_publish" value="0" {{ $user->getServiceCompany() && $user->getServiceCompany()->invoice ? 'checked'  : '' }}   >
                          			Empresa
                          		</label>
                          	</div>
                          </div>
                          <div class="row-sii" >
                            <div class="field ">
                              <div class="label-field">
                                  <span>¿Tu empresa esta registrada en el SII?</span>
                              </div>
                              <div class="control">
                                  <label class="radio">
                                      <input type="radio" name="sii" value="1" {{ $user->getServiceCompany() && $user->getServiceCompany()->sii ? 'checked' : '' }} required >
                                      Si
                                  </label>
                                  <label class="radio">
                                      <input type="radio" name="sii" value="0" {{ $user->getServiceCompany() && !$user->getServiceCompany()->sii ? 'checked' : '' }} required >
                                      No
                                  </label>
                              </div>
                          </div>
                          </div>


                        </div>
                        <div class="form-footer">
                            <a href="{{ route('users.services.second-step.one') }}" class="button is-outlined">Atrás</a>
                            <img src="{{ asset('images/icono_guardar.png') }}" id="save-button-icon">
                            <input type="submit" class="button is-outlined is-primary" value="Continuar" form="registration-form">
                        </div>
                    </div>
                    <div class="column side-info">
                        <div class="info">
                            <img src="{{ asset('images/icono-atencion.png') }}">
                            <p>Deberas adjuntar las fotos de anverso</p>
                            <p>y reverso de tu empresa carnet RUT</p>
                            <div class="border"></div>
                        </div>

                    </div>
                </div>
            </section>
        </div>
        @include('components.users.common-forms.save-button.modal')
    @endsection

    @section('scripts')
    <script src="{{ asset('js/service-forms/second-step-two.js') }}" charset="utf-8"></script>
    @endsection
