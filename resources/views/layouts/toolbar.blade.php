<div class="toolbar">
    <div class="is-hidden-tablet" style="text-align: center; padding-top: 0.5rem;">
        <a href="/">
            <img src="{{ asset('images/icons/logo_completo.png') }}" width="80px">
        </a>
        <span class="nav-title">{!! $navTitle or '' !!}</span>

        <a href="{{ url('logout') }}" class="navbar-item" style="position: absolute; top: 0.5rem; right: 0.5rem;">
            <img src="{{ asset('images/icons/logout.png') }}" style="opacity: 0.5; width: 1rem;">
        </a>
    </div>
    <div class="container is-fullhd" style="padding-left: 10px; padding-right: 10px">
        <div class="is-hidden-mobile">
            <a href="/">
                <img src="{{ asset('images/icons/logo_completo.png') }}">
            </a>
            <span class="nav-title">{!! $navTitle or '' !!}</span>
        </div>
        <div class="ico-zone">
            <a href="@if($user != null) {{ ($user->tenant_profile_redirect) ? route($user->tenant_profile_redirect) : route('user.new-role-registration', ['role_id' => 1]) }} @endif" class="tooltip is-tooltip-bottom" data-tooltip="Arrendatario">
                <div class="tool-ico {{ $active=='arrendatario'?'active':'static' }}">
                    <img src="{{ asset('images/roles/arrendatario-static.png') }}" class="static">
                    <img src="{{ asset('images/roles/arrendatario-activo.png') }}" class="active">
                </div>
            </a>

            <a href="@if($user != null) {{ ($user->owner_profile_redirect) ? route($user->owner_profile_redirect) : route('user.new-role-registration', ['role_id' => 2]) }} @endif" class="tooltip is-tooltip-bottom" data-tooltip="Arrendador">
                <div class="tool-ico {{ $active=='arrendador'?'active':'static' }}">
                    <img src="{{ asset('images/roles/arrendador-static.png') }}" class="static">
                    <img src="{{ asset('images/roles/arrendador-activo.png') }}" class="active">
                </div>
            </a>
            <a href="@if($user != null) {{ ($user->agent_profile_redirect) ? route($user->agent_profile_redirect) : route('user.new-role-registration', ['role_id' => 3]) }} @endif" class="tooltip is-tooltip-bottom" data-tooltip="Agente">
                <div class="tool-ico {{ $active=='agente'?'active':'static' }}">
                    <img src="{{ asset('images/roles/agente-static.png') }}" class="static">
                    <img src="{{ asset('images/roles/agente-activo.png') }}" class="active">
                </div>
            </a>

            <a href="@if($user != null) {{ ($user->service_profile_redirect) ? route($user->service_profile_redirect) : route('user.new-role-registration', ['role_id' => 4]) }} @endif" class="tooltip is-tooltip-bottom" data-tooltip="Servicios">
                <div class="tool-ico {{ $active=='servicios'?'active':'static' }}">
                    <img src="{{ asset('images/roles/servicios-static.png') }}" class="static">
                    <img src="{{ asset('images/roles/servicios-activo.png') }}" class="active">
                </div>
            </a>

            <a href="@if($user != null) {{ ($user->collateral_profile_redirect) ? route($user->collateral_profile_redirect) : route('user.new-role-registration', ['role_id' => 5]) }} @endif" class="tooltip is-tooltip-bottom" data-tooltip="Aval">
                <div class="tool-ico {{ $active=='collateral'?'active':'static' }}">
                        <img src="{{ asset('images/roles/aval-static.png') }}" class="static">
                        <img src="{{ asset('images/roles/aval-activo.png') }}" class="active">
                </div>
            </a>

            
            
            <!--<div class="tool-ico s24">
                <img width="24px" src="{{ asset('images/roles/icono-cambio.png') }}" class="active">
            </div>-->

            <div class="separ"></div>

            <div class="tool-ico s24">
                <img width="24px" src="{{ asset('images/roles/icono-alerta.png') }}">
            </div>
            <div class="tool-ico s24">
                <img width="24px" src="{{ asset('images/roles/icono-mensaje.png') }}">
            </div>
            <div class="separ"></div>
            <div class="tool-ico">
                <img width="32px" src="{{ asset('images/roles/avatar-uhomie.png') }}">
            </div>
            <div class="separ is-hidden-mobile"></div>
            <div class="tool-ico is-hidden-mobile">
                <a href="{{ url('logout') }}" class="navbar-item" style="">
                    <img src="{{ asset('images/icons/logout.png') }}" style="opacity: 0.5; width: 1.2rem;">
                </a>
            </div>

            <!--
            <div class="navbar-item has-dropdown is-hoverable is-left is-hidden-mobile">
                
                <a class="navbar-link is-arrowless">
                    <img width="24px" src="{{ asset('images/roles/icono-menu.png') }}">
                </a>                
                <div class="navbar-dropdown">
                    <a href="{{ url('logout') }}" class="navbar-item">
                        Salir
                    </a>
                </div>
            </div>
            -->

        </div>
    </div>
</div>

