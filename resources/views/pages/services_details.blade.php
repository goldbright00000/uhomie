@extends('layouts.app')

@section('header')
@include('layouts.header', ['isSolid' => true])
@endsection

@section('content')
<input type="hidden" id="images-dir" value="{{ asset('images') }}">
<div id="services-details" v-cloak>
    <div class="container-fluid">
        <div class="columns preview-section">
            <div class="column is-4 resume-container">
                <div class="property-resume">
                    <a href="{{ url()->previous() }}" class="back"><i class="fa fa-angle-left"></i> Volver</a>
                    <img class="membership-logo" :src="imagesDir + membershipServiceLogo(service.data.membership)">
                    <h1 class="title" v-html="service.data.service"></h1>
                    <span class="price"v-html="service.data.name"></span>
                    <p v-html="service.data.address"></p>
                    <a href="#" :class="'button is-outlined btn-contact ' + membershipServiceButtom(service.data.membership)">Contactar</a>
                </div>
            </div>
            <div class="column is-8 banner-container">
                <!--<banner-gallery v-bind:images-dir="imagesDir" v-bind:photos="service.photos.photos || []"></banner-gallery>-->
                <banner-gallery v-bind:images-dir="imagesDir" v-bind:slug="service.data.slug" v-bind:property_id="service.id" v-bind:photos="servicePhotos" ref="banner_gallery"  v-bind:favorite="isFavouriteservice">
                </banner-gallery>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="container">
            <div class="columns is-centered">
                <div class="column is-6">
                    <div class="owner-media">
                        <article class="media" v-show="service.data.user_id">
                            <div class="media-left">
                                <img class="picture" :src="service.data.path ? service.data.path : imagesDir + '/husky.png'" :alt="service.data.name + '-photo'">
                            </div>
                            <div class="media-content">
                                <star-rating v-model="starRating.value" v-bind="starRating.options"></star-rating>
                                <h1 class="name" v-html="service.data.name"></h1>
                                <h2 v-html="service.data.service"></h2>
                            </div>
                            <!--<span class="media-right">
                                <img src="{{ asset('images/explore-details/ico_comentarios.png') }}"> 8
                            </span>-->
                        </article>
                    </div>
                    <a href="#" :class="'button is-outlined btn-contact ' + membershipServiceButtom(service.data.membership)">Contactar</a>
                    <div class="description">
                        <h1 class="title">Descripción</h1>
                        <p v-html="service.data.service_description"></p>
                    </div>
                    <div id="explore-images">
                        <h1 class="title">Explorar Proyecto</h1>
                        <gallery v-if="servicePhotos.length > 0" v-bind:pictures="servicePhotos"></gallery>
                        <p v-else>Esta propiedad no posee fotos.</p>
                    </div>
                </div>
                <div class="column is-4">
                    
                </div>
            </div>
            <div class="columns is-centered">
                    <div class="similar-agents" v-show="otherAgents && otherAgents.length > 0">
                        <h1 class="title">Proyectos / Propiedades uHomie recomendadas</h1>
                        <div class="columns is-multiline">
                            <div class="column is-4" v-for="agent in otherAgents">
                                <agent v-bind="agent"></agent>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<input type="hidden" value="{{ asset('images') }}" id="images-dir">
@endsection

@section('styles')
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.css">
<link rel="stylesheet" href="{{ asset('css/common-details.css') }}">
<link rel="stylesheet" href="{{ asset('css/agents-details.css') }}">
<link rel="stylesheet" href="{{ asset('css/components/map-details.css') }}">
<link rel="stylesheet" href="{{ asset('css/components/banner-gallery.css') }}">
@endsection

@section('scripts')
<script type="text/javascript" src="{{ asset('js/services-details.js') }}"></script>
@endsection