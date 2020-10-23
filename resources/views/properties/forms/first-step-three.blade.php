@extends('layouts.flujo-base', ['navTitle' => '<span class="bold">Paso 1.3:</span> Fotografias', 'close' => route('profile.owner')])
@section('styles')
    <link rel="stylesheet" href="/css/photo-uploader.css">
@endsection
@section('content')
<input type="hidden" id="photo_limit" value="{{ isset($photo_limit) ? $photo_limit : null}}">
<input type="hidden" id="delete_photo_uri" value="@route('properties.delete-photo')">
<input type="hidden" id="photo_uri" value="@route('properties.get-photos', ['property_id' => $property->id])">
<input type="hidden" id="spaces_uri" value="@route('get-spaces')">
<input type="hidden" id="change_space_uri" value="@route('change-space')">
<input type="hidden" id="photo-save" value="@route('properties.save-photos', ['property_id' => $property->id])">
<input type="hidden" id="entity_id" value="{{$property->id}}">
<input type="hidden" value="{{ asset('images') }}" id="images-dir">
{{ csrf_field() }}
<div class="container" id="own-second-step-three">
    <section class="section main-section" id="photos">
        <div class="columns">
            <div class="column is-7 flow-form">
                <div class="div">
                    <form method="POST" enctype="multipart/form-data" id="registration-form">
  					          {{ csrf_field() }}
                      <!--<h1 class="form-title">Foto de portada  <span style="font-style: italic;font-weight: 400;">   ( para cambiar de portada puedes arrastrar las fotos )</span> </h1>
                      <div class="cover-item-preview">
                          <div class="image-preview main-image-preview">
                              <div class="close-btn"></div>
                              <label for="image-upload" class="image-label">Elija una Foto</label>
                              <input type="file" name="cover_image" id="image-upload" class="image-upload" accept="image/*" />
                              <input type="hidden" class="image-id">
                              <input type="hidden" class="cover" value="1">
                              <input type="hidden" class="image-name" value="cover_image">
                          </div>
                          <div class="select-spaces-cover" photo_id="" space_id="">
                          </div>
                      </div>
                      <div><hr></div>
                      <div class="other-photo-main columns is-multiline"></div>-->
                    </form>
                </div>

                <!--Cargador de Imagen Cover-->
                <div class="columns">
                    <div class="column">
                        <h1 class="form-title" style="margin: 0">Foto de portada</h1>
                    </div>
                </div>
                <div class="columns">
                    <div class="column">
                        <img-cover-property 
                            v-for="item in photo_cover" 
                            :key="item.photo_id" 
                            v-bind:info="item" 
                            v-bind:spaces="spaces"
                            v-bind:editing="editing"
                            @delphotocover="delPhotoCover"
                            @space_id="changeSpace"
                            property_type="{{$property->propertyType()->first()->name}}"
                            ></img-cover-property>
                        <upload-photos-property 
                            v-if="cover"
                            v-bind:id="{{$property->id}}"
                            v-bind:token="csrf"
                            v-bind:limit="1"
                            v-bind:active="cover"
                            v-bind:destroy="destroy"
                            @close="coverUpdate"
                            @photo="coverPhoto"
                            property_type="{{$property->propertyType()->first()->name}}"
                            ></upload-photos-property>
                        
                    </div>
                    
                </div>

                <!--Cargador de imagenes de espacios-->
                <div class="columns">
                    <div class="column">
                        <h1 class="form-title" style="margin: 0">Fotos de espacios</h1>
                    </div>
                </div>
                <div class="columns is-multiline is-mobile" style="margin-bottom: 0px;">
                    <div class="column is-half-mobile is-one-third-widescreen" style="margin: 0"
                        v-for="item in photo_spaces" 
                        :key="item.photo_id">
                        <img-cover-property
                            v-bind:info="item" 
                            v-bind:spaces="spaces"
                            v-bind:editing="editing"
                            @delphotocover="delPhotoSpace"
                            @space_id="changeSpace"
                            property_type="{{$property->propertyType()->first()->name}}"
                            ></img-cover-property>
                    </div>
                    <div class="column is-half-mobile is-one-third-widescreen" v-for="item in items" v-if="item.active" style="margin-bottom: 0px;">
                        <upload-photos-space-property 
                            :key="item.id"
                            v-bind:info="item"
                            v-bind:id="{{$property->id}}"
                            v-bind:token="csrf"
                            v-bind:limit="1"
                            v-bind:active="space"
                            v-bind:destroyspace="destroyspace"
                            @closespace="spaceUpdate"
                            @photospace="spacePhoto"
                            ></upload-photos-space-property>
                    </div>
                </div>

                <div class="columns" v-if="submit">
                    <div class="column">
                        <article class="message is-danger is-small">
                            <div class="message-body">
                                Para continuar con el proceso de registro al menos debes de subir una imagen de portada y al menos tres imagenes de espacios, como tambien seleccionar los espacios de cada imagen que aparezcan sombreadas en rojo.
                            </div>
                        </article>
                    </div>
                </div>
                <div class="form-footer">
                    <a href="{{route('properties.first-step.two',['id' => $property->id])}}" class="button is-outlined">Atrás</a>
                    <img src="{{ asset('images/icono_guardar.png') }}" id="save-button-icon">
                    <input type="submit" class="button is-outlined is-primary" value="Continuar" form="registration-form" :disabled="submit">
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
        <img src="{{ asset('images/back-icon.png') }}" alt="" style="max-height: 20px;">
      </a>
      <a class="button is-primary is-outlined" id="btn-next-tip" href="#" >
        <img src="{{ asset('images/next-icon.png') }}" alt="" style="max-height: 20px;">
      </a>
    </div>
</div>
@include('components.users.common-forms.save-button.modal')
@endsection
@section('scripts')
    <!--<script src="{{ asset('js/property-forms/first-step-three.js') }}" charset="utf-8"></script>-->
    <script src="{{ asset('js/property-forms/photos-property.js') }}" charset="utf-8"></script>

@endsection
