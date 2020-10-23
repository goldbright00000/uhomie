@php
  $cities = [
    'Las Condes',
    'Providencia',
    'Santiago Centro',
    'Vitacura',
    'Lo Barnechea',
    'Viña del Mar',
    'Valparaíso',
    'La Florida'
  ]
@endphp
@extends('layouts.app')

@section('header')
<div class="navbar-wrapper">
  @include('layouts.header', ['isSolid' => false])
</div>
<section class="hero is-fullheight" id="landing-header">
  <div class="hero-body" id="startchange">
    <div class="container main-title-container">
      <div class="columns is-desktop is-centered">
        <div class="column is-8">
          <h1 class="title">Descubre</h1>
          <h1 class="title">tu próximo hogar</h1>
          <form id="searchBar" action="@route('explore')" method="get" autocomplete="off">
            <div class="field has-addons">
              <div class="control is-expanded searchInput">
                <input id="autocomplete-landing" class="input typeaheaddd" type="text" name="raw" placeholder="Prueba: {{ $cities[random_int(0, 7)] }}" required>
                <input id="autocomplete-city" type="hidden" name="city" class="search-city-id">
                <input id="autocomplete-commune" type="hidden" name="commune" class="search-commune-id">
              </div>
              <div class="control">
                <a href="javascript:{}" onclick="$('#searchBar').submit();" class="button is-info search">    
                  <img src="{{ asset('images/icons/lupa.png') }}" />
                </a>
              </div>
            </div>
          </form>
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
<section id="home-init">
  <div class="background"></div>
  <div class="background-grey">
    <div class="container">
      <div class="tabs is-medium is-centered">
        <ul>
          <li class="is-active" tab="tab-1"><a>Quiero encontrar propiedades</a></li>
          <li tab="tab-2"><a>Quiero publicar mi propiedad</a></li>
        </ul>
      </div>

      <!-- Quiero encontrar propiedades -->
      <div id="tab-1" class="tab-container is-active faster">
        <div class="columns">
          <div class="column">
            <img src="{{ asset('images/home/arrendatario-1.png') }}" alt="Arrenda sin Pagar" />
            <h4>Arrienda sin Pagar comisiones y sin Fiador / Aval</h4>
            <p>
              Con uhomie tu nunca pagarias las altas comisiones que típicamente pagarias con un corredor e inmobiliaria y no necesitas fiador o aval. Transformamos la experiencia de arrendar.
            </p>
          </div>
          <div class="column">
            <img src="{{ asset('images/home/arrendatario-2.png') }}" alt="Arrenda sin Pagar" />
            <h4>Alquila Rápido y 100% Online</h4>
            <p>
              Con uhomie transformamos el proceso de arriendos haciéndolo 100% digital, Fácil, simple y seguro incluido el contrato de arriendo.
            </p>
          </div>
          <div class="column">
            <img src="{{ asset('images/home/arrendatario-3.png') }}" alt="Arrenda sin Pagar" />
            <h4>Agenda digital y paga online sin intermediarios</h4>
            <p>
              Agenda directamente con el dueño las propiedades que desees visitar, sin limitaciones de forma simple y rápida. Paga Online tu mes de adelanto con cualquier medio de pago via Transbank.
            </p>
          </div>
        </div>
        <p class="has-text-centered">
          <a href="{{ route('explore') }}" class="button is-outlined is-primary">
            EXPLORAR PROPIEDADES
          </a>
        </p>
      </div>

      <!-- Quiero pueblicar mi propiedad -->
      <div id="tab-2" class="tab-container faster">
        <div class="columns">
          <div class="column">
            <img src="{{ asset('images/home/arrendatario-1.png') }}" alt="Arrenda sin Pagar" />
            <h4>Publicar gratis</h4>
            <p>
              Publica todas las propiedades que desees sin restricciones o cobros ocultos. Anuncia tu aviso de alquiler en menos de 5 minutos y exponlo a miles de arrendatarios que nos visitan.
            </p>
          </div>
          <div class="column">
            <img src="{{ asset('images/home/arrendadores-2.png') }}" alt="Arrenda sin Pagar" />
            <h4>Alquila tu casa o departamento en tiempo record</h4>
            <p>
              Con nuestro poderoso algoritmo de inteligencia artificial y tecnología de vanguardia logramos exponer tu propiedad y nos encargamos de seleccionar entre miles de arrendatarios al candidato ideal.
            </p>
          </div>
          <div class="column">
            <img src="{{ asset('images/home/arrendadores-3.png') }}" alt="Arrenda sin Pagar" />
            <h4>Alquila con seguridad al arrendatario ideal</h4>
            <p>
              Con uHomie nos encargamos de todo el proceso por ti. Alquila tu propiedad al arrendatario ideal con mejor perfil de pago, riesgo y estabilidad laboral y obtén la garantía contra todo evento ( meses de alquiler y daños) sobre tu propiedad al arrendar y publicar exclusivamente  en uhomie.
            </p>
          </div>
        </div>
        <p class="has-text-centered">
          <a href="{{ route('publish') }}" class="button is-outlined is-primary">
            PUBLICAR MI PROPIEDAD
          </a>
        </p>
      </div>
    </div>
  </div>
