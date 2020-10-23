@extends('layouts.flujo-base',['navTitle' => '<span class="bold">Paso 3.1:</span> '. $message1])
@section('custom-css')
@endsection
@section('content')
<input type="hidden" name="property_type" id="property_type" value="{{$type_property}}"/>
<input type="hidden" name="property_type_stay" id="property_type_stay" value="{{$property->type_stay}}"/>
<input type="hidden" name="is_project" id="is_project" value="{{$property->is_project}}">
	<div class="container" id="own-third-step-one">
	    <section class="section main-section">
	        <div class="columns">
	            <div class="column is-7 flow-form">
	                <div class="div">
						<form method="POST" action="" enctype="multipart/form-data" id="registration-form">
							{{ csrf_field() }}
							<div class="project" style="display:none">
								<div class="field">
									<div class="label-field">
										<img src="{{ asset('images/icono_dinero.png') }}">
										<span>Precio de la propiedad desde</span>
									</div>
									<div class="control has-icons-left">
										<input class="input monto_formato_decimales" type="text" autocomplete="off" name="rent" id="rent" value="{{ $property && !is_null($property->rent) ? $property->rent : '' }}" required minlength="2">
										<span class="icon is-small is-left">
											UF
										</span>
									</div>
								</div>
								<div class="field">
									<div class="label-field">
										<img src="{{ asset('images/icono_dinero.png') }}">
										<span>Precio de la propiedad hasta </span>
									</div>
									<div class="control has-icons-left">
										<input class="input monto_formato_decimales" type="text" autocomplete="off" name="rent_up" id="rent_up" value="{{ $property && !is_null($property->rent_up) ? $property->rent_up : '' }}" required minlength="2">
										<span class="icon is-small is-left">
											UF
										</span>
									</div>
								</div>
							</div>
							<!-- Propiedades -->
							<div class="property" style="display:none">
								<div class="long_stay" style="display:none"> 
									<div class="form-resident" style="display: none;">
										<div class="data-long-stay" style="display: none; margin-bottom: 2rem">
											<div class="field">
												<div class="label-field">
													<img src="{{ asset('images/icono_dinero.png') }}">
													<span>Precio Mensual de arriendo</span>
												</div>
												<div class="control has-icons-left has-icons-right">
													<input class="input monto_formato_decimales" type="text" autocomplete="off" name="rent" id="rent" value="{{ $property && !is_null($property->rent) ? $property->rent : '' }}" minlength="3" required>
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
													<span>Monto de Gastos Comunes</span>
												</div>
												<div class="control has-icons-left has-icons-right">
													<input
														class="input monto_formato_decimales"
														{{ !is_null($property) && $property->common_expenses_limit == 0 ? 'disabled' : '' }}
														type="text" autocomplete="off"
														name="common_expenses_limit"
														id="common_expenses_limit"
														value="{{ !is_null($property) ? $property->common_expenses_limit : '' }}" required>
													<span class="icon is-small is-left">
														$
													</span>
													<span class="icon is-small is-right">
														CLP
													</span>
												</div>
												<button type="button" class="button btn-no-posee" >No posee</button>
											</div>
											<div class="field">
												<div class="label-field">
													<img src="{{ asset('images/icono_dinero.png') }}" style="margin-right: 0.1rem;">
													<span>¿Requieres Seguro de Arriendo?</span>
												</div>
												<div class="control">
													<label class="radio">
														<input type="radio" name="tenanting_insurance" value="1" {{ $property->tenanting_insurance ? 'checked' : ''}}>
														Si
													</label>
													<label class="radio">
														<input type="radio" name="tenanting_insurance" value="0" {{ !$property->tenanting_insurance ? 'checked' : ''}}>
														No
													</label>
												</div>
											</div>
											<div class="field">
												<div class="label-field">
													<img src="{{ asset('images/icono_numero.png') }}">
													<span>¿Cuantos meses de garantia exiges?</span>
												</div>
												<div class="select">
													<select name="warranty_months_quantity" required>
														@for($i=1;$i<=12;$i++)
														@if($i == 1)
															<option {{ $property && $property->warranty_months_quantity == $i ? 'selected' : '' }}  value="{{ $i }}">{{$i}} Mes</option>
														@else
															<option {{ $property && $property->warranty_months_quantity == $i ? 'selected' : '' }} value="{{$i}}" >{{$i}} Meses</option>
														@endif
														@endfor
													</select>
												</div>
											</div>
											<div class="field">
												<div class="label-field">
													<img src="{{ asset('images/icono_numero.png') }}">
													<span>¿Cuantos meses de adelanto exiges?</span>
												</div>
												<div class="select">
													<select name="months_advance_quantity" required>
														@for($i=1;$i<=12;$i++)
														@if($i == 1)
														<option value="{{$i}}" {{ $property && $property->months_advance_quantity == $i ? 'selected' : '' }}  >{{$i}} Mes</option>
														@else
															<option value="{{$i}}" {{ $property && $property->months_advance_quantity == $i ? 'selected' : '' }}  >{{$i}} Meses</option>
														@endif
														@endfor
													</select>
												</div>
											</div>
											<div class="field">
												<div class="label-field">
													<img src="{{ asset('images/datepicker-icon.png') }}">
													<span>Fecha disponible para arrendar</span>
												</div>
												<!--<input type="text" class="input date" autocomplete="off" name="available_date" id="available_date" value="{{ $property && !is_null($property->available_date) ? $property->available_date : '' }}">-->
												<div class="control">
													<input type="text" class="input date" autocomplete="off" name="available_date" id="available_date" value="{{ $property && !is_null($property->available_date) ? $property->available_date : '' }}">
												</div>
											</div>
											<div class="field">
												<div class="label-field">
													<img src="{{ asset('images/icono_numero.png') }}">
													<span>Tiempo mínimo de arriendo</span>
												</div>
												<div class="select">
													<select name="tenanting_months_quantity" required>
														@for($i=1; $i<=12; $i++)
															@if($i == 1)
																<option  value="{{$i}}" {{ $property && $property->tenanting_months_quantity == $i ? 'selected' : '' }}  >{{$i}} Mes</option>
															@else
																<option value="{{$i}}" {{ $property && $property->tenanting_months_quantity == $i ? 'selected' : '' }}  >{{$i}} Meses</option>
															@endif
														@endfor
														<option {{ $property && $property->tenanting_months_quantity == '18' ? 'selected' : '' }} value="18" >18 Meses</option>
														<option {{ $property && $property->tenanting_months_quantity == '24' ? 'selected' : '' }} value="24" >24 Meses</option>
														<option {{ $property && $property->tenanting_months_quantity == '36' ? 'selected' : '' }} value="36" >36 Meses</option>
													</select>
												</div>
											</div>
											<div class="field">
												<div class="label-field">
													<img src="{{ asset('images/icono_aval.png') }}">
													<span>¿Exijes Aval?</span>
												</div>
												<div class="control">
													<label class="radio">
														<input type="radio" name="collateral_require"  value="1" {{ $property && $property->collateral_require == '1' ? 'checked' : '' }} required>
														Si
													</label>
													<label class="radio">
														<input type="radio" name="collateral_require"  value="0" {{ $property && $property->collateral_require == '0' ? 'checked' : '' }} required>
														No
													</label>
												</div>
											</div>
										</div>
									</div>

									<div class="form-room" style="display: none;">
										<div class="long_stay" style="display: none; margin-bottom: 2rem;">
											<div class="field">
												<div class="label-field">
													<img src="{{ asset('images/icono_dinero.png') }}">
													<span>Precio Mensual de arriendo</span>
												</div>
												<div class="control has-icons-left has-icons-right">
													<input class="input monto_formato_decimales" type="text" autocomplete="off" name="rent" id="expenses_limit" value="{{ $property && !is_null($property->rent) ? $property->rent : '' }}" required>
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
													<span>Monto de Gastos Comunes</span>
												</div>
												<div class="control has-icons-left has-icons-right">
													<input
														class="input monto_formato_decimales"
														{{ !is_null($property) && $property->common_expenses_limit == 0 ? 'disabled' : '' }}
														type="text" autocomplete="off"
														name="common_expenses_limit"
														id="common_expenses_limit"
														value="{{ !is_null($property) ? $property->common_expenses_limit : '' }}" required>
													<span class="icon is-small is-left">
														$
													</span>
													<span class="icon is-small is-right">
														CLP
													</span>
												</div>
												<button type="button" class="button btn-no-posee" >No posee</button>
											</div>
											<div class="field">
												<div class="label-field">
													<img src="{{ asset('images/icono_dinero.png') }}" style="margin-right: 0.1rem;">
													<span>¿Requieres Seguro de Arriendo?</span>
												</div>
												<div class="control">
													<label class="radio">
														<input type="radio" name="tenanting_insurance" value="1" {{ $property->tenanting_insurance ? 'checked' : ''}}>
														Si
													</label>
													<label class="radio">
														<input type="radio" name="tenanting_insurance" value="0" {{ !$property->tenanting_insurance ? 'checked' : ''}}>
														No
													</label>
												</div>
											</div>
											<div class="field">
												<div class="label-field">
													<img src="{{ asset('images/icono_numero.png') }}">
													<span>¿Cuantos meses de garantia exiges?</span>
												</div>
												<div class="select">
													<select name="warranty_months_quantity" required>
														@for($i=1;$i<=12;$i++)
														@if($i == 1)
															<option {{ $property && $property->warranty_months_quantity == $i ? 'selected' : '' }}  value="{{ $i }}">{{$i}} Mes</option>
														@else
															<option {{ $property && $property->warranty_months_quantity == $i ? 'selected' : '' }} value="{{$i}}" >{{$i}} Meses</option>
														@endif
														@endfor
													</select>
												</div>
											</div>
											<div class="field">
												<div class="label-field">
													<img src="{{ asset('images/icono_numero.png') }}">
													<span>¿Cuantos meses de adelanto exiges?</span>
												</div>
												<div class="select">
													<select name="months_advance_quantity" required>
														@for($i=1;$i<=12;$i++)
														@if($i == 1)
														<option value="{{$i}}" {{ $property && $property->months_advance_quantity == $i ? 'selected' : '' }}  >{{$i}} Mes</option>
														@else
															<option value="{{$i}}" {{ $property && $property->months_advance_quantity == $i ? 'selected' : '' }}  >{{$i}} Meses</option>
														@endif
														@endfor
													</select>
												</div>
											</div>
											<div class="field">
												<div class="label-field">
													<img src="{{ asset('images/datepicker-icon.png') }}">
													<span>Fecha disponible para arrendar</span>
												</div>
												<!--<input type="text" class="input date" autocomplete="off" name="available_date" id="available_date" value="{{ $property && !is_null($property->available_date) ? $property->available_date : '' }}">-->
												<div class="control">
													<input type="text" class="input date" autocomplete="off" name="available_date" id="available_date2" value="{{ $property && !is_null($property->available_date) ? $property->available_date : '' }}">
												</div>
											</div>
											<div class="field">
												<div class="label-field">
													<img src="{{ asset('images/icono_numero.png') }}">
													<span>Tiempo mínimo de arriendo</span>
												</div>
												<div class="select">
													<select name="tenanting_months_quantity" required>
														@for($i=1; $i<=12; $i++)
															@if($i == 1)
																<option  value="{{$i}}" {{ $property && $property->tenanting_months_quantity == $i ? 'selected' : '' }}  >{{$i}} Mes</option>
															@else
																<option value="{{$i}}" {{ $property && $property->tenanting_months_quantity == $i ? 'selected' : '' }}  >{{$i}} Meses</option>
															@endif
														@endfor
														<option {{ $property && $property->tenanting_months_quantity == '18' ? 'selected' : '' }} value="18" >18 Meses</option>
														<option {{ $property && $property->tenanting_months_quantity == '24' ? 'selected' : '' }} value="24" >24 Meses</option>
														<option {{ $property && $property->tenanting_months_quantity == '36' ? 'selected' : '' }} value="36" >36 Meses</option>
													</select>
												</div>
											</div>
											<div class="field">
												<div class="label-field">
													<img src="{{ asset('images/icono_aval.png') }}">
													<span>¿Exijes Aval?</span>
												</div>
												<div class="control">
													<label class="radio">
														<input type="radio" name="collateral_require"  value="1" {{ $property && $property->collateral_require == '1' ? 'checked' : '' }} required>
														Si
													</label>
													<label class="radio">
														<input type="radio" name="collateral_require"  value="0" {{ $property && $property->collateral_require == '0' ? 'checked' : '' }} required>
														No
													</label>
												</div>
											</div>
										</div>
									</div>

									<div class="form-parking" style="display: none">
										<div class="long_stay" style="display:none; margin-bottom: 2rem">
											<div class="field">
												<div class="label-field">
													<img src="{{ asset('images/icono_dinero.png') }}">
													<span>Precio Mensual de arriendo</span>
												</div>
												<div class="control has-icons-left has-icons-right">
													<input class="input monto_formato_decimales" type="text" autocomplete="off" name="rent" id="expenses_limit" value="{{ $property && !is_null($property->rent) ? $property->rent : '' }}" required>
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
													<span>Monto de Gastos Comunes</span>
												</div>
												<div class="control has-icons-left has-icons-right">
													<input
														class="input monto_formato_decimales"
														{{ !is_null($property) && $property->common_expenses_limit == 0 ? 'disabled' : '' }}
														type="text" autocomplete="off"
														name="common_expenses_limit"
														id="common_expenses_limit"
														value="{{ !is_null($property) ? $property->common_expenses_limit : '' }}" required>
													<span class="icon is-small is-left">
														$
													</span>
													<span class="icon is-small is-right">
														CLP
													</span>
												</div>
												<button type="button" class="button btn-no-posee" >No posee</button>
											</div>
											<div class="field">
												<div class="label-field">
													<img src="{{ asset('images/icono_dinero.png') }}" style="margin-right: 0.1rem;">
													<span>¿Requieres Seguro de Arriendo?</span>
												</div>
												<div class="control">
													<label class="radio">
														<input type="radio" name="tenanting_insurance" value="1" {{ $property->tenanting_insurance ? 'checked' : ''}}>
														Si
													</label>
													<label class="radio">
														<input type="radio" name="tenanting_insurance" value="0" {{ !$property->tenanting_insurance ? 'checked' : ''}}>
														No
													</label>
												</div>
											</div>
											<div class="field">
												<div class="label-field">
													<img src="{{ asset('images/icono_numero.png') }}">
													<span>¿Cuantos meses de garantia exiges?</span>
												</div>
												<div class="select">
													<select name="warranty_months_quantity" required>
														@for($i=1;$i<=12;$i++)
														@if($i == 1)
															<option {{ $property && $property->warranty_months_quantity == $i ? 'selected' : '' }}  value="{{ $i }}">{{$i}} Mes</option>
														@else
															<option {{ $property && $property->warranty_months_quantity == $i ? 'selected' : '' }} value="{{$i}}" >{{$i}} Meses</option>
														@endif
														@endfor
													</select>
												</div>
											</div>
											<div class="field">
												<div class="label-field">
													<img src="{{ asset('images/icono_numero.png') }}">
													<span>¿Cuantos meses de adelanto exiges?</span>
												</div>
												<div class="select">
													<select name="months_advance_quantity" required>
														@for($i=1;$i<=12;$i++)
														@if($i == 1)
														<option value="{{$i}}" {{ $property && $property->months_advance_quantity == $i ? 'selected' : '' }}  >{{$i}} Mes</option>
														@else
															<option value="{{$i}}" {{ $property && $property->months_advance_quantity == $i ? 'selected' : '' }}  >{{$i}} Meses</option>
														@endif
														@endfor
													</select>
												</div>
											</div>
											<div class="field">
												<div class="label-field">
													<img src="{{ asset('images/datepicker-icon.png') }}">
													<span>Fecha disponible para arrendar</span>
												</div>
												<!--<input type="text" class="input date" autocomplete="off" name="available_date" id="available_date" value="{{ $property && !is_null($property->available_date) ? $property->available_date : '' }}">-->
												<div class="control">
													<input type="text" class="input date" autocomplete="off" name="available_date" id="available_date3" value="{{ $property && !is_null($property->available_date) ? $property->available_date : '' }}">
												</div>
											</div>
											<div class="field">
												<div class="label-field">
													<img src="{{ asset('images/icono_numero.png') }}">
													<span>Tiempo mínimo de arriendo</span>
												</div>
												<div class="select">
													<select name="tenanting_months_quantity" required>
														@for($i=1; $i<=12; $i++)
															@if($i == 1)
																<option  value="{{$i}}" {{ $property && $property->tenanting_months_quantity == $i ? 'selected' : '' }}  >{{$i}} Mes</option>
															@else
																<option value="{{$i}}" {{ $property && $property->tenanting_months_quantity == $i ? 'selected' : '' }}  >{{$i}} Meses</option>
															@endif
														@endfor
														<option {{ $property && $property->tenanting_months_quantity == '18' ? 'selected' : '' }} value="18" >18 Meses</option>
														<option {{ $property && $property->tenanting_months_quantity == '24' ? 'selected' : '' }} value="24" >24 Meses</option>
														<option {{ $property && $property->tenanting_months_quantity == '36' ? 'selected' : '' }} value="36" >36 Meses</option>
													</select>
												</div>
											</div>
											<div class="field">
												<div class="label-field">
													<img src="{{ asset('images/icono_aval.png') }}">
													<span>¿Exijes Aval?</span>
												</div>
												<div class="control">
													<label class="radio">
														<input type="radio" name="collateral_require"  value="1" {{ $property && $property->collateral_require == '1' ? 'checked' : '' }} required>
														Si
													</label>
													<label class="radio">
														<input type="radio" name="collateral_require"  value="0" {{ $property && $property->collateral_require == '0' ? 'checked' : '' }} required>
														No
													</label>
												</div>
											</div>
										</div>
									</div>

									<div class="form-office" style="display: none;">
										<div class="long_stay" style="display: none; margin-bottom: 2rem;">
											<div class="field">
												<div class="label-field">
													<img src="{{ asset('images/icono_dinero.png') }}">
													<span>Precio Mensual de arriendo</span>
												</div>
												<div class="control has-icons-left has-icons-right">
													<input class="input monto_formato_decimales" type="text" autocomplete="off" name="rent" id="expenses_limit" value="{{ $property && !is_null($property->rent) ? $property->rent : '' }}" minlength="6">
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
													<img src="{{ asset('images/icono-calendario-azul.png') }}">
													<span>Plazo minimo de arriendo (años)</span>
												</div>
												<div class="control">
													<input class="input numbers" v-model="term_year" type="number" min="0" max="5"  placeholder="" name="term_year" id="term_year" value="{{ !is_null($property) ? $property->term_year : '' }}" required >
												</div>
											</div>
											
											<div class="field">
												<div class="label-field">
													<img src="{{ asset('images/icono_dinero.png') }}">
													<span>Precio arriendo año 1</span>
												</div>
												<div class="control has-icons-right">
													<input class="input monto_formato_decimales" type="text" autocomplete="off" name="rent_year_1" value="{{ !is_null($property) ? $property->rent_year_1 : '' }}">
													<span class="icon is-small is-right">
														UF
													</span>
												</div>
											</div>
											<div class="field">
												<div class="label-field">
													<img src="{{ asset('images/icono_dinero.png') }}">
													<span>Precio arriendo año 2</span>
												</div>
												<div class="control has-icons-right">
													<input class="input monto_formato_decimales" type="text" autocomplete="off" name="rent_year_2" value="{{ !is_null($property) ? $property->rent_year_2 : '' }}">
													<span class="icon is-small is-right">
														UF
													</span>
												</div>
											</div>
											<div class="field">
												<div class="label-field">
													<img src="{{ asset('images/icono_dinero.png') }}">
													<span>Precio arriendo año 3</span>
												</div>
												<div class="control has-icons-right">
													<input class="input monto_formato_decimales" type="text" autocomplete="off" name="rent_year_3" value="{{ !is_null($property) ? $property->rent_year_3 : '' }}">
													<span class="icon is-small is-right">
														UF
													</span>
												</div>
											</div>
											<div class="field">
												<div class="label-field">
													<img src="{{ asset('images/icono_dinero.png') }}">
													<span>Precio mensual de gastos comunes</span>
												</div>
												<div class="control has-icons-right">
													<input class="input monto_formato_decimales" type="text" autocomplete="off" name="common_expenses_limit" id="common_expenses_limit" value="{{ $property && !is_null($property->common_expenses_limit) ? $property->common_expenses_limit : '' }}" required>
													<span class="icon is-small is-right">
														UF
													</span>
												</div>
											</div>
											<div class="field">
												<div class="label-field">
													<img src="{{ asset('images/icono_dinero.png') }}">
													<span>Multa por morosidad de no pago</span>
												</div>
												<div class="control has-icons-right">
													<input class="input monto_formato_decimales" type="text" autocomplete="off" name="penalty_fees" id="penalty_fees" value="{{ $property && !is_null($property->penalty_fees) ? $property->penalty_fees : '' }}" required>
													<span class="icon is-small is-right">
														UF
													</span>
												</div>
											</div>
											<div class="field">
												<div class="label-field">
													<img src="{{ asset('images/icono_dinero.png') }}" style="margin-right: 0.1rem;">
													<span>¿Requieres Seguro de Arriendo?</span>
												</div>
												<div class="control">
													<label class="radio">
														<input type="radio" name="tenanting_insurance" value="1" {{ $property->tenanting_insurance ? 'checked' : ''}}>
														Si
													</label>
													<label class="radio">
														<input type="radio" name="tenanting_insurance" value="0" {{ !$property->tenanting_insurance ? 'checked' : ''}}>
														No
													</label>
												</div>
											</div>
											<div class="field">
												<div class="label-field">
													<img src="{{ asset('images/icono_numero.png') }}">
													<span>¿Cuantos meses de garantia exiges?</span>
												</div>
												<div class="select">
													<select name="warranty_months_quantity" required>
														@for($i=1;$i<=12;$i++)
														@if($i == 1)
															<option {{ $property && $property->warranty_months_quantity == $i ? 'selected' : '' }}  value="{{ $i }}">{{$i}} Mes</option>
														@else
															<option {{ $property && $property->warranty_months_quantity == $i ? 'selected' : '' }} value="{{$i}}" >{{$i}} Meses</option>
														@endif
														@endfor
													</select>
												</div>
											</div>
											<div class="field">
												<div class="label-field">
													<img src="{{ asset('images/icono_numero.png') }}">
													<span>¿Cuantos meses de adelanto exiges?</span>
												</div>
												<div class="select">
													<select name="months_advance_quantity" required>
														@for($i=1;$i<=12;$i++)
														@if($i == 1)
														<option value="{{$i}}" {{ $property && $property->months_advance_quantity == $i ? 'selected' : '' }}  >{{$i}} Mes</option>
														@else
															<option value="{{$i}}" {{ $property && $property->months_advance_quantity == $i ? 'selected' : '' }}  >{{$i}} Meses</option>
														@endif
														@endfor
													</select>
												</div>
											</div>

											<div class="field">
												<div class="label-field">
													<img src="{{ asset('images/datepicker-icon.png') }}">
													<span>Fecha disponible para arrendar</span>
												</div>
												<div class="control">
													<!--<input type="text" class="input date" autocomplete="off" name="available_date" id="available_date" value="{{ $property && !is_null($property->available_date) ? $property->available_date : '' }}">-->
													<input type="text" class="input date" autocomplete="off" name="available_date" id="available_date1" value="{{ $property && !is_null($property->available_date) ? $property->available_date : '' }}">
												</div>
											</div>
											<div class="field">
												<div class="label-field">
													<img src="{{ asset('images/icono_dinero.png') }}">
													<span>Requiere boleta de garantia</span>
												</div>
												<div class="control">
													<label class="radio">
														<input type="radio" name="warranty_ticket" id="warranty_ticket" value="1" {{ $property && $property->warranty_ticket == '1' ? 'checked' : '' }} required >
														Si
													</label>
													<label class="radio">
														<input type="radio" name="warranty_ticket" id="warranty_ticket" value="0" {{ $property && $property->warranty_ticket == '0' ? 'checked' : '' }} required >
														No
													</label>
												</div>
											</div>
											<div class="warranty_ticket_price" style="display: none;">
												<div class="field">
													<div class="label-field">
														<img src="{{ asset('images/icono_dinero.png') }}">
														<span>Monto de la boleta de garantia</span>
													</div>
													<div class="control has-icons-right">
														<input class="input monto_formato_decimales" type="text" autocomplete="off" name="warranty_ticket_price" id="expenses_limit" value="{{ $property && !is_null($property->warranty_ticket_price) ? $property->warranty_ticket_price : '' }}" required>
														<span class="icon is-small is-right">
															UF
														</span>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="form-cellar" style="display: none">
										<input type="hidden" name="cellar" id="cellar" value="1"/>
										<div class="field">
											<div class="label-field">
												<img src="{{ asset('images/icono_dinero.png') }}">
												<span>Precio Mensual de arriendo</span>
											</div>
											<div class="control has-icons-left has-icons-right">
												<input class="input monto_formato_decimales" type="text" autocomplete="off" name="rent" id="expenses_limit" value="{{ $property && !is_null($property->rent) ? $property->rent : '' }}" required>
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
												<span>Monto de Gastos Comunes</span>
											</div>
											<div class="control has-icons-left has-icons-right">
												<input
													class="input monto_formato_decimales"
													{{ !is_null($property) && $property->common_expenses_limit == 0 ? 'disabled' : '' }}
													type="text" autocomplete="off"
													name="common_expenses_limit"
													id="common_expenses_limit"
													value="{{ !is_null($property) ? $property->common_expenses_limit : '' }}" required>
												<span class="icon is-small is-left">
													$
												</span>
												<span class="icon is-small is-right">
													CLP
												</span>
											</div>
											<button type="button" class="button btn-no-posee" >No posee</button>
										</div>
										<div class="field">
											<div class="label-field">
												<img src="{{ asset('images/icono_dinero.png') }}" style="margin-right: 0.1rem;">
												<span>¿Requieres Seguro de Arriendo?</span>
											</div>
											<div class="control">
												<label class="radio">
													<input type="radio" name="tenanting_insurance" value="1" {{ $property->tenanting_insurance ? 'checked' : ''}}>
													Si
												</label>
												<label class="radio">
													<input type="radio" name="tenanting_insurance" value="0" {{ !$property->tenanting_insurance ? 'checked' : ''}}>
													No
												</label>
											</div>
										</div>
										<div class="field">
											<div class="label-field">
												<img src="{{ asset('images/icono_numero.png') }}">
												<span>¿Cuantos meses de garantia exiges?</span>
											</div>
											<div class="select">
												<select name="warranty_months_quantity" required>
													@for($i=1;$i<=12;$i++)
													@if($i == 1)
														<option {{ $property && $property->warranty_months_quantity == $i ? 'selected' : '' }}  value="{{ $i }}">{{$i}} Mes</option>
													@else
														<option {{ $property && $property->warranty_months_quantity == $i ? 'selected' : '' }} value="{{$i}}" >{{$i}} Meses</option>
													@endif
													@endfor
												</select>
											</div>
										</div>
										<div class="field">
											<div class="label-field">
												<img src="{{ asset('images/icono_numero.png') }}">
												<span>¿Cuantos meses de adelanto exiges?</span>
											</div>
											<div class="select">
												<select name="months_advance_quantity" required>
													@for($i=1;$i<=12;$i++)
													@if($i == 1)
													<option value="{{$i}}" {{ $property && $property->months_advance_quantity == $i ? 'selected' : '' }}  >{{$i}} Mes</option>
													@else
														<option value="{{$i}}" {{ $property && $property->months_advance_quantity == $i ? 'selected' : '' }}  >{{$i}} Meses</option>
													@endif
													@endfor
												</select>
											</div>
										</div>
										<div class="field">
											<div class="label-field">
												<img src="{{ asset('images/datepicker-icon.png') }}">
												<span>Fecha disponible para arrendar</span>
											</div>
											<div class="control">
												<input type="text" class="input date" autocomplete="off" name="available_date" id="available_date4" value="{{ $property && !is_null($property->available_date) ? $property->available_date : '' }}">
											</div>
										</div>
										<div class="field">
											<div class="label-field">
												<img src="{{ asset('images/icono_numero.png') }}">
												<span>Tiempo mínimo de arriendo</span>
											</div>
											<div class="select">
												<select name="tenanting_months_quantity" required>
													@for($i=1; $i<=12; $i++)
														@if($i == 1)
															<option  value="{{$i}}" {{ $property && $property->tenanting_months_quantity == $i ? 'selected' : '' }}  >{{$i}} Mes</option>
														@else
															<option value="{{$i}}" {{ $property && $property->tenanting_months_quantity == $i ? 'selected' : '' }}  >{{$i}} Meses</option>
														@endif
													@endfor
													<option {{ $property && $property->tenanting_months_quantity == '18' ? 'selected' : '' }} value="18" >18 Meses</option>
													<option {{ $property && $property->tenanting_months_quantity == '24' ? 'selected' : '' }} value="24" >24 Meses</option>
													<option {{ $property && $property->tenanting_months_quantity == '36' ? 'selected' : '' }} value="36" >36 Meses</option>
												</select>
											</div>
										</div>
										<div class="field">
											<div class="label-field">
												<img src="{{ asset('images/icono_aval.png') }}">
												<span>¿Exijes Aval?</span>
											</div>
											<div class="control">
												<label class="radio">
													<input type="radio" name="collateral_require"  value="1" {{ $property && $property->collateral_require == '1' ? 'checked' : '' }} required>
													Si
												</label>
												<label class="radio">
													<input type="radio" name="collateral_require"  value="0" {{ $property && $property->collateral_require == '0' ? 'checked' : '' }} required>
													No
												</label>
											</div>
										</div>
									</div>
								</div>
								<!-- Corta temporada-->
								<div id="profiles" class="short_stay" style="display:none" renta="{{$property->rent}}" oferta_semanal="{{$property->week_sale}}" oferta_mensual="{{$property->month_sale}}" hora_llegada="{{$property->checkin_hour}}" noches_minimas="{{$property->minimum_nights}}" tarifa_limpieza="{{$property->cleaning_rate}}">
									<div class="field">
										<div class="label-field">
											<img src="{{ asset('images/icono_dinero.png') }}">
											<span>Precio Base de arriendo por noche</span>
										</div>
										<div class="control has-icons-left has-icons-right">
											<input v-model="renta_base" @keypress="onlyNumber" class="input monto_formato_decimales" type="text" autocomplete="off" name="rent" id="expenses_limit" value="{{ $property && !is_null($property->rent) ? $property->rent : '' }}" minlength="3">
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
											<span>Tarifa por Limpieza</span>
										</div>
										<div class="control has-icons-left has-icons-right">
											<input v-model="limpieza" @keypress="onlyNumber" class="input monto_formato_decimales" type="text" autocomplete="off" name="cleaning_rate" id="expenses_limit" value="{{ $property && !is_null($property->cleaning_rate) ? $property->cleaning_rate : '' }}" minlength="3">
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
														<input class="input" name="checkout_hour" v-model="campo_hora_salida" type="text" readonly>
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
							</div>
						</form>
	                </div>
	                <div class="form-footer">
	                    <a href="{{route('users.owners.third-step')}}" class="button is-outlined">Atrás</a>
	                    <img src="{{ asset('images/icono_guardar.png') }}" id="save-button-icon">
                      <input type="submit" class="button is-outlined is-primary" value="Continuar" form="registration-form">
	                </div>
	            </div>
	            <div class="column side-info">

	                <div class="info">
						<img src="{{ asset('images/icono-atencion.png') }}">
						@if( $property->type_stay == 'LONG_STAY' )
							@if( $property->is_project == 1)
							<p>{{$message2}}</p>
							@else
							<p>Estas condiciones permiten determinar</p>
							<p>los potenciales arrendatarios que</p>
							<p>califiquen a tus criterios de arriendo.</p>
							@endif
						@else
						<p>Esta información es relevante porque</p>
	                    <p>determina las caracteristicas de</p>
						<p>búsqueda de los arrendatarios.</p>
						@endif
	                    <div class="border"></div>
	                </div>
	            </div>
	        </div>
	    </section>
	</div>
	@include('components.users.common-forms.save-button.modal')
@endsection

@section("scripts")
	@if( $property->type_stay == 'LONG_STAY' )
	<script src="{{ asset('js/property-forms/second-step-one.js') }}"></script>
	@else
	<script src="{{ asset('js/property-forms/second-step-one-short-stay.js') }}"></script>
	@endif
@endsection
