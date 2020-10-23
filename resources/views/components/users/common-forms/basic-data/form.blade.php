

						{{ csrf_field() }}
                        <div class="field">
                            <div class="label-field">
                                <img src="{{ asset('images/icono_bandera.png') }}">
                                <span>¿Qué nacionalidad tienes?</span>
                            </div>
                            <div class="select">
                                <select name="nationality" required>
                                    @foreach($countries as $country)
                                    <option value="{{$country->id}}" @if($user && $user->country && $user->country->id == $country->id) selected="selected" @endif>{{$country->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="field">
                            <div class="label-field">
                                <img src="{{ asset('images/icono_id.png') }}">
                                <span>¿Qué tipo de doc. posees?</span>
                            </div>
                            <div class="select">
                                <select name="doc_type" required id="doc_type">
                                    <option value="RUT" @if($user && $user->document_type =='RUT') selected="selected" @endif >
                                        RUT (RESIDENCIA PERMANENTE)
                                    </option>
                                    <option value="PASSPORT" @if($user && $user->document_type == 'PASSPORT')selected="selected" @endif >
                                        PASAPORTE
                                    </option>
                                    <option value="RUT_PROVISIONAL" @if($user && $user->document_type == 'RUT PROVISIONAL')selected="selected" @endif >
                                        RUT (PROVISORIO)
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="field">
                            <div class="label-field">
                                <img src="{{ asset('images/icono_numero.png') }}">
                                <span>¿Número?</span>
                            </div>
                            <input type="text" autocomplete="off" class="input" name="document_number" id="document_number" value="{{ $user->document_number }}">
                        </div>
                        <div class="field">
                            <div class="label-field">
                                <img src="{{ asset('images/icono_torta.png') }}">
                                <span>Fecha de nacimiento</span>
                            </div>

                            <input type="text" class="input date " autocomplete="off" name="birthdate" id="birthdate" value="{{ !is_null($user->birthdate) ? date('m/d/Y',strtotime($user->birthdate)) : date('m/d/Y') }}">

                        </div>
                        <div class="field">
                            <div class="label-field">
                                <img src="{{ asset('images/icono_anillo.png') }}">
                                <span>Estado Civil</span>
                            </div>

                            <div class="select">
                                <select name="civil_status" required>
                                    @foreach ($civil_status as $status)
                                    	<option value="{{ $status->id }}" @if($user && $user->civil_status_id && $user->civil_status_id == $status->id) selected @endif>{{ $status->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                </div>
                <div class="form-footer">
                    <a href="{{$back_url}}" class="button is-outlined">Atrás</a>
                    <img src="{{ asset('images/icono_guardar.png') }}" id="save-button-icon">
                    <input type="submit" class="button is-outlined is-primary" value="Continuar" form="registration-form">
                </div>
