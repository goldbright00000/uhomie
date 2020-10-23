@extends('layouts.app')

@section('meta-fb')
<meta property="og:url"                content="{{url('explorar/'.$p->id.'/'.$p->getSlugAttribute())}}" />
<meta property="og:type"               content="article" />
<meta property="og:title"              content="uHomie | Te hacemos la vida mas fácil" />
<meta property="og:description"        content="{{$p->propertyType()->first()->name}} en {{$p->address}}. {{$p->description}}" />
<meta property="og:image"              content="{{str_replace('storage//', '',$p->photos()->get()->first()->getPublicUrl())}}" />
@endsection

@section('header')
@include('layouts.header', ['isSolid' => true])
@endsection

@section('content')
<style>
@media only screen and (min-width: 650px) {
  .img-membership {
  margin-left: 8rem;
  }
}
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma-carousel@4.0.4/dist/css/bulma-carousel.min.css">
<style>
.carousel .carousel-navigation.is-overlay{
  z-index: 39;
}    
</style>

<div id="explore-details" v-cloak >
  <select-days v-if="true" v-bind:fechas_ocupadas="fechas_ocupadas_computado" :property="property.data"></select-days>
  <loading :active.sync="isLoading" :can-cancel="false" is-full-page loader="dots" color="#1ac036"></loading>

  <!-- Header -->
  <property-header
    :property="property" 
    :property-photos="propertyPhotos"
    @togglefavorite="togglefavorite"
    ></property-header>

  <!-- details -->
  <property-details
    :property="property" 
    :property-photos="propertyPhotos"
    :membership-owner-logo="membershipOwnerLogo"
    :membership-owner-buttom="membershipOwnerButtom"
    :money-format="moneyFormat"
    :date-format="dateFormat"
    :number="number"
    :property-features="propertyFeatures"
    type_stay="{{ $p->type_stay }}"
    ></property-details>

  <!-- gallery notwork-->
  <property-gallery class="is-hidden-mobile"
    v-if="propertyPhotos.length > 0"
    v-bind:photos="propertyPhotos"
  ></property-gallery>

  <!-- services -->
  <property-services
    v-if="unitAmenities.length > 0"
    v-bind:services="unitAmenities"
    v-bind:services-commun="communAmenities"
    v-bind:services-basic="basicAmenities"
  ></property-services>

  <!-- spaces -->
  <property-spaces
    v-bind:photos="propertyPhotos"
  ></property-spaces>

  <!-- ubication -->
  <property-ubication
    v-bind:property="property"
  ></property-ubication>

  <!-- conditions -->
  <div class="columns">
    <div class="column is-half">
      <property-conditions
        v-bind:property="property"
      ></property-conditions>
    </div>
    <div class="column" v-if="property.data.visit == '1' && property.data.type_stay == 'LONG_STAY'">
      <calendar
        v-bind:schedule_dates="property.data.schedule_dates"
        v-bind:property_id="property.data.id"
        v-bind:from_date="property.data.visit_from_date"
        v-bind:to_date="property.data.visit_to_date"
        v-bind:range="property.data.schedule_range"
        v-bind:button="membershipOwnerButtom(property.owner.membership_name)"
        v-bind:type="1">
      </calendar>
    </div>
    <div class="column" v-if="property.data.type_stay == 'SHORT_STAY'">
      <calendar-short-stay :property="property.data"></calendar-short-stay>
    </div>
  </div>
  

  <section class="section">
    <div 
      v-if="rulesAmenities.length > 0"
      class="container">
      <h3 class="is-size-5 has-text-weight-bold">Reglas de la propiedad</h3>
      <div class="services">
        <span class="services__item" v-for="(amenities, key) in rulesAmenities" :title="amenities.name">
        <img v-show="amenities.image && amenities.image.length" :src="amenities.image">  @{{amenities.name}}</span>
      </div>
    </div>
    <div 
      v-if="detailsAmenities.length > 0"
      class="container">
      <h3 class="is-size-5 has-text-weight-bold">Detalles de la propiedad</h3>
      <div class="services">
        <span class="services__item" v-for="(amenities, key) in detailsAmenities" :title="amenities.name">
        <img v-show="amenities.image && amenities.image.length" :src="amenities.image">  @{{amenities.name}}</span>
      </div>
    </div>
  </section>
        
  <!-- <div class="column is-4 content-aside">
    <div class="columns">
      <div class="column">
        <schedule
          v-if="property.data.visit"
          v-bind:property_id="property.data.id"
          v-bind:from_date="property.data.visit_from_date"
          v-bind:to_date="property.data.visit_to_date"
          v-bind:range="property.data.schedule_range"
          v-bind:button="membershipOwnerButtom(property.owner.membership_name)"></schedule>
      </div>
    </div>-->
          
          <div class="columns" v-if="executive != null && property.data.verified == true" >
              <div class="column">
                  <article class="media">
                      <figure class="media-left">
                          <p class="image is-64x64">
                              <img class="is-rounded" :src="executive.photo">
                          </p>
                      </figure>
                      <div class="media-content">
                          <div class="content">
                              <p>
                                  <span class="tag is-success">Propiedad verificada por</span>
                                  <br>
                                  <strong>Ejecutivo</strong> <span v-html="executive.name"></span>
                                  <br>
                                  <strong>Telefono:</strong> <span v-html="executive.phone"></span>
                                  <br>
                                  <strong>Correo:</strong> <span v-html="executive.email"></span>
                              </p>
                          </div>
                      </div>
                  </article>
              </div>
          </div>

      <div class="columns is-centered">
        <div class="column is-10">
          
          
          <div style="margin-bottom: 30px" class="similar-properties" v-if="recommendedProperties.length > 0">
            <h1 class="title">Propiedades Recomendadas para ti</h1>
            
            <div class="columns is-multiline" v-for="duplas in recommendedProperties" v-if="recommendedProperties.length > 0" >
              <div class="column is-6" v-for="property in duplas">
                <property v-bind="serializeProperty(property)"></property>
              </div>
            </div>
            
          </div>
          <div class="similar-properties" v-if="similarProperties.length > 0">
            <h1 class="title">Propiedades similares</h1>
            
            <div class="columns is-multiline" v-for="duplas in similarProperties" v-if="similarProperties.length > 0" >
              <div class="column is-6" v-for="property in duplas">
                <property v-bind="serializeProperty(property)"></property>
              </div>
            </div>
          </div>
          
        </div>
      </div>

  <div class="container-fluid bottom-info" style="z-index:98">
    <div class="col">
      <img :src="imgDir + membershipOwnerLogo(property.owner.membership_name)">
    </div>
    <div class="col">
      <span class="bottom_title">@{{ property.data.name }}</span>
    </div>
    <span class="price"><img src="{{ asset('images/explore-details/ico_moneda.png') }}"> CLP <span class="value">@{{ moneyFormat(property.data.rent) }}</span> @if($p->type_stay == 'LONG_STAY')x mes @else x día @endif</span>
    <apply-button ref="botonPostular" @evento-abrir-modal-select-days="abrir_modal_select_days" :type_stay="@if($p->type_stay == 'LONG_STAY')'LONG_STAY'@else'SHORT_STAY'@endif" v-if="property.data.available == 1"  :property_id="Number(property.id())" :has_apply="property.data.applied" :class="membershipOwnerButtom(property.owner.membership_name)">
      @if($p->type_stay == 'LONG_STAY')Arrendar @else Reservar @endif
    </apply-button>
    <a v-else :class="'button is-'+(membership != '' ? membership : 'basic')" disabled>Arrendado</a>
  </div>
  <contact v-bind:contact="contact" v-bind:owner="property.owner" v-bind:tenant="property.tenant"></contact>
</div>
<input type="hidden" value="{{ asset('images') }}" id="images-dir">


  
@endsection

@section('styles')
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.css">
<link rel="stylesheet" href="{{ asset('css/common-details.css') }}">
<link rel="stylesheet" href="{{ asset('css/explore-details.css') }}">
<link rel="stylesheet" href="{{ asset('css/components/banner-gallery.css') }}">
<link rel="stylesheet" href="{{ asset('css/components/map-details.css') }}">
<link rel="stylesheet" href="{{ asset('css/components/schedule.css') }}">
@endsection

@section('scripts')
<script type="text/javascript" src="{{ asset('js/common-details.js') }}"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/bulma-carousel@3.0.0/dist/js/bulma-carousel.js"></script>
<script type="text/javascript" src="{{ asset('js/explore-details.js') }}"></script>
<!--<script type="text/javascript" src="{{ asset('js/bulma-carousel.min.js') }}"></script>-->
@endsection
