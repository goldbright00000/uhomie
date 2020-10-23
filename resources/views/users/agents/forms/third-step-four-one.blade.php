@extends('layouts.flujo-base',['navTitle' => '<span class="bold">Paso 4.1:</span> Condiciones', 'close' => route('profile.owner')])
@section('custom-css')
@endsection
@section('content')
	<div class="container" id="own-third-step-one">
	    <section class="section main-section">
	        <div class="columns">
	            <div class="column is-7 flow-form">
	                <div class="div">
	                    <form method="POST" action="" enctype="multipart/form-data" id="registration-form">
							{{ csrf_field() }}
							
							<div id="profiles" renta="{{$property->rent}}" oferta_semanal="{{$property->week_sale}}" oferta_mensual="{{$property->month_sale}}" hora_llegada="{{$property->checkin_hour}}" noches_minimas="{{$property->minimum_nights}}" tarifa_limpieza="{{$property->cleaning_rate}}">
								<div class="field">
									<div class="label-field">
										<img src="{{ asset('images/icono_dinero.png') }}">
										<span>Precio Base de arriendo por noche</span>
									</div>
									<div class="control has-icons-left has-icons-right">
										<input v-model="renta_base" @keypress="onlyNumber" class="input monto_formato_decimales" type="text" autocomplete="off" name="rent" id="expenses_limit" value="{{ $property && !is_null($property->rent) ? $property->rent : '' }}" min="1">
										<span class="icon is-small is-left">
											$
										</span>
										<span class="icon is-small is-right">
											CLP
										</span>
									</div>
								</div>
								<div class="field">
									<div class="label-field">
										<img src="{{ asset('images/icono_dinero.png') }}">
										<span>Tarifa de Limpieza</span>
									</div>
									<div class="control has-icons-left has-icons-right">
										<input v-model="limpieza" @keypress="onlyNumber" class="input monto_formato_decimales" type="text" autocomplete="off" name="cleaning_rate" id="tarifa_limpieza" value="{{ $property && !is_null($property->cleaning_rate) ? $property->cleaning_rate : '' }}" minlength="1">
										<span class="icon is-small is-left">
											$
										</span>
										<span class="icon is-small is-right">
											CLP
										</span>
									</div>
								</div>
								<div class="field">
									<div class="label-field">
										<img src="{{ asset('images/icono_lupa.png') }}">
										<span>¿Deseas agregar oferta especial 10% descuento a tus primeros 5 huéspedes?</span>
									</div>
									<div class="control">
										<label class="radio">
											<input type="radio" name="special_sale" value="1" {{ $property->special_sale ? 'checked' : ''}}>
											Si
										</label>
										<label class="radio">
											<input type="radio" name="special_sale" value="0" {{ !$property->special_sale ? 'checked' : ''}}>
											No
										</label>
									</div>
								</div>
								<div class="field is-horizontal" style="margin-bottom: 1rem;">
									<div class="label-field">
										<img src="{{ asset('images/icono-estrella.png') }}">
										<span>Oferta semanal</span>
									</div>
									<div class="field-body">
										<div class="field is-expanded">
											<div class="field has-addons">
												<p class="control" style="flex-basis: 0%;" @click="poesia(-1)">
													<a class="button is-static" >
														<i  class="fa fa-minus"></i>
													</a>
												</p>
												<p class="control is-expanded">
													<input class="input"  name="week_sale" v-model="campo_oferta_semanal"  type="text" readonly>
												</p>
												<p class="control" style="flex-basis: 0%;" @click="poesia(1)">
													<a class="button is-static" >
														<i  class="fa fa-plus"></i>
													</a>
												</p>
											</div>
											
										</div>
									</div>
								</div>
								<div class="field is-horizontal">
									<div class="label-field">
									</div>
									<div class="field-body">
										<div class="field is-expanded" style="display: inline">
											<div class="field has-addons">
												<p class="control" style="flex-basis: 0%;" ></p>
												<p></p>
												<p class="control" style="flex-basis: 0%;" ></p>
											</div>
											<p class="help" style="font-size: 0.85rem;" v-html="help_oferta_semanal"></p>
										</div>
									</div>
								</div>
								<!-- SEPARADOR -->
								<div class="field is-horizontal" style="margin-bottom: 1rem;">
									<div class="label-field">
										<img src="{{ asset('images/icono-estrella.png') }}">
										<span>Oferta mensual</span>
									</div>
									<div class="field-body">
										<div class="field is-expanded">
											<div class="field has-addons">
												<p class="control" style="flex-basis: 0%;" @click="poesiam(-1)">
													<a class="button is-static" >
														<i  class="fa fa-minus"></i>
													</a>
												</p>
												<p class="control is-expanded">
													<input class="input" name="month_sale" v-model="campo_oferta_mensual" type="text" readonly>
												</p>
												<p class="control" style="flex-basis: 0%;" @click="poesiam(1)">
													<a class="button is-static" >
														<i  class="fa fa-plus"></i>
													</a>
												</p>
											</div>
											
										</div>
									</div>
								</div>
								<div class="field is-horizontal">
									<div class="label-field">
									</div>
									<div class="field-body">
										<div class="field is-expanded" style="display: inline">
											<div class="field has-addons">
												<p class="control" style="flex-basis: 0%;" ></p>
												<p></p>
												<p class="control" style="flex-basis: 0%;" ></p>
											</div>
											<p class="help" style="font-size: 0.85rem;" v-html="help_oferta_mensual"></p>
										</div>
									</div>
								</div>
								<div class="field">
									<div class="label-field">
										<img src="{{ asset('images/datepicker-icon.png') }}">
										<span>Fecha disponible para arrendar</span>
									</div>
									<div class="control">
									<input type="text" class="input date" ref='calendarTrigger' autocomplete="off" name="available_date" id="available_date" value="{{ $property && !is_null($property->available_date) ? date('d/m/Y',strtotime($property->available_date)) : '' }}">
									</div>
									
								</div>
								<div class="field is-horizontal" style="margin-bottom: 1rem;">
									<div class="label-field">
										<img src="{{ asset('images/icono-empleo.png') }}">
										<span>Hora de llegada es a partir de:</span>
									</div>
									<div class="field-body">
										<div class="field is-expanded">
											<div class="field has-addons">
												<p class="control" style="flex-basis: 0%;" @click="poesiahl(-1)">
													<a class="button is-static" >
														<i  class="fa fa-minus"></i>
													</a>
												</p>
												<p class="control is-expanded">
													<input class="input" name="checkin_hour"  v-model="campo_hora_llegada" type="text" readonly>
												</p>
												<p class="control" style="flex-basis: 0%;" @click="poesiahl(1)">
													<a class="button is-static" >
														<i  class="fa fa-plus"></i>
													</a>
												</p>
											</div>
											
										</div>
									</div>
								</div>

								<div class="field is-horizontal" style="margin-bottom: 1rem;">
									<div class="label-field">
										<img src="{{ asset('images/icono-empleo.png') }}">
										<span>Hora de salida es a partir de:</span>
									</div>
									<div class="field-body">
										<div class="field is-expanded">
											<div class="field has-addons">
												<p class="control" style="flex-basis: 0%;" @click="poesiahs(-1)">
													<a class="button is-static" >
														<i  class="fa fa-minus"></i>
													</a>
												</p>
												<p class="control is-expanded">
													<input class="input" name="checkout_hour"  v-model="campo_hora_salida" type="text" readonly>
												</p>
												<p class="control" style="flex-basis: 0%;" @click="poesiahs(1)">
													<a class="button is-static" >
														<i  class="fa fa-plus"></i>
													</a>
												</p>
											</div>
											
										</div>
									</div>
								</div>

								<div class="field is-horizontal" style="margin-bottom: 1rem;">
									<div class="label-field">
										<img src="{{ asset('images/icono-seguro.png') }}">
										<span>Noches mínimas de alojamiento:</span>
									</div>
									<div class="field-body">
										<div class="field is-expanded">
											<div class="field has-addons">
												<p class="control" style="flex-basis: 0%;" @click="poesianm(-1)">
													<a class="button is-static" >
														<i  class="fa fa-minus"></i>
													</a>
												</p>
												<p class="control is-expanded">
													<input class="input" name="minimum_nights" v-model="campo_noches_minimas" type="text" readonly>
												</p>
												<p class="control" style="flex-basis: 0%;" @click="poesianm(1)">
													<a class="button is-static" >
														<i  class="fa fa-plus"></i>
													</a>
												</p>
											</div>
											
										</div>
									</div>
								</div>
							</div>
	                    </form>
	                </div>
	                <div class="form-footer">
	                    <a href="{{route('users.agents.third-step.four', ['id' => $property->id])}}" class="button is-outlined">Atrás</a>
	                    <img src="{{ asset('images/icono_guardar.png') }}" id="save-button-icon">
                      <input type="submit" class="button is-outlined is-primary" value="Continuar" form="registration-form">
	                </div>
	            </div>
	            <div class="column side-info">

	                <div class="info">
	                    <img src="{{ asset('images/icono-atencion.png') }}">
	                    <p>Estas condiciones permiten determinar</p>
	                    <p>los potenciales arrendatarios que</p>
	                    <p>califiquen a tus criterios de arriendo.</p>
	                    <div class="border"></div>
	                </div>
	            </div>
	        </div>
	    </section>
	</div>
	@include('components.users.common-forms.save-button.modal')
@endsection

@section("scripts")
	<script src="{{ asset('js/property-forms/second-step-one-short-stay.js') }}"></script>
@endsection