</section>
<section class="section second-section" id="about">
  <div class="columns is-gapless">
    <div class="column is-6">
    </div>
    <div class="column is-6">
      <h1 class="title">Qué es <span>uHomie<span></h1>
      <h2 class="subtitle">Encuentra y postula de forma rápida, <br>transparente y segura a tu próxima propiedad</h2>
      <h4 style="margin: 1rem 0;">Arriendo de casas, alquiler vacacional, arriendo de departamentos, todo en un solo lugar.</h4>
      <div>
        <article class="media">
          <figure class="media-left">
            <p class="image is-32x32">
              <img src="{{ asset('images/icons/iconocomision.png') }}">
            </p>
          </figure>
          <div class="media-content">
            <p>Paga cero comisión <br>para arrendar.</p>
          </div>
        </article>
        <article class="media">
          <figure class="media-left">
            <p class="image is-32x32">
              <img src="{{ asset('images/icons/icono_seguridad.png') }}">
            </p>
          </figure>
          <div class="media-content">
            <p>Control de los datos. Solo tú controlas quién <br>ve tu información al aplicar a las propiedades</p>
          </div>
        </article>
        <article class="media">
          <figure class="media-left">
            <p class="image is-32x32">
              <img src="{{ asset('images/icons/icono_scoring.png') }}">
            </p>
          </figure>
          <div class="media-content">
            <p>Encuentra las propiedades acorde <br>a tus necesidades y tu perfil.</p>
          </div>
        </article>
        <article class="media">
          <figure class="media-left">
            <p class="image is-32x32">
              <img src="{{ asset('images/icons/icono_reloj.png') }}">
            </p>
          </figure>
          <div class="media-content">
            <p>Arrienda en el menor <br>tiempo sin intermediarios.</p>
          </div>
        </article>
      </div>
      <a href="{{ route('explore') }}" class="button is-outlined is-primary">ver más</a>
    </div>
  </div>
</section>

<section class="hero main-title" id="second-title">
  <div class="hero-body">
    <div class="container">
      <h1 class="title lead">Descubre propiedades</h1>
      <h1 class="title">acorde a tus necesidades</h1>
    </div>
  </div>
</section>

