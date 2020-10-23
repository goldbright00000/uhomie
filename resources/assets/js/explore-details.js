import BulmaTooltip from "bulma-tooltip";
import bulmaAccordion from "bulma-accordion/dist/js/bulma-accordion.min";
import Gallery from "./components/pages/Gallery.vue";

import Contact from "./components/Contact.vue";
import Property from "./components/Property.vue";
import MapDetail from "./components/MapDetail.vue";
import Schedule from "./components/pages/Schedule.vue";
import ApplyButton from "./components/pages/ApplyPropertyButton.vue";
import Axios from "axios";
import bulmaCarousel from 'bulma-carousel/dist/js/bulma-carousel.min';
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';
import Calendar from './components/pages/Calendar.vue';
import SelectDays from "./components/pages/SelectDays";

const imagesDir = document.getElementById("images-dir").value;

import VCalendar from 'v-calendar/lib/v-calendar.umd.min.js';

import PropertyHeader from './components/explore-details/header.vue'
import PropertyDetails from './components/explore-details/details.vue'
import PropertyGallery from './components/explore-details/gallery.vue'
import PropertyServices from './components/explore-details/services.vue'
import PropertySpaces from './components/explore-details/spaces.vue'
import PropertyUbication from './components/explore-details/ubication.vue'
import PropertyConditions from './components/explore-details/conditions.vue'

import CalendarShortStay from './components/explore-details/calendarShortStay.vue'

// Use v-calendar & v-date-picker components
Vue.use(VCalendar, {
  componentPrefix: 'vc',  // Use <vc-calendar /> instead of <v-calendar />
  //...,// ...other defaults
});

