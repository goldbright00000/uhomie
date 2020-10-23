    @extends('layouts.flujo-base', ['navTitle' => '<span class="bold">Paso 2.1:</span> Tu publicacion'])
    @section('styles')
        <link rel="stylesheet" href="/css/photo-uploader.css">
    @endsection
    @section('content')
    <input type="hidden" value="{{ asset('images') }}" id="images-dir">
    @if($user->getServiceCompany())
    <input type="hidden" id="photo_uri" value="@route('users.services.get-logo', ['company_id' => $user->getServiceCompany()->id])">
    <input type="hidden" id="photo_save" value="@route('users.services.save-logo', ['company_id' => $user->getServiceCompany()->id])">
    <input type="hidden" id="photo_del" value="@route('users.services.del-logo')">
    <input type="hidden" id="company_id" value="{{$user->getServiceCompany()->id}}">
    @endif
    <div class="container" id="service-second-step-one">
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
                                        <input type="radio" name="invoice" value="1" {{ $user->getServiceCompany() && $user->getServiceCompany()->invoice ? 'checked' : '' }} >
                                        Si
                                    </label>
                                    <label class="radio">
                                        <input type="radio" name="invoice" value="0" {{ $user->getServiceCompany() && !$user->getServiceCompany()->invoice ? 'checked' : '' }} >
                                        No
                                    </label>
                                </div>
                            </div>

                            <div class="field">
                                <div class="label-field">
                                    <span>Razon social de la empresa</span>
                                </div>
                                <input type="text" autocomplete="off" class="input" name="name" id="name" value="{{ $user->getServiceCompany() ? $user->getServiceCompany()->name : '' }}" >
                            </div>
                            <div class="field">
                                <div class="label-field">
                                    <span>Giro</span>
                                </div>
                                <input type="text" autocomplete="off" class="input numbers" name="giro" id="giro" value="{{ $user->getServiceCompany() ? $user->getServiceCompany()->giro : '' }}" >
                            </div>
                            <div class="field">
                                <div class="label-field">
                                    <span>RUT</span>
                                </div>
                                <input type="text" autocomplete="off" class="input" name="rut" id="rut" value="{{ $user->getServiceCompany() ? $user->getServiceCompany()->rut : '' }}" >
                            </div>

                            @include('components.common.files', [
                                'title' => "Rut (Foto anverso) / Pasaporte (Foto Hoja Principal)",
                                'name' => 'id_front',
                                'mimes' => 'image/*',
                                'file' => $user->getServiceCompany() ? $user->getServiceCompany()->files()->where('name', 'id_front')->first() : null
                            ])
                            @include('components.common.files', [
                                 'title' => "Rut (Foto reverso) / Pasaporte (Foto Visado)",
                                 'name' => 'id_back',
                                 'mimes' => 'image/*',
                                 'file' => $user->getServiceCompany() ? $user->getServiceCompany()->files()->where('name', 'id_back')->first() : null
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
                                      <label for="image-upload" id="image-label">Choose File</label>
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
                                        v-bind:id="{{$user->getServiceCompany()->id}}"
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
                                <input type="text" autocomplete="off" class="input" name="phone" id="phone" value="{{ $user->getServiceCompany() ? $user->getServiceCompany()->phone : '' }}">
                            </div>
                            <!-- <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_numero.png') }}">
                                    <span>Celular</span>
                                </div>
                                <input type="text" autocomplete="off" class="input" name="cell_phone" id="cell_phone" value="{{ $user->getServiceCompany() ? $user->getServiceCompany()->cell_phone : '' }}">
                            </div>
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_numero.png') }}">
                                    <span>Email</span>
                                </div>
                                <input type="text" autocomplete="off" class="input" name="email" id="email" value="{{ $user->getServiceCompany() ? $user->getServiceCompany()->email : '' }}">
                            </div> -->
                            <div class="field">
                                <div class="label-field">
                                    <img src="{{ asset('images/icono_numero.png') }}">
                                    <span>Pagina Web</span>
                                </div>
                                <input type="text" autocomplete="off" class="input" name="website" id="website" value="{{ $user->getServiceCompany() ? $user->getServiceCompany()->website : '' }}">
                            </div>

                        </div>
                        <div class="form-footer">
                            <a href="{{ route('users.services.second-step') }}" class="button is-outlined">Atrás</a>
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
    <script src="{{ asset('js/service-forms/second-step-one.js') }}" charset="utf-8"></script>
    <script src="{{ asset('js/agent-forms/photo-company.js') }}" charset="utf-8"></script>
    @endsection
