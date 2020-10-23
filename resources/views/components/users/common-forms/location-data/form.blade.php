<div class="container" id="first-step-second">
    <section class="section main-section">
        <div class="columns">
            <div class="column is-7 flow-form">
                <div class="div">
                    <form method="POST" action="{{ route($form_route) }}" enctype="multipart/form-data" id="registration-form">
						            {{ csrf_field() }}
                        <input type="hidden" name="latitude" value="{{ $user->latitude }}" id="latitude">
                        <input type="hidden" name="longitude" value="{{ $user->longitude }}" id="longitude">
                        <div class="field">
                            <div class="label-field">
                                <img src="{{ asset('images/icono_ciudad.png') }}">
                                <span>Ciudad</span>
                            </div>
                            <div class="select">
                                <select name="city" id="city" required>
                                    <option selected disabled>Seleccione</option>
	                                    @foreach($cities as $city)
	                                    	<option value="{{$city->id}}" @if($user && $user->city && $user->city->id == $city->id) selected="selected" @endif>{{$city->name}}</option>
	                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="field">
                            <div class="label-field">
                                <img src="{{ asset('images/icono-direccion.png') }}">
                                <span>Dirección</span>
                            </div>
                            <input type="text" class="input" name="address" id="address" value="{{$user ? $user->address : ''}}" autocomplete="off" required>
                        </div>
                        <div class="field">
                            <div class="label-field">
                                <img src="{{ asset('images/icono-casa.png') }}">
                                <span>Casa / Apto / Piso</span>
                            </div>
                            <input type="text" autocomplete="off" class="input" name="address_details" value="{{$user ? $user->address_details : ''}}" autocomplete="off" required>
                        </div>
                        <div id="map"></div>
                </div>
                <div class="form-footer">
                    <a href="{{$back_url}}" class="button is-outlined">Atrás</a>
                    <img src="{{ asset('images/icono_guardar.png') }}" id="save-button-icon">
                    <input type="submit" class="button is-outlined is-primary" value="Continuar" form="registration-form">
                </div>
            </div>
            <div class="column side-info">
                <div class="info">
                    <img src="{{ asset('images/icono-atencion.png') }}">
                    <p>Esta información es importante, para confeccionar el contrato digital que firmarás con tu próximo arrendador.</p>
                    <div class="border"></div>
                </div>
            </div>
        </div>
    </section>
</div>
@include('components.users.common-forms.save-button.modal')
@section('scripts')
    <script src="{{asset('js/location-data.js')}}"></script>
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
          infowindow.close();
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
          document.getElementById("longitude").value = lng;
      });

    }

    function updatePosition(latLng) {
     jQuery('#latitude').val(latLng.lat());
     jQuery('#longitude').val(latLng.lng());
    }

    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCMgpmFGUn8m5P2bjASBTcH3cVYxqbIs-4&libraries=places&callback=initMap"
          async defer></script>
@endsection
