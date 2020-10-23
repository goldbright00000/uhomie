@extends('layouts.app')

@section('header')
    @include('layouts.toolbar', ['active'=>'arrendatario', 'user' => $user])
@endsection

@section('content')
    
    <div class="container" @if( $user->show_welcome || !$user->isVerified() ) style="display:none" @endif id="panel-principal">

        <section class="section profiles" id="profiles">
            <vue-progress-bar></vue-progress-bar>
            <div class="pageloader has-text-centered" v-if="loading">
                <img src="{{ asset('images') }}/icons/gif-uhomie.gif"/>
            </div>
            <div class="columns main" v-if="!loading">


                <section class="column ihumie-menu is-one-third">                   {{-- MENU --}}
                    <menus v-bind:menus="menus" @setmenuindex="setmenuindex($event)"></menus>
                </section> {{-- END MENU --}}


                <section class="column">
                    <div :class="!loading ? 'profile-scene' : ''">

                        {{-- PROFILES --}}


                    <!--<tenant-profile :filters="filters"  :info="info"  v-bind:curr_menu="menuIndex" :menu_id="0" class="content" v-bind:class="(menuIndex==0)?'':'is-hidden'"></tenant-profile>
                    <tenant-postulations    v-bind:curr_menu="menuIndex" :menu_id="1" class="content" v-bind:class="(menuIndex==1)?'':'is-hidden'"></tenant-postulations>
                    <tenant-holdings        v-bind:curr_menu="menuIndex" :menu_id="2" class="content" v-bind:class="(menuIndex==2)?'':'is-hidden'"></tenant-holdings>
                    <tenant-my-form :filters="filters"  :info="info"    v-bind:curr_menu="menuIndex" :menu_id="3" class="content" v-bind:class="(menuIndex==3)?'':'is-hidden'"></tenant-my-form>
                    <tenant-membership      v-bind:curr_menu="menuIndex" :info="info.membership_data" :menu_id="4" class="content" v-bind:class="(menuIndex==4)?'':'is-hidden'"></tenant-membership>
                    <tenant-contracts       v-bind:curr_menu="menuIndex" :menu_id="5" class="content" v-bind:class="(menuIndex==5)?'':'is-hidden'"></tenant-contracts>
                    <tenant-messages        v-bind:curr_menu="menuIndex" :menu_id="6" class="content" v-bind:class="(menuIndex==6)?'':'is-hidden'" :user-id="{{ auth()->id() }}" ></tenant-messages>
                    <tenant-interesting     v-bind:curr_menu="menuIndex" :menu_id="7" class="content" v-bind:class="(menuIndex==7)?'':'is-hidden'"></tenant-interesting>
                    <tenant-suggested       v-bind:curr_menu="menuIndex" :menu_id="8" class="content" v-bind:class="(menuIndex==8)?'':'is-hidden'"></tenant-suggested>
                    <tenant-config  :filters="filters"  :info="info"    v-bind:curr_menu="menuIndex" :menu_id="9" class="content" v-bind:class="(menuIndex==9)?'':'is-hidden'"></tenant-config>
                    -->
                    @include('profiles.router-view')

                    {{-- SAMPLE --}}

                    </div>
                </section>
            </div>
        </section>
        
    </div>
    
    <style>
        .numero-grande-celeste{
            font-size: 4rem;
            color: rgb(0, 181, 255);
            font-weight: bold;
        }
        .div-vacio{
            border-bottom: 3px solid #ffd900;
            width: 60px;
            height: 20px;
            margin: 0 auto;
        }
    </style>
    @if($user->show_welcome || !$user->isVerified() )
    <div id="panel-welcome">
        <img src="/images/welcome_banner.jpg" style="width: 100%, max-height: 1vh">
        <nav  class="columns is-centered" style="margin-top: -30px;background: #fafafa;">
            <div class="column has-text-centered">
                <img src="/images/flecha-abajo.png" >
            </div>
            
        </nav>
        <nav  style="padding-top: 20px;padding-bottom: 30px;background: #fafafa;" class="columns is-centered">
            <div class="column has-text-centered" style="max-width: 500px; font-weight: bold;padding-left: 25px; padding-right: 25px;">
                <span>
                    Nos alegra que seas parte de la comunidad de uHomie, estamos felices de tenerte con nosotros.
                </span>
                <br>
                <span>
                    A continuación te pedimos puedas seguir estos pasos para avanzar en la publicación de tu primera propiedad (si eres arrendador) o si eres arrendatario (postulación a todas las propiedades).
                </span>
            </div>
        </nav>
        <nav style="background-image: url('/images/trama-diagonales.png'); background-size:cover;padding-bottom:50px;" class="columns is-centered">
            <div class="column has-text-centered" style="max-width: 400px;padding: 0px 40px;">
                <span class="numero-grande-celeste">1</span>
                <strong>Ahora debes ingresar a tu perfil y adjuntar tus fotos del carnet de identidad</strong>
                <div class="div-vacio"></div>
            </div>
            <div class="column has-text-centered" style="max-width: 400px;padding: 0px 40px;">
                <span class="numero-grande-celeste">2</span>
                <strong>Verifica tu identidad desde la configuración de tu cuenta</strong>
                <div class="div-vacio"></div>
            </div>
        </nav>
        <nav  style="padding-top: 50px;padding-bottom: 50px;background: #fafafa;" class="columns is-centered">
            <div class="column has-text-centered" style="max-width: 500px; font-weight: bold;padding-left: 25px; padding-right: 25px;">
                <span style="margin-bottom: 30px;">
                    Estos dos simples pasos nos permiten asegurar la autenticidad de tu perfil y asegurarnos de que nuestra comunidad sólo cuenta con usuarios verificados.
                </span>
                <br>
                <img style="margin-bottom: 30px;" src="/images/listo.png">
                <br><br>
                <button class="button is-outlined is-primary" id="buttonCloseWelcome">EMPEZAR</button>
            </div>
        </nav>
    </div>
    @endif
    
    </section>
    </div>
    
    <input type="hidden" value="{{ asset('images') }}" id="images-dir">
    <input type="hidden" value="{{ @csrf_token() }}" id="_token">
