@extends('layouts.flujo-base')

@section('content')
<div class="container" id="agent-second-step">
    <section class="section main-section">
        <div class="columns">
            <div class="column is-7 flow-form">
                <form method="POST" action="" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div>
                        <div class="edit-step">
                            <div>
                                <span>Paso 1</span>
                                <span>Tus datos personales</span>
                            </div>
                            <div class="edit-right">
                                <img src="{{ asset('images/icono-ok.png') }}">
                                <a href="{{route('users.agents.first-step')}}">Editar</a>
                            </div>
                        </div>
                        <h1 class="title step-title">Paso 2</h1>
                        <section class="hero main-title">
                            <div class="hero-body">
                                <div class="container">
                                    <h1 class="title">Tu Propiedad</h1>
                                </div>
                            </div>
                        </section>
                        <div class="columns is-vcentered">
                            <div class="column">
                                <div>Cuentanos que tipo y</div>
                                <div>caractersticas posee </div>
                                <div>la propiedad que quieres</div>
                                <div>publicar.</div>
                            </div>
                            <div class="column">
                                <div class="control">
                                    <label class="radio">
                                        <input type="radio" name="is_project" id="is_project" value="0" {{ isset($project) && $project->is_project == '0' ? 'checked' : '' }} required>
                                        Propiedad para arrendar
                                    </label>
                                    <br>
                                    <label class="radio">
                                        <input type="radio" name="is_project" id="is_project" value="1" {{ isset($project) && $project->is_project == '1' ? 'checked' : '' }} required>
                                        Propiedad para vender
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-footer">
                        <!-- <a href="{{ route('users.agents.first-step.four') }}" class="button">Atrás</a> -->
                        <!--<a href="{{ route('users.agents.second-step.one') }}" class="button is-outlined is-primary">Continuar</a>-->
                        <button type="submit" class="button is-outlined is-primary">Continuar</button>
                    </div>
                </form>
            </div>
            <div class="column side-info small">
                <div class="info small">
                    <img src="{{ asset('images/foco.png') }}">
                    <p>Recuerda llenar todos los</p>
                    <p>items de tu proyecto</p>
                    <div class="border"></div>
                </div>
                <div class="info small">
                    <img src="{{ asset('images/foco.png') }}">
                    <p>En UHOMIE puedes publicar</p>
                    <p>todos los proyectos que desees</p>
                    <div class="border"></div>
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
<script>
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