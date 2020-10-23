@extends('layouts.flujo-base', ['navTitle' => '<span class="bold">Paso 1:</span> Elige tu medio de pago'])

@section('content')

<link rel="stylesheet" href="{{url('css/only_tooltip.css')}}">
<div class="container" id="ten-third-step-four">
    <section class="section main-section">
        <div class="columns">
            <div class="column flow-form">
                <div class="div">
                    <form method="POST" action="{{route('payments.short_stay')}}" id="payment_form">
                        {{ csrf_field() }}
                        <input type="hidden" name="property" value="{{ $property->id }}">
                        <input type="hidden" name="success_redir" value="@route('users.payments.congratulations.short_stay')">
                        <input type="hidden" name="back_url" value="@route('payments.short_stay.view', ['property_id' => $property->id])">
                        
                        <h1 class="form-title"><strong>Métodos de Pago</strong></h1>
                        <table id="tabla1">
                            <tr style="margin-bottom: 10px;">
                                <td class="border-bottom" style="padding: 10px 0px;">
                                    <div class="control">
                                        <label class="radio" style="letter-spacing: 1.5px;" >
                                            <input style="margin-right: 10px;" type="radio" name="payment_method" value="debit" checked >
                                            <strong>Pago con Tarjeta de Débito en pesos chilenos</strong>
                                        </label>
                                    </div>
                                <td>
                            </tr>
                            <tr>
                                <td class="border-bottom" style="padding: 10px 0px;">
                                    <div class="control">
                                        <label class="radio" style="letter-spacing: 1.5px;">
                                            <input style="margin-right: 10px;" type="radio" name="payment_method" value="credit_clp" checked >
                                            <b>Pago con Tarjeta de Crédito en pesos chilenos</b>
                                            
                                            
                                        </label>
                                        <!--
                                        <br>
                                        <label>
                                            gasto bla bla
                                        </label>
                                        -->
                                    </div>
                                <td>
                            </tr>
                            <tr>
                                <td class="border-bottom" style="padding: 10px 0px;">
                                    <div class="control">
                                        <label class="radio" style="letter-spacing: 1.5px;">
                                            <input style="margin-right: 10px;" type="radio" name="payment_method" value="credit_usd" >
                                            <b>Pago con Tarjeta de Crédito en USD</b>
                                        </label>
                                    </div>
                                <td>
                            </tr>
                        </table>
                        
                    </form>
                </div>
                
                <!--
                <div class="div" style="margin-top: 50px;">
                    <table id="tabla2">
                        <tr>
                            <td class="icono_payment"  style="padding-bottom: 10px; padding-top:10px; padding-right: 10px;">
                                <img src="{{ asset('images/icono-reembolso.png') }}">
                            </td>
                            <td  style="padding: 10px 0px;">
                                <span style="font-size: 0.75rem;">Reembolso: hasta 48 horas para reembolsar el dinero. Mes de arriendo y Garantía si no estás satisfecho</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="icono_payment"  style="padding-bottom: 10px; padding-top:10px; padding-right: 10px;">
                                <img src="{{ asset('images/icono-seguro.png') }}">
                            </td>
                            <td  style="padding: 10px 0px;">
                                <span style="font-size: 0.75rem;">Seguro y protegido: Conservamos tus fondos hasta que decidas liberarlo</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="icono_payment"  style="padding-bottom: 10px; padding-top:10px; padding-right: 10px;">
                                <img src="{{ asset('images/icono-ahorro.png') }}">
                            </td>
                            <td  style="padding: 10px 0px;">
                                <span style="font-size: 0.75rem;">Ahorro: con uHomie ahorraste más de un 40%</span>
                            </td>
                        </tr>
                    </table>
                </div>
                -->
                <!--
                <div class="form-footer">
                    <a href="{{route('users.tenants.third-step.three')}}" class="button is-outlined">Atrás</a>
                    <img src="{{ asset('images/icono_guardar.png') }}" id="save-button-icon">
                    <input type="submit" class="button is-outlined is-primary" value="Continuar" form="registration-form">
                </div>
                -->
            </div>
            <div class="column" style="background-color:rgb(243, 243, 243);">
                <div style="padding: 30px 30px;">
                    <!--
                    <div class="columns">
                        <div class="column is-half">
                            <span class="with-line"  ><b style="margin-right: 10px">Mes(es) de Garantía</b><span  class="tooltip is-tooltip-multiline" data-tooltip="Este mes representa el mes de garantía que indico el dueño en las condiciones de arriendo"><img width="20px" src="{{asset('images/icono_info.png')}}"></span></span>
                        </div>
                        <div class="column is-half">
                            <span class="with-line">
                                <div class="level">
                                    <span class="level-left">$ {{number_format($property->rent*$property->warranty_months_quantity,0,',','.')}}</span>
                                    <span class="level-right">CLP</span>
                                </div>
                            </span>
                        </div>
                    </div>
                    -->
                    <div class="columns">
                        <div class="column is-half">
                            <span class="with-line"  ><b class="letras-responsive" style="margin-right: 5px">Precio Base arriendo por día</b><span  class="tooltip is-tooltip-multiline" id="tt_rent" data-tooltip="Este mes representa el mes de garantía que indico el dueño en las condiciones de arriendo"><img class="icono-responsive" width="20px" src="{{asset('images/icono_info.png')}}"></span></span>
                        </div>
                        <div class="column is-half">
                            <span class="with-line">
                                <div class="level">
                                    <span class="level-left" id="monto_arriendo_por_dia">$ {{number_format($property->rent,0,',','.')}}</span>
                                    <span class="level-right">CLP</span>
                                </div>
                            </span>
                        </div>
                    </div>
                    <!--
                    <table style="width: 100%">
                        <tr>
                            <td style="padding-right:20px;width:40%">
                                <span class="with-line"  ><b style="margin-right: 5px; font-size:calc(3px + 1vw)">Mes(es) de Garantía</b><span  class="tooltip is-tooltip-multiline" data-tooltip="Este mes representa el mes de garantía que indico el dueño en las condiciones de arriendo"><img style="width:1vw;" src="{{asset('images/icono_info.png')}}"></span></span>
                            </td>
                            <td style="padding-left:20px;width:40%">
                                <span class="with-line">
                                    <div class="level">
                                        <span class="level-left">$ {{number_format($property->rent*$property->warranty_months_quantity,0,',','.')}}</span>
                                        <span class="level-right">CLP</span>
                                    </div>
                                </span>
                            </td>
                        </tr>
                    </table>
                    -->
                    <div class="columns">
                        <div class="column is-half">
                            <span class="with-line"><b class="letras-responsive" style="margin-right: 10px">Días por arrendar</b><span class="tooltip is-tooltip-multiline" data-tooltip="Esto representa la cantidad de dias que elegiste para alojar"><img class="icono-responsive" width="20px" src="{{asset('images/icono_info.png')}}"></span></span>
                        </div>
                        <div class="column is-half">
                            <span class="with-line">
                                <div class="level">
                                    <span class="level-left">{{ $days }} Días</span>
                                    <span class="level-right"></span>
                                </div>
                            </span>
                        </div>
                    </div>
                    <div class="columns">
                        <div class="column is-half">
                            <span class="with-line">
                                <b class="letras-responsive" style="margin-right: 10px">Tarifa por Limpieza</b>
                                <span class="tooltip is-tooltip-multiline" data-tooltip="Esto representa el valor de la tarifa por limpieza por la estadía en esta propiedad">
                                    <img class="icono-responsive" width="20px" src="{{asset('images/icono_info.png')}}">
                                </span>
                            </span>
                        </div>
                        <div class="column is-half">
                            <span class="with-line">
                                <div class="level">
                                    <span class="level-left" id="monto_tarifa_limpieza">$ {{number_format($property->cleaning_rate,0,',','.')}} </span>
                                    <span class="level-right">CLP</span>
                                </div>
                            </span>
                        </div>
                    </div>
                    <!--
                    <div class="columns">
                        <div class="column is-half">
                            <div class="field">
                                <input id="switchRoundedOutlinedInfo" type="checkbox" name="switchRoundedOutlinedInfo" class="switch is-rounded is-outlined is-info" checked="checked">
                                <label for="switchRoundedOutlinedInfo">Acepto</label>
                            </div>
                        </div>
                    </div>
                    -->
                    <div class="columns">
                        <div class="column is-half">
                            <span class="with-line"><b class="letras-responsive" style="margin-right: 10px">Servicio Digital uHomie</b><span class="tooltip is-tooltip-multiline" data-tooltip="Esto equivale al 11% de la transaccion"><img class="icono-responsive" width="20px" src="{{asset('images/icono_info.png')}}"></span></span>
                        </div>
                        <div class="column is-half">
                            <span class="with-line">
                                <div class="level">
                                    <span class="level-left" id="servicio_digital">$ {{number_format((($property->rent*$days+$property->cleaning_rate)*0.07), 0, ",", ".")}}</span>
                                    <span class="level-right">CLP</span>
                                </div>
                            </span>
                        </div>
                    </div>
                    <div class="columns">
                        <div class="column is-half">
                            <span ><b class="letras-responsive" style="margin-right: 10px">Total</b>&nbsp;</span>
                        </div>
                        <div class="column is-half">
                            <span class="with-line">
                                <div class="level">
                                    <span class="level-left" id="total"></span>
                                    <span class="level-right">CLP</span>
                                </div>
                            </span>
                        </div>
                    </div>
                    <br>
                    <div>
                    </div>
                </div>
                
                <div class="has-text-centered">
                    <button form="payment_form" type="submit" class="button is-outlined is-primary ">PAGAR</button>
                </div>
                <div style="padding: 30px 30px;">
                    <span style="font-size: 0.75rem;">Por seguridad este dinero será resguardado por uHomie y no será transferido al dueño de la propiedad
                        hasta tanto el arrendatario confirma la recepción de las llaves o después de 48 horas de firmar
                        el contrato digital
                    </span>
                </div>
                <div style="padding: 30px 30px;">
                    <img src="{{asset('images/ssl.png')}}" width="70px">
                    <img id="imgWP" hidden src="{{asset('images/paypal.png')}}" width="70px">
                    <img id="imgPP" hidden src="{{asset('images/webpay.png')}}" width="70px">
                </div>
            </div>
        </div>
        
    </section>
