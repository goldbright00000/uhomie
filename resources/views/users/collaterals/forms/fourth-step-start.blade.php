@extends('layouts.flujo-base', ['navTitle' => '<span class="bold">Paso 2:</span> Tus datos documentos'])
@section('content')
	<div class="container" id="collateral-fourth-step-start">
	    <section class="section main-section">
	        <div class="columns">
	            <div class="column is-7 flow-form">
	                <div>
	                    <div class="edit-step">
	                        <div>
	                            <span>Paso 1</span>
	                            <span>Tus datos personales</span>
	                        </div>
	                        <div class="edit-right">
	                            <img src="{{ asset('images/icono-ok.png') }}">
	                            <a href="{{route('users.collaterals.first-step')}}">Editar</a>
	                        </div>
	                    </div>
	                    <h1 class="title step-title">Paso 2</h1>
	                    <section class="hero main-title">
	                        <div class="hero-body">
	                            <div class="container">
	                                <h1 class="title">Tus Documentos</h1>
	                            </div>
	                        </div>
	                    </section>
	                    <span class="question"></span>
	                    <div class="control">
	                    </div>
	                </div>
	                <div class="form-footer">
	                    <a href="{{route('users.collaterals.third-step')}}" class="button">Atrás</a>
	                    <a href="{{route('users.collaterals.fourth-step')}}" class="button is-outlined is-primary">Continuar</a>
	                </div>
	            </div>
	            <div class="column side-info">
	                <div class="info small">
	                    <img src="{{ asset('images/foco.png') }}">
	                    <p>Recuerda que esto ayudará a tener una mejor evaluación cuando aplicas.</p>
	                    <div class="border"></div>
	                </div>
	                <div class="info">
	                    <img src="{{ asset('images/icono-atencion.png') }}">
	                    <p>Te recomendamos tener a la mano tus documentos de respaldo como Liquidaciones de Sueldo. Contrato de Trabajo. Certificados de AFP entre otros según tu Tipo de Trabajo.</p>
	                    <div class="border"></div>
	                </div>
	            </div>
	        </div>
	    </section>
	</div>
@endsection
@section('custom-js')
@endsection
