@php
	$header = ["title"=>"PAGO:  " , "subtitle" => "  Descripcion paso 1"];
@endphp
@extends('layouts.multi-form')
@section('custom-css')
	<style media="screen">
		.invoice-title h2, .invoice-title h3 {
			display: inline-block;
		}

		.table > tbody > tr > .no-line {
			border-top: none;
		}

		.table > thead > tr > .no-line {
			border-bottom: none;
		}

		.table > tbody > tr > .thick-line {
			border-top: 2px solid;
		}
	</style>
@endsection
@section('content')
	<div class="container">
	    <div class="row">
	        <div class="col-xs-12">
	    		<div class="invoice-title">
	    			<h2>Facturación</h2><h3 class="pull-right">Orden #{{ date('Ymdhms').$user->id }}</h3>
	    		</div>
	    		<hr>
	    		<div class="row">
	    			<div class="col-xs-6">
	    				<address>
	    				<strong>Facturado a:</strong><br>
	    					{{ $user->fullname }}<br>
	    					{{ $user->address }}
	    				</address>
	    			</div>
	    		</div>
	    		<div class="row">
	    			<div class="col-xs-6">
	    				<address>
	    					<strong>Metodo de pago:</strong><br>
	    					Paypal
	    				</address>
	    			</div>
	    			<div class="col-xs-6 text-right">
	    				<address>
	    					<strong>Fecha:</strong><br>
	    					{{date('d-m-Y')}}<br><br>
	    				</address>
	    			</div>
	    		</div>
	    	</div>
	    </div>

	    <div class="row">
	    	<div class="col-md-12">
	    		<div class="panel panel-default">
	    			<div class="panel-heading">
	    				<h3 class="panel-title"><strong>Resumen</strong></h3>
	    			</div>
	    			<div class="panel-body">
	    				<div class="table-responsive">
	    					<table class="table table-condensed">
	    						<thead>
	                                <tr>
	        							<td><strong>Item</strong></td>
	        							<td class="text-center"><strong>Precio</strong></td>
	        							<td class="text-center"><strong>Cantidad</strong></td>
	        							<td class="text-right"><strong>Totales</strong></td>
	                                </tr>
	    						</thead>
	    						<tbody>
									@foreach ($items as $item)
		    							<tr>
											<tr>
												<td>{{ $item['title'] }}</td>
												<td class="text-center">{{ $item['amount'] }}</td>
												<td class="text-center">{{ $item['quantity'] }}</td>
												<td class="text-right">{{ $item['amount'] }}</td>
											</tr>
		    							</tr>
									@endforeach
	                                	<tr>
	    								<td class="thick-line"></td>
	    								<td class="thick-line"></td>
	    								<td class="thick-line text-center"><strong>Subtotal</strong></td>
	    								<td class="thick-line text-right">${{ $item['amount'] }}</td>
	    							</tr>
	    							<tr>
	    								<td class="no-line"></td>
	    								<td class="no-line"></td>
	    								<td class="no-line text-center"><strong>Total</strong></td>
	    								<td class="no-line text-right">${{ $item['amount'] }}</td>
	    							</tr>
	    						</tbody>
	    					</table>
	    				</div>
	    			</div>
	    		</div>
	    	</div>
	    </div>
		<div class="row">
			<div class="col-md-12">
				<div class="text-center">
					<div id="paypal-button"></div>
				</div>
			</div>
		</div>
	</div>
@endsection
@section('custom-js')
	
@endsection
