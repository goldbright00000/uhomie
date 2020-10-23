@extends('layouts.flujo-base', ['navTitle' => '<span class="bold">Paso 4:</span> Elige tu Membresía', 'close' => route($close)])

@section('content')
<div class="container" id="fourth-step">
    <section class="section main-section">
        <div class="columns">
            <div class="column is-6">
                <div class="membership-info ">
                    <div class="membership-title-{{ strtolower($membership->name) }}">
                        <span>Elegiste</span>
                        <span>membresía</span>
                        <span>{{ $membership->name }}</span>
                    </div>
                    <a href="{{route($route)}}" class="button is-outlined is-primary">Cambiar</a>
                </div>
                <table class="plans-table">
                    <tr>
                        <td class="td-title">Su plan incluye</td>
                        <td class="price">
                            <span>$ {{ $membership->getFeatures()->package_amount }} @if($membership->getFeatures()->package_amount)<span>+ IVA</span>@endif</span>
                            <span>(CLP)</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="border-bottom">Cantidad Publicaciones Mensuales</td>
                        <td class="value">
                            @if ($membership->getFeatures()->services_counts == -1)
                                Illimitadas
                            @else
                                {{$membership->getFeatures()->services_counts}}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="border-bottom">Cantidad de Fotos por Proyecto</td>
                        <td class="value">
                            @if ($membership->getFeatures()->photos_per_project == -1)
                                Illimitadas
                            @else
                                {{ $membership->getFeatures()->photos_per_project }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="border-bottom">Cantidad de Videos en la publicación</td>
                        <td class="value">
                            @if ($membership->getFeatures()->videos_per_project == 0)
                                <img src="{{ asset('images/icono_cruz_'.strtolower($membership->name).'.png') }}">
                            @else
                                {{ $membership->getFeatures()->videos_per_project }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="border-bottom">Cantidad de Servicios Principales</td>
                        <td class="value">
                            @if ($membership->getFeatures()->main_services == 0)
                                <img src="{{ asset('images/icono_cruz_'.strtolower($membership->name).'.png') }}">
                            @else
                                {{ $membership->getFeatures()->main_services }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="border-bottom">Cantidad de Servicios Secundarios</td>
                        <td class="value">
                            @if ($membership->getFeatures()->secondary_services == 0)
                                <img src="{{ asset('images/icono_cruz_'.strtolower($membership->name).'.png') }}">
                            @else
                                {{ $membership->getFeatures()->secondary_services }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="border-bottom">Días continuos de la publicación</td>
                        <td class="value">
                            @if ($membership->getFeatures()->project_due_days == -1)
                                Illimitados
                            @else
                                {{ $membership->getFeatures()->project_due_days }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="border-bottom">Contacto c/ Potenciales Clientes</td>
                        <td class="value">
                            {{ $membership->ownerContact }}
                        </td>
                    </tr>
                    <tr>
                        <td class="border-bottom">Publicidad en zonas especiales WEB</td>
                        <td class="value">
                            @if ($membership->getFeatures()->public_support)
                                <img src="{{ asset('images/icono_ok_'.strtolower($membership->name).'.png') }}">
                            @else
                            <img src="{{ asset('images/icono_cruz_'.strtolower($membership->name).'.png') }}">
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Recomendaciones UHOMIE</td>
                        <td class="value {{ strlen($membership->recommendationMessage) > 10 ? 'wide' : '' }}">
                            @if (strlen($membership->recommendationMessage))
                                {{ $membership->recommendationMessage }}
                            @else
                                <img src="{{ asset('images/icono_cruz_'.strtolower($membership->name).'.png') }}">
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
            <div class="column side-payment is-5 is-offset-1">
                <form class="" action="@route('payments.memberships')" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="membership" value="{{ $membership->id }}">
                    <input type="hidden" name="success_redir" value="{{url($success)}}/">
                    <input type="hidden" name="back_url" value="{{url($back)}}/">
                    <h1 class="{{ strtolower($membership->name) }}-title">Métodos de pago</h1>
                    <span>Recuerda que podrás renovar y <br>visualizar tu membresía desde <br>tu panel.</span>
                    <h2>Tu cupón</h2>
                    <span>Si tienes un cupón de descuento <br>puedes canjearlo aquí.</span>
                    <div class="coupon">
                        <input style="width: 40%;" class="input" type="text" name="coupon"  id="input_cupon" placeholder="Ingresa el codigo del cupón">
                        <button id="botonComprobarCupon" type="button" class="button is-outlined is-info">VERIFICAR</button>
                        <span id="span_check" ><img src="{{ asset('images/icono-tilde-azul.png') }}"> Cupón aplicado correctamente</span>
                        <div class="lds-css ng-scope" id="span_loading" style="display: none;"><div style="width:100%;height:100%" class="lds-eclipse"><div></div></div><style type="text/css">@keyframes lds-eclipse {
                            0% {
                                -webkit-transform: rotate(0deg);
                                transform: rotate(0deg);
                            }
                            50% {
                                -webkit-transform: rotate(180deg);
                                transform: rotate(180deg);
                            }
                            100% {
                                -webkit-transform: rotate(360deg);
                                transform: rotate(360deg);
                            }
                            }
                            @-webkit-keyframes lds-eclipse {
                            0% {
                                -webkit-transform: rotate(0deg);
                                transform: rotate(0deg);
                            }
                            50% {
                                -webkit-transform: rotate(180deg);
                                transform: rotate(180deg);
                            }
                            100% {
                                -webkit-transform: rotate(360deg);
                                transform: rotate(360deg);
                            }
                            }
                            .lds-eclipse {
                            position: relative;
                            }
                            .lds-eclipse div {
                            position: absolute;
                            -webkit-animation: lds-eclipse 1s linear infinite;
                            animation: lds-eclipse 1s linear infinite;
                            width: 160px;
                            height: 160px;
                            top: 20px;
                            left: 20px;
                            border-radius: 50%;
                            box-shadow: 0 4px 0 0 #1d3f72;
                            -webkit-transform-origin: 80px 82px;
                            transform-origin: 80px 82px;
                            }
                            .lds-eclipse {
                            width: 34px !important;
                            height: 34px !important;
                            -webkit-transform: translate(-17px, -17px) scale(0.17) translate(17px, 17px);
                            transform: translate(-17px, -17px) scale(0.17) translate(17px, 17px);
                            }
                            </style></div>
                    </div>
                    {{-- <h3 class="total-price before">$ 14.750 <span>+ IVA</span></h3> --}}
                    <h3 class="total-price">$ <span id="span_monto" style="font-size: 1.15rem;font-weight: 500;">{{ $membership->getFeatures()->package_amount }}</span> CLP<span>+ IVA</span></h3>
                    <div class="control">
                        <label class="radio" >
                            <input type="radio" name="payment_method" value="transbank" checked >
                            <img src="{{ asset('images/icono-transbank.jpg') }}">
                            <img src="{{ asset('images/icono-visa.png') }}">
                            <img src="{{ asset('images/icono-mastercard.png') }}">
                            <img src="{{ asset('images/icono-americanexpress.png') }}">
                        </label>
                        <label class="radio" >
                            <input type="radio" name="payment_method" value="paypal" disabled >
                            <img src="{{ asset('images/icono-paypal.png') }}">
                        </label>
                    </div>
                    <hr>
                    <div style="text-align: center">
                        <input style="display: inline-block" type="submit" name="submit" value="PAGAR" class="button is-outlined is-primary">
                    </div>
                    {{-- <div id="paypal-button"></div> --}}
                    <input type="hidden" id="inputCupon" name="cupon" value="">
                    <input type="hidden" id="input-membership" name="input-membership" value="{{$_GET['membership']}}"/>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection

@section('scripts')
<!--<script src="{{ asset('js/third-step-three.js') }}"></script>-->
{{-- <script src="https://www.paypalobjects.com/api/checkout.js"></script>
<script>
  paypal.Button.render({
    // Configure environment
    env: 'sandbox',
    client: {
      sandbox: 'demo_sandbox_client_id',
      production: 'demo_production_client_id'
    },
    // Customize button (optional)
    locale: 'en_US',
    style: {
      size: 'medium',
      color: 'blue',
      shape: 'rect',
    },
    // Set up a payment
    payment: function(data, actions) {
      return actions.payment.create({
        transactions: [{
          amount: {
            total: '0.01',
            currency: 'USD'
          }
        }]
      });
    },
    // Execute the payment
    onAuthorize: function(data, actions) {
      return actions.payment.execute().then(function() {
        // Show a confirmation message to the buyer
        window.alert('Thank you for your purchase!');
        var global_response = ""
        $.ajax({
            headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            async:false,
            cache:false,
            type:'post'
        })
        .done(function(response){
            window.location.replace("{{ route('users.services.second-step.three') }}");
        })
      });
    }
  }, '#paypal-button');
</script> --}}
<script>
    $(function(){
        $('#span_check').hide();
    });
    var monto = {{ str_replace('.', '',$membership->getFeatures()->package_amount) }};
    toastr.options = {"preventDuplicates": true};
    $('#botonComprobarCupon').click(function(event){
        
        $(this).prop('disabled', true);
        if ( $('#input_cupon').val().length < 1 ){
            $('#span_check').hide();
            $('#span_monto').html(monto.toLocaleString('de-DE'));
            if(monto > 0){
                $('#boton_pagar').attr('value', 'PAGAR');
            }
            $('#inputCupon').val($(this).val());
            return;
        }
        $('#span_loading').css({display: 'inline-block'});
        var self = $('#input_cupon');
        var idmembership = $('#input-membership').val();
        $.ajax({
            url: '/check-coupon',
            method: 'POST',
            headers: {'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')},
            data: { code: $(self).val(), membership: idmembership },
            success: function(response){
                $('#span_loading').css({display: 'none'});
                $('#botonComprobarCupon').prop('disabled', false);
                if ( $(self).val().length < 1 ){
                    $('#span_check').hide();
                    $('#span_monto').html(monto.toLocaleString('de-DE'));
                    if(monto > 0){
                        $('#boton_pagar').attr('value', 'PAGAR');
                    }
                    $('#inputCupon').val($(self).val());
                    return;
                }
                if(response.respuesta){
                    $('#span_check').show();
                    let nuevo_monto = monto - response.monto_dscto;
                    $('#span_monto').html(Math.max(0, nuevo_monto).toLocaleString('de-DE'));
                    if(nuevo_monto <= 0){
                        $('#boton_pagar').attr('value', 'FINALIZAR');
                    } else {
                        $('#boton_pagar').attr('value', 'PAGAR');
                    }
                    $('#inputCupon').val($(self).val());
                }else{
                    $('#span_check').hide();
                    $('#span_monto').html(monto.toLocaleString('de-DE'));
                    if(monto > 0){
                        $('#boton_pagar').attr('value', 'PAGAR');
                    }
                    $('#inputCupon').val('');
                    if( response.hasOwnProperty('razon') ){
                        toastr.info(response.razon);
                    }
                }
            },
            //async: false,
            error: function(){
                $('#span_loading').css({display: 'none'});
                $('#botonComprobarCupon').prop('disabled', false);
                console.log('error en check-coupon');
                $('#span_check').hide();
                $('#span_monto').html(monto.toLocaleString('de-DE'));
                if(monto > 0){
                    $('#boton_pagar').attr('value', 'PAGAR');
                }
                $('#inputCupon').val($(self).val(''));
            }
        });
        
    });
</script>
@endsection
