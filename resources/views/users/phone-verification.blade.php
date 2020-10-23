@extends('layouts.flujo-base')

@section('content')
<div class="container" id="phone-verification">
    <section class="section main-section">
        <div class="columns">
            <div class="column is-7">
                <span class="bold">Hola {{ $user->firstname }} {{ $user->lastname }},</span>
                <span>ingrese el código de</span>
                <span>validación enviado a su</span>
                <span>móvil para empezar.</span>
                <div class="form">
                    <form action="{{ route('users.check-phone-verification') }}" method="POST" id="form-phone-verification" autocomplete="off">
                        {{ csrf_field() }}
                        <div id="token-code"></div>
                        <p class="timer-text">Escriba el código (<span id="timer-count">60</span> s)</p>
                        <button type="button" class="button is-primary is-outlined" id="btn-retry" disabled>Reenviar</button>
                        <input type="hidden" name="phone" value="{{ $phone }}">
                        <input type="hidden" name="code" value="{{ $code }}">
                        <input type="hidden" name="token">
                    </form>
                    <input type="hidden" id="url-retry-sms" value="{{ route('users.phone-verification') }}">
                </div>
                <input type="submit" value="Empezar" form="form-phone-verification" class="button is-outlined is-primary" id="btn-send-form">
                <a href="{{ route('users.phone-verify') }}" class="button is-outlined is-primary" id="btn-change-phone" disabled>Cambiar número</a>
            </div>
            <div class="column side-info">
                <div class="info">
                    <img src="{{ asset('images/foco.png') }}">
                    <p>Recuerda llenar con información actualizada.</p>
                    <div class="border"></div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal modal-info">
    <div class="modal-background"></div>
    <div class="modal-content">
      <div class="info-card">
            <img id="clap" src="{{ asset('images/clap.png') }}">
          <span id="modal-title"></span>
          <p id="modal-text"></p>
      </div>
      <a class="button is-primary is-inverted is-outlined" id="btn-next-page"></a>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/phone-verification.js') }}"></script>
@endsection
