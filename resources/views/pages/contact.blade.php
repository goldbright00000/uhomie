@extends('layouts.app')

@section('header')
<section class="hero is-fullheight" id="landing-header">
    <div class="hero-head">
        @parent
    </div>
    <div class="hero-body">
        <div class="container main-title-container">
            <div class="columns is-mobile is-centered">
                <div class="column is-12 has-text-centered title-container">
                    <img src="{{ asset('images/contactanos.svg') }}" class="title-image">
                    <p class="description">¡Te hacemos la vida más fácil</p>
                </div>
            </div>
        </div>
    </div>
    <div class="hero-foot has-text-centered">
        <i class="fa fa-angle-down" id="arrow-down"></i>
    </div>
</section>
@endsection

@section('content')
<main id="contact" v-cloak>
    <div class="form-container second-section">
        <form action="#" @submit.prevent="submitForm()" ref="form" >
            <div class="control">
                <input v-model="form.name" type="text" class="input" name="name" required placeholder="Nombres y Apellidos">
                <div class="error-container" v-if="errors.name && errors.name.length > 0">
                    <span class="error">@{{ errors.name[0] }}</span>
                </div>
            </div>
            <div class="control">
                <input v-model="form.phone" type="tel" class="input" name="phone" required placeholder="Teléfono">
                <div class="error-container" v-if="errors.phone && errors.phone.length > 0">
                    <span class="error">@{{ errors.phone[0] }}</span>
                </div>
            </div>
            <div class="control">
                <input v-model="form.email" type="email" class="input"  name="email" required placeholder="Email">
                <div class="error-container" v-if="errors.email && errors.email.length > 0">
                    <span class="eemail">@{{ errors.name[0] }}</span>
                </div>
            </div>
            <div class="control columns is-gapless is-multiline">
                <div class="column is-narrow is-flex is-vcentered">
                    <label for="reason_contact">Quiero</label>
                </div>
                <div class="column">
                    <div class="select">
                        <select v-model="form.reason_contact" name="reason_contact" id="reason_contact" required>
                            <option value hidden selected>Seleccione una opción</option>
                            <option value="0">Publicar propiedad</option>
                            <option value="1">Conocer más de uHomie</option>
                            <option value="2">Arrendar una propiedad</option>
                            <option value="3">Contactar con servicio al cliente</option>
                        </select>
                    </div>
                </div>
                <div class="column is-12">
                    <div class="error-container" v-if="errors.reason_contact && errors.reason_contact.length > 0">
                        <span class="error">@{{ errors.reason_contact[0] }}</span>
                    </div>
                </div>
            </div>

            <div class="control">
                <textarea v-model="form.message" name="message" id="message"  class="textarea" rows="7" placeholder="Déjanos tu mensaje"></textarea>
                <div class="error-container" v-if="errors.message && errors.message.length > 0">
                    <span class="error">@{{ errors.message[0] }}</span>
                </div>
            </div>

            <div class="control captcha-container" ref="recaptcha-container">
                <vue-recaptcha
                    ref="recaptcha"
                    sitekey="{{ env('GOOGLE_RECAPTCHA_KEY', "6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI") }}"
                    @verify="onReCaptchaVerify"
                    @expired="onReCaptchaExpired"
                ></vue-recaptcha>
                <div class="error-container" v-if="errors.recaptcha && errors.recaptcha.length > 0">
                    <span class="error">@{{ errors.recaptcha[0] }}</span>
                </div>
            </div>

            <div class="control has-text-centered">
                <button type="submit" :class="'button is-primary is-outlined' + ( state == STATE.SENDING ? ' is-loading' : '')">Enviar</button>
            </div>

        </form>
    </div>
    <div class="modal success-modal" ref="modal">
        <div class="modal-content">
            <img src="{{ asset('images/clap.png') }}" alt="hand-clapping">
            <div class="message">
                <div class="title">Mensaje enviado!</div>
                En breve estaremos respondiendo tu consulta. Gracias!
            </div>

            <button class="button is-inverted is-outlined" aria-label="close">Cerrar</button>
        </div>
    </div>
</main>

@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('css/contact.css') }}">
@endsection

@section('scripts')
<script src="{{ asset('js/contact.js') }}"></script>
@endsection
