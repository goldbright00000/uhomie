<template>
	<div class="container-fluid">
	  <div class="columns">
      <div class="column is-6 resume-container">
          <div class="property-resume">
              <div class="columns is-vcentered is-mobile">
                <div class="column is-two-fifths">
                    <img class="membership-logo" :src="imagesDir + membershipOwnerLogo(property.owner.membership_name)">
                </div>
                <div class="column has-text-right">
                  <p class="buttons is-right">
                    <a class="button is-primary is-outlined" onClick="javascript:history.go(-1)">
                      <span>VOLVER</span>
                    </a>
                    <a class="button is-primary is-outlined" target="_blank" :href="'/explorar/get-document-property/'+property.data.id">
                      <span class="icon"><i class="fa fa-print fa-2x"></i></span>
                    </a>
                  </p>
                </div>
              </div>
              
              <h1 class="title">{{property.properties_type.name + ' - ' + property.data.name }}</h1>
              <span class="price">
              	<img :src="imagesDir + '/explore-details/ico_moneda.png'"> CLP 
              	<span class="value" v-html="moneyFormat(property.data.rent)">150.000</span>
              	<span v-html="type_stay == 'LONG_STAY' ? 'x mes ' : ' x día '"></span>
              </span>

              <span class="verified" v-show="property.data.verified">
              	<img :src="imagesDir +'/explore-details/ico_propiedad_verificada.png'"> Propiedad verificada
              </span>
          </div>

          <div class="owner-media" style="margin-left: 2rem">
            <article class="media" v-show="property.owner.id">
              <a target="_blank" :href="linkTypeUser">
                <div class="media-left">
                  <img class="picture" :src="property.owner.photo ? property.owner.photo : imagesDir + '/husky.png'" :alt="property.owner.firstname + ' ' + property.owner.lastname + '-photo'">
                  <img class="overlay-picture" :src="imagesDir + membershipOwnerMask(property.owner.membership_name)">
                </div>
              </a>
              <div class="media-content">
                <star-rating v-model="starRating.value" v-bind="starRating.options"></star-rating>
                <h2 class="name">
                  <a target="_blank" :href="linkTypeUser" v-html="property.owner.firstname + ' ' + property.owner.lastname"></a>
                </h2>
                <h2><span v-html="titleTypeUser"></span>  <span v-if="property.owner.verify" class="tag is-link is-success"><i style="color: white; margin-right: 5px;" class="fa fa-check"></i>Verificado</span><span v-if="!property.owner.verify" class="tag is-link is-warning"><i style="color: white; margin-right: 5px;" class="fa fa-hourglass"></i>Por verificar</span></h2>
                <a :class="'button is-outlined btn-contact ' + membershipOwnerButtom(property.owner.membership_name)" @click="contact = true">
                  Contacto
                </a>
              </div>
              <!--<span class="media-right">
                <img src="{{ asset('images/explore-details/ico_comentarios.png') }}"> 8
              </span>-->
            </article>
          </div>
      
      </div>
      <div class="column is-6">
      	<div class="description">
          <h5 class="title">Descripción</h5>
          <view-more :content="property.data.description">
          </view-more>          
        </div>
        <hr :style="{ margin: '1rem'}" />
        <div class="features">
          <span v-for="(feature, index) in propertyFeatures" :key="index" :title="feature.name"  class="tooltip" :data-tooltip="feature.name">
            <img :src="imagesDir + feature.image" :alt="feature.name + '-icon'">
            <span v-html="feature.value(property.data)"></span>
          </span>
      	</div>
      	<hr :style="{ margin: '1rem'}" />
      	<div class="columns">
      		<div class="column is-3">
            <div>
              <span class="date tooltip" data-tooltip="Disponible desde"><img :src="imagesDir + '/explore-details/ico_calendario.png'">{{dateFormat(property.data.available_date)}}</span>
            </div>
      			<div>
              <span v-show="propertyDemand" :class="propertyDemand.class" >
                <img :src="imagesDir + propertyDemand.image"> {{ propertyDemand.text }}
              </span>
            </div>
      		</div>
      		<div class="column is-3">
      			<span class="title-scoring">
      				<img :src="imagesDir + '/explore-details/ico_scoring.png'"> Tu scoring uHomie para esta propiedad es:
      			</span>
      		</div>
      		<div class="column is-6">
      			<div class="property-scoring tooltip" :data-tooltip="scoring_data">		          
		          <vue-slider v-model="scoringSlider.value" v-bind="scoringSlider"></vue-slider>
		        </div>
      		</div>
      	</div>
      	<div class="columns">
      		<div class="column is-6">
      			<table class="precios" style="width: 100%" v-if="property.data.is_project == 0 && property.data.type_stay == 'LONG_STAY'">
              <tbody>
              	<tr class="is-size-7">
                  <td class="has-text-weight-bold">
                    <p>
                      Mes de adelanto:
                      <span class="tooltip" data-tooltip="Adelanto exigido por el dueño">
                        <img style="width: 15px;" :src="imagesDir + '/icons/info1.png'"/>
                      </span>
                    </p>
                  </td>
                  <td class="has-text-weight-bold" v-html="moneyFormat(prices.months_advance_quantity) + ' CLP'"></td>
                </tr>
                <tr class="is-size-7">
                  <td class="has-text-weight-bold">
                    <p>
                      Mes de garantía:
                      <span class="tooltip is-tooltip-multiline" data-tooltip="La garantía quedara retenida en UHOMIE por el tiempo de arriendo">
                        <img style="width: 15px;" :src="imagesDir + '/icons/info1.png'"/>
                      </span>
                    </p>
                  </td>
                  <td class="has-text-weight-bold" v-html="moneyFormat(prices.warranty_months_quantity) + ' CLP'"></td>
                </tr>
                <tr class="is-size-7" v-if="prices.rental_insurance > 0">
                  <td class="has-text-weight-bold">
                    <p>
                      Seguro:
                      <span class="tooltip" data-tooltip="Es el seguro de arriendo exigido por el dueño">
                        <img style="width: 15px;" :src="imagesDir + '/icons/info1.png'"/>
                      </span>
                    </p>
                  </td>
                  <td class="has-text-weight-bold" v-html="moneyFormat(prices.rental_insurance) + ' CLP'"></td>
                </tr>
                <tr class="is-size-7">
                  <td class="has-text-weight-bold">
                    <p>
                      Servicio uHomie:
                      <span class="tooltip is-tooltip-multiline" data-tooltip="Incluye contrato digital, Firma digital, Validación de identidad Digital y Procesamiento de Pago electrónico : 11%">
                        <img style="width: 15px;" :src="imagesDir + '/icons/info1.png'"/>
                      </span>
                    </p>
                  </td>
                  <td class="has-text-weight-bold" v-html="moneyFormat(prices.services_provided) + ' CLP'"></td>
                </tr>
                <tr class="tfooter is-size-7">
                  <td class="has-text-weight-bold">
                    <p>
                      Total a Pagar:
                      <span class="tooltip" data-tooltip="Total mas IVA">
                        <img style="width: 15px;" :src="imagesDir + '/icons/info1.png'"/>
                      </span>
                      </p>
                  </td>
                  <td class="has-text-weight-bold" v-html="moneyFormat(prices.total_to_pay) + ' CLP'" ></td>
                </tr>
              </tbody>              	
            </table>
      		</div>
      		<div class="column is-6 contacto" v-if="property.data.visit == 1">
      			<a href="#schedule" :class="'button is-outlined btn-contact ' + membershipOwnerButtom(property.owner.membership_name)">
      				Agendar visita
      			</a>
      		</div>
      	</div>
      </div>
	  </div>
    <contact v-bind:contact="contact" v-bind:owner="property.owner" v-bind:tenant="property.tenant"></contact>
	</div>