const exploreDetails = new Vue({
  el: "#explore-details",
  delimiters: ["{{", "}}"],
  components: {    
    Contact,    
    Property,
    MapDetail,
    Schedule,
    Gallery,
    ApplyButton,    
    Loading,
    bulmaCarousel,
    Calendar,
    SelectDays,
    PropertyHeader,
    PropertyDetails,
    PropertyGallery,
    PropertyServices,
    PropertySpaces,
    PropertyUbication,
    PropertyConditions,
    CalendarShortStay
  },
  data() {
    return {
      check_in: '',
      check_out: '',
      count_days: '',
      commission: 0,
      sub_total: 0,
      total_stay: 0,
      total_iva: 0,
      iva: 0.19,
      offer_week: 0,
      offer_month: 0,
      schedule_dates: [],
      dias_seleccionados: [],
      fechas_ocupadas: null,
      attributesC: [],
      now: Date(),
      fechas: [],
      modal: false,
      mode: 'range',
      logged: false,
      contact: false,
      upgradeMembershipUrl: '',
      isLoading: true,
      recommendedProperties: [],
      similarProperties: [],      
      property: {
        id: function() {
          return window.location.pathname.split("/")[2];
        },
        data: {},
        owner: {},
        tenant: {},
        photos: {},
        amenities: [],
        properties_for: [],
        properties_type: {}
      },
      imgDir: imagesDir,
      executive: {}
    };
  },
  computed: {    
    fechas_ocupadas_computado: function(){
      if( !this.fechas_ocupadas ) return null;
      else {
        let arreglo = [];
        this.fechas_ocupadas.disables.forEach(function(elem){
          arreglo.push({start: elem, end: elem});
        });
        this.fechas_ocupadas.postulations.forEach(function(elem){
          arreglo.push(JSON.parse(elem))
        })
        return arreglo;
      }
    },
    unitAmenities: function() {
      return this.property.amenities.filter(amenity => amenity.type == 0);
    },
    communAmenities: function() {
      return this.property.amenities.filter(amenity => amenity.type == true);
    },
    basicAmenities: function() {
      return this.property.amenities.filter(amenity => amenity.type == 2);
    },
    rulesAmenities: function() {
      return this.property.amenities.filter(amenity => amenity.type == 3);
    },
    detailsAmenities: function() {
      return this.property.amenities.filter(amenity => amenity.type == 4);
    },
    propertyFeatures: function() {
      let features = [],
        vm = this;
      let available_features = [
        // Bedrooms
        {
          image: "/explore/ico_habitacion_azul.png",
          name: "Habitaciones",
          value: function(property) {
            if (typeof property.bedrooms == "undefined") return undefined;

            return isNaN(parseFloat(property.bedrooms)) ? property.rooms : property.bedrooms;
          }
        },
        // Bathroroms
        {
          image: "/explore/ico_banos_azul.png",
          name: "Baños",
          value: function(property) {
            if (typeof property.bathrooms == "undefined") return undefined;

            return isNaN(parseInt(property.bathrooms)) ? 0 : property.bathrooms;
          }
        },
        // Private Parking
        {
          image: "/explore/ico_estacionamiento_azul.png",
          name: "Estacionamiento",
          value: function(property) {
            return property.private_parking;
          }
        },
        // Pets
        {
          image: "/explore/ico_animales_azul.png",
          name: "Mascotas",
          value: function(property) {
            return property.pet_preference != "no" ? "SI" : "NO";
          }
        },
        // Furnished
        {
          image: "/explore/ico_amueblado_azul.png",
          name: "Amoblado",
          value: function(property) {
            return property.furnished ? "SI" : "NO";
          }
        },
        // Meters
        {
          image: "/icono_superficie.png",
          name: "Metros cuadrados",
          value: function(property) {
            return isNaN(parseInt(property.meters))
              ? 0
              : parseInt(property.meters) +
                  "<span style='font-size: .9em;'>m<sup>2</sup></span>";
          }
        }
      ];

      if (vm.property.data) {
        features = available_features.filter(
          feature => typeof feature.value(vm.property.data) != "undefined"
        );
      }

      return features;
    },    
    propertyPhotos: function() {
      let vm = this;
      let photos = [];
      if (typeof vm.property.photos.photos == "undefined") return photos;

      if (vm.property.photos.cover == null) return photos;

      photos = vm.property.photos.photos;
      photos.unshift(vm.property.photos.cover[0]);

      photos = photos.map(photo => {
        let new_photo = {};

        new_photo.name =
          typeof photo.space == "undefined" || !photo.space
            ? null
            : photo.space.name;

        new_photo.src = photo.path;

        return new_photo;
      });

      console.log(photos)

      return photos;
    },    
  },
  mounted: function() {
    
    this.getData();    
    this.getOwner();
    this.getTenant();
    this.getPhotos();
    
    this.getAmenities();
    this.getPropertiesFor();
    this.getPropertiesType();
    this.getProperties();
    
    this.getRecommendedProperties();
    
    this.getSimilarProperties();
    
    this.getExecutive();
    
  },  
  methods: {
    
    abrir_modal_select_days: function(valor){
        console.log('evento escuchado el property_id es: '+valor);
        var vm = this;
        
        Axios.post('/explorar/get-unavailable-property-days', {
            property_id: valor
        }).then(function(response){
            console.log('response: ', response);
            if( response.status == 244 ){
            toastr.warning('Debes estar logueado para poder postular');
            } 
            if( response.status == 200) {
            vm.fechas_ocupadas = response.data;
            $('#modalSelectDays').addClass('is-active');
            }
            
        });
        

    },
    getRecommendedProperties: function(){
      let vm = this;
      Axios.get("/explorar/get-recommended-properties/4")
        .then(response => {
          vm.recommendedProperties = response.data;
          if(response.data.length > 0) vm.logged = true;
          //console.log(vm.recommendedProperties);
        })
        .catch(e => {
          console.log("Ha ocurrido un error...");
        });
    },
    getSimilarProperties: function(){
      let vm = this;
      Axios.get("/explorar/get-similar-properties/" + this.property.id())
        .then(response => {
          vm.similarProperties = response.data;
          if(response.data.length > 0) vm.logged = true;
          //console.log(vm.similarProperties);
        })
        .catch(e => {
          console.log("Ha ocurrido un error...");
        });
    },
    getData: function() {
      this.isLoading = true
      let vm = this;
      // Get properties self data
      Axios.get("/properties/" + this.property.id())
        .then(response => {
          this.isLoading = false
          console.log(response)
          vm.$set(vm.property, "data", response.data);
        })
        .catch(error => {
          this.isLoading = false
          console.log(error);
        });
    },    
    getOwner: function() {
      let vm = this;
      Axios.get("/properties/" + vm.property.id() + "/owner").then(response => {
        vm.$set(vm.property, "owner", response.data);
      });
    },
    getTenant: function() {
      let vm = this;
      Axios.get("/properties/" + vm.property.id() + "/tenant")
      .then(response => {
        console.log(response)
        vm.$set(vm.property, "tenant", response.data);
      });
    },
    getPhotos: function() {
      let vm = this;
      Axios.get("/properties/" + this.property.id() + "/photos").then(
        response => {
          vm.$set(vm.property, "photos", response.data);

          if (typeof vm.property.photos.cover === undefined)
            vm.property.photos.cover = null;

          // Set cover image if null
          if (vm.property.photos.cover === null) {
            if (vm.property.photos.photos.length > 0) {
              vm.property.photos.cover = vm.property.photos.photos.shift();
            }
          }
        }
      );
    },
    getAmenities: function() {
      let vm = this;
      Axios.get("/properties/" + vm.property.id() + "/amenities").then(
        response => {
          vm.$set(vm.property, "amenities", response.data);
        }
      );
    },
    getPropertiesFor: function() {
      let vm = this;
      Axios.get("/properties/" + vm.property.id() + "/properties-for")
        .then(response => {
          vm.property.properties_for = response.data;
        })
        .catch(e => {
          console.log("Ha ocurrido un error");
        });
    },
    getPropertiesType: function() {
      let vm = this;
      Axios.get("/properties/" + vm.property.id() + "/properties-type")
        .then(response => {
          vm.property.properties_type = response.data;
        })
        .catch(e => {
          console.log("Ha ocurrido un error");
        });
    },
    
    getProperties: function() {
      let vm = this;

      Axios.get("/explorar/get-properties")
        .then(response => {
          //vm.similarProperties = response.data.properties;
          console.log(vm.similarProperties);
        })
        .catch(e => {
          console.log("Ha ocurrido un error...");
        });
    },
    
    togglefavorite: function(el) {
      let vm = this;
      el.classList.add("is-loading");

      Axios.put("/properties/" + vm.property.id() + "/toggle-favourite")
        .then(response => {
          if ((response.status = 200)) {
            vm.property.data.favourite = Number(response.data) > 0;
          }
        })
        .catch(e => {
          switch (e.response.status) {
            case 401: {
              toastr["warning"]("Debes iniciar sesion!");
              break;
            }
            case 403: {
              toastr["error"](
                "Debes ser arrendatario para guardar como favorita esta propiedad."
              );
              break;
            }
            case 422: {
              for (let field in e.response.data.errors) {
                toastr["error"](e.response.data.errors[field].pop());
                break;
              }
              break;
            }
            default: {
              toastr["error"]("Ha ocurrido un error inesperado.");
            }
          }
        })
        .then(e => {
          el.classList.remove("is-loading");
        });
    },
    applyToProperty: function(el) {
      let vm = this;

      if ($(el).hasClass("is-loading") || $(el).hasClass("is-active"))
        return false;

      el.classList.add("is-loading");

      Axios.post("/properties/" + vm.property.id() + "/apply")
        .then(response => {
          $(el)
            .text("¡Postulado!")
            .addClass("is-active");
        })
        .catch(e => {
          switch (e.response.status) {
            case 401: {
              toastr["warning"]("Debes iniciar sesion");
              break;
            }
            default: {
              toastr["error"]("Ha ocurrido un error inesperado.");
            }
          }
        })
        .then(e => {
          el.classList.remove("is-loading");
        });
    },
    // Filters
    serializeProperty: function(property) {
      const rules = {
        boolean: ["verified", "favorite", "demand"],
        number: [
          "id",
          "price",
          "bathNumber",
          "roomNumber",
          "parkingNumber",
          "scoring"
        ]
      };

      let property_copy = Object.assign({}, property);

      for (let type in rules) {
        for (let i = 0; i < rules[type].length; i++) {
          let field = rules[type][i];

          switch (type) {
            case "boolean": {
              property_copy[field] = Boolean(property[field]);
              break;
            }
            case "number": {
              property_copy[field] = Number(property[field]);
              break;
            }
            default: {
              property_copy[field] = String(property[field]);
            }
          }
        }
      }

      return property_copy;
    },
    moneyFormat: function(n, c, d, t) {
      var c = isNaN((c = Math.abs(c))) ? 0 : c,
        d = d == undefined ? "," : d,
        t = t == undefined ? "." : t,
        s = n < 0 ? "-" : "",
        i = String(parseInt((n = Math.abs(Number(n) || 0).toFixed(c)))),
        j = (j = i.length) > 3 ? j % 3 : 0;

      return (
        s +
        (j ? i.substr(0, j) + t : "") +
        i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) +
        (c
          ? d +
            Math.abs(n - i)
              .toFixed(c)
              .slice(2)
          : "")
      );
    },
    number: function(a) {
      let b = a * 1;
      return b === b && typeof b == "number" ? b : 0;
    },
    dateFormat: function(date) {
      return date
        ? date
            .split("-")
            .reverse()
            .join("/")
        : "";
    },
    imageDir: function(img_path) {
      return "/" + img_path;
    },
    membershipOwnerLogo: function(name) {
      switch (name) {
        case 'Basic':{
            return '/explore-details/basic.png';}
        case 'Select':{
            return '/explore-details/select.png';}
        case 'Premium':{
            return '/explore-details/premium.png';}
        default:{
            return '/explore-details/basic.png';}
      }
    },
    membershipOwnerButtom: function(name) {
      switch (name) {
        case 'Basic':{
            return 'is-basic';}
        case 'Select':{
            return 'is-select';}
        case 'Premium':{
            return 'is-premium';}
      
        default:{
            return 'is-basic';}
      }
    },
    getExecutive: function(){
      var vm = this;
      Axios.get("/explorar/get-executive/" + vm.property.id())
        .then(function(response){
          console.log(response)
          vm.executive = response.data
        })
        .catch(function(){

        })
    }
  }
});

/** Fixed Footer */
const $content_footer = $(".bottom-info");

const fixedFooter = function() {
  let _window = {
    init: $(window).scrollTop(),
    end: $(window).scrollTop() + $(window).innerHeight()
  };

  let content_footer_position =
    $content_footer.parent().offset().top +
    $content_footer.parent().outerHeight();

  _window.end >= content_footer_position
    ? $content_footer.removeClass("fixed")
    : $content_footer.addClass("fixed");
};

const initFooter = function() {
  let footer_height = $content_footer.outerHeight();

  $content_footer
    .addClass("in-place")
    .parent()
    .css("padding-bottom", footer_height + "px");

  fixedFooter();
};
initFooter();
$(window).on("resize", () => initFooter());

$(window).on("scroll", e => fixedFooter(e));

/** End Fixed Footer */

var carousels = bulmaCarousel.attach();
var accordions = bulmaAccordion.attach();

