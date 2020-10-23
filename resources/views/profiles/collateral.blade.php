@extends('layouts.app')

@section('header')
    @include('layouts.toolbar', ['active'=>'collateral', 'user' => $user])
@endsection

@section('content')
    <div class="container">
        <section class="section profiles" id="profiles">
            <vue-progress-bar></vue-progress-bar>
            <div class="pageloader has-text-centered" v-if="loading">
                <img src="{{ asset('images') }}/icons/gif-uhomie.gif"/>
            </div>
            <div class="columns main" v-if="!loading">
                <section class="column ihumie-menu is-one-third">
                    {{-- MENU --}}
                    <menus v-bind:menus="menus" @setmenuindex="setmenuindex($event)"></menus>
                    {{-- END MENU --}}
                </section>

                <section class="column">
                    <div :class="!loading ? 'profile-scene' : ''">
                        @include('profiles.router-view')
                    </div>
                </section>
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
    <script src="{{ asset('js/collaterals.js') }}"></script>
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
