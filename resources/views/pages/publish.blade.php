@extends('layouts.app')

@section('header')
<div class="navbar-wrapper">
	@include('layouts.header', ['isSolid' => false])
</div>
<section class="hero is-fullheight" id="publish-header">
	<div class="hero-head">
		@parent
	</div>
	<div class="hero-body">
		<div class="container">
			<div class="columns is-tablet is-centered">
				<div class="column is-6 is-offset-6 main-title-wrapper">
				  <h1 class="title" style="max-width: 400px;">Publica Gratis todas las Propiedades que desees, podrías pagar hasta 6% de comisión al arrendar. </h1>
				  <p class="has-text-light" style="max-width: 400px;">
					UHOMIE te ayuda en todo el proceso y se encarga de todo, arrienda con tranquilidad, confianza y seguridad sin pagar demás, ahorra dinero y arrienda rápido.
				  </p>
				  <a href="#" class="button is-outlined link-register">Registra tu propiedad</a>
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
<div id="publish">
	<section class="section second-section" id="calculate">
		<div class="hero-body">
			<div class="container">
				<h1 class="title">Descubre cuánto<br/> Ahorras por arrendar </br> con uhomie</h1>			
			</div>
		</div>
		<div class="columns" style="margin-bottom: 2rem;">
			<div class="column is-6 descripcion">
				<p>
					Indica el monto / precio del arriendo para tu propiedad y <b>descubre cuánto ahorrarías con uHomie.</b>
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
		<p class="leyenda">Cálculo hecho sobre 1 mes de adelanto.</p>

		<div class="columns is-mobile">
			<div class="column is-1"></div>
			<div class="column is-4">
			b	<ul>
					<li>CON UHOMIE</li>
					<li><i class="fa fa-check"></i> Gasto incluye contrato de arriendos.</li>
					<li><i class="fa fa-check"></i> Incluye seguro protección Hogar falabella 300 UF.</li>
					<li><i class="fa fa-check"></i> Protección e indemnización ante la cancelación de contratos.</li>
				</ul>
			</div>
			<div class="column is-5">
				<ul>
					<li>CON CORREDORES</li>
					<li><i class="fa fa-times"></i> No incluye gasto contrato notarial.</li>
					<li><i class="fa fa-times"></i> No incluye seguro de protección Propiedad.</li>
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
				<h1 class="title">Tú tienes el control</h1>
				<h2 class="subtitle">Controla el tipo de <br>arrendatario y las condiciones <br>de arriendo</h2>
				<div>
					<article class="media">
						<figure class="media-left">
							<p class="image is-64x64">
								<img src="{{ asset('images/publish/ico_propiedad.png') }}">
							</p>
						</figure>
						<div class="media-content">
							<p>Mayor tasa de ocupación <br>de tu propiedad.</p>
						</div>
					</article>
					<article class="media">
						<figure class="media-left">
							<p class="image is-64x64">
								<img src="{{ asset('images/publish/ico_control.png') }}">
							</p>
						</figure>
						<div class="media-content">
							<p>Obtener un reporte financiero completo,<br> laboral y de riesgo de tus potenciales arrendatarios </p>
						</div>
					</article>
					<article class="media">
						<figure class="media-left">
							<p class="image is-64x64">
								<img src="{{ asset('images/publish/ico_publicidad.png') }}">
							</p>
						</figure>
						<div class="media-content">
							<p>Mayores opciones de publicidad <br>sobre tu propiedad a costo cero, <br>acceso a cientos de clientes.</p>
						</div>
					</article>
				</div>
				<a href="#" class="button is-outlined is-primary link-register">Regístrate</a>
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
				<img src="{{ asset('images/publish/ico_billetera.png') }}">
				<p class="title">Sin <br>sobrecostos</p>
			</div>
		</div>
		<div class="level-item">
			<div>
				<img src="{{ asset('images/publish/ico_sin_intermediario.png') }}">
				<p class="title">Sin <br>intermediarios</p>
			</div>
		</div>
		<div class="level-item">
			<div>
				<img src="{{ asset('images/publish/ico_ahorra_tiempo.png') }}">
				<p class="title">Ahorra <br>tiempo</p>
			</div>
		</div>
		<div class="level-item">
			<div>
				<img src="{{ asset('images/publish/ico_pesos.png') }}">
				<p class="title">Aumentar <br>ganancia</p>
			</div>
		</div>
	</nav>

	<section class="section" id="system">
		<div class="columns is-gapless">
			<div class="column is-6">
				<div>
					<h1 class="title">Un sistema <br>pensado para ti</h1>
					<div>
						<article class="media">
							<figure class="media-left">
								<p class="image is-64x64">
									<img src="{{ asset('images/publish/ico_arrendado.png') }}">
								</p>
							</figure>
							<div class="media-content">
								<p>Configuras qué tipo de <br>arrendatario requieres y <br>las condiciones.</p>
							</div>
						</article>
						<article class="media">
							<figure class="media-left">
								<p class="image is-64x64">
									<img src="{{ asset('images/publish/ico_billete.png') }}">
								</p>
							</figure>
							<div class="media-content">
								<p>Apoyo continuo UHOMIE incluido <br>asesoría Legal y Jurídica. <br>¡Estamos para ayudarte 24X7!</p>
							</div>
						</article>
						<article class="media">
							<figure class="media-left">
								<p class="image is-64x64">
									<img src="{{ asset('images/publish/ico_estadisticas.png') }}">
								</p>
							</figure>
							<div class="media-content">
								<p>Maximiza tu ganancia, paga <br>menos en comisiones y gastos <br>ocultos. Arrienda en el menor <br>tiempo al mejor arrendatario.</p>
							</div>
						</article>
					</div>
					<a href="#" class="button is-outlined is-primary link-register">Regístrate</a>
				</div>
			</div>
			<div class="column is-6">
			</div>
		</div>
	</section>

	<section class="section has-text-centered" id="quote-contract">
		<h1 class="title">Contrato digital UHOMIE</h1>
		<p>Te hacemos la Vida Fácil. Olvídate de todos los trámites engorrosos y de <br>perder tiempo en notarias. Con nuestro modelo avanzado de firma digital <br>Ley 19.799, todos tus contratos de arriendos tienen validez legal y jurídica.</p>
		<img src="{{ asset('images/publish/firma.png') }}">
	</section>

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
						<p>Publica tu propiedad gratis, <br>en minutos, y comienza a <br>recibir propuestas para <br>arrendar tu propiedad.</p>
						<a href="#" class="button is-outlined is-primary link-register">Regístrate</a>
					</div>
				</div>
				<div class="column is-6">
					<img src="{{ asset('images/publish/img_1.png') }}">
				</div>
				<div class="column is-6">
					<img src="{{ asset('images/publish/img_2.png') }}">
				</div>
				<div class="column is-6 process-step">
					<div>
						<h1 class="title">2</h1>
						<p>Decide qué tipo de arrendatario <br>requieres en tu Propiedad. Al <br>recibir las postulaciones en tus <br>propiedades, cuanto más alto <br>sea el Scoring de los <br>postulantes mejor!. UHOMIE <br>trabaja para ayudarte a <br>seleccionar a los mejores <br>arrendatarios: evaluación <br>financiera, DICOM, AVAL, etc. <br>Vive la experiencia UHOMIE</p>
					</div>
				</div>
				<div class="column is-6 process-step">
					<div>
						<h1 class="title">3</h1>
						<p>Al aceptar a tu arrendatario y <br>gestionarse contrato digital, <br>recibe tu pago online (Meses <br>de garantía, adelanto, etc) con <br>nuestro sistema Pago Seguro. <br>Te hacemos la Vida Fácil.</p>
						<a href="#" class="button is-outlined is-primary link-register">Regístrate</a>
					</div>
				</div>
				<div class="column is-6">
					<img src="{{ asset('images/publish/img_3.png') }}">
				</div>
			</div>
		</div>
	</section>
</div>
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('css/publish.css') }}">
<link rel="stylesheet" href="{{ asset('css/components/calculate.css') }}">
@endsection
@section('scripts')
<script src="{{ asset('js/landing.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/publish.js') }}"></script>
@endsection
