@extends('layouts.flujo-base', ['navTitle' => '<span class="bold"> Elige tu Membresía</span>', 'close' => route($close)])

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
                            <span>UF {{ $membership->getFeatures()->package_amount }} @if($membership->getFeatures()->package_amount)<span>+ IVA</span>@endif</span>
                        </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="border-bottom">Cantidad Publicaciones Mensuales</td>
                        @foreach ($memberships as $membership)
                        <td class="value">
                            @if ($membership->getFeatures()->montly_publications == -1)
                                Ilimitadas
                            @else
                                {{$membership->getFeatures()->montly_publications}}
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="border-bottom">Cantidad de Fotos por Proyecto</td>
                        @foreach ($memberships as $membership)
                        <td class="value">
                            @if ($membership->getFeatures()->photos_per_project == -1)
                                Ilimitadas
                            @else
                                {{ $membership->getFeatures()->photos_per_project }}
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="border-bottom">Cantidad de Videos en la publicación</td>
                        @foreach ($memberships as $membership)
                        <td class="value">
                            @if ($membership->getFeatures()->videos_per_project == 0)
                                <img src="{{ asset('images/icono_cruz_'.strtolower($membership->name).'.png') }}">
                            @else
                                {{ $membership->getFeatures()->videos_per_project }}
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="border-bottom">Días continuos de la publicación</td>
                        @foreach ($memberships as $membership)
                        <td class="value">
                            @if ($membership->getFeatures()->project_due_days == -1)
                                Ilimitados
                            @else
                                {{ $membership->getFeatures()->project_due_days }}
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="border-bottom">Contacto c/ Potenciales Clientes</td>
                        @foreach ($memberships as $membership)
                        <td class="value">
                            {{ $membership->ownerContact }}
                        </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="border-bottom">Publicidad en zonas especiales WEB</td>
                        @foreach ($memberships as $membership)
                        <td class="value">
                            @if ($membership->getFeatures()->public_support)
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
                    <p>A los potenciales clientes les encantan los proyectos con membresias <strong>Select</strong> o <strong>Premium</strong>. Destácate de otras empresas y obtén las mejores postulaciones.</p>
                    <div class="border"></div>
                </div>
                <div class="side-footer">

                    <a href="{{route($back)}}" class="button">Atrás</a>
                    <form method="get" action="@route($route)" id="form">
                      <input type="hidden" name="membership" id="input_membership">
                      <button type="button" class="button is-outlined is-primary" id="payment_button" onclick="makePayment();">
                        Pagar
                      </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    @if(!empty($payment))
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
                            La transacción ha sido <b>{{isset($payment->statusname) ? $payment->statusname : ''}}</b>
                        </div>
                    </div>
                </div>
                <div class="columns has-text-left">
                    <div class="column is-one-third"><b>Orden:</b></div>
                    <div class="column">{{isset($payment->order) ? $payment->order : ''}}</div>
                </div>
                <div class="columns has-text-left">
                    <div class="column is-one-third"><b>Fecha de Orden:</b></div>
                    <div class="column">{{isset($payment->updated_at) ? $payment->updated_at : ''}}</div>
                </div>
                <div class="columns has-text-left">
                    <div class="column is-one-third"><b>Detalle de Orden:</b></div>
                    <div class="column">{{isset($payment->details) ? $payment->details : ''}}</div>
                </div>
                <div class="columns has-text-left">
                    <div class="column is-one-third"><b>Monto:</b></div>
                    <div class="column">$ {{isset($payment->amount) ? $payment->amount : ''}} {{isset($payment->currency) ? $payment->currency : ''}}</div>
                </div>
                <div class="columns has-text-left">
                    <div class="column is-one-third"><b>Monto + IVA:</b></div>
                    <div class="column">$ {{isset($payment->total) ? $payment->total : ''}} {{isset($payment->currency) ? $payment->currency : ''}}</div>
                </div>
                @if(isset($payment->status) == 2)
                <div class="columns has-text-left">
                    <div class="column is-one-third"><b>token_ws:</b></div>
                    <div class="column">{{isset($payment->token_ws) ? $payment->token_ws : ''}}</div>
                </div>
                @endif
            </div>
            <div class="has-text-centered" style="padding: 5px 0;">
                <a class="button is-info is-outlined" onclick="closeModal()">VOLVER</a>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@section('scripts')
    <script src="{{ asset('js/third-step-three.js') }}"></script>
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
        $('#select_button_'+id).hasClass('is-basic') ? $('#payment_button').html('CONTINUAR') : $('#payment_button').html('PAGAR') 
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