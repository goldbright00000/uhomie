<!--  Vista de emergencia para el upgrade de membresia  -->
@extends('layouts.app')

<div class="toolbar">
    <div class="container">
        <div>
          <img src="{{ asset('images/icons/logo_completo.png') }}">
        	<span class="nav-title is-hidden-mobile">Mejora tu Membresía</span>
        </div>
        <button  onclick="window.history.back();" class="button is-outlined is-primary">Volver</button>
    </div>
    
	    <div id="title-mobile" class="container is-hidden-tablet">
	    	<span class="nav-title">Mejora tu Membresía</span>
	    </div>
	  
</div>

<div class="container" id="fourth-step">
    <section class="section main-section">
        <div class="columns">
            <div class="column is-8">
                <table class="plans-table">
                    <tr>
                        <td></td>
                        <!--<td class="border-bottom border-basic">
                            <img src="{{ asset('images/logo_basic.png') }}">
                        </td>-->
                        @if($user->getTenantMerbership()->name == 'Basic')
                        <td class="border-bottom border-select">
                            <img src="{{ asset('images/logo_select.png') }}">
                        </td>
                        @endif
                        @if($user->getTenantMerbership()->name != 'Premium' )
                        <td class="border-bottom border-premium">
                            <img src="{{ asset('images/logo_premium.png') }}">
                        </td>
                        @endif
                    </tr>
                    <tr>
                        <td></td>
                        @foreach ($memberships as $membership)
                        @if($membership->name != 'Basic' && $user->getTenantMerbership()->name != $membership->name)
                        <td class="price">
                            <span>$ {{ $membership->getFeatures()->package_amount }} @if($membership->getFeatures()->package_amount)<span>+ IVA</span>@endif</span>
                            <span>(CLP)</span>
                        </td>
                        @endif
                        @endforeach
                    </tr>
                    <tr>
                        <td class="border-bottom">Cantidad de Postulaciones disponibles</td>
                        @foreach ($memberships as $membership)
                        @if($membership->name != 'Basic' && $user->getTenantMerbership()->name != $membership->name)
                        <td class="value">
                            @if ($membership->getFeatures()->applications_received_count == -1)
                                Illimitadas
                            @else
                                {{ $membership->getFeatures()->applications_received_count }}
                            @endif
                        </td>
                        @endif
                        @endforeach
                    </tr>
                    <tr>
                        <td class="border-bottom">Scoring UHOMIE</td>
                        @foreach ($memberships as $membership)
                        @if($membership->name != 'Basic' && $user->getTenantMerbership()->name != $membership->name)
                        <td class="value">
                            @if ($membership->getFeatures()->score_display)
                                <img src="{{ asset('images/icono_ok_'.strtolower($membership->name).'.png') }}">
                            @else
                                <img src="{{ asset('images/icono_cruz_'.strtolower($membership->name).'.png') }}">
                            @endif
                        </td>
                        @endif
                        @endforeach
                    </tr>
                    <tr>
                        <td class="border-bottom">Cantidad de Propiedades a visualizar</td>
                        @foreach ($memberships as $membership)
                        @if($membership->name != 'Basic' && $user->getTenantMerbership()->name != $membership->name)
                        <td class="value">
                            @if ($membership->getFeatures()->display_all_properties)
                                <img src="{{ asset('images/icono_ok_'.strtolower($membership->name).'.png') }}">
                            @else
                            <img src="{{ asset('images/icono_cruz_'.strtolower($membership->name).'.png') }}">
                            @endif
                        </td>
                        @endif
                        @endforeach
                    </tr>
                    <tr>
                        <td class="border-bottom">Comisión por Arriendo</td>
                        @foreach ($memberships as $membership)
                        @if($membership->name != 'Basic' && $user->getTenantMerbership()->name != $membership->name)
                        <td class="value">
                            @if ($membership->getFeatures()->commission == 0)
                                gratis
                            @else
                                {{ $membership->getFeatures()->commission.' %' }}
                            @endif
                        </td>
                        @endif
                        @endforeach
                    </tr>
                    <tr>
                        <td class="border-bottom">Contacto c/ propietarios</td>
                        @foreach ($memberships as $membership)
                        @if($membership->name != 'Basic' && $user->getTenantMerbership()->name != $membership->name)
                        <td class="value">
                            {{ $membership->ownerContact }}
                        </td>
                        @endif
                        @endforeach
                    </tr>
                    <tr>
                        <td class="border-bottom">Días continuos de membresía</td>
                        @foreach ($memberships as $membership)
                        @if($membership->name != 'Basic' && $user->getTenantMerbership()->name != $membership->name)
                        <td class="value">30</td>
                        @endif
                        @endforeach
                        <!--<td class="value">30</td>
                        <td class="value">30</td>-->
                    </tr>
                    <tr>
                        <td class="border-bottom">Sugerencias a Potenciales propietarios</td>
                        @foreach ($memberships as $membership)
                        @if($membership->name != 'Basic' && $user->getTenantMerbership()->name != $membership->name)
                        <td class="value">
                            @if ($membership->getFeatures()->suggestions_to_owners)
                                <img src="{{ asset('images/icono_ok_'.strtolower($membership->name).'.png') }}">
                            @else
                            <img src="{{ asset('images/icono_cruz_'.strtolower($membership->name).'.png') }}">
                            @endif
                        </td>
                        @endif
                        @endforeach
                    </tr>
                    <tr>
                        <td>Recomendaciones UHOMIE</td>
                        @foreach ($memberships as $membership) 
                        @if($membership->name != 'Basic' && $user->getTenantMerbership()->name != $membership->name)
                        <td class="value {{ strlen($membership->recommendationMessage) > 10 ? 'wide' : '' }}">
                            @if (strlen($membership->recommendationMessage))
                                {{ $membership->recommendationMessage }}
                            @else
                                <img src="{{ asset('images/icono_cruz_'.strtolower($membership->name).'.png') }}">
                            @endif
                        </td>
                        @endif
                        @endforeach
                    </tr>
                    <tr>
                        <td></td>
                        @foreach ($memberships as $membership)
                        
                        @if($membership->name != 'Basic' && $user->getTenantMerbership()->name != $membership->name)
                            <td><a class="button is-outlined is-{{strtolower($membership->name)}}" onclick="selectMembership({{ $membership->id }})" id="select_button_{{ $membership->id }}">seleccionar</a></td>
                        @endif
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
                    <a href="{{route('users.tenants.third-step.four')}}" class="button">Atrás</a>
                    <form method="get" action="@route('users.tenants.memberships-checkout-update')" id="form">
                      <input type="hidden" name="membership" id="input_membership">
                      <button type="button" class="button is-outlined is-primary" id="payment_button" onclick="makePayment();">
                        Pagar
                      </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

@section('flow-styles')
<link rel="stylesheet" href="{{ asset('css/flujo.css') }}">
@endsection
@section('flow-scripts')
    <script src="{{ asset('js/save-button.js') }}"></script>
@endsection

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
}
</script>


