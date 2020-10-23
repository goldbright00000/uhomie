@extends('layouts.app')

@section('header')
<section class="hero is-fullheight" id="referalls-header">
  <div class="hero-head is-light">
    @parent
  </div>
  <div class="hero-body">
    <div class="container">
      <div class="columns is-tablet">
        <div class="column is-6 main-title-wrapper">
          <h1 class="title has-bottom-line">
            GANA DINERO REFIRIENDO<br/>
            PROPIEDADES EN ALQUILER
          </h1>
          <p>
            ¿Conoces a alguien que tenga una propiedad para alquilar? Recomiéndalo! Obtienes $ 10.000 cuando se publica la propiedad, y un 3% de la comisión de arriendo.
          </p>
          <a href="{{ route('referalls.register' )}}" class="button is-outlined is-black link-register">QUIERO REFERIR A UNA PROPIEDAD</a>
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
<main id="referrals">
  <section id="easy" class="container is-fluid is-primary">
    <div class="container">
      <h1 class="title has-text-white has-text-weight-light has-bottom-line">
        MIRA LO FÁCIL QUE ES GANAER <br/>
        DINERO REFIRIENDO PROPIEDADES
      </h1>
      <div class="columns">
        <div class="column is-four">
          <img src="{{ asset('images/referalls/img-1.png') }}" />
          <p>
            Refiere una propiedad de su cliente, un amigo o vecino del mismo edificio.    
          </p>
        </div>
        <div class="column is-four">
          <img src="{{ asset('images/referalls/img-gana.png') }}" />
          <p>
            Cuando se anuncia la propiedad, ¡gana $10.000 por la publicación!
          </p>
        </div>
        <div class="column is-four">
          <img src="{{ asset('images/referalls/img-comision.png') }}" />
          <p>
            ¡Cuanod se alquila, se obtiene un 3% más de la comisión de arriendo!
          </p>
        </div>
      </div>
    </div>
  </section>

  <section id="two-forms" class="container is-fluid">
    <div class="container">
      <h1 class="title has-text-weight-light is-uppercase has-bottom-line">
        hay dos formas<br/>
        de referir propiedades
      </h1>
      <div class="columns">
        <div class="column">
          <img src="{{ asset('images/referalls/img-login.png') }}" />
          <h4 class="is-size-6 has-text-weight-bold">
            Registrando la propiedad
          </h4>
          <p>Usted registra la dirección de la
propiedad y el contacto del propietario.
Puede referir la propiedad registrando
la dirección de la propiedad , junto con
el nombre, teléfono y correo
electrónico del propietario y nuestro
equipo se comunicará con usted.
          </p>
        </div>
        <div class="column">
          <img src="{{ asset('images/referalls/img-click.png') }}" />
          <h4 class="is-size-6 has-text-weight-bold">
            Eviando tu enlace
          </h4>
          <p>
            Envía tu enlace único al propietario y
ellos se registran
También puede enviar su enlace único
al propietario. Incluso registra la
propiedad y todavía recibe un
descuento.
          </p>
        </div>
      </div>
    </div>
  </section>

  <section class="hero" id="fifth-title" >
    <div class="hero-body">
      <div class="container has-text-centered">
        <h1 class="title">
          Te hacemos la vida más fácil
        </h1>
      </div>
    </div>
  </section>


</main>

@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('css/referalls.css') }}">
@endsection

@section('scripts')
<script src="{{ asset('js/referalls.js') }}"></script>
@endsection
