@extends('layouts.app')

@section('header')
<div id="services">
  <section class="hero is-fullheight" id="services-header">
    <div class="hero-head">
      @parent
    </div>
    <div class="hero-body">
      <div class="container main-title-container">
        <div class="columns is-mobile is-centered">
          <div class="column is-8">
            <h1 class="title">Encuentra</h1>
            <h1 class="title">el servicio que necesitas</h1>
            <div class="field has-addons">
              <div class="control is-expanded">
                <input
                  v-model="serviceInputValue"
                  list="services_list" 
                  class="input"
                  type="text" 
                  placeholder="Qué necesitas?" 
                  autocomplete="off"
                  >
                <datalist id="services_list">
                  <option 
                    v-for="(s,i) in servicesList"
                    :key="i"
                    v-html="s.text"
                    >            
                    </option>
                </datalist>
              </div>
              <div class="control">
                <a class="button is-info search">
                  <img src="{{ asset('images/icons/lupa.png') }}" style="max-width: 1.5rem;" />
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="hero-foot has-text-centered">
      <i class="fa fa-angle-down" id="arrow-down" ref="arrowDown"></i>
    </div>
  </section>

  <div class="container-fluid second-section">

    <div class="columns is-gapless">
      <div class="column">
        <div class="filter-list">
          <ul>
            <li><button class="button is-outlined is-primary is-active">Lista</button></li>
            <li><button class="button is-outlined is-primary">Mapa</button></li>
          </ul>

          <h1 class="title">Ciudad</h1>
          <select2 v-model="basic.city.value" :options="basic.city.options"
          @change="fetchVillages"
          >
          </select2>
          <h1 class="title">Comuna</h1>
          <select2 v-model="basic.village.value" :options="basic.village.options"
          @change="filters"
          >
          </select2>
          <h1 class="title">Servicio</h1>
          <select2 
            v-model="basic.service.value" :options="basic.service.options"
            @change="filters"
            >
          </select2>
          <h1 class="title">Tipo de Membresía</h1>
          <select2 v-model="basic.membership.value"  :options="basic.membership.options"
            @change="filters"
          >
          </select2>
          <!-- 
          <h1 class="title">Calificación</h1>
          <star-rating v-model="basic.rating.value" v-bind="basic.rating.options"></star-rating> -->
        </div>
      </div>
      <div class="column is-9 has-loading">
        <div v-if="loading" class="loading-uhomie">
          <img src="{{ asset('images/gif-uhomie.gif') }}" alt="loading..." />
        </div>
        <div v-show="initial">
          @if (!request()->has('perfil'))
          <section class="hero main-title">
          <div class="hero-body">
            <div class="container">
            <h1 class="title lead">Los servicios</h1>
            <h1 class="title">más recomendados</h1>
            </div>
          </div>
          </section>
          <div class='carousel carousel-animated carousel-animate-slide carousel-services' data-size="3">
            <div class='carousel-container'>
              <div                
                class='carousel-item is-active'
                @click="filterService(21)"
                >
                <img src="{{ asset('images/services/servicio1.jpg') }}">
                <h1 class="title">Gasistas</h1>
              </div>
              <div 
                class='carousel-item'
                @click="filterService(3)"
              >
                <img src="{{ asset('images/services/servicio2.jpg') }}">
                <h1 class="title">Electricistas</h1>
              </div>
              <div 
                class='carousel-item'
                @click="filterService(6)"
                >
                <img src="{{ asset('images/services/servicio3.jpg') }}">
                <h1 class="title">Jardineros</h1>
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
          @endif
          
          
          <section class="hero main-title" id="second-title">
            <div class="hero-body">
              <div class="container">
                <h1 class="title lead">Servicios</h1>
                <h1 class="title">principales</h1>
              </div>
            </div>
          </section>
          <!--<div 
            v-for="(s,i) in servicesInitial"
            :key="i"
            v-if="s.companies.length > 0"
            class="service-type"
            >
            <h1 class="title" v-html="s.name"></h1>
            <service 
              v-for="(service, i) in s.companies"
              :key="i" 
              v-bind="service"
              ></service>

            <p class="has-text-centered">
              <button 
                @click="filterService(21)"
                class="button is-medium"
              >
              <span class="icon is-medium">
                <img src="{{ asset('images/icons/more.png') }}" style="max-width: 1.5rem; margin: 0 auto;" />
              </span>
            </button>
            </p>            
          </div>-->
          <div class="service-type"v-for="(service, i) in servicesInitial"
              :key="i" >
              <service 
              
              v-bind="service"
              ></service>
          </div>
          <!--
          <div class="service-type">
            <h1 class="title">Mudanzas</h1>
            <service v-for="service in services" v-bind="service"></service>
          </div>
          -->
        </div>

        <div v-show="!initial" >
          <section class="hero main-title">
            <div class="hero-body">
              <div class="container">
                <h1 class="title lead">Servicios</h1>
                <h1 class="title">Resultados Encontrados</h1>
              </div>
            </div>
          </section>
          <div class="service-type">
            <service v-for="service in services" v-bind="service"></service>
          </div>

          <p class="has-text-centered" style="padding: 4rem;">
              <button 
                v-if="services.length == limit"
                @click="more"
                class="button is-medium"
              >
              <span class="icon is-medium">
                <img src="{{ asset('images/icons/more.png') }}" style="max-width: 1.5rem; margin: 0 auto;" />
              </span>
            </button>
          </p>
        </div>
      </div>
    </div>
  </div>
  <input type="hidden" value="{{ asset('images') }}" id="images-dir">
</div>
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('css/services.css') }}">
@endsection

@section('scripts')
<script type="text/javascript" src="{{ asset('js/services-explore.js') }}"></script>
@endsection
