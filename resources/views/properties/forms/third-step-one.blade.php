@extends('layouts.flujo-base',['navTitle' => '<span class="bold">Paso 3.1:</span> Arrendatario', 'close' => route('profile.owner')])
@section('custom-css')

@endsection
@section('content')
	<input type="hidden" name="property_type" id="property_type" value="{{$property_type->id}}"/>
	<input type="hidden" name="type" id="type" value="{{$type}}"/>
	<input type="hidden" name="type_stay" id="type_stay" value="{{$property->type_stay}}"/>
	<div class="container" id="own-fourth-step-one">
	    <section class="section main-section">
	        <div class="columns">
	            <div class="column is-7 flow-form">
	                <div class="div">
	                    <form method="POST" action="{{-- route($form_route) --}}" enctype="multipart/form-data" id="registration-form">
						  {{ csrf_field() }}
						  	<div class="form-resident" style="display: none;">
								<h1 class="form-title">Tu propiedad es apta para <br> que tipo de arrendatario:</h1>
								@foreach($properties_for as $property_for)
									<label class="checkbox square " >
										<input type="checkbox" name="property_for[]" {{ $property->propertiesFor->contains($property_for->id) ? 'checked' : '' }} value="{{ $property_for->id }}"  class="check_amenities">
										{{ $property_for->name }} <span class="fa fa-plus"></span>
									</label>
								@endforeach
								<h1 class="form-title"> </h1>
								<div class="field">
									<div class="label-field">
										<img src="{{ asset('images/icono-mascota.png') }}">
										<span>¿Aceptas Mascotas?</span>
									</div>
									<div class="control">
										<label class="radio">
											<input type="radio" name="pet_preference" value="dog" {{ $property && $property->pet_preference == 'dog' ? 'checked' : '' }} >
											<img src="{{ asset('images/icono-perro.png') }}">
										</label>
										<label class="radio">
											<input type="radio" name="pet_preference" value="cat" {{ $property && $property->pet_preference == 'cat' ? 'checked' : '' }} >
											<img src="{{ asset('images/icono-gato.png') }}">
										</label>
										<label class="radio">
											<input type="radio" name="pet_preference" value="other" {{ $property && $property->pet_preference == 'other' ? 'checked' : '' }} >
											Otro
										</label>
										<label class="radio">
											<input type="radio" name="pet_preference" value="no" {{ $property && $property->pet_preference == 'no' ? 'checked' : is_null($property->pet_preference) ? 'checked' : '' }}>
											No
										</label>
									</div>
								</div>
								<div class="field">
									<div class="label-field">
										<img src="{{ asset('images/icono-fumar.png') }}">
										<span>¿Aceptas que fumen?</span>
									</div>
									<div class="control">
										<label class="radio">
											<input type="radio" name="smoking_allowed" value="1" {{ $property && $property->smoking_allowed == '1' ? 'checked' : is_null($property->smoking_allowed) ? 'checked' : '' }} >
											Si
										</label>
										<label class="radio">
											<input type="radio" name="smoking_allowed" value="0" {{ $property && $property->smoking_allowed == '0' ? 'checked' : '' }} >
											No
										</label>
									</div>
								</div>
								<!--<div class="field">
									<div class="label-field">
										<img src="{{ asset('images/icono_chile.png') }}">
										<span>¿Aceptas Nacionales Chilenos con RUT?</span>
									</div>
									<div class="control">
										<label class="radio">
											<input type="radio" name="nationals_with_rut" value="1" {{ $property && $property->nationals_with_rut == '1' ? 'checked' : is_null($property->nationals_with_rut) ? 'checked' : '' }} >
											Si
										</label>
										<label class="radio">
											<input type="radio" name="nationals_with_rut" value="0" {{ $property && $property->nationals_with_rut == '0' ? 'checked' :  '' }} >
											No
										</label>
									</div>
								</div>
								<div class="field">
									<div class="label-field">
										<img src="{{ asset('images/icono_mundo.png') }}">
										<span>¿Aceptas Extranjeros con RUT?</span>
									</div>
									<div class="control">
										<label class="radio">
											<input type="radio" name="foreigners_with_rut" value="1" {{ $property && $property->foreigners_with_rut == '1' ? 'checked' : is_null($property->foreigners_with_rut) ? 'checked' : '' }} >
											Si
										</label>
										<label class="radio">
											<input type="radio" name="foreigners_with_rut" value="0" {{ $property && $property->foreigners_with_rut == '0' ? 'checked' : '' }} >
											No
										</label>
									</div>
								</div>
								<div class="field">
									<div class="label-field">
										<img src="{{ asset('images/icono_mundo.png') }}">
										<span>¿Aceptas Extranjeros con pasaporte?</span>
									</div>
									<div class="control">
										<label class="radio">
											<input type="radio" name="foreigners_with_passport" value="1" {{ $property && $property->foreigners_with_passport == '1' ? 'checked' : is_null($property->foreigners_with_passport) ? 'checked' : '' }} >
											Si
										</label>
										<label class="radio">
											<input type="radio" name="foreigners_with_passport" value="0" {{ $property && $property->foreigners_with_passport == '0' ? 'checked' : '' }} >
											No
										</label>
									</div>
								</div>-->
							</div>
							<div class="form-office" style="display: none;">
								<h1 class="form-title">Tu propiedad es apta para <br> que tipo de negocios o actividades<br> economicas:</h1>
								@foreach($properties_for as $property_for)
									<label class="checkbox square " >
										<input type="checkbox" name="property_for[]" {{ $property->propertiesFor->contains($property_for->id) ? 'checked' : '' }} value="{{ $property_for->id }}"  class="check_work">
										{{ $property_for->name }} <span class="fa fa-plus"></span>
									</label>
								@endforeach
							</div>
							<!--<div class="field">
								<div class="label-field">
									<img src="{{ asset('images/icono_mundo.png') }}">
									<span>¿Aceptas Extranjeros con pasaporte?</span>
								</div>
								<div class="control">
									<label class="radio">
										<input type="radio" name="foreigners_with_passport" value="1" {{ $property && $property->foreigners_with_passport == '1' ? 'checked' : is_null($property->foreigners_with_passport) ? 'checked' : '' }} >
										Si
									</label>
									<label class="radio">
										<input type="radio" name="foreigners_with_passport" value="0" {{ $property && $property->foreigners_with_passport == '0' ? 'checked' : '' }} >
										No
									</label>
								</div>
							</div>-->
							@if(false/*$property->type_stay == 'SHORT_STAY'*/)
							<div class="field">
								<div class="label-field">
									<!--<img src="{{ asset('images/icono-fumar.png') }}">-->
									<span>¿Adecuado para niños de 2 a 12 años?</span>
								</div>
								<div class="control">
									<label class="radio">
										<input type="radio" name="allow_small_child" value="1" {{ $property && $property->allow_small_child == '1' ? 'checked' : is_null($property->allow_small_child) ? 'checked' : '' }} >
										Si
									</label>
									<label class="radio">
										<input type="radio" name="allow_small_child" value="0" {{ $property && $property->allow_small_child == '0' ? 'checked' : '' }} >
										No
									</label>
								</div>
							</div>
							<div class="field">
								<div class="label-field">
									<!--<img src="{{ asset('images/icono-fumar.png') }}">-->
									<span>¿Adecuado para bebes hasta 2 años?</span>
								</div>
								<div class="control">
									<label class="radio">
										<input type="radio" name="allow_baby" value="1" {{ $property && $property->allow_baby == '1' ? 'checked' : is_null($property->allow_baby) ? 'checked' : '' }} >
										Si
									</label>
									<label class="radio">
										<input type="radio" name="allow_baby" value="0" {{ $property && $property->allow_baby == '0' ? 'checked' : '' }} >
										No
									</label>
								</div>
							</div>
							<div class="field">
								<div class="label-field">
									<!--<img src="{{ asset('images/icono-fumar.png') }}">-->
									<span>¿Se permiten fiestas o eventos?</span>
								</div>
								<div class="control">
									<label class="radio">
										<input type="radio" name="allow_parties" value="1" {{ $property && $property->allow_parties == '1' ? 'checked' : is_null($property->allow_parties) ? 'checked' : '' }} >
										Si
									</label>
									<label class="radio">
										<input type="radio" name="allow_parties" value="0" {{ $property && $property->allow_parties == '0' ? 'checked' : '' }} >
										No
									</label>
								</div>
							</div>
							<h1 class="form-title" style="font-weight: 600;">Detalles de tu alojamiento que <br> los huéspedes deben conocer:</h1>
							<div class="field">
								<div class="label-field">
									<!--<img src="{{ asset('images/icono-fumar.png') }}">-->
									<span>Es necesario utilizar escaleras</span>
								</div>
								<div class="control">
									<label class="radio">
										<input type="radio" name="use_stairs" value="1" {{ $property && $property->use_stairs == '1' ? 'checked' : is_null($property->use_stairs) ? 'checked' : '' }} >
										Si
									</label>
									<label class="radio">
										<input type="radio" name="use_stairs" value="0" {{ $property && $property->use_stairs == '0' ? 'checked' : '' }} >
										No
									</label>
								</div>
							</div>
							<div class="field">
								<div class="label-field">
									<!--<img src="{{ asset('images/icono-fumar.png') }}">-->
									<span>Puede haber ruido</span>
								</div>
								<div class="control">
									<label class="radio">
										<input type="radio" name="there_could_be_noise" value="1" {{ $property && $property->there_could_be_noise == '1' ? 'checked' : is_null($property->there_could_be_noise) ? 'checked' : '' }} >
										Si
									</label>
									<label class="radio">
										<input type="radio" name="there_could_be_noise" value="0" {{ $property && $property->there_could_be_noise == '0' ? 'checked' : '' }} >
										No
									</label>
								</div>
							</div>
							<div class="field">
								<div class="label-field">
									<!--<img src="{{ asset('images/icono-fumar.png') }}">-->
									<span>Hay zonas comunes que<br>se comparten con otros huéspedes</span>
								</div>
								<div class="control">
									<label class="radio">
										<input type="radio" name="common_zones" value="1" {{ $property && $property->common_zones == '1' ? 'checked' : is_null($property->common_zones) ? 'checked' : '' }} >
										Si
									</label>
									<label class="radio">
										<input type="radio" name="common_zones" value="0" {{ $property && $property->common_zones == '0' ? 'checked' : '' }} >
										No
									</label>
								</div>
							</div>
							<div class="field">
								<div class="label-field">
									<!--<img src="{{ asset('images/icono-fumar.png') }}">-->
									<span>Limitaciones de servicios</span>
								</div>
								<div class="control">
									<label class="radio">
										<input type="radio" name="services_limited" value="1" {{ $property && $property->services_limited == '1' ? 'checked' : is_null($property->services_limited) ? 'checked' : '' }} >
										Si
									</label>
									<label class="radio">
										<input type="radio" name="services_limited" value="0" {{ $property && $property->services_limited == '0' ? 'checked' : '' }} >
										No
									</label>
								</div>
							</div>
							<div class="field">
								<div class="label-field">
									<!--<img src="{{ asset('images/icono-fumar.png') }}">-->
									<span>Dispositivos de vigilancia o<br>de grabación en la vivienda</span>
								</div>
								<div class="control">
									<label class="radio">
										<input type="radio" name="survellaince_camera" value="1" {{ $property && $property->survellaince_camera == '1' ? 'checked' : is_null($property->survellaince_camera) ? 'checked' : '' }} >
										Si
									</label>
									<label class="radio">
										<input type="radio" name="survellaince_camera" value="0" {{ $property && $property->survellaince_camera == '0' ? 'checked' : '' }} >
										No
									</label>
								</div>
							</div>
							<div class="field">
								<div class="label-field">
									<!--<img src="{{ asset('images/icono-fumar.png') }}">-->
									<span>Armas en la vivienda</span>
								</div>
								<div class="control">
									<label class="radio">
										<input type="radio" name="weaponry" value="1" {{ $property && $property->weaponry == '1' ? 'checked' : is_null($property->weaponry) ? 'checked' : '' }} >
										Si
									</label>
									<label class="radio">
										<input type="radio" name="weaponry" value="0" {{ $property && $property->weaponry == '0' ? 'checked' : '' }} >
										No
									</label>
								</div>
							</div>
							<div class="field">
								<div class="label-field">
									<!--<img src="{{ asset('images/icono-fumar.png') }}">-->
									<span>Animales peligrosos en la vivienda</span>
								</div>
								<div class="control">
									<label class="radio">
										<input type="radio" name="dangerous_animals" value="1" {{ $property && $property->dangerous_animals == '1' ? 'checked' : is_null($property->dangerous_animals) ? 'checked' : '' }} >
										Si
									</label>
									<label class="radio">
										<input type="radio" name="dangerous_animals" value="0" {{ $property && $property->dangerous_animals == '0' ? 'checked' : '' }} >
										No
									</label>
								</div>
							</div>
							<div class="field">
								<div class="label-field">
									<!--<img src="{{ asset('images/icono-fumar.png') }}">-->
									<span>Hay mascotas en la propiedad</span>
								</div>
								<div class="control">
									<label class="radio">
										<input type="radio" name="pets_friendly" value="1" {{ $property && $property->pets_friendly == '1' ? 'checked' : is_null($property->pets_friendly) ? 'checked' : '' }} >
										Si
									</label>
									<label class="radio">
										<input type="radio" name="pets_friendly" value="0" {{ $property && $property->pets_friendly == '0' ? 'checked' : '' }} >
										No
									</label>
								</div>
							</div>
							@endif
	                    </form>
	                </div>
	                <div class="form-footer">
	                    <a href="{{route('properties.third-step',['id' => $property->id])}}" class="button is-outlined">Atrás</a>
	                    <img src="{{ asset('images/icono_guardar.png') }}" id="save-button-icon">
                     	<input type="submit" class="button is-outlined is-primary" value="Continuar" form="registration-form">
	                </div>
	            </div>
	            <div class="column side-info">
	                <div class="info">
	                    <img src="{{ asset('images/icono-atencion.png') }}">
	                    <p>Por lo general para residentes chilenos y extranjeros con RUT el arrendado exigirá entre 1 y 2 meses de adelanto.</p>
	                    <div class="border"></div>
	                </div>
	                <div class="info">
	                    <img src="{{ asset('images/icono-atencion.png') }}">
	                    <p>Para Extranjeros con pasaporte para aumentar tus probabilidades de arriendo se recomienda dar entre 3 a 4 meses por adelantado.</p>
	                    <div class="border"></div>
	                </div>
	                <div class="info">
	                    <img src="{{ asset('images/icono-atencion.png') }}">
	                    <p>Uhomie te recomienda 1 mes de garantía y entre 1 y 2 meses de adelanto.</p>
	                    <div class="border"></div>
	                </div>
	            </div>
	        </div>
	    </section>
	</div>


	<div class="modal modal-info">
	    <div class="modal-background"></div>
	    <div class="modal-content">
	      <div class="info-card">
	            <img src="{{ asset('images/clap.png') }}">
	          <span id="modal-title"></span>
	          <p id="modal-text"></p>
	      </div>
	      <a class="button is-primary is-inverted is-outlined btn-close" id="btn-next-page"></a>
	    </div>
	</div>
	@include('components.users.common-forms.save-button.modal')
@endsection

@section('scripts')
	<script src="{{ asset('js/property-forms/third-step-one.js') }}" charset="utf-8"></script>
@endsection
