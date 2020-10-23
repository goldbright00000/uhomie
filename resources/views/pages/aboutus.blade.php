@extends('layouts.app')

@section('header')
<div class="navbar-wrapper">
    @include('layouts.header', ['isSolid' => false])
</div>
<section class="hero is-fullheight" id="aboutus-header">
    <div class="hero-head">
        @parent
    </div>
    <div class="hero-body">
        <div class="container">
            <div class="columns is-tablet is-centered">
                <div class="main-title-wrapper">
                  	<img src="{{ asset('images/somos-uhomie.png') }}" width="300px" />
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
<section class="section" id="process-section">
    <div class="container">
      <div class="process-step has-text-centered">
          <div>
              <h1 class="title">1</h1>
              <h2 class="subtitle">
              	Somos Inconformistas
              </h2>
              <p>
              	Para qué pagar de más durante el proceso de arriendos, en Chile los Arrendadores y Arrendatarios pagan hasta
100% del valor de 1 mes por gestionar el alquiler de una propiedad. Nosotros enfocamos muestra experiencia,
conocimientos y esfuerzos con Diseño de experiencia UX y Tecnología Digital podemos resolver este problema
logrando una disminución del 100% de comisión para el arrendatario y hasta un 75% para el arrendador
              </p>
              <p>
              	Durante el 2018 Unimos nuestras experiencias y habilidades en UX, Machine Learning, Big Data, Cloud para
redefinir la reglas y modelo de negocios del alquiler de propiedades logrando una experiencia digital 100%,
simple, Segura y transparente.
              </p>
              <p>
              	Nuestro Objetivo es eliminar los sobre costos actuales del proceso de arriendos. Si estas cansado de pagar de
más por arrendar Unete a UHOMIE. Queremos hacerte la vida fácil
              </p>
          </div>
      </div>
      <div style="max-width: 640px; margin: 50px auto;">
          <img src="{{ asset('images/aboutus/img-vertical.jpg') }}" width="100%">
      </div>

      <div class="process-step has-text-centered">
          <div>
              <h1 class="title">2</h1>
              <h2 class="subtitle">
              	Somos Diferentes,<br/>
              	Sociables y Empáticos
              </h2>
              <p>
              	Cada miembro de nuestro equipo ha vivido los problemas del actual modelo y proceso de arriendos, por eso
trabajamos para diseñar una plataforma de experiencias que te entrega un valor tangible enfocado.
              </p>
              <p>
              	Estamos en contra de todo lo que afecte nuestro proceso digital por eso logramos ahorros significativos que se
ven reflejados en lo que pagarías en UHOMIE al gestiónar tu proceso de arriendos
              </p>
              <p>
              	Para nosotros es relevante como Startup tener siempre un feedback directo y constante de nuestros clientes
Arrendatarios, Arrendadores y Aval de forma que nuestra plataforma esta constantemente siendo mejorada para
darte un valor tangible y relevante.
              </p>
          </div>
      </div>      

      <div class="process-step has-text-centered">
          <div>
              <h1 class="title">3</h1>
              <h2 class="subtitle">
              	Estamos siempre para Tí
              </h2>
              <p>
              	Nuestra plataforma esta siempre disponible 24/7 para ser usada por ti, cuando quieras a la hora que quieras,
desde donde estes y desde cualquier dispositivo. Eliminamos la barreras físicas, burocracia, manualidades del
actual proceso de arriendos. Queremos hacer la vida fácil de nuestros usuarios. 
              </p>
              <p class="has-text-centered">
              	1. Nos apoyamos en Partners tecnológicos para Digitales la experiencia
              </p>
          </div>
      </div>
      <div style="max-width: 600px; margin: 0 auto;">
          <img src="{{ asset('images/aboutus/logos-partners.jpg') }}" width="100%">
      </div>
      <p class="has-text-centered">2. Simplicidad</p>
     	<p class="has-text-centered">3. Feedback Inmediato</p>
     	<p class="has-text-centered">4. Control y Transparencia</p>
     	<p class="has-text-centered">5. Digitales 100%.</p>
     	<p class="has-text-centered" style="margin-bottom: 4rem;">6. Proceso digital robusto y seguro </p>
  </div>
</section>

@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('css/aboutus.css') }}">
@endsection
@section('scripts')
<script src="{{ asset('js/landing.js') }}"></script>
@endsection
