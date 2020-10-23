@extends('layouts.app')

@section('header')
<div class="navbar-wrapper">
    @include('layouts.header', ['isSolid' => false])
</div>
<section id="memberships">
  <section class="hero is-fullheight" id="landing-header">
      <div class="hero-head">
          @parent
      </div>
      <div class="hero-body">
          <div class="container main-title-container">
              <div class="columns is-mobile is-centered">
                  <div class="column is-8 has-text-centered">
                      <img src="{{ asset('images/membresias_banner.png') }}" class="title-image">
                  </div>
              </div>
          </div>
      </div>
      <div class="hero-foot has-text-centered">
          <i class="fa fa-angle-down" id="arrow-down"></i>
      </div>
  </section>
  <section class="hero main-title second-section">
      <div class="hero-body">

          <div class="container">
              <h1 class="title">Selecciona el plan</h1>
              <h1 class="title">acorde a tus necesidades</h1>
          </div>
      </div>
  </section>
  <section class="section" v-cloak>
      <div class="columns">
          <div class="column">
              <div class="membership-item" data-account-type="1">
                  <div class="overlay" v-on:click="clickOverlay"></div>
                  <i class="fa fa-angle-down icon-arrow-down"></i>
                  <div class="membership-image-wrapper">
                      <img src="{{ asset('images/arrendatario.jpg') }}">
                      <div class="membership-title" v-on:click="clickPlanTable">
                          <h1>Arrendatario</h1>
                          <span>Busco una propiedad para arrendar</span>
                      </div>
                  </div>
                  <div class="membership-action-wrapper">
                      <h1>Arrendar</h1>
                      <span>Sin comisión ni intermedarios.</span>
                      <button class="button is-outlined is-primary btn-check-plan" v-on:click="clickPlanTable">Ver plan</button>
                      <div class = "check-plan"><br></div>
                  </div>
              </div>
          </div>
          <div class="column">
              <div class="membership-item" data-account-type="2">
                  <div class="overlay" v-on:click="clickOverlay"></div>
                  <i class="fa fa-angle-down icon-arrow-down"></i>
                  <div class="membership-image-wrapper" v-on:click="clickPlanTable">
                      <img src="{{ asset('images/propietario.jpg') }}">
                      <div class="membership-title">
                          <h1>Propietario</h1>
                          <span>Quiero arrendar mi propiedad</span>
                      </div>
                  </div>
                  <div class="membership-action-wrapper">
                      <h1>Publicar tu propiedad </h1>
                      <span>Baja comisión, sin intermediarios.</span>
                      <button class="button is-outlined is-primary btn-check-plan" v-on:click="clickPlanTable">Ver plan</button>
                      <div class = "check-plan"><br></div>
                  </div>
                  
              </div>
          </div>
          <div class="column">
              <div class="membership-item" data-account-type="3">
                  <div class="overlay" v-on:click="clickOverlay"></div>
                  <i class="fa fa-angle-down icon-arrow-down"></i>
                  <div class="membership-image-wrapper" v-on:click="clickPlanTable">
                      <img src="{{ asset('images/agente.jpg') }}">
                      <div class="membership-title">
                          <h1>Agente</h1>
                          <span>Quiero vender mi propiedad</span>
                      </div>
                  </div>
                  <div class="membership-action-wrapper">
                      <h1>Gestiona proyectos</h1>
                      <span>De manera rápida y efectiva.</span>
                      <button class="button is-outlined is-primary btn-check-plan" v-on:click="clickPlanTable">Ver plan</button>
                      <div class = "check-plan"><br></div>
                  </div>
              </div>
          </div>
          <div class="column">
              <div class="membership-item" data-account-type="4">
                  <div class="overlay" v-on:click="clickOverlay"></div>
                  <i class="fa fa-angle-down icon-arrow-down"></i>
                  <div class="membership-image-wrapper" v-on:click="clickPlanTable">
                      <img src="{{ asset('images/servicios.jpg') }}">
                      <div class="membership-title">
                          <h1>Servicio</h1>
                          <span>Quiero ofrecer mi servicio</span>
                      </div>
                  </div>
                  <div class="membership-action-wrapper">
                      <h1>Ofrece</h1>
                      <span>Mayor alcance de su servicio</span>
                      <button class="button is-outlined is-primary btn-check-plan" v-on:click="clickPlanTable">Ver plan</button>
                      <div class = "check-plan"><br></div>
                  </div>
              </div>
          </div>
      </div>
  </section>

  <section class="section plans-section">
      <section class="hero main-title">
          <div class="hero-body">
              <div class="container">
                  <h1 class="title">Selecciona un</h1>
                  <h1 class="title">plan a tu medida</h1>
              </div>
          </div>
      </section>


      <div class="table-wrapper">
          <table class="plans-table" >
            <tbody>
              <tr>
                <td></td>
                <td class="border-bottom border-basic">
                  <img src="{{ asset('images/logo_basic.png') }}">
                </td>
                <td class="border-bottom border-select">
                  <img src="{{ asset('images/logo_select.png') }}">
                </td>
                <td class="border-bottom border-premium">
                  <img src="{{ asset('images/logo_premium.png') }}">
                </td>
              </tr>
              <!-- Price ELement -->
              <tr v-for="item in features" v-if="item.human_name == 'Precio'">
                <td  class="price">
                </td>
                <td  class="price" >
                  <div v-if="item.basic == 0">
                    <span>$ @{{ item.basic }}</span><span>(CLP)</span>
                  </div>
                  <div v-if="item.basic != 0">
                    <div v-if="item.role != 3"><span>$ @{{ item.basic }}<span>+ IVA</span></span><span>(CLP)</span></div>
                    <div v-else><span>UF @{{ item.basic }}<span>+ IVA</span></span></div>
                  </div>
                </td>
                <td  class="price" >
                  <div v-if="item.select == 0">
                    <span>$ @{{ item.select }}</span><span>(CLP)</span>
                  </div>
                  <div v-if="item.select != 0">
                    <div v-if="item.role != 3"><span>$ @{{ item.select }}<span>+ IVA</span></span><span>(CLP)</span></div>
                    <div v-else><span>UF @{{ item.select }}<span>+ IVA</span></span></div>
                  </div>
                </td>
                <td  class="price" >
                  <div v-if="item.premium == 0">
                    <span>$ @{{ item.premium }}</span><span>(CLP)</span>
                  </div>
                  <div v-if="item.premium != 0">
                    <div v-if="item.role != 3"><span>$ @{{ item.premium }}<span>+ IVA</span></span><span>(CLP)</span></div>
                    <div v-else><span>$ @{{ item.premium }}<span>+ IVA</span></span></div>
                  </div>
                </td>
              </tr>
              <!-- Others Elements -->
              <tr v-for="item in features" v-if="item.human_name !== 'Precio'">
                <td  class="border-bottom">
                  @{{ item.human_name }}
                </td>
                <td  class="value" >
                  <div v-if="item.type == 'boolean'">
                    <div v-if="item.basic == true">
                      <img src="{{ asset('images/icono_ok_basic.png') }}">
                    </div>
                    <div v-else>
                      <span>No</span>
                    </div>
                  </div>
                  <div v-if="item.type == 'unlimited'">
                    <div v-if="item.basic == -1">
                      <span>Illimitado</span>
                    </div>
                    <div v-else>
                      <span>@{{ item.basic }}</span>
                    </div>
                  </div>
                  <div v-if="item.type == 'number'">
                      <span>@{{ item.basic }}</span>
                  </div>
                </td>
                <td  class="value" >
                  <div v-if="item.type == 'boolean'">
                    <div v-if="item.select == true">
                      <img src="{{ asset('images/icono_ok_select.png') }}">
                    </div>
                    <div v-else>
                      <span>No</span>
                    </div>
                  </div>
                  <div v-if="item.type == 'unlimited'">
                    <div v-if="item.select == -1">
                      <span>Illimitado</span>
                    </div>
                    <div v-else>
                      <span>@{{ item.select }}</span>
                    </div>
                  </div>
                  <div v-if="item.type == 'number'">
                      <span>@{{ item.select }}</span>
                  </div>
                </td>
                <td  class="value" >
                  <div v-if="item.type == 'boolean'">
                    <div v-if="item.premium == true">
                      <img src="{{ asset('images/icono_ok_premium.png') }}">
                    </div>
                    <div v-else>
                      <span>No</span>
                    </div>
                  </div>
                  <div v-if="item.type == 'unlimited'">
                    <div v-if="item.premium == -1">
                      <span>Illimitado</span>
                    </div>
                    <div v-else>
                      <span>@{{ item.premium }}</span>
                    </div>
                  </div>
                  <div v-if="item.type == 'number'">
                      <span>@{{ item.premium }}</span>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
      </div>
      <input type="hidden" value="{{ asset('images') }}" id="images-dir">
  </section>
</section>
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('css/memberships.css') }}">
@endsection

@section('scripts')
<script src="{{ asset('js/memberships.js') }}"></script>
<script src="{{ asset('js/landing.js') }}"></script>
<script type="text/javascript">
  // $( ".btn-check-plan" ).click(function() {
  //                     $(this).next().append($('.plans-section')).html();
  //                   });
  $(document).ready(function () {
    var $window = $(window);
    function checkWidth() {
      var windowsize = $window.width();
      if (windowsize <= 768) {
        $( ".btn-check-plan" ).click(function() {
          $(this).next().append($('.plans-section')).html();
          return false;
        });
      }
    }
    checkWidth();
  });
</script>
@endsection
