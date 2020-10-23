@extends('layouts.flujo-base', ['navTitle' => '<span class="bold">Paso 1.2:</span> Tu dirección'])
@section('content')
<div class="container" id="agent-first-step-two">
    <section class="section main-section">
        <div class="columns">
            <div class="column is-7 flow-form">
              <div class="div">
                  <form method="POST" action="" enctype="multipart/form-data" id="registration-form">

                    @include('components.users.common-forms.location-data.form2', [
                      'cities' => $cities,
                			'entity' => $user,
                			'back_url' => route('users.agents.first-step.one'),
                      'company' => false
                    ])
              </div>
            </div>
            <div class="column side-info">
                <div class="info">
                    <img src="{{ asset('images/icono-atencion.png') }}">
                    <p>Esta información se mostrará en UHOMIE a tus pontenciales clientes.</p>
                    <div class="border"></div>
                </div>
            </div>
        </div>
    </section>
</div>
@include('components.users.common-forms.save-button.modal')
@endsection
@section('scripts')
    <script src="{{asset('js/location-data.js')}}"></script>
    <script src="{{asset('js/agent-forms/first-step-four.js')}}"></script>
    <script type="text/javascript">
    //GOOGLE MAPS

    var _lat = document.getElementById("latitude").value;
    var _lng = document.getElementById("longitude").value;
    function initMap() {
        zoom = 15;
      if( _lat == "" && _lng == "" ){
          _lat = -33.8688;
          _lng = 151.2195;
      }

      $('#address').on('keyup', function(){
        _lat = "";
        _lng = "";
        document.getElementById("btn-submit").disabled = true;
      });

      if( _lat != -33.8688 && _lng != 151.2195){
        document.getElementById("btn-submit").disabled = false;
      }

      _lat = parseFloat(_lat);
      _lng = parseFloat(_lng);

      var map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: _lat, lng: _lng },
          zoom: zoom,
            mapTypeControl: false
      });

      var input = document.getElementById('address');
      //restriccion
      var options = {
        types: ['address'],
        componentRestrictions: {country: 'cl'},
        zoom : 15
      };
      // para restringir (input,options)
      var autocomplete = new google.maps.places.Autocomplete(input,options);
      var infowindow = new google.maps.InfoWindow();
      var infowindowContent = document.getElementById('infowindow-content');

      infowindow.setContent(infowindowContent);
      var marker = new google.maps.Marker({
          map: map,
          draggable: true,
          animation: google.maps.Animation.DROP,
          position: new google.maps.LatLng(_lat, _lng),
          zoom : 20
      });

      google.maps.event.addListener(marker, 'dragend', function(){
          updatePosition(marker.getPosition());
      });

      autocomplete.addListener('place_changed', function() {
          // infowindow.close();
          marker.setVisible(true);
          var place = autocomplete.getPlace();
          if (!place.geometry) {
              window.alert("No hay detalles disponibles para la entrada: '" + place.name + "'");
              return;
          }

          if (place.geometry.viewport) {
              map.fitBounds(place.geometry.viewport);
          } else {
              map.setCenter(place.geometry.location);
              map.setZoom(20);  // Why 17? Because it looks good.
          }
          marker.setPosition(place.geometry.location);
          marker.setVisible(true);

          var address = '';
          if (place.address_components) {
              address = [
                  (place.address_components[0] && place.address_components[0].short_name || ''),
                  (place.address_components[1] && place.address_components[1].short_name || ''),
                  (place.address_components[2] && place.address_components[2].short_name || '')
              ].join(' ');
          }
          infowindow.open(map, marker);
          var place = autocomplete.getPlace();
          var lat = place.geometry.location.lat();
          var lng = place.geometry.location.lng();
          var placeId = place.place_id;
          document.getElementById("latitude").value = lat;
          document.getElementById("btn-submit").disabled = false;
          document.getElementById("address_details").disabled = false;
          document.getElementById("longitude").value = lng;
          console.log(place)

      });

    }

    function updatePosition(latLng) {
     jQuery('#latitude').val(latLng.lat());
     jQuery('#longitude').val(latLng.lng());
    }

    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDTKRiKb5oaS7Z13QezK4K0V9XQI99UHiI&libraries=places&callback=initMap"
          async defer></script>
          <script type="text/javascript">
      /*$(function(){
        if ( $('input[name=address]').val() ) {

          document.getElementById("btn-submit").disabled = false;
          document.getElementById("address_details").disabled = false;
        }
      })

      $('input[name=address]').on('click',function(){
          document.getElementById("btn-submit").disabled = true;
          document.getElementById("address_details").disabled = true;
      })*/
    </script>
@endsection
