@extends('layouts.flujo-base', ['navTitle' => '<span class="bold">Paso 2.2:</span> Tu servicio'])
@section('content')

<input type="hidden" id="uri_get_services_lists" value="@route('users.services.get-services-list')" >
<input type="hidden" id="main_services_limit" value="{{ $main_services_limit }}" >
<input type="hidden" id="secondary_services_limit" value="{{ $secondary_services_limit }}" >
<div class="container" id="service-second-step-three">
    <section class="section main-section">
        <div class="columns">
            <div class="column is-7 flow-form">
                <div class="div">
                    <form method="POST" action="{{-- route($form_route) --}}" enctype="multipart/form-data" id="registration-form">
                        {{ csrf_field() }}
                        <h1 class="form-title">Servicios disponibles</h1>
                        <div class="field">

                          <div class="control">
                            <input type="text" list="services_type" id="services_type_input" class="input" placeholder="Seleccione un servicio principal" >
                            <datalist id="services_type" class="service_type_datalist">
                              @foreach ($services_type as $service_type)
                                <option value="{{ $service_type->name }}" service_type_id="{{ $service_type->id }}">
                              @endforeach
                            </datalist>
                          </div>
                        </div>
                        @foreach($services_type as $service_type)
                          <div class="service_type" service_type_name="{{ $service_type->name }}" service_type_id="{{ $service_type->id }}" style="display:none"><br>
                            <div class="field" >
                              <div class="label-field"> 
                                <a href="#" class="close_main_service" >
                                  <img src="{{ asset('images/icono_cruz_basic.png') }}">
                                </a>
                                <span>{{ $service_type->name }} </span>
                              </div>
                              <div class="control ">
                                <input type="text" list="services_list_{{ $service_type->id }}" id="services_type_input_{{ $service_type->id }}" name="services_list" class="input datalist_services_list" placeholder="Servicios secundarios de {{ $service_type->name }}">
                                <datalist id="services_list_{{ $service_type->id }}" class="secondary_services_list" >
                                  @foreach ($services_list as $service)
                                    @if( $service->service_type_id == $service_type->id )
                                    <option value="{{ $service->name }}" >
                                    @endif
                                  @endforeach
                                </datalist>
                              </div>
                            </div>
                            <div class="tags-services">
                              @foreach ($services_list as $service)
                                @if( $service->service_type_id == $service_type->id )
                                  <div class="secondary-service" style="display:none;">
                                    <label class="checkbox square active"  >
                                        <input type="checkbox" name="secondary_services[]" service_name="{{ $service->name }}" value="{{ $service->id }}" class="check_services check_property_service" >
                                       {{ $service->name }} <span class="fa fa-minus"></span>
                                    </label>
                                  </div>
                                @endif
                              @endforeach
                            </div>
                          </div>
                        @endforeach
                        <div class="field">
                          <div class="label-field">
                              <img src="{{ asset('images/icono_ciudad.png') }}">
                              <span>Descripcion de la empresa de Servicios</span>
                          </div>
                          <input type="text" autocomplete="off" class="input" name="description" id="description" value="{{ isset($user) ? $user->getServiceCompany()->description : '' }}" >
                      </div>
                    </form>
                </div>
                <div class="form-footer">
                    <a href="{{route('users.services.second-step.two')}}" class="button is-outlined">Atrás</a>
                    <img src="{{ asset('images/icono_guardar.png') }}" id="save-button-icon">
                    <input type="submit" class="button is-outlined is-primary" value="Continuar" form="registration-form">
                </div>
            </div>
            <div class="column side-info">
                <div class="info">
                    <img src="{{ asset('images/icono-atencion.png') }}">
                    <p>Puedes seleccionar mas de un</p>
                    <p>servicio. Toda esa informacion hara</p>
                    <p>mas efectiva la promocion de tu aviso</p>
                    <div class="border"></div>
                </div>
            </div>
        </div>
    </section>
    @if($payment != null)
    <div id="back_dir" class="modal is-active">
      <div class="modal-background"></div>
      <div class="modal-card">
          <div class="has-text-centered">
              <img src="{{ asset('images/foco.png') }}">
          </div>
          <div class="modal-card-body">
              <div class="has-text-centered">
                  <h4>Atención!</h4>
                  <div class="columns">
                      <div class="column">
                          La transacción ha sido <b>{{$payment->statusname}}</b>
                      </div>
                  </div>
              </div>
              <div class="columns has-text-left">
                  <div class="column is-one-third"><b>Orden:</b></div>
                  <div class="column">{{$payment->order}}</div>
              </div>
              <div class="columns has-text-left">
                  <div class="column is-one-third"><b>Fecha de Orden:</b></div>
                  <div class="column">{{$payment->updated_at}}</div>
              </div>
              <div class="columns has-text-left">
                  <div class="column is-one-third"><b>Detalle de Orden:</b></div>
                  <div class="column">{{$payment->details}}</div>
              </div>
              <div class="columns has-text-left">
                  <div class="column is-one-third"><b>Monto:</b></div>
                  <div class="column">$ {{$payment->amount}} {{$payment->currency}}</div>
              </div>
              <div class="columns has-text-left">
                  <div class="column is-one-third"><b>Monto + IVA:</b></div>
                  <div class="column">$ {{$payment->total}} {{$payment->currency}}</div>
              </div>
              @if($payment->token_ws != null)
              <div class="columns has-text-left">
                  <div class="column is-one-third"><b>token_ws:</b></div>
                  <div class="column">{{$payment->token_ws}}</div>
              </div>
              @endif
          </div>
          <div class="has-text-centered" style="padding: 5px 0;">
              <a class="button is-info is-outlined" onclick="closeModal()">VOLVER</a>
          </div>
      </div>
    </div>
    @endif
</div>
@include('components.users.common-forms.save-button.modal')
@endsection

@section('scripts')
  <script src="{{ asset('js/service-forms/second-step-three.js') }}" charset="utf-8"></script>
  <script>
  function closeModal(){
      $('#back_dir').removeClass('is-active');
  }
  </script>
@endsection
