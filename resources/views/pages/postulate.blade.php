@extends('layouts.app')

@section('header')
<div class="navbar-wrapper">
	@include('layouts.header', ['isSolid' => false])
</div>
<section class="hero is-fullheight" id="postulate-header">
	<div class="hero-head">
		@parent
	</div>
	<div class="hero-body">
		<div class="container">
			<div class="columns is-tablet is-centered">
				<div class="column is-6 main-title-wrapper">
					<h1 class="title">Arrienda online</h1>
					<h1 class="title">directo al dueño,</h1>
					<h1 class="title">fácil seguro y simple</h1>
					<h1 class="title">sin pagar comisiones.</h1>
					<p class="is-hidden">Al registrarte en uHomie puedes crear <br>tu perfil para hacer match con la <br>propiedad perfecta.</p>
					<a href="#" class="button is-primary link-register">Regístrate</a>
				</div>
			</div>
		</div>
	</div>
	<div class="hero-foot has-text-centered">
		<i class="fa fa-angle-down" id="arrow-down"></i>
	</div>
</section>
@endsection

@section('content')
<div id="postulate">
	<section class="section second-section" id="calculate">
		<div class="hero-body">
			<div class="container">
				<h1 class="title">Descubre cuánto<br/> Ahorras por arrendar </br> con uhomie</h1>			
			</div>
		</div>
		<div class="columns" style="margin-bottom: 2rem;">
			<div class="column is-6 descripcion">
				<p>
					Indica el monto máximo de arriendo que pagarías por una propiedad y <b>descubre cuánto ahorrarías con uHomie.</b>
				</p>

				<img class="flecha is-hidden-mobile" src="{{ asset('images/postulate/flecha-calculadora.png') }}">
			</div>
			<div class="column is-6 input-div">
				<input id="input-calculate" v-model="calculate" type="text">
					<span id="line"></span>
					<span id="clp" v-if="calculate">CLP</span>
				</input>
			</div>
		</div>

		<div style="margin: 2rem;">
			<vue-slider
	      v-model="calculate"
	      :interval="100000"
	      :marks="marks2"
	      :min="0"
	      :max="10000000"
	      :process-style="processStyle"
	      :label-style="labelStyle"
	    ></vue-slider>
		</div>

		<div class="columns rectangulos">
			<div class="column is-2 flecha-resultado is-hidden-mobile">
				<img class="flecha" src="{{ asset('images/postulate/flecha-calculadora.png') }}">
			</div>
			<div class="column is-10 results">
				<div class="bloque">
					<span class="bloque-title">TOTAL A PAGAR CON UHOMIE</span>
					<span v-html="totalWithUhomie + ' CLP'"></span>
				</div>
				<span class="vs">VS</span>
				<div class="bloque medio">
					<span class="bloque-title">TOTAL A PAGAR CON CORREDORES</span>
					<span v-text="totalWithCorredores + ' CLP'"></span>
				</div>
				<span class="igual"> = </span>
				<div class="bloque resultado">
					<span class="bloque-title ahorro"><b>AHORRO</b></span>
					<span v-text="totalResult + ' CLP'"></span>
				</div>
			</div>
		</div>
		<p class="leyenda">Cálculo hecho considerando 1 mes de adelanto + 1 mes de garantía.</p>

		<div class="columns is-mobile">
			<div class="column is-1"></div>
			<div class="column is-4">
				<ul>
					<li>CON UHOMIE</li>
					<li><i class="fa fa-check"></i> Incluye gastos de contrato de arriendos.</li>
					<li><i class="fa fa-check"></i> No pierdes tu garantía.</li>
				</ul>
			</div>
			<div class="column is-5">
				<ul>
					<li>CON CORREDORES</li>
					<li><i class="fa fa-times"></i> Debes agregar 10.000 gastos notaría.</li>
					<li><i class="fa fa-times"></i> No se te asegura tu garantía.</li>
				</ul>
			</div>
		</div>

		<div class="columns is-mobile" style="margin-top: 3rem;">
			<div class="column is-5">
				<p class="p-ahorrando">
					Con uHomie <span v-text="' estás ahorrando '+totalResult+' CLP'"></span><br/>
					Ya puedes ir pensando en qué lo vas a gastar...
				</p>
			</div>
			<div class="column is-7">
				<img class="is-64x64" src="{{ asset('images/postulate/muebles-03.jpg') }}">
				<img class="is-64x64" src="{{ asset('images/postulate/muebles-04.jpg') }}">
				<img class="is-64x64 is-hidden-mobile" src="{{ asset('images/postulate/muebles-05.jpg') }}">
			</div>
		</div>
	</section>

	<section class="section second-section" id="about">
		<div class="columns is-gapless">
			<div class="column is-6">
			</div>
			<div class="column is-6">
				<h1 class="title">UHOMIE Scoring</h1>
				<h2 class="subtitle">En uHomie sabemos <br>que no dispones de <br>suficiente tiempo</h2>
				<div>
					<article class="media">
						<figure class="media-left">
							<p class="image is-64x64">
								<img src="{{ asset('images/postulate/ico_personalizar.png') }}">
							</p>
						</figure>
						<div class="media-content">
							<p><strong>Personalizamos</strong> todas <br>las búsquedas</p>
						</div>
					</article>
					<article class="media">
						<figure class="media-left">
							<p class="image is-64x64">
								<img src="{{ asset('images/postulate/ico_scoring.png') }}">
							</p>
						</figure>
						<div class="media-content">
							<p>Para brindarte en todas las <br>propiedades el <strong>Scoring</strong> <br>correspondiente a tus <strong>preferencias</strong></p>
						</div>
					</article>
					<article class="media">
						<figure class="media-left">
							<p class="image is-64x64">
								<img src="{{ asset('images/postulate/ico_match.png') }}">
							</p>
						</figure>
						<div class="media-content">
							<p>Asi podrás saber cuáles <br>propiedades hacen <strong>match</strong> con tu <br><strong>perfil y características</strong></p>
						</div>
					</article>
				</div>
				<h2 class="subtitle">Te hacemos la vida más fácil.</h2>
			</div>
		</div>
	</section>

	<section class="hero main-title">
		<div class="hero-body">
			<div class="container">
				<h1 class="title">¡Arrendar de forma</h1>
				<h1 class="title">simple con uhomie!</h1>
			</div>
		</div>
	</section>

	<nav class="level is-tablet" id="level-benefits">
		<div class="level-item">
			<div>
				<img src="{{ asset('images/postulate/ico_pagas.png') }}">
				<p class="title">Con uHomie no pagas <br>comisiones</p>
			</div>
		</div>
		<div class="level-item">
			<div>
				<img src="{{ asset('images/postulate/ico_ahorras.png') }}">
				<p class="title">Ahorras hasta un <br>100% por arrendar</p>
			</div>
		</div>
		<div class="level-item">
			<div>
				<img src="{{ asset('images/postulate/ico_proceso.png') }}">
				<p class="title">Proceso ágil, <br>simple y digital</p>
			</div>
		</div>
	</nav>

	<section class="hero main-title">
		<div class="hero-body">
			<div class="container">
				<h1 class="title">Nuestro proceso de</h1>
				<h1 class="title">arriendo pensado en ti</h1>
			</div>
		</div>
	</section>

	<section class="section" id="process-section">
		<div class="container">
			<div class="columns is-multiline is-gapless">
				<div class="column is-6 process-step">
					<div>
						<h1 class="title">1</h1>
						<p>Con tu perfil, nuestro sistema <br>determina un scoring UHOMIE: <br>cuanto más alto sea el Scoring <br>más posibilidades de arrendar tendrás.</p>
						<a href="#" class="button is-outlined is-primary link-register">Regístrate</a>
					</div>
				</div>
				<div class="column is-6">
					<img src="{{ asset('images/postulate/img_1.png') }}">
				</div>
				<div class="column is-6">
					<img src="{{ asset('images/postulate/img_2.png') }}">
				</div>
				<div class="column is-6 process-step">
					<div>
						<h1 class="title">2</h1>
						<p>Postula a todas las propiedades <br>con tu formulario digital, fácil, <br>simple y seguro.</p>
						<a href="@route('explore')" class="button is-outlined is-primary">Explorar</a>
					</div>
				</div>
				<div class="column is-6 process-step">
					<div>
						<h1 class="title">3</h1>
						<p>Al ser aceptado por el dueño, <br>firma tu contrato digital con <br>validez notarial.</p>
						<a href="#" class="button is-outlined is-primary link-register">Regístrate</a>
					</div>
				</div>
				<div class="column is-6">
					<img src="{{ asset('images/postulate/img_3.png') }}">
				</div>
				<div class="column is-6">
					<img src="{{ asset('images/postulate/img_4.png') }}">
				</div>
				<div class="column is-6 process-step">
					<div>
						<h1 class="title">4</h1>
						<p>En menos de 48 hs mudate a tu <br>nuevo hogar. <strong>Vive una <br>experiencia 100% digital</strong>.</p>
						<a href="@route('explore')" class="button is-outlined is-primary">Explorar</a>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="section has-text-centered" id="quote-contract">
		<h1 class="title">Contrato digital UHOMIE</h1>
		<p>Te hacemos la Vida Fácil, olvidate de todos los tramites engorrosos y <br>perder tiempo en notarias. Con nuestro Modelo avanzado de firma digital <br>Ley 19.799 todos tus contratos de arriendos tienen validez legal y jurídica.</p>
		<img src="{{ asset('images/publish/firma.png') }}">
	</section>

	<section class="hero main-title">
		<div class="hero-body">
			<div class="container">
				<h1 class="title lead">Navega entre cientos</h1>
				<h1 class="title">de propiedades</h1>
			</div>
		</div>
	</section>

	<section class="section" id="postulate">
		<div class="columns is-multiline property-list">
			<div class="column is-6" v-for="property in properties">
				<property v-bind="property"></property>
			</div>
		</div>
	</section>

	<input type="hidden" value="{{ asset('images') }}" id="images-dir">
</div>
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('css/postulate.css') }}">
<link rel="stylesheet" href="{{ asset('css/components/calculate.css') }}">
@endsection

@section('scripts')
<script type="text/javascript" src="{{ asset('js/landing.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/postulate.js') }}"></script>
@endsection
