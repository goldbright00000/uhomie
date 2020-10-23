@extends('layouts.app')

@section('header')
<section class="hero is-fullheight" id="error-header">
    <div class="hero-head">
        @include('layouts.header', ['isSolid' => false])
    </div>
    <div class="hero-body">
        <div class="container">
            <div class="columns is-mobile is-centered">
                <div class="column is-6 main-title-wrapper">
					<img src="{{ asset('images/errors/500.png') }}" alt="">
					<h1 class="title">Lo sentimos, estamos teniendo problemas. Por favor contáctate con nuestro departamento de Servicio al Cliente.</h1>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('styles')
	<link rel="stylesheet" href="/css/errors.css">
@endsection
@section('scripts')
@endsection