<section class="section" id="options-gallery" >
  <div class="columns is-centered is-multiline">
    <div class="column is-4 has-text-centered">
      <div class="wrapper-option">
        <a href="{{ route('explore', ['perfil' => 'soltero']) }}">
          <div class="img">
            <img src="{{ asset('images/soltero.jpg') }}">
          </div>
          <div class="text">
            <h1 class="title">Soltero</h1>
            <p>Encuentra un alojamiento acorde a ti. <br>Ponte cómodo y disfruta tu espacio.</p>
          </div>
        </a>
      </div>
    </div>
    <div class="column is-4 has-text-centered">
      <div class="wrapper-option">
        <a href="{{ route('explore', ['perfil' => 'estudiante']) }}">
          <div class="img">
            <img src="{{ asset('images/estudiante.jpg') }}">
          </div>
          <div class="text">
            <h1 class="title">Estudiantes</h1>
            <p>Encuentra un alojamiento acorde a ti. <br>Ponte cómodo y disfruta tu espacio.</p>
          </div>
        </a>
      </div>
    </div>
    <div class="column is-4 has-text-centered">
      <div class="wrapper-option">
        <a href="{{ route('explore', ['perfil' => 'hijos']) }}">
          <div class="img">
            <img src="{{ asset('images/flia_hijos.jpg') }}">
          </div>
          <div class="text">
            <h1 class="title">Flia con hijos</h1>
            <p>Encuentra un alojamiento acorde a ti. <br>Ponte cómodo y disfruta tu espacio.</p>
          </div>
        </a>
      </div>
    </div>
    <div class="column is-4 has-text-centered">
      <div class="wrapper-option">
        <a href="{{ route('explore', ['perfil' => 'sinhijos']) }}">
          <div class="img">
            <img src="{{ asset('images/flia_sin_hijos.jpg') }}">
          </div>
          <div class="text">
            <h1 class="title">Flia sin hijos</h1>
            <p>Encuentra un alojamiento acorde a ti. <br>Ponte cómodo y disfruta tu espacio.</p>
          </div>
        </a>
      </div>
    </div>
    <div class="column is-4 has-text-centered">
      <div class="wrapper-option">
        <a href="{{ route('explore', ['perfil' => 'mascota']) }}">
          <div class="img">
            <img src="{{ asset('images/flia_mascotas.jpg') }}">
          </div>
          <div class="text">
            <h1 class="title">Flia con mascota</h1>
            <p>Encuentra un alojamiento acorde a ti. <br>Ponte cómodo y disfruta tu espacio.</p>
          </div>
        </a>
      </div>
    </div>
    <div class="column is-4 has-text-centered">
      <div class="wrapper-option">
        <a href="{{ route('explore', ['perfil' => 'grupo']) }}">
          <div class="img">
            <img src="{{ asset('images/grupo_grande.jpg') }}">
          </div>
          <div class="text">
            <h1 class="title">Grupos (+5)</h1>
            <p>Encuentra un alojamiento acorde a ti. <br>Ponte cómodo y disfruta tu espacio.</p>
          </div>
        </a>
      </div>
    </div>
    <div class="column is-4 has-text-centered">
      <div class="wrapper-option">
        <a href="{{ route('explore', ['perfil' => 'ejecutivos']) }}">
          <div class="img">
            <img src="{{ asset('images/ejecutivo.jpg') }}">
          </div>
          <div class="text">
            <h1 class="title">Ejecutivos</h1>
            <p>Encuentra un alojamiento acorde a ti. <br>Ponte cómodo y disfruta tu espacio.</p>
          </div>
        </a>
      </div>
    </div>
  </div>
</section>

<section class="hero main-title" id="third-title" >
  <div class="hero-body">
    <div class="container">
      <h1 class="title">¡Arrendar de forma</h1>
      <h1 class="title">simple con uhomie!</h1>
    </div>
  </div>
</section>

<img src="{{ asset('images/grafico.png') }}" class="grafico image">

<section class="hero" id="features-titles" >
  <div class="hero-body">
    <div class="container">
      <nav class="level">
        <p class="level-item">Conveniente</p>
        <p class="level-item">Práctico</p>
        <p class="level-item">
          <img src="{{ asset('images/icons/logo_grande.png') }}">
        </p>
        <p class="level-item">Transparente</p>
        <p class="level-item">Accesible</p>
      </nav>
    </div>
  </div>
</section>

<section class="hero main-title" id="fourth-title" >
  <div class="hero-body">
    <div class="container">
      <h1 class="title">Las comunas</h1>
      <h1 class="title">más arrendadas</h1>
    </div>
  </div>
</section>