</div>
<style>
.control input[type="radio"] {
    border-radius: 100%;
    border-color: #00a2ff;
}
td.border-bottom {
    border-bottom: 1px solid #0a0a0a;
}
.with-line {
    border-bottom: 1px solid #333;
    min-height: 34px;
    padding: 0px;
    margin: 0px;
    display: flex;
}
@media screen and (min-width: 1366px) {
    .section {
        padding: 3rem 5rem;
    }
}
@media screen and (min-width: 816px) and (max-width: 1920px){
    .letras-responsive {
        font-size:calc(2px + 0.95vw);
    }
    .icono-responsive {
        width:1.2vw;
    }
}
    

</style>
@include('components.users.common-forms.save-button.modal')
@endsection

@section('scripts')
<script src="{{ asset('js/third-step-three.js') }}"></script>
<script>
    function calcularTotal(){
        let a = 0;
        let tarifa_limpieza = {{$property->cleaning_rate}};
        @if( $property->week_sale > 0 && $days >= 7 && $days < 30)
        let pre_rent = {{$property->rent*(1-($property->week_sale/100))}};
        @elseif( $property->month_sale > 0 && $days >= 30 )
        let pre_rent = {{$property->rent*(1-($property->month_sale/100))}};
        @else
        let pre_rent = {{$property->rent}};
        @endif
        
        let rent = pre_rent*{{$days}};
        a += rent;
        @if($property->special_sale == 1 && $cantidad_arriendos <= 10)
        $('#tt_rent').attr('data-tooltip', 'Este valor representa el valor del precio base de arriendo por día menos el 10% por ser uno de los primeros huéspedes')
        let descuento_unitario = {{$property->rent}}*0.1;
        let descuento_primeros_huespedes = {{$property->rent*$days}}*0.1; 
        a -= descuento_primeros_huespedes;
        $('#monto_arriendo_por_dia').html('$ '+pre_rent.toLocaleString('de-DE')+' - $ '+descuento_unitario.toLocaleString('de-DE')); 
        console.log('a con el dscto esta quedando asi: '+a);
        @else
        $('#tt_rent').attr('data-tooltip', 'Este valor representa el valor del precio base de arriendo por día')
        @endif
        console.log('a esta quedando asi: '+a);
        a += tarifa_limpieza;
        console.log('a mas tarifa limpieza esta  quedando asi: '+a);
        let total = a;
        if( $("input[name='payment_method']:checked").val() == 'debit' ){
            $('#imgWP').prop('hidden', true);
            $('#imgPP').prop('hidden', false);
            
            total = a *1.07;
            $('#servicio_digital').html('$ '+(a*0.07).toLocaleString('de-DE'));
        }
        if( $("input[name='payment_method']:checked").val() == 'credit_clp' ){
            $('#imgWP').prop('hidden', true);
            $('#imgPP').prop('hidden', false);
            total = a *1.11;
            $('#servicio_digital').html('$ '+(a*0.11).toLocaleString('de-DE'));
        }
        if( $("input[name='payment_method']:checked").val() == 'credit_usd' ){
            $('#imgWP').prop('hidden', false);
            $('#imgPP').prop('hidden', true);
            total = a *1.3;
            $('#servicio_digital').html('$ '+(a*0.3).toLocaleString('de-DE'));
        }

        
        $('#total').html('$ '+total.toLocaleString('de-DE'));
        $('#total').html('$ '+total.toLocaleString('de-DE'));
    };
    
    $(function(){
        
        calcularTotal();

        $('#payment_form').change(function(){
            calcularTotal();
        });
    });

</script>
@endsection
