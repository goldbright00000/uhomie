@extends('layouts.flujo-base', ['navTitle' => '<span class="bold">SCORING:</span> Prueba de datos'])
@section('styles')
	<link rel="stylesheet" href="{{ asset('css/explore.css') }}">
@endsection
@section('scripts')
	<script src="{{ asset('js/test.js') }}"></script>
@endsection
@section('content')
	<div class="container" id="first-step-second">
		<section class="section main-section">
		<div class="column is-7 flow-form">
	        <div class="columns">
				<div class="div">
					<section class="accordions">
                    <article class="accordion">
                        <div class="accordion-header toggle">
                            <p>Propiedades</p>
                            <button class="toggle" aria-label="toggle"></button>
                        </div>
                        <div class="accordion-body">
                            <div class="accordion-content">
								<table class="plans-table">
									<th>
										<td>Renta</td>
										<td>Descripción</td>
										<td>Tipo de propiedad</td>
										<td>Ciudad</td>
										<td>Mascotas</td>
										<td>Admite Fumadores</td>
										<td>Fecha de disponibilidad</td>
										<td>Tipo de arrendador</td>
									</th>
									@foreach ($properties as $property)
										<tr>
											<td>{{ $property->name }}</td>
											<td>{{ $property->rent }}</td>
											<td>{{ $property->description }}</td>
											<td>{{ $property->propertyType->name }} ({{ $property->property_type_id }})</td>
											<td>{{ $property->city->name }} ({{ $property->city_id }})</td>
											<td>{{ $property->pet_preference}}</td>
											<td>{{ $property->smoking_allowed ? 'SI' : 'NO'}}</td>
											<td>{{ $property->available_date }}</td>
											<td>
												<ol>
													@foreach ($property->propertiesFor as $pt)
														<li>{{$pt->name}}</li>
													@endforeach
												</ol>
											</td>
										</tr>
									@endforeach
								</table>
                            </div>
                        </div>
                    </article>
					<article class="accordion">
						<div class="accordion-header toggle">
							<p>Usuarios</p>
							<button class="toggle" aria-label="toggle"></button>
						</div>
						<div class="accordion-body">
							<div class="accordion-content">
								<table class="plans-table">
									<th>
										<td>Financiera</td>
										<td>Verificación</td>
										<td>Aval Verificado</td>
										<td>Meses Adelanto</td>
										<td>Tiempo de arriendo</td>
										<td>Meses Garantia</td>
										<td>Membresia</td>
										<td>Micelaneos</td>
										<td>Nacionalidad</td>
										<td>Tipo de empleo</td>
										<td>Documentos</td>
									</th>
									@foreach ($users as $user)
										<tr>
											<td>{{ $user->firstname }}</td>
											<td>
												<ol>
													@if ($user->employment_type == 1)
														<li>Salario: {{ $user->amount }}</li>
													@elseif ($user->employment_type == 2)
														<li>Monto Ultima Factura: {{ $user->amount }}</li>
													@endif
														<li>Ahorros: {{ $user->saves }} ({{ $user->saves/12 }})</li>
														<li>Otros Ingresos: {{ $user->other_amount }}</li>
												</ol>

											</td>
											<td>
												<ol>
													<li>Tlf Verif: {{ $user->phone_verified ? 'si' : 'no' }}</li>
													<li>@ Verif: {{ $user->mail_verified ? 'si' : 'no' }}</li>
												</ol>
											</td>
											<td>
												{{ $user->confirmed_collateral ? 'si' : 'no' }}
											</td>
											<td>{{ $user->months_advance_quantity }}</td>
											<td>{{ $user->tenanting_months_quantity}}</td>
											<td>{{ $user->warranty_months_quantity }}</td>
											<td>---</td>
											<td>
												<ol>
													<li>Tipo de propiedad: {{ $user->propertyType->name}}</li>
													<li>Tipo arrendatario: {{ $user->propertyFor->name}}</li>
													<li>F.Mudanza: {{ $user->move_date}}</li>
													<li>Mascota: {{ $user->pet_preference}}</li>
													<li>Fumador: {{ $user->smoking_allowed ? 'SI' : 'NO'}}</li>
												</ol>

											</td>
											<td>
												<ol>
													<li>
														Nacionalidad: {{ $user->country->name }}
													</li>
													<li>
														Tipo DOC: {{ $user->document_type }}
													</li>
												</ol>
											</td>
											<td>{{ $user->employment_type == 1 ? 'Empleado' : ($user->employment_type == 2 ? 'Cuenta Propia' : 'Desempleado') }}</td>
											<td>
												<ol>
													@foreach ($user->files as $file)
														<li>Documento: {{ $file->name }} Verificado: {{ $file->verified ? 'si' : 'no'}}</li>
													@endforeach
												</ol>
											</td>
										</tr>
									@endforeach

								</table>
							</div>
						</div>
					</article>

					@foreach ($properties as $property)
					<article class="accordion">
						<div class="accordion-header toggle">
							<p>Scoring de Usuarios para {{$property->name}}</p>
							<button class="toggle" aria-label="toggle"></button>
						</div>
						<div class="accordion-body">
							<div class="accordion-content">
								<table class="plans-table">
									<th>
										<td>Financiera (420)</td>
										<td>Verificación Mail/Tlf(25c/u)</td>
										<td>Aval Verificado (100)</td>
										<td>Meses de adelanto (70)</td>
										<td>Tiempo de arriendo (20)</td>
										<td>Meses de garantía (70)</td>
										<td>Tipo de Membresia (80)</td>
										<td>Mecelaneos (25)</td>
										<td>Nacionalidad (50)</td>
										<td>Tipo de Empleo (200)</td>
										<td>Documentación (140)</td>
										<td>Scoring</td>
									</th>
									@foreach ($users as $user)
										@php
											$scoreObj = \DB::select('call sp_score_user(?,?)', [$user->id, $property->id])[0];
										@endphp
										<tr>
											<td>{{$user->firstname}}</td>
											<td>{{ $scoreObj->finantial }}</td>
											<td>{{ $scoreObj->contact }}</td>
											<td>{{ $scoreObj->endorsement }}</td>
											<td>{{ $scoreObj->advance }}</td>
											<td>{{ $scoreObj->time_f }}</td>
											<td>{{ $scoreObj->warranty }}</td>
											<td>{{ $scoreObj->membership }}</td>
											<td>{{ $scoreObj->misce }}</td>
											<td>{{ $scoreObj->nation }}</td>
											<td>{{ $scoreObj->job }}</td>
											<td>{{ $scoreObj->docs }}</td>
											<td>{{ $scoreObj->score }}</td>
										</tr>
									@endforeach
								</table>
							</div>
						</div>
					</article>
					@endforeach
				</section>
				</div>
	        </div>
		</div>
	</section>
	</div>
@endsection
