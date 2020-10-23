@php
$isSolid = $isSolid ?? false;
@endphp

<nav class="navbar {{ $isSolid ? 'navbar-solid' : '' }}" role="navigation" aria-label="main navigation" id="nav-header">
  <div class="container">       
    <div class="navbar-brand">
      <a class="navbar-item logo" href="{{ url('/') }}">
        <img class="logo-solid" src="{{ asset('images/logo_completo.png') }}">
        <img class="logo-transparent" src="{{ asset('images/icons/logo_uhomie.png') }}">
      </a>
      
      <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false">
        <span aria-hidden="true"></span>
        <span aria-hidden="true"></span>
        <span aria-hidden="true"></span>
      </a>
    </div>

    <div class="navbar-item search" style="display:none" id="search_input_navbar">
      <form class="searchBar" action="@route('explore')" method="get" autocomplete="off">
        <div class="field has-addons " >
          <div class="control">
            <a href="javascript:{}" onclick="$('navbar-menu .searchBar').submit();" class="button is-info search" style="background-color: #ffffff;border-color: transparent;color: #209cee;">
              <img src="{{ asset('images/icons/lupa.png') }}" style="max-width: 1.5rem;" />
            </a>
          </div>
          <div class="control is-expanded">
            <input 
              style="border-bottom: 0; border-left: 1px solid #ffd900;"
              class="input typeahead" 
              type="text" name="raw" placeholder="Qué estas buscando" required="">
          </div>                          
        </div>
      </form>
    </div>

    <div class="navbar-menu">
      <div class="columns is-multiline is-gapless">
        <div class="column is-12">          
          <div class="navbar-end menu-access">
            @if(!Auth::user())
            <div class="navbar-item">
              <a href="#" class="link-login" id="ingresarButton">
                <img class="icon-link login-icon" src="{{ asset('images/icons/login.png') }}"> Ingresar
              </a>
              <span class="h-separator">|</span>
              <a href="#" class="link-register" id="registrarButton">Registro</a>
            </div>
            <div class="navbar-item {{ Request::is('contacto') ? 'is-active' : '' }} is-hidden-mobile">
            <a href="{{ url('contacto' )}}">Contacto</a>
            </div>
            @else
            <div class="navbar-item has-dropdown is-hoverable is-right">
              <a class="navbar-link">
                Mi cuenta
              </a>
              <div class="navbar-dropdown">

                <input type="hidden" id="roles_uri" value="{{ route('user.get-roles-user') }}">
                <input type="hidden" id="profile_uri" value="{{ url('/') }}">
                <a href="#" class="navbar-item" id="profile-link" >
                  Perfil
                </a>
                <a href="{{ url('logout') }}" class="navbar-item">
                  Salir
                </a>
              </div>
            </div>
            @endif
          </div>
        </div>
        <div class="column is-12">
          <div class="navbar-end menu-general">
          <div class="navbar-item {{ Request::is('/') ? 'is-active' : '' }}">
              <a href="{{ url('/') }}">Inicio </a>
            </div>
            <div class="navbar-item {{ Request::is('publicar') ? 'is-active' : '' }}">
              <a href="{{ url('publicar') }}">Publica</a>
            </div>
            <div class="navbar-item {{ Request::is('postular') ? 'is-active' : '' }}">
              <a href="{{ url('postular') }}">
                <img class="icon-link" src="{{ asset('images/icons/rayo-postular.png') }}"> Postular
              </a>
            </div>
            <div class="navbar-item {{ Request::is('membresias') ? 'is-active' : '' }}">
              <a href="{{ url('membresias') }}">Membresías</a>
            </div>
            <div class="navbar-item {{ Request::is('explorar', 'explorar/*') ? 'is-active' : '' }}">
              <a href="{{ url('explorar') }}">Arriendos</a>
            </div>
            <div class="navbar-item {{ Request::is('agentes', 'agentes/*') ? 'is-active' : '' }}">
              <a href="{{ url('agentes') }}">Venta de propiedades</a>
            </div>
            <div class="navbar-item {{ Request::is('servicios') ? 'is-active' : '' }}">
              <a href="{{ url('servicios') }}">Servicios</a>
            </div>            
            <div class="navbar-item {{ Request::is('contacto') ? 'is-active' : '' }} is-hidden-tablet">
              <a href="{{ url('contacto' )}}">Contacto</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</nav>