@extends('layouts.flujo-base', ['navTitle' => '<span class="bold">Paso 2:</span> Pago aprobado'])

@section('content')

<div class="container" id="ten-third-step-four">
    <section class="section main-section">
        <div class="columns">
            <div class="column flow-form columns">
                <div class="column is-6">
                    <form method="POST" action="{{-- route($form_route) --}}" enctype="multipart/form-data" >
                        {{ csrf_field() }}
                        
                        <h1 class="form-title"><strong>¡Felicitaciones!</strong></h1>
                        <h5 style="font-size: 90%; !important"><strong>Has pagado exitosamente tu estadia de la siguiente propiedad</strong></h5>
                        <div class="div" style="margin-top: 20px;">
                            <table id="tabla2">
                                <tr>
                                    <td class="icono_payment"  style="padding-bottom: 10px; padding-top:10px; padding-right: 10px;">
                                        <img src="{{ asset('images/perfil.png') }}">
                                    </td>
                                    <td  style="padding: 10px 0px;">
                                        <span style="font-size: 0.75rem;">En tu perfil podrás ver ésta y todas las transacciones históricas</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="icono_payment"  style="padding-bottom: 10px; padding-top:10px; padding-right: 10px;">
                                        <img src="{{ asset('images/mail.png') }}">
                                    </td>
                                    <td  style="padding: 10px 0px;">
                                        <span style="font-size: 0.75rem;">Revisa tu correo electrónico, enviamos un comprobante de la transacción</span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </form>
                </div>
                        
                
                <div class="form-footer">
                    
                    <a type="submit" class="button is-outlined is-primary" href="{{url('users/profile/tenant#/postulations')}}" form="registration-form">FINALIZAR</a>
                </div>
                
            </div>
            <div class="column" style="background-color:rgb(243, 243, 243);">
                
                
                <div class="has-text-centered">
                    <img width="100px" src="{{asset('images/ok.png')}}">
                </div>
                <div class="has-text-centered" style="margin: 20px;font-size: 20px;">
                    ¡Pago Efectuado!
                </div>
                <div class="has-text-centered" style="padding: 30px 30px;">
                    <img src="{{asset('images/ssl.png')}}" width="70px">
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
        padding: 3rem 10rem;
    }
}

</style>
@include('components.users.common-forms.save-button.modal')
@endsection

@section('scripts')
<script src="{{ asset('js/third-step-three.js') }}"></script>
<script>
    $(function(){
        $('#tabla2').width(parseInt($('#tabla1').width()));
        $('.icono_payment').width('30px');
    });

</script>
@endsection
