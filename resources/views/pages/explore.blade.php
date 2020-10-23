@extends('layouts.app')

@section('header')
@if (request()->has('perfil'))
<section class="hero is-fullheight {{ request()->get('perfil') }}" id="explore-header">
  <div class="hero-head">    
    @include('layouts.header', ['isSolid' => false])
    }
  </div>
  <div class="hero-body">
    <div class="container">
      <div class="columns is-mobile is-centered">
        <div class="column is-6 main-title-wrapper">
          <h1 class="title">Propiedades para</h1>
          <img class="img-title" src="{{ asset('images/explore/'.request()->get('perfil').'.png') }}" alt="">
        </div>
      </div>
    </div>
  </div>
  <div class="hero-foot has-text-centered">
    <i class="fa fa-angle-down" id="arrow-down"></i>
  </div>
</section>
@else
@include('layouts.header', ['isSolid' => true])
@endif
@endsection

@section('content')
  
<div class="container-fluid second-section" id="explore">
  <div class="modal " id="modalSelectDays">
      <div class="modal-background"></div>
      <div class="modal-card">
      <header class="modal-card-head">
          <p class="modal-card-title">Seleccione los dias que desea reservar</p>
          <button class="delete botonCerrarModalSelectDays" @click="cerrarModalSelectDays" aria-label="close" ></button>
      </header>
      <section class="modal-card-body">
          <v-date-picker
              v-model="fechas" 
              :min-date='new Date()'
              :mode="mode"
              is-inline
              is-expanded
              color="green"
              :attributes="attributesC"
              :dayclick="alterScheduleDate()"
              :disabled-dates="fechas_ocupadas_computado"
              >
          </v-date-picker><br>
          <span v-if="fechas.length > 0">Dias seleccionados: <span v-html="fechas.length"> @{{fechas.length}} </span></span>
      </section>
      <footer class="modal-card-foot">
          <button class="button is-success" ref="btn_enviar_postulacion" :disabled="fechas.length == 0" @click="postularArriendoCorto">Enviar</button>
          <button class="button botonCerrarModalSelectDays" @click="cerrarModalSelectDays">Cancelar</button>
      </footer>
      </div>
  </div>
  <div class="columns is-gapless">
    <!-- FILTERS: -->
    <div
      v-if="filtersOn || !activeMap"
      :class="{'column': !activeMap, 'map-filter': (filtersOn && activeMap) }"
      >
      <div class="filter-list">
        <ul>          
          <li>
            <button 
              @click="activeMap = !activeMap"
              class="button is-outlined is-primary"
              v-bind:class="{ 'is-active': !activeMap}"
              >Lista</button>
          </li>
          <li>
            <button 
              @click="activeMap = !activeMap; filtersOn = false"
              class="button is-outlined is-primary"
              v-bind:class="{ 'is-active': activeMap}"
              >Mapa</button>
          </li>
        </ul>

        <button 
          v-if="filtersActive"
          @click="cleanFilters(true)"
          class="button is-outlined is-secondary is-small is-fullwidth"
          :style="{ marginBottom: '1rem'}"
          >LIMPIAR FILTROS</button>

        <h1 class="title">Ciudad</h1>
        <span :class="{ 'inverte-color': basic.city.value > 0 }">
          <select2 
            v-model="basic.city.value" 
            :options="basic.city.options" 
            @change="fetchVillages(true)"            
            >
          </select2>
          <!--
          <span 
            v-if="false && basic.city.value > 0"
            class="quitar-filtro is-size-7 has-text-grey-light has-text-centered is-italic" @click="setBasicCityValue()">- quitar filtro -</span>
          -->
        </span>          
        <h1 class="title">Comuna</h1>
        <span :class="{ 'inverte-color': basic.village.value > 0 }">
          <select2 v-model="basic.village.value" :options="basic.village.options" 
          @change="setBasicVillageValue(basic.village.value)"></select2>
          <!--
          <span 
            v-if="false && basic.village.value > 0"
            class="quitar-filtro is-size-7 has-text-grey-light has-text-centered is-italic" @click="setBasicVillageValue()">- quitar filtro -</span>
          -->
        </span>

        <div class="buttons is-right">
          <button
            @click="filtrosShow = !filtrosShow"
            id="js-filters-active-mobile"
            class="button is-small is-hidden-tablet is-right mt-4"
            >
            <span v-html="filtrosShow ? '+ FILTROS' : '- FILTROS'"></span>
            <img src="{{ asset('images/explore/filters.png') }}">
          </button>
        </div>

        <div :class="{ 'is-hidden-mobile': filtrosShow }">

          <h1 class="title">Precio de Arriendo Mensual</h1>
          <div class="price-slider-wrapper">
            <img src="{{ asset('images/explore/grafica_precio.png') }}">
            <vue-slider
              v-model="priceSlider.value" 
              v-bind="priceSlider" 
              @drag-end="fetchFilteredProperties"
              ></vue-slider>
          </div>
          <span 
            class="price-range" 
            v-html="'CLP '+ priceSlider.value[0] + ' - CLP ' + priceSlider.value[1]"></span>

          <h1 class="title">Scoring uHomie</h1>
          <vue-slider 
            v-model="scoringSlider.value" 
            v-bind="scoringSlider" @drag-end="fetchFilteredProperties"></vue-slider>
          <div class="scoring-range">
            <span v-html="scoringSlider.value[0]"></span>
            <span v-html="scoringSlider.value[1]"></span>
          </div>

          <h1 class="title">Fecha</h1>
          <div class="control">
            <input type="date" class="input bulmaCalendar" v-model="date" ref="dateFilter">
          </div>
          <h1 class="title">Membresía</h1>
          <select2 v-model="basic.membership.value" :options="basic.membership.options" @change="setMembershipValue(basic.membership.value)">
          </select2>
          <h1 class="title">Tipo de Propiedad</h1>
          <select2 v-model="basic.propertyType.value" :options="basic.propertyType.options" @change="setPropertyTypeValue(basic.propertyType.value)">
          </select2>
          <h1 class="title">Perfil</h1>
          <select2 v-model="basic.profile.value" :options="basic.profile.options" @change="setProfileValue(basic.profile.value)">
          </select2>

          <h1 class="title">Arrienda</h1>
          <select2 v-model="basic.type_user.value" :options="basic.type_user.options" @change="setTypeUserValue(basic.type_user.value)">
          </select2>

          <h1 class="title">Temporada</h1>
          <select2 v-model="basic.type_stay.value" :options="basic.type_stay.options" @change="setTypeStayValue(basic.type_stay.value)">
          </select2>

          <section class="accordions">
            <article class="accordion is-active">
              <div class="accordion-header toggle">
                <p>Características</p>
                <button class="toggle" aria-label="toggle"></button>
              </div>
              <div class="accordion-body">
                <div class="accordion-content">
                  <div class="filter-field">
                    <img src="{{ asset('images/explore/ico_habitacion_azul.png') }}">
                    <span>Habitaciones</span>
                    <vue-numeric-input v-model="features.rooms" :min="0" align="center" size="4rem" @change="setFeaturesValue('rooms', features.rooms)"></vue-numeric-input>
                  </div>
                  <div class="filter-field">
                    <img src="{{ asset('images/explore/ico_banos_azul.png') }}">
                    <span>Baños</span>
                    <vue-numeric-input v-model="features.bath" :min="0" align="center" size="4rem" @change="setFeaturesValue('bath', features.bath)"></vue-numeric-input>
                  </div>
                  <div class="filter-field">
                    <img src="{{ asset('images/explore/ico_habitacion_azul.png') }}">
                    <span>Metros Cuadrados</span>
                    <vue-numeric-input v-model="features.meters" :min="0" align="center" size="4rem" @change="setFeaturesValue('meters', features.meters)"></vue-numeric-input>
                  </div>
                  <div class="filter-field">
                    <img src="{{ asset('images/explore/ico_propiedades_azul.png') }}">
                    <span>Propiedades validadas</span>
                    <div class="field">
                      <input v-model="features.verifiedProperty" @change="setFeaturesValue('verifiedProperty', features.verifiedProperty)" id="checkbox-properties" type="checkbox" class="switch is-small is-rounded">
                      <label for="checkbox-properties"></label>
                    </div>
                  </div>
                  <div class="filter-field">
                    <img src="{{ asset('images/explore/ico_animales_azul.png') }}">
                    <span>Mascotas</span>
                    <div class="field">
                      <input v-model="features.pets" @change="setFeaturesValue('pets', features.pets)" id="checkbox-pets" type="checkbox" class="switch is-small is-rounded">
                      <label for="checkbox-pets"></label>
                    </div>
                  </div>
                  <div class="filter-field">
                    <img src="{{ asset('images/explore/ico_estacionamiento_azul.png') }}">
                    <span>Estacionamiento</span>
                    <div class="field">
                      <input v-model="features.parking" @change="setFeaturesValue('parking', features.parking)" id="checkbox-parking" type="checkbox" class="switch is-small is-rounded">
                      <label for="checkbox-parking"></label>
                    </div>
                  </div>
                  <div class="filter-field">
                    <img src="{{ asset('images/explore/ico_amueblado_azul.png') }}">
                    <span>Amoblado</span>
                    <div class="field">
                      <input v-model="features.furnished" @change="setFeaturesValue('furnished', features.furnished)" id="checkbox-furniture" type="checkbox" class="switch is-small is-rounded">
                      <label for="checkbox-furniture"></label>
                    </div>
                  </div>
                  <div class="filter-field">
                    <img src="{{ asset('images/icons/bodega.png') }}">
                    <span>Bodega</span>
                    <div class="field">
                      <input v-model="features.cellar" @change="setFeaturesValue('cellar', features.cellar)" id="checkbox-cellar" type="checkbox" class="switch is-small is-rounded">
                      <label for="checkbox-cellar"></label>
                    </div>
                  </div>
                  <div class="filter-field">
                    <img src="{{ asset('images/icons/seguridad.png') }}">
                    <span>Seguro de arriendo</span>
                    <div class="field">
                      <input v-model="features.insurance" @change="setFeaturesValue('insurance', features.insurance)" id="checkbox-insurance" type="checkbox" class="switch is-small is-rounded">
                      <label for="checkbox-insurance"></label>
                    </div>
                  </div>
                </div>
              </div>
            </article>
            <article class="accordion">
              <div class="accordion-header toggle">
                <p>Servicios de la Unidad</p>
                <button class="toggle" aria-label="toggle"></button>
              </div>
              <div class="accordion-body">
                <div class="accordion-content">
                  <select2
                    v-model="unitServices.selected"
                    :options="unitServices.options"
                    :settings="{ multiple: true }"
                    @change="fetchFilteredProperties">
                  </select2>
                </div>
              </div>
            </article>
            <article class="accordion">
              <div class="accordion-header toggle">
                <p>Servicios del condominio</p>
                <button class="toggle" aria-label="toggle"></button>
              </div>
              <div class="accordion-body">
                <div class="accordion-content">
                  <select2
                    v-model="condoServices.selected"
                    :options="condoServices.options"
                    :settings="{ multiple: true }"
                    @change="fetchFilteredProperties">
                  </select2>
                </div>
              </div>
            </article>
            <!--
            <article class="accordion">
              <div class="accordion-header toggle">
                <p>Otros</p>
                <button class="toggle" aria-label="toggle"></button>
              </div>
              <div class="accordion-body">
                <div class="accordion-content">
                  {{-- Empty --}}
                </div>
              </div>
            </article>
          -->
          </section>
        </div>
      </div>
      <!-- END FILTERS -->
    </div>
    
    <div
      class="column is-desktop has-loading"
      :class="activeMap ? 'is-6' : 'is-9'"
      >
      <div v-if="loading" class="loading-uhomie">
        <img src="{{ asset('images/gif-uhomie.gif') }}" alt="loading..." />
      </div>
      <div 
        v-if="activeMap"
        class="map-filter-min"
        :class="{'map-filter-close': filtersOn }"
        >
        <button 
          @click="filtersOn = !filtersOn"
          class="button"
          >
          <span class="icon is-small">
            <i class="fa fa-filter"></i>
          </span>
          <span v-html="filtersOn ? 'OCULTAR' : 'FILTROS'"></span>
        </button>
      </div>

      <div>
        <div>
          <section class="hero main-title">
            <div class="hero-body">
              <div class="container">
                <h1 class="title lead">Descubre propiedades</h1>
                <h1 class="title">acorde a tu necesidad</h1>
              </div>
            </div>
          </section>
          <div class='icons-necesidades carousel carousel-animated carousel-animate-slide' data-size="7">
            <div class='icons-explore carousel-container'>
              <div class='carousel-item is-active' 
                @click="filtrarPerfil(1)">
                <img 
                  src="{{ asset('images/explore/iconos/icono-temporada-larga.png') }}"
                  >
                <h4 class="title">Temporada larga</h4>
              </div>
              <div class='carousel-item' @click="filtrarPerfil(2)">
                <img 
                  src="{{ asset('images/explore/iconos/icono-temporada-corta.png') }}">
                <h4 class="title">Temporada corta</h4>
              </div>
              <div class='carousel-item' @click="filterRoom()">
                <img v-if="features.rooms" src="{{ asset('images/explore/iconos/icono-habitaciones-hover.png') }}">
                <img v-else src="{{ asset('images/explore/iconos/icono-habitaciones.png') }}">
                <h4 class="title">Habitaciones</h4>
              </div>
              <div class='carousel-item' @click="filterOficinas">
                <img v-if="basic.propertyType.value == 5" src="{{ asset('images/explore/iconos/icono-oficinas-hover.png') }}">
                <img v-else src="{{ asset('images/explore/iconos/icono-oficinas.png') }}">
                <h4 class="title">Oficinas</h4>
              </div>
              <div class='carousel-item' @click="filterCellar">
                <img v-if="features.cellar" src="{{ asset('images/explore/iconos/icono-bodegas-hover.png') }}">
                <img v-else src="{{ asset('images/explore/iconos/icono-bodegas.png') }}">
                <h4 class="title">Bodegas</h4>
              </div>            
              <div class='carousel-item' @click="filtrarPerfil(6)">
                <img src="{{ asset('images/explore/iconos/icono-terrenos.png') }}">
                <h4 class="title">Terrenos</h4>
              </div>
              <div class='carousel-item' @click="filterParking">
                <img v-if="features.parking" src="{{ asset('images/explore/iconos/icono-estacionamientos-hover.png') }}">
                <img v-else src="{{ asset('images/explore/iconos/icono-estacionamientos.png') }}">
                <h4 class="title">Estacionamiento</h4>
              </div>
            </div>
          </div>
        </div>
        @if (false && !request()->has('perfil'))
        <div v-if="!explore">
          <section class="hero main-title">
            <div class="hero-body">
              <div class="container">
                <h1 class="title lead">Descubre propiedades</h1>
                <h1 class="title">acorde a tu perfil</h1>
              </div>
            </div>
          </section>
          <div class='carousel carousel-animated carousel-animate-slide' data-size="3">
            <div class='carousel-container'>
              <div class='carousel-item is-active' @click="filtrarPerfil(1)">
                <img src="{{ asset('images/soltero.jpg') }}">
                <h1 class="title">Soltero</h1>
              </div>
              <div class='carousel-item' @click="filtrarPerfil(2)">
                <img src="{{ asset('images/estudiante.jpg') }}">
                <h1 class="title">Estudiante</h1>
              </div>
              <div class='carousel-item' @click="filtrarPerfil(3)">
                <img src="{{ asset('images/flia_hijos.jpg') }}">
                <h1 class="title">Flia con hijos</h1>
              </div>
              <div class='carousel-item' @click="filtrarPerfil(4)">
                <img src="{{ asset('images/flia_sin_hijos.jpg') }}">
                <h1 class="title">Flia sin hijos</h1>
              </div>
              <div class='carousel-item' @click="filtrarPerfil(5)">
                <img src="{{ asset('images/flia_mascotas.jpg') }}">
                <h1 class="title">Flia con mascota</h1>
              </div>            
              <div class='carousel-item' @click="filtrarPerfil(6)">
                <img src="{{ asset('images/ejecutivo.jpg') }}">
                <h1 class="title">Ejecutivos</h1>
              </div>
              <div class='carousel-item' @click="filtrarPerfil(7)">
                <img src="{{ asset('images/grupo_grande.jpg') }}">
                <h1 class="title">Grupos (+5)</h1>
              </div>
            </div>
            <div class="carousel-navigation">
              <div class="carousel-nav-left">
                <img src="{{ asset('images/explore/ico_flecha_left.png') }}">
              </div>
              <hr>
              <div class="carousel-nav-right">
                <img src="{{ asset('images/explore/ico_flecha_right.png') }}">
              </div>
            </div>
          </div>
        </div>
        @endif
        <section class="hero main-title" id="second-title">
          <div class="hero-body">
            <div class="container">
              <h1 class="title lead">Navega entre cientos</h1>
              <h1 class="title">de propiedades</h1>
            </div>
          </div>
        </section>
        <div class="columns is-multiline property-list">
          <div class="column is-6" v-for="property in properties">
            <property v-bind="property" @evento_abrir_modal="abrirModalio"></property>
          </div>
        </div>
        <div 
          v-if="loadMore"
          style="text-align: center; margin: 2rem;">
          <button 
            @click="loadMoreProperties"
            class="button is-large"
            :class="{ 'is-loading': loadMoreLoading }"
            style="border: 0;"
          >
            <i
              v-if="!loadMoreLoading" 
              class="fa fa-plus" style="color: #ffd900"></i>
          </button>
        </div>        
      </div>
    </div>

    <div 
      v-if="activeMap"
      class="column is-6 is-desktop"
      style="min-height: 500px;"
      >

      <gmap
        id="maps"
        :center="centerMap"
        :zoom="14"
        map-type-id="roadmap"
        style="width: 100%; height: 100%;"

      >
        <gmap-infowindow 
          v-if="selectedMarker"
          :position="selectedMarker.position"
          @closeclick="selectedMarker = undefined"
          >
          <div>
            <property v-if="selectedMarker" v-bind="properties[selectedMarker.propertyIndex]"></property>
          </div>     
        </gmap-infowindow>
        <gmap-marker
          v-for="(m, i) in markers"
          :key="i"
          :position="m.position"
          :clickable="true"
          @click="selectMarker(m)"
          :icon="m.icon">
        </gmap-map>
      </gmap>

    </div>
  </div>
</div>

<input type="hidden" value="{{ asset('images') }}" id="images-dir">
@endsection

@section('styles')
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.css">
<link rel="stylesheet" href="{{ asset('css/explore.css') }}">
@endsection

@section('scripts')
@if(isset($village))
<script>
  var commune = {{ $village->id }}
  var city = {{ $village->city_id }}
</script>
@endif
<script type="text/javascript" src="{{ asset('js/explore.js') }}"></script>
@endsection