<section class="section" >
  <div class="container">
    <div class="tile is-ancestor">
      <div class="tile is-8 is-vertical is-parent">
        <div class="tile is-parent">
          <div class="tile is-4 is-child has-text-centered text">
            <p>¡Descubre todo lo que te ofrece tu próxima ciudad!</p>
          </div>
          <figure class="tile is-8 is-child image">
            <a href="{{ route('explore', ['city' => '25', 'commune' => '109']) }}">
            <div class="img">
              <img src="{{ asset('images/lascondes.jpg') }}">
            </div>
            <div class="text">
              <h1 class="title">Las Condes</h1>
            </div>
            </a>
          </figure>
        </div>
        <div class="tile is-parent">
          <figure class="tile is-child image">
            <a href="{{ route('explore', ['city' => '25', 'commune' => '127']) }}">
            <div class="img">
              <img src="{{ asset('images/santiago.jpg') }}">
            </div>
            <div class="text">
              <h1 class="title">Santiago Centro</h1>
            </div>
            </a>
          </figure>
        </div>
        <div class="tile is-parent">
          <figure class="tile is-4 is-child image is-hidden-mobile">
            <a href="{{ route('explore', ['city' => '25', 'commune' => '118']) }}">
            <div class="img">
              <img src="{{ asset('images/providencia.jpg') }}">
            </div>
            <div class="text">
              <h1 class="title">Providencia</h1>
            </div>
            </a>
          </figure>
          <div class="tile is-8 is-vertical is-parent">
            <figure class="tile is-child image">
              <a href="{{ route('explore', ['city' => '25', 'commune' => '128']) }}">
              <div class="img">
                <img src="{{ asset('images/vitacura.jpg') }}">
              </div>
              <div class="text">
                <h1 class="title">Vitacura</h1>
              </div>
              </a>
            </figure>
            <div class="tile is-child has-text-centered text-logo">
              <p>Vive la experiencia</p>
              <img src="{{ asset('images/icons/logo_completo.png') }}">
            </div>
          </div>
        </div>
      </div>
      <div class="tile is-vertical is-parent">
        <div class="tile is-child has-text-centered text">
          <p>Encuentra la propiedad a la medida de tus exigencias sin comisiones</p>
        </div>
        <figure class="tile is-child image is-hidden-mobile">
          <a href="{{ route('explore', ['city' => '25', 'commune' => '110']) }}">
          <div class="img">
            <img src="{{ asset('images/lobernechea.jpg') }}">
          </div>
          <div class="text">
            <h1 class="title">Lo Barnechea</h1>
          </div>
          </a>
        </figure>
        <figure class="tile is-child image">
          <a href="{{ route('explore', ['city' => '20', 'commune' => '81']) }}">
          <div class="img">
            <img src="{{ asset('images/vinadelmar.jpg') }}">
          </div>
          <div class="text">
            <h1 class="title">Viña del Mar</h1>
          </div>
          </a>
        </figure>
      </div>
      <div class="tile is-vertical is-parent">
        <figure class="tile is-child image">
          <a href="{{ route('explore', ['city' => '20', 'commune' => '79']) }}">
          <div class="img">
            <img src="{{ asset('images/valparaiso.jpg') }}">
          </div>
          <div class="text">
            <h1 class="title">Valparaíso</h1>
          </div>
          </a>
        </figure>
        <figure class="tile is-child image is-hidden-mobile">
          <a href="{{ route('explore', ['city' => '25', 'commune' => '106']) }}">
          <div class="img">
            <img src="{{ asset('images/laflorida.jpg') }}">
          </div>
          <div class="text">
            <h1 class="title">La Florida</h1>
          </div>
          </a>
        </figure>
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

<section class="hero main-title" id="sixth-title">
  <div class="hero-body">
    <div class="container">
      <div>
        <h1 class="title">Nuestras</h1>
        <h1 class="title">membresías uhomie</h1>
      </div>
      <p>Tenemos tres distintos planes de membresía adaptada a <br>cada necesidad. Descubre la más conveniente.</p>
    </div>
  </div>
</section>

<section class="hero" id="membresias">
  <div class="hero-body">
    <div class="container has-text-centered">
      <a href="{{ route('memberships') }}" id="option-basic-link"></a>
      <a href="{{ route('memberships') }}" id="option-select-link"></a>
      <a href="{{ route('memberships') }}" id="option-premium-link"></a>
      <a href="{{ route('memberships') }}">
        <img src="{{ asset('images/opciones_membresia.png') }}">
      </a>
      <a class="button is-outlined is-primary" href="{{ route('memberships') }}">ver más</a>
    </div>
  </div>
</section>

<section class="section" id="calendar-section">
  <section class="hero main-title" id="seventh-title">
    <div class="hero-body">
      <div class="container">
        <h1 class="title">Encontrar tu casa ideal</h1>
        <h1 class="title">desde cualquier lugar</h1>
      </div>
    </div>
  </section>
  <section class="section" id="newsletter">
    <div class="container">
      <div class="columns is-centered">
        <div class="column is-8">
          <h1 class="title">A dónde</h1>
          <h1 class="title">te quieres mudar?</h1>
          <p>En UHOMIE nos encanta trabajar para ti y hacerte la <br>vida fácil en la búsqueda de tu próxima propiedad.</p>
          <div class="city-list-wrapper">
            <div class="city-list columns is-desktop">
              <a href="#" class="city-title column" city="Las Condes">Las Condes</a>
              <a href="#" class="city-title column" city="Providencia">Providencia</a>
              <a href="#" class="city-title column" city="Santiago Centro">Santiago Centro</a>
              <a href="#" class="city-title column" city="Vitacura">Vitacura</a>
            </div>
            <div class="city-list columns is-desktop">
              <a href="#" class="city-title column" city="Lo Barnechea">Lo Barnechea</a>
              <a href="#" class="city-title column" city="Viña del Mar">Viña del Mar</a>
              <a href="#" class="city-title column" city="Valparaíso">Valparaíso</a>
              <a href="#" class="city-title column" city="La Florida">La Florida</a>
            </div>
          </div>
          <nav class="level">
            <div class="level-left">
              <p>Por favor indícanos: <br>La fecha que te quieres mudar <br>Presupuesto mensual arriendo <br>Tu número celular <br>Tu e-mail</p>
            </div>
          </nav>
        </div>
      </div>
    </div>
  </section>
