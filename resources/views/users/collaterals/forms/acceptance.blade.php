@extends('layouts.flujo-base')
@section('content')
	<div class="container" id="second-step">
	    <section class="section main-section">
	        <div class="columns">
	            <div class="column is-7 flow-form">
					<form method="POST" action="{{route('users.collateral.acceptance')}}" id="registration-form">
						{{csrf_field()}}
						<input type="hidden" name="answer" value="" id="answer">
						<input type="hidden" name="creditor_id" value="{{ $creditor->id }}" id="answer">
						<h2>
							¿Acepta servir como aval para {{ $creditor->fullname }}?
						</h2>
		                <div class="info">
							<p>
								Recuerda que al aceptar, estas aceptando asumir todas las responsabilidades inherentes al título
								<br/>Asimismo, aceptas nuestros <a href="@route('get-terms')" class="has-text-primary">Terminos y Condiciones</a> y <a href="@route('get-terms')" class="has-text-primary">Privacidad de datos</a>.
								<br/>Acepta para continuar con el proceso de registro.
								<br/>De no aceptar, sientete libre de crear una cuenta en Uhomie y disfrutar de sus beneficios
							</p>
		                </div>
					</form>
	                <div class="form-footer email_not_exist_case" >
						<a href="#" class="button is-outlined"onclick="sendForm(0)">Cancelar</a>
						<a href="#" class="button is-outlined is-primary"onclick="sendForm(1)">Aceptar y Continuar</a>
						<div class="border"></div>
	                </div>
	            </div>
	            <div class="column side-info">
	                <div class="info">
	                    <img src="{{ asset('images/icono-atencion.png') }}">
	                    <p>Notificaremos a tu aval para su proceso de registro, recuerda notificarle a tu aval que revise su bandeja de entrada en su correo electrónico para iniciar el proceso.</p>
	                </div>
					<div class="info">
						<img src="{{ asset('images/icono-atencion.png') }}">
						<p>Notificaremos a tu aval para su proceso de registro, recuerda notificarle a tu aval que revise su bandeja de entrada en su correo electrónico para iniciar el proceso.</p>
					</div>
					<div class="info">
						<img src="{{ asset('images/icono-atencion.png') }}">
						<p>Notificaremos a tu aval para su proceso de registro, recuerda notificarle a tu aval que revise su bandeja de entrada en su correo electrónico para iniciar el proceso.</p>
					</div>
	            </div>
	        </div>
	    </section>
	</div>
@endsection
@section('scripts')
<script type="text/javascript">
	function sendForm(answer){
		$("#answer").val(answer);
		$("#registration-form").submit();
	}
</script>
@endsection
