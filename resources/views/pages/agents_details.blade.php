@extends('layouts.app')

@section('header')
@include('layouts.header', ['isSolid' => true])
@endsection

@section('content')
<input type="hidden" id="images-dir" value="{{ asset('images') }}">
<div id="explore-details" v-cloak>
    <div class="container-fluid">
        <div class="columns preview-section">
            <div class="column is-4 resume-container">
                <div class="property-resume">
                    <a href="{{ url()->previous() }}" class="back"><i class="fa fa-angle-left"></i> Volver</a>
                    <img class="membership-logo" :src="imagesDir + membershipAgentLogo(project.agent.membership_name)">
                    <h1 class="title" v-html="project.data.name"></h1>
                    <span class="price"><img src="{{ asset('images/explore-details/ico_moneda.png') }}"> UF <span class="value" v-html="moneyFormat(project.data.rent)">0</span></span>
                    <div class="features">
                    <span title="Habitaciones"><img src="{{ asset('images/explore/ico_habitacion_azul.png') }}"> <span v-html="project.data.bedrooms">0</span></span>
                        <span title="Baños"><img src="{{ asset('images/explore/ico_banos_azul.png') }}"> <span v-html="project.data.bathrooms">0</span></span>
                        <span title="Estacionamiento"><img src="{{ asset('images/explore/ico_estacionamiento_azul.png') }}"> <span v-html="project.data.private_parking">0</span></span>
                        <span title="Mascotas"><img src="{{ asset('images/explore/ico_animales_azul.png') }}"> <span v-html="project.data.pet_preference != 'no' ? 'SI' : 'NO' ">SI</span></span>
                        <span title="Amueblado"><img src="{{ asset('images/explore/ico_amueblado_azul.png') }}"> <span v-html="project.data.furnished ? 'SI' : 'NO'">SI</span></span>
                    </div>
                    <span class="status">{{-- <img src="{{ asset('images/explore-details/ico_propiedad_verificada.png') }}"> --}} Etapa de proyecto / Propiedad: <span v-html="project.data.condition ? conditionProyect(project.data.condition) : '' "></span></span>
                    <a href="#" :class="'button is-outlined btn-contact ' + membershipAgentButtom(project.agent.membership_name)">Contactar</a>
                </div>
            </div>
            <div class="column is-8 banner-container">
                <!--<banner-gallery v-bind:images-dir="imagesDir" v-bind:photos="project.photos.photos || []"></banner-gallery>-->
                <banner-gallery v-bind:images-dir="imagesDir" v-bind:slug="project.data.slug" v-bind:property_id="project.id" v-bind:photos="projectPhotos" ref="banner_gallery"  v-bind:favorite="isFavouriteProject">
                </banner-gallery>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="container">
            <div class="columns is-centered">
                <div class="column is-6">
                    <div class="owner-media">
                        <article class="media" v-show="project.agent.id">
                            <div class="media-left">
                                <a target="_blank" :href="'/users/agente/'+project.agent.id">
                                    <img class="picture" :src="project.agent.photo ? project.agent.photo : imagesDir + '/husky.png'" :alt="project.agent.firstname + ' ' + project.agent.lastname + '-photo'">
                                </a>
                            </div>
                            <div class="media-content">
                                <star-rating v-model="starRating.value" v-bind="starRating.options"></star-rating>
                                <h1 class="name">
                                    <a target="_blank" style="color: #ffd900;" :href="'/users/agente/'+project.agent.id" v-html="project.agent.firstname + ' ' + project.agent.lastname"></a>
                                </h1>
                                <h2>Agencia / Propietario</h2>
                            </div>
                            <!--<span class="media-right">
                                <img src="{{ asset('images/explore-details/ico_comentarios.png') }}"> 8
                            </span>-->
                        </article>
                    </div>
                    <a href="#" :class="'button is-outlined btn-contact ' + membershipAgentButtom(project.agent.membership_name)">Contactar</a>
                    <div class="description">
                        <h1 class="title">Descripción</h1>
                        <p v-html="project.data.description"></p>
                    </div>
                    <section class="accordions">
                        <article class="accordion is-active">
                            <div class="accordion-header service">
                                <p>Servicios de la unidad</p>
                                <button class="toggle" aria-label="toggle"></button>
                            </div>
                            <div class="accordion-body">
                                <div class="accordion-content">
                                    <div class="content-expander" v-if="unitAmenities.length > 0">
                                        <div class="services">
                                            <span v-for="(amenities, key) in unitAmenities" :title="amenities.name">
                                                <!--<img :src=" (amenities.image && imageDir(amenities.image))  || imagesDir + '/explore-details/ico_servicios_gimnasio.png'">-->
                                                <span v-html="amenities.name"></span>
                                            </span>
                                        </div>
                                        <a href="#">ver más</a>
                                    </div>
                                    <p v-else>Esta propiedad no posee ninguno de estos servicios.</p>
                                </div>
                            </div>
                        </article>
                        <article class="accordion">
                            <div class="accordion-header">
                                <p>Servicios de condominio</p>
                                <button class="toggle" aria-label="toggle"></button>
                            </div>
                            <div class="accordion-body">
                                <div class="accordion-content">
                                    <div class="content-expander" v-if="communAmenities.length > 0">
                                        <div class="services">
                                            <span v-for="(amenities, key) in communAmenities" :title="amenities.name">
                                                <!--<img :src="imageDir(amenities.image) || imagesDir + '/explore-details/ico_servicios_gimnasio.png'">-->
                                                <span v-html="amenities.name"></span>
                                            </span>
                                        </div>
                                        <a href="#">ver más</a>
                                    </div>
                                    <p v-else>Esta propiedad no posee ninguno de estos servicios.</p>
                                </div>
                            </div>
                        </article>
                        <article class="accordion">
                            <div class="accordion-header">
                                <p>Información de la Contrucción</p>
                                <button class="toggle" aria-label="toggle"></button>
                            </div>
                            <div class="accordion-body">
                                <div class="accordion-content">
                                    <div class="content-expander" v-if="communAmenities.length > 0">
                                        <div>
                                            <div><b>Arquitecto:</b> <span v-html="project.data.architect"></span></div><br>
                                            <div><b>Constructora:</b> <span v-html="project.data.builder"></span></div>
                                        </div>
                                    </div>
                                    <p v-else>Esta propiedad no posee ninguno de estos servicios.</p>
                                </div>
                            </div>
                        </article>
                    </section>
                    <div id="explore-images">
                        <h1 class="title">Explorar Proyecto</h1>
                        <gallery v-if="projectPhotos.length > 0" v-bind:pictures="projectPhotos"></gallery>
                        <p v-else>Esta propiedad no posee fotos.</p>
                    </div>
                </div>
                <div class="column is-4">
                    <calendar v-if="project.data.visit == '1'"
                        v-bind:schedule_dates="project.data.schedule_dates"
                        v-bind:property_id="project.data.id"
                        v-bind:from_date="project.data.visit_from_date"
                        v-bind:to_date="project.data.visit_to_date"
                        v-bind:range="project.data.schedule_range"
                        v-bind:button="membershipAgentButtom(project.agent.membership_name)"
                        v-bind:type="5">
                    </calendar>
                </div>
            </div>
            <div class="columns is-centered">
                <div class="column is-10">
                    <div class="location">
                        <h1 class="title">Ubicación de la propiedad</h1>
                        <p>
                            <i><img :src="imagesDir + '/icono-direccion.png'"/></i>
                            <span v-html="'Localizado en ' + project.data.address"></span>
                        </p>
                        <map-detail v-bind:property_location="projectLocation"></map-detail>
                    </div>
                    <div class="similar-agents" v-show="similarAgents && similarAgents.length > 0">
                        <h1 class="title">Otras propiedades del mismo vendedor</h1>
                        <div class="columns is-multiline">
                            <div v-for="agent in similarAgents" class="column is-4" v-if="agent.id != project.id">
                                <agent v-bind="agent"></agent>
                            </div>
                        </div>
                        
                    </div>
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
    <div class="container-fluid bottom-info in-place fixed">
        <div class="col"></div>
        <div class="col">
            <img class="membership-logo" :src="imagesDir + membershipAgentLogo(project.agent.membership_name)">
            <div>
                <span class="bottom_title" v-html="project.data.name"></span>
            </div>
        </div>
        <div class="col">
            <span class="price"><img src="{{ asset('images/explore-details/ico_moneda.png') }}"> UF <span class="value" v-html="moneyFormat(project.data.rent)">0</span></span>
            <a href="#" :class="'button is-outlined ' + membershipAgentButtom(project.agent.membership_name)">Contactar</a>
        </div>
    </div>
</div>

<input type="hidden" value="{{ asset('images') }}" id="images-dir">
@endsection

@section('styles')
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.css">
<link rel="stylesheet" href="{{ asset('css/common-details.css') }}">
<link rel="stylesheet" href="{{ asset('css/agents-details.css') }}">
<link rel="stylesheet" href="{{ asset('css/components/map-details.css') }}">
<link rel="stylesheet" href="{{ asset('css/components/schedule.css') }}">
<link rel="stylesheet" href="{{ asset('css/components/banner-gallery.css') }}">
@endsection

@section('scripts')
<script type="text/javascript" src="{{ asset('js/agents-details.js') }}"></script>
@endsection
