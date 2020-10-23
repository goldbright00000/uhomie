@extends('layouts.flujo-base',['navTitle' => '<span class="bold">Paso 2.6:</span> Preferencias de visita'])
@section('content')
@if( $property->type_stay == 'LONG_STAY' )
<div class="container" id="agent-third-step-six">
	<section class="section main-section">
		<div class="columns">
			<div class="column is-7 flow-form" id="schedule">
				<div class="div">
				<form method="POST" action="{{-- route($form_route) --}}" enctype="multipart/form-data" id="registration-form">
						{{ csrf_field() }}
						<input type="hidden" name="property_id" id="property_id" value="{{$property->id}}">
						<input type="hidden" name="visit_schedule" id="visit_schedule" value="{{!is_null($property->visit) && $property->visit == 1 ? 1 : 0}}">
						<div class="columns">
							<div class="column">
								<div class="field">
									<div class="label-field">
										<img src="{{ asset('images/icono_lupa.png') }}">
										<span>¿Deseas coordinar visitas con potenciales compradores?</span>
									</div>
									<div class="control">
										<label class="radio">
											<input type="radio" v-model="visit" name="visit" value="1" {{ $property && $property->visit == '1' ? 'checked' : ''}}>
											Si
										</label>
										<label class="radio">
											<input type="radio" v-model="visit" name="visit" value="0" {{ $property && $property->visit == '0' ? 'checked' : is_null($property->visit) ? 'checked' : '' }} >
											No
										</label>
									</div>
								</div>
							</div>
						</div>
						<div v-if="visit == 1">
							<p style="font-style: italic;font-weight: 400;" >Seleccione dias disponible para </p>
							<p style="font-style: italic;font-weight: 400;" > que potenciales compradores</p>
							<p style="font-style: italic;font-weight: 400;" > visiten su propiedad.</p>
							<br>
							{{-- schedule range --}}
							<div class="columns">
								<div class="column">
									<p style="font-style: italic;font-weight: 400;" >Elige un rango horario</p>
									<div class="options-wrapper">
										<div class="option" style="border: 0">
											<label>
											<input
												v-model="schedule_range"
												type="radio"
												name="schedule_range"
												value="9-12"
												@click="schedule_range = '9-12'"
												required
											>
											<div class="text is-basic">
												<span>Mañana</span>
												<span>09-12</span>
											</div>
											</label>
										</div>
										<div class="option" style="border: 0">
											<label>
											<input
												v-model="schedule_range"
												type="radio"
												name="schedule_range"
												value="12-3"
												@click="schedule_range = '12-3'"
												required
											>
											<div class="text is-basic">
												<span>Mediodía</span>
												<span>12-15</span>
											</div>
											</label>
										</div>
										<div class="option" style="border: 0">
											<label>
											<input
												v-model="schedule_range"
												type="radio"
												name="schedule_range"
												value="3-7"
												@click="schedule_range = '3-7'"
												required
											>
											<div class="text is-basic">
												<span>Tarde</span>
												<span>15-19</span>
											</div>
											</label>
										</div>
									</div>
								</div>
							</div>
							<div class="columns" v-if="schedule_range == '9-12'">
								<div class="column">
									<v-date-picker
										v-model="morning" 
										:min-date="now" 
										:mode="mode"
										is-inline
										is-expanded
										color="green"
										:attributes="attributesC"
										:dayclick="alterScheduleDate()"
										>
									</v-date-picker>
								</div>
							</div>
							<div class="columns" v-if="schedule_range == '12-3'">
								<div class="column">
									<v-date-picker
										v-model="noon" 
										:min-date="now" 
										:mode="mode"
										is-inline
										is-expanded
										color="blue"
										:attributes="attributesC"
										:dayclick="alterScheduleDate()"
										>
									</v-date-picker>
								</div>
							</div>
							<div class="columns" v-if="schedule_range == '3-7'">
								<div class="column">
									<v-date-picker
										v-model="afternoon" 
										:min-date="now" 
										:mode="mode"
										is-inline
										is-expanded
										color="red"
										:attributes="attributesC"
										:dayclick="alterScheduleDate()"
										>
									</v-date-picker>
								</div>
							</div>
							<input type="hidden" id="schedule_dates" name="schedule_dates" v-model="schedule_dates"/>
						</div>
						<div v-else>
							<p style="font-style: italic;font-weight: 400;" >Recuerda si usas esta opción </p>
							<p style="font-style: italic;font-weight: 400;" >los potenciales compradores no podran</p>
							<p style="font-style: italic;font-weight: 400;" > coordinar visitas en tu propiedad</p>
							<p style="font-style: italic;font-weight: 400;" >lo cual puede afectar tus</p>
							<p style="font-style: italic;font-weight: 400;" >probabilidades de venta.</p>
						</div>
					</form>
				</div>
				<div class="form-footer">
					<a href="@route('users.agents.second-step.five')" class="button is-outlined">Atrás</a>
					<img src="{{ asset('images/icono_guardar.png') }}" id="save-button-icon">	                    <input type="submit" class="button is-outlined is-primary" value="Continuar" form="registration-form">
				</div>
			</div>
			<div class="column side-info">
				<div class="info">
					<img src="{{ asset('images/icono-atencion.png') }}">
					<p>Se recomienda configurar un maximo de</p>
					<p>hasta 3 semanas para configurar las</p>
					<p>visitas que recibiras en tu propiedad</p>
					<div class="border"></div>
				</div>
				<div class="info">
					<img src="{{ asset('images/icono-atencion.png') }}">
					<p>Tu tienes el control y podras cambiar</p>
					<p>estas preferencias desde tu perfil en</p>
					<p>cualquier momento.</p>
					<div class="border"></div>
				</div>
			</div>
		</div>
	</section>
