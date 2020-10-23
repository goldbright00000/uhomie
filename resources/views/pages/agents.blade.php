@extends('layouts.app')

@section('header')
<section class="hero is-fullheight" id="agents-header">
  <div class="hero-head">
    @parent
  </div>
  <div class="hero-body">
    <div class="container">
      <div class="columns is-tablet">
        <div class="column is-6 main-title-wrapper">
          <h1 class="title">Con UHOMIE</h1>
          <h1 class="title">Publica tus propiedades</h1>
          <h1 class="title">para venta o arriendos con</h1>
          <h1 class="title">Mayor alcance publicitario.</h1>
          <h1 class="title">Sin pago de comisiones!</h1>
          <a href="#" class="button is-outlined link-register">Regístrate</a>
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
<div class="container-fluid second-section" id="agents" >  
  <!-- <h1 class="title">{[ mensaje ]}</h1> -->
  <div class="columns is-gapless">
    <div class="column">
        <div class="filter-list">
        <ul>
          <li><button class="button is-outlined is-primary is-active">Lista</button></li>
          <li><button class="button is-outlined is-primary">Mapa</button></li>
        </ul>

        <h1 class="title">Ciudad</h1>
        <select2 v-model="basic.city.value" :options="basic.city.options" @change="fetchVillages">
        </select2>
        <h1 class="title">Comuna</h1>
        <select2 v-model="basic.village.value" :options="basic.village.options" @change="fetchFilteredProjects">
        </select2>
        <h1 class="title">Propiedades</h1>
        <select2 v-model="basic.propertyType.value" :options="basic.propertyType.options" @change="fetchFilteredProjects">
        </select2>
        <h1 class="title">Estado de Proyecto</h1>
        <select2 v-model="basic.projectStatus.value" :options="basic.projectStatus.options" @change="fetchFilteredProjects">
        </select2>
        <h1 class="title">Tipo de Membresía</h1>
        <select2 v-model="basic.membership.value" :options="basic.membership.options" @change="fetchFilteredProjects">
        </select2>


        <h1 class="title">Precio de Venta</h1>
        <div class="price-slider-wrapper">
          <img src="{{ asset('images/explore/grafica_precio.png') }}">
          <vue-slider v-model="priceSlider.value" v-bind="priceSlider" @drag-end="fetchFilteredProjects"></vue-slider>
        </div>
        <span class="price-range">UF {[ priceSlider.value[0] ]} - UF {[ priceSlider.value[1] ]}</span>

      </div>
    </div>
    <div class="column is-9 has-loading" style="margin-bottom: 2rem;">
      <div v-if="loading" class="loading-uhomie">
        <img src="{{ asset('images/gif-uhomie.gif') }}" alt="loading..." />
      </div>
      <div v-if="showAgents">
        <section class="hero main-title">
          <div class="hero-body">
            <div class="container">
              <h1 class="title lead">Navega entre cientos</h1>
              <h1 class="title">de agentes</h1>
            </div>
          </div>
        </section>
        <!-- Lista de Agentes -->
        <div class="columns is-multiline">
          <div class="column is-4" v-for="agent in agents">          
            <agent v-bind="agent" @filter="filterAgent" v-bind:company="true"></agent>
          </div>

          <div 
            v-if="!loadMoreAgentLoading"
          style="text-align: center; margin: 2rem; width: 100%;">
            <button 
              @click="loadMoreAgents"
              class="button is-large"
              :class="{ 'is-loading': loadMoreAgentLoading }"
            >
              <i
                v-if="!loadMoreAgentLoading" 
                class="fa fa-plus" style="color: #ffd900"></i>
            </button>
          </div>
        </div>
      </div>      

      <section class="hero main-title" id="second-title">
        <div class="hero-body">
          <div class="container">
            <h1 class="title lead">Navega entre</h1>
            <h1 class="title">Propiedades en venta</h1>
          </div>
        </div>
      </section>
      <div class="columns is-multiline project-list">
        <div class="column is-4" v-for="project in projects">
          <agent v-bind="project"></agent>
        </div>
      </div>
      <div 
        v-if="loadMore"
        style="text-align: center; margin: 2rem;">
        <button 
          v-if="!loadMoreLoading"
          @click="loadMoreProperties"
          class="button is-large"
          :class="{ 'is-loading': loadMoreLoading }"
        >
          <i
            v-if="!loadMoreLoading" 
            class="fa fa-plus" style="color: #ffd900"></i>
        </button>
      </div>
      <section class="hero main-title" id="second-title">
        <div class="hero-body">
          <div class="container">
            <h1 class="title lead">Navega entre</h1>
            <h1 class="title">Propiedades para arrendar</h1>
          </div>
        </div>
      </section>
      <div class="columns is-multiline property-list">
        <div class="column is-6" v-for="property in properties">
          <property v-bind="property" @evento_abrir_modal="abrirModalio"></property>
        </div>
      </div>
      <div 
        v-if="!loadMorePropertiesLoading"
        style="text-align: center; margin: 2rem;">
        <button 
          v-if="!loadMorePropertiesLoading"
          @click="loadMoreProperties2"
          class="button is-large"
          :class="{ 'is-loading': loadMorePropertiesLoading }"
        >
          <i
            v-if="!loadMorePropertiesLoading" 
            class="fa fa-plus" style="color: #ffd900"></i>
        </button>
      </div>
    </div>
  </div>
</div>

<input type="hidden" value="{{ asset('images') }}" id="images-dir">
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('css/agents.css') }}">
@endsection

@section('scripts')
<script type="text/javascript" src="{{ asset('js/agents-explore.js') }}"></script>
@endsection