</section>

<div class="modal modal-access" id="city-modal">
  <div class="modal-background"></div>
  <div class="modal-content">
    <div class="toolbar">
      <div class="container">
        <img src="{{ asset('images/icons/logo_completo.png') }}">
        <button class="button is-outlined is-primary btn-close-modal">Cerrar</button>
      </div>
    </div>
    <div class="container">
      <div class="columns is-multiline">
        <div class="column is-12">
          <img class="top-logo" src="{{ asset('images/icons/logo_grande.png') }}">
          <h1 class="title top-title" id="modal-title">Bienvenido a uHomie</h1>
        </div>
        <div class="column is-6 is-offset-3 form">
          <form action="#" method="POST" id="form-city">
            {{ csrf_field() }}
            <input class="input" type="hidden" autocomplete="off" name="step" value="1">
            <input class="input" type="hidden" autocomplete="off" name="city" >
            <div class="column is-6 is-offset-3" id="caja1" style="margin-bottom: 19rem;" >
              <!-- <input class="input" type="hidden" autocomplete="off" id="furnished_date" name="furnished_date_landing" > -->
              <input class="input" type="date" style="display:none;" autocomplete="off" id="furnished_date" name="furnished_date_landing" >
            </div>
            <div class="column is-6 is-offset-3" id="caja2" style="display:none" >
              <input class="input number-format" type="text" autocomplete="off" id="bathrooms" name="bathrooms" placeholder="Cantidad de baños" required>
              <input class="input number-format" type="text" autocomplete="off" id="bedrooms" name="bedrooms" placeholder="Cantidad de habitaciones" required >
              <input class="input money" type="text" autocomplete="off" id="price" name="price" placeholder="Precio de arriendo" required>
            </div>
            <div class="column is-6 is-offset-3" id="caja3" style="display:none" >
              <input class="input" type="text" autocomplete="off" id="firstname"  name="firstname" placeholder="Nombre" required>
              <input class="input" type="text" autocomplete="off" id="lastname"  name="lastname" placeholder="Apellido" required>
              <input class="input" type="text" autocomplete="off" id="other_email" name="other_email" placeholder="Email" required>
              <input class="input number-format" type="text" autocomplete="off" id="cell_phone" name="cell_phone" placeholder="Nro. Telefonico" required>
            </div>
            <div class="column is-6 is-offset-3" id="caja4" style="display:none" >
              <span>Gracias por suscribirte, te notificarémos via Mail/SMS</span>
            </div>
            <div class="" id="cajaFooter" style="display:none" >
              <input type="button" name="back-step" value="Atras" class="button is-outlined is-primary btn-access">
              <input type="button" name="change-step" value="Siguiente" class="button is-outlined is-primary btn-access">
            </div>
            <div class="" id="cajaFooterTwo" style="display:none" >
              <button type="button" class="button is-outlined is-primary btn-close-modal">OK</button>
            </div>
          </form>
        </div>
        <div class="column is-6 is-offset-3">
          <p class="register-option">¿No tiene una cuenta en UHOMIE? <a href="#" class="btn-register-two has-text-primary">Regístrese</a></p>

        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script src="{{ asset('js/landing.js') }}"></script>
<script src="{{ asset('js/newsletter.js') }}"></script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDTKRiKb5oaS7Z13QezK4K0V9XQI99UHiI&libraries=places&callback=initSearch"
        async defer></script>

<script>
  var autocomplete;

  function initSearch() {
    var options = {
      //types: ['(cities)'],
      componentRestrictions: { country: 'cl' }
    };

    var input = document.getElementById('autocomplete-landing');
    
    autocomplete = new google.maps.places.Autocomplete(input, options);
    autocomplete.setFields(['address_component']);
    autocomplete.addListener('place_changed', fillInAddress);

    google.maps.event.addDomListener(input, 'keydown', function(event) { 
      if (event.keyCode === 13) { 
          event.preventDefault(); 
      }
    });
  }

  function fillInAddress() {
    var place = autocomplete.getPlace() 

    console.log(place)

    for (var i = 0; i < place.address_components.length; i++) {
      var addressType = place.address_components[i].types[0];

      if(addressType == 'colloquial_area' || addressType == 'locality') {     
        document.getElementById('autocomplete-commune').value = place.address_components[i]['short_name'];
      }
      if(addressType == 'administrative_area_level_2') {
        document.getElementById('autocomplete-city').value = place.address_components[i]['short_name'];
      }

      document.getElementById('searchBar').submit()

    }
  }
</script>

@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('css/landing.css') }}">
@endsection
