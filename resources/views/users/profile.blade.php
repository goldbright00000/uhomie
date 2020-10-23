@php
	$header = ["title"=>" PERFIL:  " , "subtitle" => "  Revisa tus datos"];
@endphp
@extends('layouts.flujo-base')
@section('custom-css')
@endsection
@section('content')
	<div class='row '>
		<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12 '>
			<h2>Datos USUARIO</h2>
		  	<dt>Nombre: </dt>	<dd>{{ $user->firstname }}</dd>
			<hr><dt>Apellido: </dt>	<dd>{{ $user->lastname }}</dd><hr>
			<dt>Doc: </dt>	<dd>{{ $user->document_type }}</dd><hr>
			<dt>Número: </dt>	<dd>{{ $user->document_number }}</dd><hr>
			<dt>Fecha de nacimiento: </dt>	<dd>{{ $user->birthdate }}</dd><hr>
			<dt>direccion: </dt>	<dd>{{ $user->address }}</dd><hr>
			<dt>direccion exacta: </dt>	<dd>{{ $user->address_details }}</dd><hr>
			<dt>email: </dt>	<dd>{{ $user->email }} .. {{ $user->mail_verified  ? 'verificado' : ''}}</dd><hr>
			<dt>telefono: </dt>	<dd>{{ $user->phone }} .. {{ $user->phone_verified  ? 'verificado' : ''}}</dd><hr>
			<dt>Suscripciones: </dt>
			@foreach ($user->memberships as $suscription)
				@if ($suscription->enabled)
					<dd>{{ $suscription->name }} de {{ $suscription->role->name }}</dd>
					<dd>FECHA DE COMPRA {{ $suscription->purchased_at }} ... FECHA DE VENCIMIENTO {{ $suscription->expires_at }}</dd>
				@endif
			@endforeach

			<hr>
			<dt>Preferencias de arriendo: </dt>	<dd>{{ $user->tenanting_preferences }}</dd><hr>
			<dt>Comodidades que busca:</dt>
			@foreach ($user->amenities as $amenity)
				<dd>{{ $amenity->name }} ... {{ $amenity->type ? 'de Unidad' : 'de Condominio' }}</dd>

			@endforeach<hr>
			<dt>Archivos: </dt>
			<dd>{{ $user->files }}</dd>
<hr>
			<dt>Datos de trabajo: </dt>	<dd>{{ $user->employment_details }}</dd>
		</div>


	</div>
@endsection
@section('custom-js')
@endsection
