<div class="modal modal-access" id="profile-modal">
    <div class="modal-background"></div>
    <div class="modal-content">
        <div class="toolbar">
            <div class="container">
                <img src="{{ asset('images/icons/logo_completo.png') }}">
                <button class="button is-outlined is-primary btn-close-modal">Cerrar</button>
            </div>
        </div>

        <div class="container">
            <img class="top-logo" src="{{ asset('images/icons/logo_grande.png') }}">
            <h1 class="title top-title">Con que rol quieres ingresar?</h1>
            <input type="hidden" id="tenant-image-static-path" value="{{ asset('images/roles/arrendatario-static.png') }}">
            <input type="hidden" id="tenant-image-active-path" value="{{ asset('images/roles/arrendatario-activo.png') }}">
            <input type="hidden" id="agent-image-static-path" value="{{ asset('images/roles/arrendador-static.png') }}">
            <input type="hidden" id="agent-image-active-path" value="{{ asset('images/roles/arrendador-activo.png') }}">
            <input type="hidden" id="owner-image-static-path" value="{{ asset('images/roles/agente-static.png') }}">
            <input type="hidden" id="owner-image-active-path" value="{{ asset('images/roles/agente-activo.png') }}">
            <input type="hidden" id="service-image-static-path" value="{{ asset('images/roles/servicios-static.png') }}">
            <input type="hidden" id="service-image-active-path" value="{{ asset('images/roles/servicios-activo.png') }}">
            <input type="hidden" id="collateral-image-static-path" value="{{ asset('images/roles/aval-static.png') }}">
            <input type="hidden" id="collateral-image-active-path" value="{{ asset('images/roles/aval-activo.png') }}">
            <div class="form">
              <div class="columns is-multiline is-centered roles-user"></div>
            </div>
        </div>
    </div>
</div>