</template>

<script>
	import Axios from 'axios';
  import Contact from "../Contact.vue";
	import ViewMore from "../pages/ViewMore.vue";
	import vueSlider from "vue-slider-component";
	import StarRating from "vue-star-rating";


const imagesDir = document.getElementById('images-dir').value;

export default {
  components: {
  	StarRating,
  	vueSlider,
    ViewMore,
    Contact
  },
  props: [ 'property', 'property-photos', 'membership-owner-logo', 'membership-owner-buttom','money-format', 'date-format', 'property-features', 'type_stay' ],
  data: function () {
    return {
      imagesDir: imagesDir,
      scoring_data: '',
      contact: false,
      scoringSlider: {
        value: 0,
        min: 0,
        max: 1500, //1380
        height: 5,
        tooltip: "always",
        piecewise: true,
        disabled: true,
        interval: 250,
        bgStyle: {
          background:
            "linear-gradient(90deg, rgba(241,62,13,1) 0%, rgba(232,245,11,1) 50%, rgba(30,181,6,1) 100%)"
        },
        processStyle: {
          background: "transparent"
        },
        piecewiseStyle: {
          visibility: "visible",
          width: "12px",
          height: "12px"
        },
        sliderStyle: {
          background: "transparent",
          boxShadow: "none"
        }
      },
      starRating: {
        value: 0,
        options: {
          starSize: 15,
          showRating: false,
          borderWidth: 3,
          borderColor: "hsl(51, 100%, 50%)",
          inactiveColor: "#fff",
          activeColor: "hsl(51, 100%, 50%)"
        }
      },
    }
  },
  mounted() {
  	this.getUserScoring();
  },
  methods: {
  	membershipOwnerMask: function(name) {
      switch (name) {
        case 'Basic':{
          return '/explore-details/mascara_img_basic.png';}
        case 'Select':{
          return '/explore-details/mascara_img_select.png';}
        case 'Premium':{
          return '/explore-details/mascara_img_premium.png';}
      
        default:{
          return '/explore-details/mascara_img_basic.png';}
      }
    },
  	getUserScoring: function() {
      let vm = this;

      Axios.get("/properties/" + vm.property.id() + "/my_score")
        .then(response => {
          vm.$set(vm.scoringSlider, "value", response.data);
          if(response.data >= 0 && response.data <= 300){
            vm.scoring_data = 'Tu probabilidad de arriendo es: Muy baja';
          }
          if(response.data >= 301 && response.data <= 500){
            vm.scoring_data = 'Tu probabilidad de arriendo es: Bajo';
          }
          if(response.data >= 501 && response.data <= 800){
            vm.scoring_data = 'Tu probabilidad de arriendo es: Medio';
          }
          if(response.data >= 801 && response.data <= 900){
            vm.scoring_data = 'Tu probabilidad de arriendo es: Bueno';
          }
          if(response.data >= 901 && response.data <= 1100){
            vm.scoring_data = 'Tu probabilidad de arriendo es: Alto';
          }
          if(response.data >= 1101){
            vm.scoring_data = 'Tu probabilidad de arriendo es: Muy alta';
          }
        })
        .catch(e => {
          switch (e.response.status) {
            default: {
            }
          }
        });
    },
  },
  computed: {
  	titleTypeUser: function(){
      if(this.property.owner.type == 'Owner'){
        return 'Dueño'
      } else if(this.property.owner.type == 'Agent'){
        return 'Agente';
      }
    },
  	linkTypeUser: function(){
      if(this.property.owner.type == 'Owner'){
        return '/users/show/'+ this.property.owner.id;
      } else if(this.property.owner.type == 'Agent'){
        return '/users/agente/'+ this.property.owner.id;
      }
    },
  	propertyDemand: function() {
      let available_demands = {
        // High
        high: {
          code: 2,
          class: "demand demand-high",
          image: "/explore-details/ico_demanda_high.png",
          text: "Demanda alta"
        },
        // Medium
        medium: {
          code: 1,
          class: "demand demand-medium",
          image: "/explore-details/ico_demanda_medium.png",
          text: "Demanda media"
        },
        // Low
        low: {
          code: 0,
          class: "demand demand-low",
          image: "/explore-details/ico_demanda_low.png",
          text: "Demanda baja"
        }
      };

      let vm = this;

      if (!vm.property.data) return {};

      switch (vm.property.data.demand_property) {
        // High demand
        case available_demands.high.code: {
          return available_demands.high;
        }
        // Medium demand
        case available_demands.medium.code: {
          return available_demands.medium;
        }
        // Low demand or default
        case available_demands.low.code:
        default: {
          return available_demands.low;
        }
      }
    },
    isFavouriteProperty: function() {
      let vm = this;

      return typeof vm.property.data.favourite != "undefined"
        ? vm.property.data.favourite
        : false;
    },
    prices(){
      let vm = this;
      var months_advance_quantity = parseInt(vm.property.data.months_advance_quantity) * parseInt(vm.property.data.rent);
      var warranty_months_quantity = parseInt(vm.property.data.warranty_months_quantity) * parseInt(vm.property.data.rent);
      var services_provided = parseInt(parseInt(vm.property.data.rent) * 0.11) * 1.19;
      
      if(vm.property.data.tenanting_insurance > 0){
        var rental_insurance = parseInt(parseInt(vm.property.data.rent) * 0.5) * 1.19
      } else {
        var rental_insurance = 0;
      }
      
      var total_to_pay = parseInt(months_advance_quantity + warranty_months_quantity + services_provided + rental_insurance);
      
      
      
      return {
        months_advance_quantity: months_advance_quantity,
        warranty_months_quantity: warranty_months_quantity,
        services_provided: services_provided,
        total_to_pay: total_to_pay,
        rental_insurance: rental_insurance
      }
    },

  }
}
</script>

