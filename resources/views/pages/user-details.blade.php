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
                                    <h2 class="subtitle">Propiedades Registradas</h2>
                                </div>
                            </div>
                            <div class="columns is-multiline property-list">
                                <div class="column is-6" v-for="property in user.data.properties">
                                    <property v-bind="property"></property>
                                </div>
                            </div>
                            <div class="column"  v-if="user.data.properties && user.data.properties.length == 0">
                                <p>No se encuentran propiedades registradas</p>
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

@section('scripts')
<script type="text/javascript" src="{{ asset('js/user-details.js') }}"></script>
@endsection