@endsection

@section('flow-styles')
    <link rel="stylesheet" href="{{ asset('css/profiles.css') }}">
@endsection

@section('styles')
    <style>
        .online {
          height: 8px;
          width: 8px;
          background-color: green;
          border-radius: 50%;
          display: inline-block;
        }
        .offline {
          height: 8px;
          width: 8px;
          background-color: grey;
          border-radius: 50%;
          display: inline-block;
        }
    </style>
@endsection

@section('footer')
    {{-- Keep empty --}}
@endsection

@section('flow-scripts')
    
    <script>
        @php
        $all=\App\Country::all();
        $countries=[];
        foreach ($all as $country){
            $countries[$country->id]=$country->name;
        }

        $all=\App\CivilStatus::all();
        $cstts=[];
        foreach ($all as $cs){
            $cstts[$cs->id]=$cs->name;
        }


        $doctype=["RUT"=>"RUT (RESIDENCIA PERMANENTE)",
        "PASSPORT"=>"PASAPORTE",
        "RUT_PROVISIONAL"=>"RUT (PROVISORIO)"]

        @endphp

    </script>
    <script>
        $('#buttonCloseWelcome').click(function(){
            $('#panel-welcome').hide();
            $('#panel-principal').fadeIn();
            document.body.scrollTop = 0;
            document.documentElement.scrollTop = 0;
            $.ajax({
                url: '/toogle_show_welcome',
                method: 'get',
                headers: {'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')},
                success: function(response){
                    console.log('Bienvenido toggled');
                }
            });
        });
    </script>
    @if( $user->long_stay )
    <script src="{{ asset('js/tenant.js') }}"></script>
    @else
    <script src="{{ asset('js/tenant_shortstay.js') }}"></script>
    @endif
    
    <script src="{{asset('js/recordrtc/RecordRTC.js')}}"></script>
    <script>
    axios.interceptors.request.use(function(config) {
      // Do something before request is sent
      var body = $("body");
      body.addClass("loading");
      $('#mlmlml').css('background', 'rgba( 255, 255, 255, .8 ) url("/images/spinner_100px.svg") 50% 50% no-repeat;');
      console.log('Start Ajax Call');
      return config;
    }, function(error) {
      // Do something with request error
      var body = $("body");
      body.removeClass("loading");
      console.log('Error');
      return Promise.reject(error);
    });

    axios.interceptors.response.use(function(response) {
      // Do something with response data
      var body = $("body");
      body.removeClass("loading");
      console.log('Done with Ajax call');
      return response;
    }, function(error) {
      // Do something with response error
      var body = $("body");
      body.removeClass("loading");
      console.log('Error fetching the data');
      return Promise.reject(error);
    });
    </script>
    
@endsection
@section('styles')
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.css">
<link rel="stylesheet" href="{{ asset('css/common-details.css') }}">
<link rel="stylesheet" href="{{ asset('css/explore-details.css') }}">

@endsection