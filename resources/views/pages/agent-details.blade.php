@extends('layouts.app')

@section('header')
@include('layouts.header', ['isSolid' => true])
@endsection

@section('content')

<div id="user-details">
    <div class="container">
        <div class="columns">
            <div class="column is-4">
                <div class="card">
                    <div class="card-content">
                        <div class="columns">
                            <div class="column is-half is-offset-one-quarter">
                                <figure class="image is-square">
                                    <img class="is-rounded" :src="user.data.imagen ? user.data.imagen : imagesDir + '/husky.png'" :alt="user.data.name+'-photo'">
                                </figure>
                            </div>
                        </div>
                        <hr>
                        <div>
                            <img style="display:block; margin:auto;" class="membership-logo" :src="imagesDir + membershipOwnerLogo(user.data.membership)">
                        </div>
                        <hr>
                        <div class="columns">
                            <div class="column">
                                <div>
                                    <i v-if="user.data.verified == true" class="fa fa-check has-text-success"></i>
                                    <i v-else class="fa fa-times has-text-danger"></i>
                                    <span>Verificación de identidad</span>
                                </div>
                                <div>
                                    <i v-if="user.data.phone == true"class="fa fa-check has-text-success"></i>
                                    <i v-else class="fa fa-times has-text-danger"></i>
                                    <span>Verificación de Telefono</span>
                                </div>
                                <div>
                                    <i v-if="user.data.email == true"class="fa fa-check has-text-success"></i>
                                    <i v-else class="fa fa-times has-text-danger"></i>
                                    <span>Verificación de Email</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="column is-8">
                <div class="card">
                    <div class="card-content">
                        <div>
                            <h1 class="title">
                                Hola mi nombre es <span v-html="user.data.name"></span>
                            </h1>
                            <h2 class="subtitle">
                                Se registro el <span v-html="moment(user.data.created_at).locale('es').format('LL')"></span>
                            </h2>
                        </div>
                        <hr>
                        <div>
                            <p>
                                <i><img :src="imagesDir + '/icono-direccion.png'"/></i>
                                <span v-html="'Vive en ' + user.data.address"></span>
                            </p>
                        </div>
                        <hr>
                        <div>
                            <div class="columns">
                                <div class="column">
                                    <h2 class="subtitle">Datos de la Compañia</h2>
                                </div>
                            </div>
                            <div class="columns">
                                <div class="column is-two-fifths" v-if="company.logo != null">
                                    <img :src="company.logo">
                                </div>
                                <div class="column">
                                    <p>
                                        <i><img :src="imagesDir + '/icono-empresa.png'"/></i>
                                        <span v-html="company.name != null ? company.name : 'No Especifica'"></span>
                                    </p>
                                    <p>
                                        <i><img :src="imagesDir + '/icono-direccion.png'"/></i>
                                        <span v-html="'Localizado en ' + company.address"></span>
                                    </p>
                                    <p>
                                        <i><img style="width: 24px; height: 24px;" :src="imagesDir + '/mail.png'"/></i>
                                        <span v-html="company.email != null ? company.email : 'No Especifica'"></span>
                                    </p>
                                    <p>
                                        <i><img :src="imagesDir + '/icono_numero.png'"/></i>
                                        <span v-html="company.phone != null ? company.phone : 'No Especifica'"></span>
                                    </p>
                                </div>
                            </div>
                            <div class="columns">
                                <div class="column">
                                    <p>
                                        <i><img :src="imagesDir + '/icono-doc.png'"/></i>
                                        <span v-html="user.data.company.description != null ? user.data.company.description : 'No Especifica'"></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div>
                            <div class="columns">
                                <div class="column">
                                    <h2 class="subtitle">Propiedades registradas para arrendar</h2>
                                </div>
                            </div>
                            <div class="columns is-multiline property-list">
                                <div class="column is-6" v-for="property in user.data.properties">
                                    <property v-bind="property"></property>
                                </div>
                                <div class="column" v-if="user.data.properties && user.data.properties.length == 0">
                                    <p>No se encuentran propiedades registradas</p>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div>
                            <div class="columns">
                                <div class="column">
                                    <h2 class="subtitle">Propiedades registradas para vender</h2>
                                </div>
                            </div>
                            <div class="columns is-multiline property-list">
                                <div class="column is-6" v-for="project in user.data.projects">
                                    <agent v-bind="project"></agent>
                                </div>
                                <div class="column" v-if="user.data.projects && user.data.projects.length == 0">
                                    <p>No se encuentran proyectos registrados</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>
<input type="hidden" value="{{ asset('images') }}" id="images-dir">

@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('css/components/map-details.css') }}">
@endsection

@section('scripts')
<script type="text/javascript" src="{{ asset('js/agent-details.js') }}"></script>
@endsection