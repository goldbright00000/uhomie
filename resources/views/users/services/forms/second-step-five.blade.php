@extends('layouts.flujo-base', ['navTitle' => '<span class="bold">Paso 2.5:</span> Descripción de Servicios'])
@section('styles')
<style>
    .textarea {
        border-radius: 5px;
        border: 2px dashed rgb(0, 135, 247);
    }
</style>
@endsection
@section('content')


<div class="container" id="service-second-step-four">
  <section class="section main-section">
      <div class="columns">
          <div class="column is-7 flow-form">
              <div class="div">
                <form method="POST" action="{{ route('users.services.second-step.five') }}" enctype="multipart/form-data" id="registration-form">
                    {{ csrf_field() }}
                    @foreach ($services as $service)
                    <div class="field">
                        <input type="hidden" name="id[]" value="{{$service->id}}">
                        <div class="label-field">
                            <span><b>Descripción de servicio:</b> {{$service->name}}</span>
                        </div>
                        <div class="control">
                            <textarea class="textarea" name="description[]" minlength="50" maxlength="1024" placeholder="Escriba una descripcion detallada de su servicio {{$service->name}}">{{$service->description}}</textarea>
                        </div>
                    </div>
                    @endforeach
                </form>
              </div>
              <div class="form-footer">
                  <a href="{{url('/users/service/r/s-s/four')}}" class="button is-outlined">Atrás</a>
                  <img src="{{ asset('images/icono_guardar.png') }}" id="save-button-icon">
                  <input type="submit" class="button is-outlined is-primary" value="Continuar" form="registration-form">
              </div>
          </div>
          <div class="column side-info">
              <div class="info">
                  <img src="{{ asset('images/icono-atencion.png') }}">
                  <p>Debes introducir una breve descripción de los servicios que ofreces para que los clientes determinen el alcance de tus servicios.</p>
                  <div class="border"></div>
              </div>
          </div>
      </div>
  </section>
</div>
@include('components.users.common-forms.save-button.modal')
@endsection

@section('scripts')
  <!--<script src="{{ asset('js/service-forms/second-step-four.js') }}" charset="utf-8"></script>-->
@endsection