</div>
@else
<div class="container" id="own-third-step-two">
	<section class="section main-section">
		<div class="columns">
			<div class="column is-5">
				<section class="hero main-title">
					<div class="hero-body">
						<div class="container">
							<h1 style="font-size: 1.5rem;font-weight: 600;" class="title">Disponibilidad</h1>
						</div>
					</div>
				</section>
				<p style="font-style: italic;font-weight: 600;" >Seleccione los dias que </p>
				<p style="font-style: italic;font-weight: 600;" > están ocupados.</p><br>
				<p style="font-style: italic;font-weight: 600;" > Los dias marcados</p>
				<p style="font-style: italic;font-weight: 600;" > quedarán bloqueados.</p>
				<br>
				<div>
					<span style="display: inline;font-style: normal;background: green;color: white;border-radius: 39px;padding: 10px 10px;font-weight: bold;"> 17 </span>
					<span style="margin-left: 10px;font-weight: 600;">Ocupado</span>
				</div>
				<br>
				<div>
					<span style="display: inline;font-style: normal;color: black;border-radius: 39px;padding: 10px 10px;font-weight: inherit;"> 17 </span>
					<span style="margin-left: 10px;font-weight: 600;">Disponible</span>
				</div>
			</div>
			<div class="column is-7 flow-form" id="schedule">
				<div class="div">
					<form method="POST" action="{{-- route($form_route) --}}" enctype="multipart/form-data" id="registration-form">
						{{ csrf_field() }}
						<input type="hidden" name="property_id" id="property_id" value="{{$property->id}}">
						<div>
							
							
							{{-- schedule range --}}
							
							<div class="columns">
								<div class="column">
									<v-date-picker
										v-model="morning" 
										:min-date="now" 
										:mode="mode"
										is-inline
										is-expanded
										color="green"
										:attributes="attributesC"
										:dayclick="alterScheduleDate()"
										>
									</v-date-picker>
								</div>
							</div>
							<input type="hidden" id="schedule_dates" name="schedule_dates" v-model="schedule_dates"/>
						</div>
						
					</form>
				</div>
			</div>
		</div>
		<div class="columns">
			<div class="form-footer">
				<a href="{{route('users.agents.second-step.five')}}" class="button is-outlined">Atrás</a>
				<img style="margin: 0px 10px;" src="{{ asset('images/icono_guardar.png') }}" id="save-button-icon">
				<input type="submit" class="button is-outlined is-primary" value="Continuar" form="registration-form">
			</div>
		</div>
	</section>
</div>
@endif
	<style>
	.vc-rounded-lg {
		border-radius: 0;
	}
	</style>
	@include('components.users.common-forms.save-button.modal')
@endsection

@section("scripts")
	<!--<script src="{{ asset('js/property-forms/second-step-two.js') }}"></script>-->
	@if($property->type_stay == 'LONG_STAY')
	<script src="{{ asset('js/property-forms/schedule-property.js') }}">
	@else
	<script src="{{ asset('js/property-forms/schedule_short_stay.js') }}">
	@endif
@endsection
