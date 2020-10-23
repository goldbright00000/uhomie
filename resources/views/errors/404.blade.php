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
					<img src="{{ asset('images/errors/404.png') }}" alt="">
					<h1 class="title">Al parecer la página solicitada no existe o fue movida, ¡Vuelve y navega entre cientas de propiedades!</h1>
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
