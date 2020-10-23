@extends('layouts.app')

@section('header')
    @include('layouts.toolbar', ['active'=>'agente', 'user' => $user])
@endsection

@section('content')
    <div class="container">
        <section class="section profiles" id="profiles">
            <vue-progress-bar></vue-progress-bar>
            <div class="pageloader has-text-centered" v-if="loading">
                <img src="{{ asset('images') }}/icons/gif-uhomie.gif"/>
            </div>
            <div class="columns main" v-if="!loading">
                <section class="column ihumie-menu is-4">                   {{-- MENU --}}
                    <menus v-bind:menus="menus" @setmenuindex="setmenuindex($event)"></menus>
                </section> {{-- END MENU --}}
                <section class="column is-8">
                    <div :class="!loading ? 'profile-scene' : ''">
                        @include('profiles.router-view')
                    </div>
                </section>
            </div>
        </section>        
    </div>
    <input type="hidden" value="{{ asset('images') }}" id="images-dir">
    <input type="hidden" value="{{ @csrf_token() }}" id="_token">
@endsection

@section('flow-styles')
    <link rel="stylesheet" href="{{ asset('css/profiles.css') }}">
@endsection

@section('footer')
    {{-- Keep empty --}}
@endsection

@section('flow-scripts')
    <script src="{{ asset('js/agents.js') }}"></script>    
@endsection