<style lang="scss" scoped>
	.property-resume {
		background-color: transparent;
	}
	.property-resume .title {
		font-family: 'Lato';
		font-weight: 500;
	}
	.features .tooltip {
		padding: 1rem;
	}
	.property-scoring {
		background-color: transparent;
		margin-top: 0;
		padding-top: 0;
	}
	.title-scoring {
		font-size: 0.8rem;
	}
	.name {		
		font-family: 'Lato';
		font-weight: 500;
	}
	.name a {
		color: #191919;
	}
	table.precios tr td:nth-child(2) {
		text-align: right;
		font-weight: 500;
	}
	.tfooter {
		border-top: 1px dashed #ddd;
		padding-top: 1rem;
		margin-top: 1rem;
	}
	.contacto {
		text-align: center;
		padding-top: 2rem;
  }
  .demand {
    font-size: 0.9rem;
    text-transform: uppercase;
    line-height: 1.5rem;
    display: inline-flex;
    vertical-align: middle;

    &-high {
      color: red;
    }

    &-medium {
      color: #ffd900;
      font-weight: 500;
    }

    &-low {
      color: green;
      font-weight: 500;
    }

    img {
      vertical-align: middle;
      height: 1.5rem;
      margin-right: 0.25rem;
    }
  }
</style>
