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
                            <span>$ {{ $membership->getFeatures()->package_amount }} @if($membership->getFeatures()->package_amount)<span>+ IVA</span>@endif</span>
                            <span>(CLP)</span>
                        </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="border-bottom">Cantidad Publicaciones Mensuales</td>
                        @foreach ($memberships as $membership)
                        <td class="value">
                            @if ($membership->getFeatures()->services_counts == -1)
                                Ilimitadas
                            @else
                                {{$membership->getFeatures()->services_counts}}
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="border-bottom">Cantidad de Fotos por Proyecto</td>
                        @foreach ($memberships as $membership)
                        <td class="value">
                            @if ($membership->getFeatures()->photos_per_project == -1)
                                Illimitadas
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
                        <td class="border-bottom">Cantidad de Servicios Principales</td>
                        @foreach ($memberships as $membership)
                        <td class="value">
                            @if ($membership->getFeatures()->main_services == 0)
                                <img src="{{ asset('images/icono_cruz_'.strtolower($membership->name).'.png') }}">
                            @else
                                {{ $membership->getFeatures()->main_services }}
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="border-bottom">Cantidad de Servicios Secundarios</td>
                        @foreach ($memberships as $membership)
                        <td class="value">
                            @if ($membership->getFeatures()->secondary_services == 0)
                                <img src="{{ asset('images/icono_cruz_'.strtolower($membership->name).'.png') }}">
                            @else
                                {{ $membership->getFeatures()->secondary_services }}
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="border-bottom">Días continuos de la publicación</td>
                        @foreach ($memberships as $membership)
                        <td class="value">
                            @if ($membership->getFeatures()->project_due_days == -1)
                                Illimitados
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
                    <p>A los arrendadores les encantan arrendatarios con membresias <strong>Select</strong> o <strong>Premium</strong>. Destácate de otros postulantes y obtén las mejores propiedades en el menor tiempo.</p>
                    <div class="border"></div>
                </div>
                <div class="side-footer">{{--
                    @if( $user->getServiceCompany()->personal_publish )
                      <a href="{{route('users.services.second-step.one')}}" class="button">Atrás</a>
                    @else
                      <a href="{{route('users.services.second-step.two')}}" class="button">Atrás</a>
                    @endif
                    --}}
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
    </script>

@endsection
