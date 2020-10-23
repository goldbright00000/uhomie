@extends('layouts.flujo-base')

@section('content')
<div class="container" id="phone-verify">
    <section class="section main-section">
        <div class="columns">
            <div class="column is-7">
                <span class="bold">Hola {{ $user->firstname }} {{ $user->lastname }},</span>
                <span>Hola antes de empezar necesitamos</span>
                <span>validar tu celular, se le</span>
                <span>enviará un código de</span>
                <span>validación.</span>
                <div class="form" id="app">
                    <form action="{{ route('users.phone-verification') }}" method="POST" id="register_user" autocomplete="off">
                        {{ csrf_field() }}
                        <vue-tel-input :disabled-formatting="true" @input="handle"   autofocus :max-len="largo_chile"  name="phone" placeholder="9XXXXXXXX" :required="true" v-model="phone"></vue-tel-input>
                        <p v-bind:style="{'display': mensajeCorto}" class="help">Debes ingresar 9 digitos</p>
                        <p v-bind:style="{'display': mensajeUnicidad}" class="help">Este numero ya fue registrado</p>
                        <br>
                        <!--<input type="tel" autocomplete="off" name="phone" id="phone" value="{{$user->phone}}">-->
                        <input type="hidden" name="code" v-model="code_phone">
                        <button type="submit" :disabled="deshabilitar" class="button is-outlined is-primary" form="register_user">Enviar</button>
                    </form>
                    
                </div>
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
@endsection

@section('scripts')
<script src="{{ asset('js/phone-verify.js') }}"></script>
@endsection
