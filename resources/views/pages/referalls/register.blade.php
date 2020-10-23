@extends('layouts.app')

@section('header')
<section class="hero is-fullheight register" id="referalls-header">
  <div class="hero-head is-light">
    @parent
  </div>
  <div class="hero-body">
    <div class="container">
      <div class="columns is-tablet">
        <div class="column is-5-desktop is-6-tablet is-offset-1 form-section">
          <p>
          	¿ Conoces a alguien que tenga una propiedad para alquilar ? Recomiéndalo !<br/>
          	Obtienes $10.000 cuando se publica la propiedad, y un 3% de la comisión de arriendo.
          </p>
          <hr/>
          <form action="{{ route('referalls.register') }}" method="POST" id="form-register" autocomplete="off">
            {{ csrf_field() }}
            <label class="label">Dirección de la propiedad: calle y número</label>
						<input class="input" type="text" placeholder="Ejemplo: Av. O'Higgins" name="address" required>
						<input class="input" type="text" placeholder="Número" name="address_number">

						<label class="label" style="margin-top: 2rem;">Complemento</label>
						<input class="input" type="text" placeholder="Ejemplo: Apt. 42" name="address_extra">

						<label class="label" style="margin-top: 2rem;">Contacto del propietario</label>
						<input class="input" type="text" placeholder="Nombre del propietario" name="contact_name" required>
						<input class="input" type="email" placeholder="Email del propietario" name="contact_email" required>
						<input class="input" type="tel" placeholder="Teléfono" name="contact_tel" required style="margin-right: 2rem;">
						
						<input class="input" type="email" placeholder="Información adicional (opcional)" name="more_info">

            <div class="has-text-centered mt-2">
            	<button type="submit" class="button is-primary is-outlined">
	            	REFERIR
	            </button>
            </div>

            <div class="has-text-centered mt-2">
            	<img src="{{ asset('images/referalls/referalls.png') }}">
	            <div class="link-ref">
	            		También comparte tu enlace y gana cuando un propietario registre una propiedad.

	            </div>
            </div>            
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection

@section('content')

@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('css/referalls.css') }}">
@endsection

@section('scripts')
<script src="{{ asset('js/referalls.js') }}"></script>
@endsection
