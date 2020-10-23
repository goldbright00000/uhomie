@extends('layouts.flujo-base', ['navTitle' => '<span class="bold">Paso 1.3:</span> Registro de tu Empresa'])
@section('styles')
    <link rel="stylesheet" href="/css/photo-uploader.css">
@endsection
@section('content')
    <input type="hidden" value="{{ asset('images') }}" id="images-dir">
    @if($user->getAgentCompany())
    <input type="hidden" id="photo_uri" value="@route('users.agents.get-logo', ['company_id' => $user->getAgentCompany()->id])">
    <input type="hidden" id="photo_save" value="@route('users.agents.save-logo', ['company_id' => $user->getAgentCompany()->id])">
    <input type="hidden" id="photo_del" value="@route('users.agents.del-logo')">
    <input type="hidden" id="company_id" value="{{$user->getAgentCompany()->id}}">
    @endif
    <div class="container" id="agent-first-step-three">
        <section class="section main-section">
            <div class="columns">
                <div class="column is-7 flow-form">
                    <div class="div">
                        <form method="POST" action="" enctype="multipart/form-data" id="registration-form">
                            {{ csrf_field() }}
                            <div class="field">
                                <div class="label-field">
                                    <span>¿Necesitas factura?</span>
                                </div>
                                <div class="control">
                                    <label class="radio">
                                        <input type="radio" name="invoice" value="1" {{ $user->getAgentCompany() && $user->getAgentCompany()->invoice ? 'checked' : '' }} >
                                        Si
                                    </label>
                                    <label class="radio">
                                        <input type="radio" name="invoice" value="0" {{ $user->getAgentCompany() && !$user->getAgentCompany()->invoice ? 'checked' : '' }} >
                                        No
                                    </label>
                                </div>
                            </div>

                            <div class="field">
                                <div class="label-field">
                                    <span>Razon social de la empresa</span>
                                </div>
                                <input type="text" autocomplete="off" class="input" name="name" id="name" value="{{ $user->getAgentCompany() ? $user->getAgentCompany()->name : '' }}" >
                            </div>
                            <div class="field">
                                <div class="label-field">
                                    <span>Giro</span>
                                </div>
                                <input type="text" autocomplete="off" class="input numbers" name="giro" id="giro" value="{{ $user->getAgentCompany() ? $user->getAgentCompany()->giro : '' }}" >
                            </div>
                            <div class="field">
                                <div class="label-field">
                                    <span>RUT</span>
                                </div>
                                <input type="text" autocomplete="off" class="input" name="rut" id="rut" value="{{ $user->getAgentCompany() ? $user->getAgentCompany()->rut : '' }}" >
                            </div>

                            @include('components.common.files', [
                                'title' => "Rut (Foto anverso) / Pasaporte (Foto Hoja Principal)",
                                'name' => 'id_front',
                                'mimes' => 'image/*',
                                'file' => $user->getAgentCompany() ? $user->getAgentCompany()->files()->where('name', 'id_front')->first() : null
                            ])
                            @include('components.common.files', [
                                 'title' => "Rut (Foto reverso) / Pasaporte (Foto Visado)",
                                 'name' => 'id_back',
                                 'mimes' => 'image/*',
                                 'file' => $user->getAgentCompany() ? $user->getAgentCompany()->files()->where('name', 'id_back')->first() : null
                            ])
                            <!--<h2 class="file-title">Logo (Opcional)</h2>
                            <div class="file has-name">
                                <label class="file-label">
                            </label>
                            </div>
                            <div class="columns is-multiline">
                                <div class="column is-5">
                                    <div class="image-preview">
                                      <div class="close-btn"></div>
                                      <label for="image-upload" id="image-label">Subir logo</label>
                                      <input type="file" name="image_logo" id="image-upload" />
                                      <input type="hidden" name="logo" value="1" />
                                    </div>
                                </div>
                            </div>-->

                            <div class="columns" id="logo">
                                <div class="column is-half">
                                    <h2 class="file-title">Logo (Opcional)</h2>
                                </div>
                                <div class="column">
                                    <upload-photo-company 
                                        v-if="cover"
                                        v-bind:id="{{$user->getAgentCompany()->id}}"
                                        v-bind:save="save"
                                        v-bind:del="del"
                                        v-bind:token="csrf"
                                        v-bind:limit="1"
                                        v-bind:active="cover"
                                        v-bind:destroy="destroy"
                                        @close="coverUpdate"
                                        @photo="coverPhoto"
                                        ></upload-photo-company>
                                    <img-cover-logo
                                        v-for="item in photo_cover" 
                                        :key="item.id" 
                                        v-bind:info="item"
                                        v-bind:editing="editing"
                                        @delphotocover="delPhotoCover"
                                        ></img-cover-logo>
                                </div>
                            </div>

                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_numero.png') }}">
                                    <span>Telefono fijo (opcional)</span>
                                </div>
                                <input type="text" autocomplete="off" class="input" name="phone" id="phone" value="{{ $user->getAgentCompany() ? $user->getAgentCompany()->phone : '' }}">
                            </div>
                            <!-- <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_numero.png') }}">
                                    <span>Celular</span>
                                </div>
                                <input type="text" autocomplete="off" class="input" name="cell_phone" id="cell_phone" value="{{ $user->getAgentCompany() ? $user->getAgentCompany()->cell_phone : '' }}">
                            </div>
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_numero.png') }}">
                                    <span>Email</span>
                                </div>
                                <input type="text" autocomplete="off" class="input" name="email" id="email" value="{{ $user->getAgentCompany() ? $user->getAgentCompany()->email : '' }}">
                            </div> -->
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_numero.png') }}">
                                    <span>Pagina Web</span>
                                </div>
                                <input type="text" autocomplete="off" class="input" name="website" id="website" value="{{ $user->getAgentCompany() ? $user->getAgentCompany()->website : '' }}">
                            </div>

                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_descripcion.png') }}">
                                    <span>Descripción de tu Proyecto</span>
                                </div>
                            </div>
                            <div class="field">                          	
                                <textarea name="description" id="description" rows="8" cols="80" required>{{ $user->getAgentCompany() ? $user->getAgentCompany()->description : '' }}</textarea>
                            </div>

                        </div>
                        <div class="form-footer">
                            <a href="{{ route('users.agents.first-step.two') }}" class="button is-outlined">Atrás</a>
                            <img src="{{ asset('images/icono_guardar.png') }}" id="save-button-icon">
                            <input type="submit" class="button is-outlined is-primary" value="Continuar" form="registration-form">
                        </div>
                    </div>
                    <div class="column side-info">
                        <div class="info">
                            <img src="{{ asset('images/icono-atencion.png') }}">
                            <p>Deberas adjuntar las fotos de anverso</p>
                            <p>y reverso de tu empresa carnet RUT</p>
                            <div class="border"></div>
                        </div>

                    </div>
                </div>
            </section>
        </div>
        @include('components.users.common-forms.save-button.modal')
    @endsection

    @section('scripts')
    <script src="{{ asset('js/photo-uploader.js') }}" charset="utf-8"></script>
    <script type="text/javascript">
      $(document).ready(function() {
        $.uploadPreview({
          input_field: "#image-upload",
          preview_box: ".image-preview",
          label_field: "#image-label"
        });
      });
    </script>
    <script src="{{ asset('js/agent-forms/first-step-three.js') }}" charset="utf-8"></script>
    <script src="{{ asset('js/agent-forms/photo-company.js') }}" charset="utf-8"></script>
    @endsection
