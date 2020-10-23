  @extends('layouts.flujo-base', ['navTitle' => '<span class="bold">Paso 1.2:</span> Fotografias'])
  @section('styles')
        <link rel="stylesheet" href="/css/photo-uploader.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.4.0/min/dropzone.min.css">
        <style>
            .dropzone {
                background: white;
                border-radius: 5px;
                border: 2px dashed rgb(0, 135, 247);
                border-image: none;
            }
            .line-down {

            .down, .up {
            cursor: pointer;
            }

            .down::after, .up::after {
            border: 2px solid #ccc;
            border-radius: 2px;
            border-right: 0;
            border-top: 0;
            content: " ";
            height: 0.625em;

            margin-left: 20px;
            pointer-events: none;
            position: absolute;

            -webkit-transform-origin: center;
            transform-origin: center;
            width: 0.625em;
            }
            .down::after {
            -webkit-transform: rotate(-45deg);
            transform: rotate(-45deg);
            top: 18px;
            }

            .up::after {
            -webkit-transform: rotate(135deg);
            transform: rotate(135deg);
            top: 23px;
            }

            position: relative;
            font-size: 19px;
            font-weight: bold;

            :last-child::before {
            content: "";
            position: absolute;

            bottom: 0;
            z-index: 30;
            left: 12px;
            height: 3px;
            width: 10%;
            background: #ffd900;
            }
            }
        </style>
  @endsection
  @section('content')
  <input type="hidden" id="photo_limit" value="{{ isset($photo_limit) ? $photo_limit : null}}">
  <input type="hidden" id="photo_uri" value="@route('users.services.get-photos', ['company_id' => $user->getServiceCompany()->id])">
  <input type="hidden" id="entity_id" value="{{$user->getServiceCompany()->id}}">
  <input type="hidden" id="photo-save" value="@route('users.services.save-photos', ['company_id' => $user->getServiceCompany()->id])">
  <input type="hidden" id="entity_id" value="{{$user->getServiceCompany()->id}}">
  <input type="hidden" id="delete_photo_uri" value="@route('users.services.delete-photo')">
  <input type="hidden" id="service" value="{{$services}}">

  <div class="container" id="service-second-step-four">
    <section class="section main-section">
        <div class="columns">
            <div class="column is-7 flow-form">
                <div class="div">
                    <form method="POST" action="{{ route('services.first-step.two') }}" enctype="multipart/form-data" id="registration-form">
                      {{ csrf_field() }}
                    </form>
                </div>
                @foreach($services as $service)
                <div class="line-down">
                    <span>Servicio {{$service->name}}</span>
                </div>
                <form method="post" action="{{url('/users/service/save-photos')}}/{{$service->id}}" enctype="multipart/form-data" 
                    class="dropzone" id="dropzone{{$service->id}}">
                    {{ csrf_field() }}
                    <div class="dz-message" data-dz-message><span>Agrega del servicio <b>{{$service->name}}</b> tus fotos aqui!</span></div>
                </form>
                @endforeach


                <div class="form-footer">
                    <a href="@route('services.first-step.one')" class="button is-outlined">Atrás</a>
                    <img src="{{ asset('images/icono_guardar.png') }}" id="save-button-icon">
                    <input type="submit" class="button is-outlined is-primary" value="Continuar" form="registration-form">
                </div>
            </div>
            <div class="column side-info">
                <div class="info">
                    <img src="{{ asset('images/icono-atencion.png') }}">
                    <p>Fotos Correctas te pueden ayudar a lograr que tu propiedad destaque.</p>
                    <div class="border"></div>
                </div>
                <div class="info">
                    <img src="{{ asset('images/icono-atencion.png') }}">
                    <p>Antes de tomar fotos de tu espacio, dedica algo de tiempo a ordenarlo todo como si te prepararas para recibir a tu arrendatario Ideal. Las fotos deben mostrar tu Propiedad y cada aspecto relevante y ayudar a los arrendatarios a hacerse una idea de cómo es.</p>
                    <div class="border"></div>
                </div>
            </div>
        </div>
    </section>
</div>
<div class="modal modal-tip modal-info">
    <div class="modal-background"></div>
    <div class="modal-content">
      <div class="info-card">
            <img src="{{ asset('images/foco.png') }}">
          <span id="modal-title">Recuerda Tomar y Usar fotos basadas en los Guía de estilos UHOMIE:</span>
          <input type="hidden" name="current-step" id="current-step" value="0" > 
          <p id="modal-text"></p>
      </div>
      <a class="button is-primary is-outlined" href="#" id="btn-back-tip"> 
        <img src="{{ asset('images/icono_aval.png') }}" alt="">
      </a>
      <a class="button is-primary is-outlined" id="btn-next-tip" href="#" >
        <img src="{{ asset('images/icono_aval.png') }}" alt="">
      </a>
    </div>
</div>
@include('components.users.common-forms.save-button.modal')
@endsection

@section('scripts')
    <!--<script src="{{ asset('js/service-forms/second-step-four.js') }}" charset="utf-8"></script>-->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.4.0/dropzone.js"></script>
    @foreach($services as $service)
    <script>
        Dropzone.options.dropzone{{$service->id}} =
        {
            maxFilesize: 12,
            renameFile: function(file) {
                var dt = new Date();
                var time = dt.getTime();
                return time+file.name;
            },
            acceptedFiles: ".jpeg,.jpg,.png,.gif",
            addRemoveLinks: true,
            dictRemoveFile: 'Remover Imagen',
            dictCancelUpload: 'Cancelar Subida',
            timeout: 5000,
            maxFiles: {{$photo_limit}},
            success: function(file, response) 
            {
                toastr.success("Se ha cargado la imagen exitosamente.");
                console.log(response);
            },
            error: function(file, response)
            {
                return false;
            },
            removedfile: function(file) 
            {
                var name = file.upload.filename;
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    url: '{{ url("/users/service/delete-service-photos") }}',
                    data: {filename: name},
                    success: function (data){
                        toastr.success("Se ha removido la imagen exitosamente.");
                        console.log("File has been successfully removed!!");
                    },
                    error: function(e) {
                        console.log(e);
                    }});
                    var fileRef;
                    return (fileRef = file.previewElement) != null ? 
                    fileRef.parentNode.removeChild(file.previewElement) : void 0;
            }
        };
    </script>
    @endforeach
@endsection
