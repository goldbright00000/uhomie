@extends('layouts.flujo-base', ['navTitle' => '<span class="bold">Paso 1:</span> Elige tu medio de pago'])

@section('content')
<link rel="stylesheet" href="{{url('css/only_tooltip.css')}}">
<div class="container" id="ten-third-step-four">
    <section class="section main-section">
        <div class="columns">
            <div class="column flow-form">
                <div class="div">
                    <form method="POST" action="{{route('payments.rents')}}" id="payment_form">
                        {{ csrf_field() }}
                        <input type="hidden" name="property" value="{{ $property->id }}">
                        <input type="hidden" name="success_redir" value="@route('users.payments.congratulations')">
                        <input type="hidden" name="back_url" value="@route('users.payments.step-one', ['property_id' => $property->id])">
                        <!--
                        <div class="form-info">
                            <div class="text">
                                <span>Solicitamos opcionalmente</span>
                                <span>el Reporte de DICOM.</span>
                                <span>Informe de Arriendo</span>
                            </div>
                            <img src="{{ asset('images/icono-atencion.png') }}">
                        </div>
                        <div class="download-link">
                            <img src="{{ asset('images/icono-descargar.png') }}">
                            <a href="https://soluciones.equifax.cl/" target="_blank">lo puedes descargar de aquí</a>
                        </div>
                        -->
                        <h1 class="form-title"><strong>Métodos de Pago</strong></h1>
                        <table id="tabla1">
                            <tr style="margin-bottom: 10px;">
                                <td class="border-bottom" style="padding: 10px 0px;">
                                    <div class="control">
                                        <label class="radio" style="letter-spacing: 1.5px;" >
                                            <input style="margin-right: 10px;" type="radio" name="payment_method" value="credit_clp" checked >
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
                            <!--
                            <tr>
                                <td class="border-bottom" style="padding: 10px 0px;">
                                    <div class="control">
                                        <label class="radio" style="letter-spacing: 1.5px;">
                                            <input style="margin-right: 10px;" type="radio" name="payment_method" value="khipu" disabled >
                                            <b>Pago con Transferencia Online Khipu</b>
                                        </label>
                                    </div>
                                <td>
                            </tr>
                            -->
                        </table>
                        <h1 class="form-title" style="margin-bottom:15px;"><strong>Seguro de Arriendo HDI</strong></h1>
                        <nav class="level">
                            <!-- Left side -->
                            <div class="level-left">
                                <div class="level-item">
                                <a target="_blank" href="{{url('documento_seguros')}}"><img src="{{asset('images/icons/contratos_hover.png')}}" width="25px"></a>
                                    <p class="subtitle is-5" style="font-size:12px;">
                                    &nbsp;Descargar archivo
                                    </p>
                                </div>
                            </div>
                        </nav>
                        
                        
                        <div class="field" style="margin-top:10px;">
                            <input id="switchRoundedOutlinedInfo" type="checkbox" name="seguro_check" class="switch is-rounded is-outlined is-info" checked>
                            <label id="labelSeguro" for="switchRoundedOutlinedInfo">Acepto</label>
                        </div>
                    </form>
                </div>
                
                
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
                            <span class="with-line"  ><b class="letras-responsive" style="margin-right: 5px">Mes(es) de Garantía</b><span  class="tooltip is-tooltip-multiline" data-tooltip="Este mes representa el mes de garantía que indico el dueño en las condiciones de arriendo"><img class="icono-responsive" width="20px" src="{{asset('images/icono_info.png')}}"></span></span>
                        </div>
                        <div class="column is-half">
                            <span class="with-line">
                                <div class="level">
                                    <span class="level-left" >$ {{number_format($property->rent*$property->warranty_months_quantity,0,',','.')}}</span>
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
                            <span class="with-line"><b class="letras-responsive" style="margin-right: 10px">Mes de Adelanto</b><span class="tooltip is-tooltip-multiline" data-tooltip="Este mes representa  el monto indicado por el dueño en las condiciones de arriendo"><img class="icono-responsive" width="20px" src="{{asset('images/icono_info.png')}}"></span></span>
                        </div>
                        <div class="column is-half">
                            <span class="with-line">
                                <div class="level">
                                    <span class="level-left">$ {{number_format($property->rent*$property->months_advance_quantity,0,',','.')}}</span>
                                    <span class="level-right">CLP</span>
                                </div>
                            </span>
                        </div>
                    </div>
                    <div class="columns">
                        <div class="column is-half">
                            <span class="with-line">
                                <b class="letras-responsive" style="margin-right: 10px">Seguro Arriendo HDI</b>
                                <span class="tooltip is-tooltip-multiline" data-tooltip="Este representa la prima Anual pagada @if($property->tenanting_insurance) en partes iguales por el arrendatario y arrendador @else en parte completa por el arrendatario @endif según condiciones HDI">
                                    <img class="icono-responsive" width="20px" src="{{asset('images/icono_info.png')}}">
                                </span>
                                @if( $property->tenanting_insurance )
                                <span class="tooltip is-tooltip-multiline" data-tooltip="El arrendador Si acepta contratar seguro de arriendo">
                                    <img class="icono-responsive" width="20px" src="{{asset('images/icons/tenanting_insurance_green.png')}}">
                                </span> @else
                                <span class="tooltip is-tooltip-multiline" data-tooltip="El arrendador No acepta contratar seguro de arriendo">
                                    <img class="icono-responsive" width="20px" src="{{asset('images/icons/tenanting_insurance_red.png')}}">
                                </span>
                                @endif
                            </span>
                        </div>
                        <div class="column is-half">
                            <span class="with-line">
                                <div class="level">
                                    <span class="level-left" id="montoSeguro">$ @if($property->tenanting_insurance){{number_format(\Auth::user()->confirmed_collateral? $property->rent*0.25*1.19 : $property->rent*0.4*1.19,0,',','.')}} @else {{number_format(\Auth::user()->confirmed_collateral? $property->rent*0.5*1.19 : $property->rent*0.8*1.19,0,',','.')}}@endif</span>
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
                            <span class="with-line"><b class="letras-responsive" style="margin-right: 10px">Servicio Digital uHomie</b><span class="tooltip is-tooltip-multiline" data-tooltip="Este incluye firma digital, validación Facial, Validación Identidad, Política de Protección al consumidor etc."><img class="icono-responsive" width="20px" src="{{asset('images/icono_info.png')}}"></span></span>
                        </div>
                        <div class="column is-half">
                            <span class="with-line">
                                <div class="level">
                                    <span class="level-left" id="servicio_digital"></span>
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
        let a = {{$property->rent*$property->warranty_months_quantity}};
        let b = {{$property->rent*$property->months_advance_quantity}};
        
        let c = @if($property->tenanting_insurance){{\Auth::user()->confirmed_collateral? $property->rent*0.25*1.19 : $property->rent*0.4*1.19}}; 
                @else {{\Auth::user()->confirmed_collateral? $property->rent*0.5*1.19 : $property->rent*0.8*1.19}};
                @endif
        let total = 0;
        c2 = ($('#switchRoundedOutlinedInfo').prop('checked')? c: 0);
        if( $("input[name='payment_method']:checked").val() == 'debit' ){
            $('#imgWP').prop('hidden', true);
            $('#imgPP').prop('hidden', false);
            
            total = a + b + c2 + ((a+b+c2)*0.09);
            $('#servicio_digital').html('$ '+((a+b+c2)*0.09).toLocaleString('de-DE'));
        }
        if( $("input[name='payment_method']:checked").val() == 'credit_clp' ){
            $('#imgWP').prop('hidden', true);
            $('#imgPP').prop('hidden', false);
            total = a + b + c2 + ((a+b+c2)*0.11);
            $('#servicio_digital').html('$ '+((a+b+c2)*0.11).toLocaleString('de-DE'));
        }
        if( $("input[name='payment_method']:checked").val() == 'credit_usd' ){
            $('#imgWP').prop('hidden', false);
            $('#imgPP').prop('hidden', true);
            total = a + b + c2 + ((a+b+c2)*0.3);
            $('#servicio_digital').html('$ '+((a+b+c2)*0.3).toLocaleString('de-DE'));
        }
        $('#total').html('$ '+total.toLocaleString('de-DE'));
    };
    function seguroEvento()
    {
        let c = @if($property->tenanting_insurance){{\Auth::user()->confirmed_collateral? $property->rent*0.25*1.19 : $property->rent*0.4*1.19}}; 
                @else {{\Auth::user()->confirmed_collateral? $property->rent*0.5*1.19 : $property->rent*0.8*1.19}};
                @endif
        if($('#switchRoundedOutlinedInfo').prop('checked')){
            $('#labelSeguro').html('Acepto y declaro haber leído los términos y condiciones de Seguros HDI');
            $('#montoSeguro').html('$ '+c.toLocaleString('de-DE'));
            $('#montoSeguro').css('text-decoration','none');
        } else {
            $('#labelSeguro').html('No Acepto');
            $('#montoSeguro').html('$ 0');
            //$('#montoSeguro').css('text-decoration','line-through');
        }
        calcularTotal();
    }
    $(function(){
        $('#tabla2').width(parseInt($('#tabla1').width()));
        $('.icono_payment').width('30px');
        
        calcularTotal();
        seguroEvento();
        
        $('#payment_form').change(function(){
            calcularTotal();
        });
        //text-decoration:line-through;
        $('#switchRoundedOutlinedInfo').change(seguroEvento);
    });

</script>
@endsection
