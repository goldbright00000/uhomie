@extends('layouts.flujo-base', ['navTitle' => '<span class="bold">Paso 6:</span> Elige tu Membresía'])
@section('content')
<div class="container" id="fourth-step">
    <section class="section main-section">
        <div class="columns">
            <div class="column is-8">
                <table class="plans-table">
                    <tr>
                        <td></td>
                        <td class="border-bottom border-basic">
                            <img src="{{ asset('images/logo_basic.png') }}">
                        </td>
                        <td class="border-bottom border-select">
                            <img src="{{ asset('images/logo_select.png') }}">
                        </td>
                        <td class="border-bottom border-premium">
                            <img src="{{ asset('images/logo_premium.png') }}">
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        @foreach ($memberships as $membership)
                        <td class="price">
                            <span>$ {{ $membership->getFeatures()->package_amount }} @if($membership->getFeatures()->package_amount)<span>+ IVA</span>@endif</span>
                            <span>(CLP)</span>
                        </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="border-bottom">Cantidad de Postulaciones Recibidas</td>
                        @foreach ($memberships as $membership)
                        <td class="value">
                            @if ($membership->getFeatures()->application_count == -1)
                                Illimitadas
                            @else
                                {{ $membership->getFeatures()->application_count }}
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="border-bottom">Cantidad de Propiedades a Publicar</td>
                        @foreach ($memberships as $membership)
                        <td class="value">
                            @if ($membership->getFeatures()->properties_count== -1)
                                Illimitadas
                            @else
                                {{ $membership->getFeatures()->properties_count }}
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="border-bottom">Postulaciones a gestionar</td>
                        @foreach ($memberships as $membership)
                            <td class="value">
                                @if ($membership->getFeatures()->applications_received_count== -1)
                                    Illimitadas
                                @else
                                    {{ $membership->getFeatures()->applications_received_count }}
                                @endif
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="border-bottom">Scoring UHOMIE</td>
                        @foreach ($memberships as $membership)
                        <td class="value">
                            @if ($membership->getFeatures()->score_display)
                                <img src="{{ asset('images/icono_ok_'.strtolower($membership->name).'.png') }}">
                            @else
                            <img src="{{ asset('images/icono_cruz_'.strtolower($membership->name).'.png') }}">
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="border-bottom">%Comisión x Arriendo en c/Propiedad</td>
                        @foreach ($memberships as $membership)
                        <td class="value">
                            @if ($membership->getFeatures()->tenanting_fee == 0)
                                gratis
                            @else
                                {{ $membership->getFeatures()->tenanting_fee.' %' }}
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="border-bottom">Contacto c/ clientes</td>
                        @foreach ($memberships as $membership)
                        <td class="value">
                            {{ $membership->ownerContact }}
                        </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="border-bottom">Días continuos de membresía</td>
                        <td class="value">30</td>
                        <td class="value">30</td>
                        <td class="value">30</td>
                    </tr>
                    <tr>
                        <td class="border-bottom">Sugerencias a Potenciales clientes</td>
                        @foreach ($memberships as $membership)
                        <td class="value">
                            @if ($membership->getFeatures()->suggestions_to_tenants)
                                <img src="{{ asset('images/icono_ok_'.strtolower($membership->name).'.png') }}">
                            @else
                            <img src="{{ asset('images/icono_cruz_'.strtolower($membership->name).'.png') }}">
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td>Recomendaciones UHOMIE</td>
                        @foreach ($memberships as $membership)
                        <td class="value {{ strlen($membership->recommendationMessage) > 10 ? 'wide' : '' }}">
                            @if (strlen($membership->recommendationMessage))
                                {{ $membership->recommendationMessage }}
                            @else
                                <img src="{{ asset('images/icono_cruz_'.strtolower($membership->name).'.png') }}">
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td></td>
                        @foreach ($memberships as $membership)
                            <td><a class="button is-outlined is-{{strtolower($membership->name)}}" onclick="selectMembership({{ $membership->id }})" id="select_button_{{ $membership->id }}">seleccionar</a></td>
                        @endforeach
                    </tr>
                </table>
            </div>
            <div class="column side-info">
                <div class="info">
                    <img src="{{ asset('images/icono-atencion.png') }}">
                    <p>A los arrendadores les encantan arrendatarios con membresias <strong>Select</strong> o <strong>Premium</strong>. Destácate de otros postulantes y obtén las mejores propiedades en el menor tiempo.</p>
                    <div class="border"></div>
                </div>
                <div class="side-footer">
                    <a href="{{route('users.owners.fifth-step.one')}}" class="button">Atrás</a>
                    <form method="get" action="@route('users.owners.memberships-checkout')" id="form">
                      <input type="hidden" name="membership" id="input_membership">
                      <button type="button" class="button is-outlined is-primary" id="payment_button" onclick="makePayment();">
                        Pagar
                      </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <div id="back_dir" class="modal is-active">
        <div class="modal-background"></div>
        <div class="modal-card">
            <div class="has-text-centered">
                <img src="{{ asset('images/foco.png') }}">
            </div>
            <div class="modal-card-body">
                <div class="has-text-centered">
                    <h4>Atención!</h4>
                    <div class="columns">
                        <div class="column">
                            La transacción ha sido <b>{{$payment->statusname}}</b>
                        </div>
                    </div>
                </div>
                <div class="columns has-text-left">
                    <div class="column is-one-third"><b>Orden:</b></div>
                    <div class="column">{{$payment->order}}</div>
                </div>
                <div class="columns has-text-left">
                    <div class="column is-one-third"><b>Fecha de Orden:</b></div>
                    <div class="column">{{$payment->updated_at}}</div>
                </div>
                <div class="columns has-text-left">
                    <div class="column is-one-third"><b>Detalle de Orden:</b></div>
                    <div class="column">{{$payment->details}}</div>
                </div>
                <div class="columns has-text-left">
                    <div class="column is-one-third"><b>Monto:</b></div>
                    <div class="column">$ {{$payment->amount}} {{$payment->currency}}</div>
                </div>
                <div class="columns has-text-left">
                    <div class="column is-one-third"><b>Monto + IVA:</b></div>
                    <div class="column">$ {{$payment->total}} {{$payment->currency}}</div>
                </div>
                @if($payment->status == 2)
                <div class="columns has-text-left">
                    <div class="column is-one-third"><b>token_ws:</b></div>
                    <div class="column">{{$payment->token_ws}}</div>
                </div>
                @endif
            </div>
            <div class="has-text-centered" style="padding: 5px 0;">
                <a class="button is-info is-outlined" onclick="closeModal()">VOLVER</a>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
    <script type="text/javascript">
    function makePayment () {
      if($('#input_membership').val() == null || $('#input_membership').val() == 0){
        alert('No se selecciono ninguna membresia');
        return;
        }else {
            $('#form').submit();
        }
    }
    function selectMembership(id){
        $('.button').removeClass('is-active');
        $('#select_button_'+id).addClass('is-active');
        $('#input_membership').val(id);
    }
    function closeModal(){
        $('#back_dir').removeClass('is-active');
    }
    </script>

@endsection

@section('styles')
<style>
.overlay {
    position: fixed;
    z-index: 999999999999999999999;
    width: 100%;
    height: 100vh;
    top: 0;
    left: 0;
    background-color: rgba(2, 2, 2, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
}

.overlay.hidden {
    display: none;
}
</style>
@endsection
