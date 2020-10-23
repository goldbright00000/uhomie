@extends('layouts.app')

@section('header')
<div class="toolbar">
    <div class="container">
        <div>
          <img src="{{ asset('images/icons/logo_completo.png') }}">
        	<span class="nav-title is-hidden-mobile">{!! $navTitle or '' !!}</span>
        </div>
        <a href="{{ isset($close) ? $close : url('/') }}" class="button is-outlined is-primary">Cerrar</a>
    </div>
    @if(isset($navTitle))
	    <div id="title-mobile" class="container is-hidden-tablet">
	    	<span class="nav-title">{!! $navTitle or '' !!}</span>
	    </div>
	  @endif
</div>
@endsection

@section('content')

@endsection

@section('flow-styles')
<link rel="stylesheet" href="{{ asset('css/flujo.css') }}">
@endsection

@section('footer')
{{-- Keep empty --}}
@endsection

@section('flow-scripts')
    <script src="{{ asset('js/save-button.js') }}"></script>
@endsection
