@extends('layouts.flujo-base')

@section('content')
<style>
.div-vacio {
    border-bottom: 3px solid #ffd900;
    width: 60px;
    height: 20px;
    margin: 0 auto;
}
</style>
<div class="container" id="first-step">
    <section class="section main-section">
        <div id="app-select-stay">
            <select-stay siguiente_paso="/users/agent/r/third-step/select/{{$property->id}}" titulo="¿Con qué fin registras tu propiedad?"></select-stay>
        </div>
    </section>
</div>
@endsection

@section('flow-scripts')
<script src="{{ asset('js/select_stay.js') }}"></script>
@endsection