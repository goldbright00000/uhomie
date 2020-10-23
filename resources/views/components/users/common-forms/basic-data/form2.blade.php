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
                CEDULA DE IDENTIDAD CHILENO
            </option>
            <option value="RUT_EXTRANJERO" @if($user && $user->document_type == 'RUT_EXTRANJERO')selected="selected" @endif >
                CEDULA DE IDENTIDAD EXTRANJERO (RESIDENCIA DEFINITIVA)
            </option>
            <option value="RUT_PROVISIONAL" @if($user && $user->document_type == 'RUT_PROVISIONAL')selected="selected" @endif >
                CEDULA DE IDENTIDAD EXTRANJERO (RESIDENCIA TEMPORAL)
            </option>
            <option value="PASSPORT" @if($user && $user->document_type == 'PASSPORT')selected="selected" @endif >
                PASAPORTE
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
<div class="field" id="formulario">
    <div class="label-field">
        <img src="{{ asset('images/icono_torta.png') }}">
        <span>Fecha de nacimiento</span>
    </div>

    <!--<input type="text" class="input date " autocomplete="off" name="birthdate" id="birthdate" value="{{ !is_null($user->birthdate) ? date('m/d/Y',strtotime($user->birthdate)) : date('m/d/Y') }}">-->
    <input type="hidden" v-model="date" autocomplete="off" name="birthdate" id="birthdate" value="{{ !is_null($user->birthdate) ? $user->birthdate : '' }}">
    <v-flex >
        <v-dialog
            v-model="modal"
            :close-on-content-click="false"
            :nudge-right="40"
            lazy
            transition="scale-transition"
            offset-y
            full-width
            max-width="290px"
            min-width="290px"
            >
            <template v-slot:activator="{ on }">
                <v-text-field
                    v-model="computedDateFormatted"
                    persistent-hint
                    prepend-icon="event"
                    readonly
                    v-on="on"
                ></v-text-field>
            </template>
            <v-date-picker locale="es-ES" v-model="date" color="blue darken-1" :max="max_date" no-title @input="modal = false"></v-date-picker>
        </v-dialog>
    </v-flex >
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
