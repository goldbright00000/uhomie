import bulmaCarousel from 'bulma-carousel/dist/js/bulma-carousel.min';
import bulmaAccordion from 'bulma-accordion/dist/js/bulma-accordion.min';
import vueSlider from 'vue-slider-component';
import VueNumericInput from 'vue-numeric-input';
import Select2 from 'v-select2-component';
import Property from './components/Property.vue';
import Axios from 'axios';
import ApplyButton from "./components/pages/ApplyPropertyButton.vue";
import bulmaCalendar from 'bulma-calendar/dist/js/bulma-calendar.min.js';

import { mapState } from 'vuex';

import store from './store/';

import '../sass/animate.scss';

import * as VueGoogleMaps from 'vue2-google-maps'

import VCalendar from 'v-calendar/lib/v-calendar.umd.min.js';

Vue.use(VueGoogleMaps, {
  load: {
  key: 'AIzaSyDTKRiKb5oaS7Z13QezK4K0V9XQI99UHiI',
  libraries: 'places', // This is required if you use the Autocomplete plugin
  installComponents: true,
  // OR: libraries: 'places,drawing'
  // OR: libraries: 'places,drawing,visualization'
  // (as you require)
 
  //// If you want to set the version, you can do so:
  // v: '3.26',
  },
 
  //// If you intend to programmatically custom event listener code
  //// (e.g. `this.$refs.gmap.$on('zoom_changed', someFunc)`)
  //// instead of going through Vue templates (e.g. `<GmapMap @zoom_changed="someFunc">`)
  //// you might need to turn this on.
  // autobindAllEvents: false,
})


Vue.component('select2', Select2);

Vue.component('gmap', VueGoogleMaps.Map)
Vue.component('gmap-marker', VueGoogleMaps.Marker)
Vue.component('gmap-infowindow', VueGoogleMaps.InfoWindow)
//Vue.component('gmap-autocomplete', VueGoogleMaps.Autocomplete)


const imagesDir = document.getElementById('images-dir').value;
const propertiesUrl = '/explorar/get-properties';//route('fetch-properties').url(); // Put actual route name in here

const explore = new Vue({
  el: '#explore',
  components: {
    vueSlider,
    VueNumericInput,
    Property,
    ApplyButton
  },
  data() {
    return {
      property_id: Number,
      schedule_dates: [],
      dias_seleccionados: [],
      fechas_ocupadas: null,
      attributesC: [],
      now: Date(),
      fechas: [],
      modal: false,
      mode: 'multiple',
      markers: [],
      filtersOn: false,
      activeMap: false, 
      centerMap: { lat: -33.4724728, lng: -70.6692655 },
      selectedMarker: undefined,
      filtrosShow: true,
      loadMoreLoading: false,
      loadMore: true,
      loading: true,
      explore: false,
      properties: [
        
      ],
      offset: 0,
      limit: 8,
      date: '',
      address: undefined,
      fromOut: false,      
      priceSlider: {
        value: [0, 40000000],
        min: 0,
        max: 40000000,
        height: 2,
        tooltip: false,
        clickable: false,
        bgStyle: {
          // 'backgroundColor': 'hsl(0, 0%, 86%)',
        },
        processStyle: {
          // 'backgroundColor': 'hsl(51, 100%, 50%)'
        },
        sliderStyle: [{
            'backgroundImage': 'url("' + imagesDir + '/explore/ico_flecha_amarilla_1.png")',
            'backgroundRepeat': 'no-repeat',
            'backgroundSize': 'cover',
            'backgroundColor': 'none',
            'boxShadow': 'none'
          },
          {
            'backgroundImage': 'url("' + imagesDir + '/explore/ico_flecha_amarilla_2.png")',
            'backgroundRepeat': 'no-repeat',
            'backgroundSize': 'cover',
            'backgroundColor': 'none',
            'boxShadow': 'none'
          }
        ]
      },
      scoringSlider: {
        value: [0, 1400],
        min: 0,
        max: 1400,
        height: 2,
        tooltip: false,
        clickable: false,
        bgStyle: {
          'backgroundColor': 'hsl(0, 0%, 86%)',
        },
        processStyle: {
          'backgroundColor': 'hsl(0, 0%, 48%)'
        },
        sliderStyle: [{
            'backgroundImage': 'url("' + imagesDir + '/explore/ico_flecha_rojo_1.png")',
            'backgroundRepeat': 'no-repeat',
            'backgroundSize': 'cover',
            'backgroundColor': 'none',
            'boxShadow': 'none'
          },
          {
            'backgroundImage': 'url("' + imagesDir + '/explore/ico_flecha_verde_2.png")',
            'backgroundRepeat': 'no-repeat',
            'backgroundSize': 'cover',
            'backgroundColor': 'none',
            'boxShadow': 'none'
          }
        ]
      },
      autocompleteOptions: {
        componentRestrictions: {
          country: [
            'cl',
          ],
        },
      },
    }
  },
  watch: {
    date: function() {
      this.fetchFilteredProperties()
    },
    properties: function() {
    //Add markers
    this.markers = []
    for (var i = this.properties.length - 1; i >= 0; i--) {
      if(this.properties[i].latitude && this.properties[i].longitude ) {
      var position = {
        lat: Number(this.properties[i].latitude),
        lng: Number(this.properties[i].longitude)
      }

      var icono = '/images/pin-'+this.properties[i].membership+'.png'

      this.markers.push({
        propertyIndex: i, 
        position: position,
        icon: icono
      })                
      }                
    }
    this.centerMap = this.markers.length > 0 ? this.markers[0].position : { lat: -33.4488897, lng: -70.6692655}
    }
  },
  created: function() {

    this.fetchFiltersData();

  },
  mounted: function() {
    window.onscroll = () => {
      var bottomOfWindow = document.documentElement.scrollTop + window.innerHeight + 10 >= document.documentElement.offsetHeight;
      if (bottomOfWindow && this.loadMore) {
        this.loadMoreProperties()
      }
    };

    const calendar = bulmaCalendar.attach(this.$refs.dateFilter, {
      dateFormat: 'DD-MM-YYYY',
      lang: 'es',
      overlay: true,
      closeOnOverlayClick: true,
      closeOnSelect: true,
      displayMode:'default',
      changeYear:false,
      showFooter:false,
      showHeader:false
    })    
    const vm = this
    calendar.on('select', e => {
      vm.date = calendar.value()
    })    
    //this.geolocate();       

    var perfil = this.getParameterByName('perfil')
    var perfilId = undefined

    switch(perfil) {
      case 'soltero': {
        perfilId = 1;
        break;
      }
      case 'estudiante': {
        perfilId = 2;
        break;
      }
      case 'sinhijos': {
        perfilId = 3;
        break;
      }
      case 'hijos': {
        perfilId = 4;
        break;
      }
      case 'mascota': {
        perfilId = 5;
        break;
      }
      case 'ejecutivos': {
        perfilId = 6;
        break;
      }
      case 'grupo': {
        perfilId = 7;
        break;
      }
      default: {
        perfilId = 0;
        break;
      }
    }

    //Vengo del buscar:

    //Recupero el id de la city
    var city = this.getParameterByName('city')    
    if(city && typeof(city) == 'string') {
      var cityOption = this.basic.city.options.find(function(element) {
        if(element) {
          return element.text.toLowerCase() == city.toLowerCase()
        }        
      })
      city = cityOption ? cityOption.id : 0
    }
    //Recupero el id de la comuna
    var commune = this.getParameterByName('commune')
    if(commune && typeof(commune) == 'string') {
      var communeOption = this.basic.village.options.find(function(element) {
        if (element) {
          return element.text.toLowerCase() == commune.toLowerCase()
        }        
      })
      commune = communeOption ? communeOption.id : 0
    }
    var raw =  this.getParameterByName('raw')

    //Vengo del buscar con raw text, window.commune variable de blade
    if(raw || commune || city) {
      this.cleanFilters()
    }

    if(window.commune) {
      this.setBasicCityValue(window.city)
      this.fetchVillages()
      this.setBasicVillageValue(window.commune)
      this.fromOut = true
      this.fetchFilteredProperties();
    } else {
      if(commune) {
        this.setBasicCityValue(city ? city : 0)
        this.fetchVillages()
        this.setBasicVillageValue(commune)
        this.fromOut = true
        this.fetchFilteredProperties();
      } else {
        if(city) {
          this.setBasicCityValue(city)
          this.fetchVillages()
          this.setBasicVillageValue(commune ? commune : 0)    
          this.fetchFilteredProperties();
        } else {
          if(perfilId) {
            //Filtro por perfil, viene del home
            this.filtrarPerfil(perfilId)
          } else {
            if(raw) {
              //Si no es una comuna intento buscar por la direccion
              this.address = raw              
              this.fetchFilteredProperties();
            } else {
              this.fetchInitialProperties();
            }
          }
        }
      }
    }    

    this.loading = false

  },
  methods: {
    //Filters
    setMembershipValue(value) {
      this.fetchFilteredProperties()
      store.commit('explore/setMembershipValue', value)
    },
    setPropertyTypeValue(value) {
      this.fetchFilteredProperties()
      store.commit('explore/setPropertyTypeValue', value)
    },
    setProfileValue(value) {
      this.fetchFilteredProperties()
      store.commit('explore/setProfileValue', value)
    },
    setTypeUserValue(value) {
      this.fetchFilteredProperties()
      store.commit('explore/setTypeUserValue', value)
    },
    setTypeStayValue(value) {
      this.fetchFilteredProperties()
      store.commit('explore/setTypeStayValue', value)
    },
    setFeaturesValue(type, value) {
      this.fetchFilteredProperties()
      store.commit('explore/setFeaturesValue', {
        type: type,
        value: value
      })
    },
    cleanFilters(reset = false) {
      console.log("clean filters")
      store.commit('explore/cleanFilters')
      if(reset) {
        window.location.reload()
      }
    },
    setBasicCityValue(city = 0) {
      this.setBasicCityKey++
      store.commit('explore/setBasicCityValue', city)
    },
    setBasicVillageValue(village = 0) {
      this.fetchFilteredProperties()
      store.commit('explore/setBasicVillageValue', village)      
    },
    alterScheduleDate(){
      var morningFormat = [];
      this.fechas.forEach(element => {
          morningFormat.push(moment(element).format("YYYY-MM-DD"));
      });

      this.schedule_dates = JSON.stringify(
          morningFormat);

      this.attributesM = [
          {
              highlight: true
          }
      ];
    },
    cerrarModalSelectDays: function(){
      $('#modalSelectDays').removeClass('is-active');
    },
    abrirModalio: function( valor ){
      console.log('evento escuchado el property_id es: '+valor);
      this.property_id = valor;
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
    postularArriendoCorto: function(){
      console.log('enviar postulacion');
      var vm = this;
      $('#botonPostularJQ_'+vm.property_id).addClass('is-loading');
      vm.$refs['btn_enviar_postulacion'].classList.add("is-loading");
      Axios.post("/properties/" + vm.property_id + "/apply", {days: vm.schedule_dates})
        .then(response => {
          $('#botonPostularJQ_'+vm.property_id).removeClass('is-loading');
          vm.$refs['btn_enviar_postulacion'].classList.remove("is-loading");
          if(response.data.espera == 1){
            toastr['info']("Tu postulación ha sido recibida, pero mientras no verifiques tu identidad, la postulación estará en modo espera. Cuando tu identidad sea verificada tu postulación será enviada y te avisaremos via e-mail.");
            vm.cerrarModalSelectDays();
          } else {
            $('#botonPostularJQ_'+vm.property_id).html('Postulado');
            vm.cerrarModalSelectDays();
          }
          
        })
        .catch(e => {
          $('#botonPostularJQ_'+vm.property_id).removeClass('is-loading');
          vm.$refs['btn_enviar_postulacion'].classList.remove("is-loading");
          vm.cerrarModalSelectDays();
          switch (e.response.status) {
            case 302: {
              console.log('caso 302');
            }
            case 401: {
              //Toast.danger('HOLa');
              toastr["warning"]("Para postular, debes iniciar sesion.");
              break;
            }
            case 403: {
              toastr["warning"](
                "Debes ser arrendatario para postularte en una propiedad."
              );
              break;
            }
            case 422: {
              if(e.response.data.errors['user_id']){
                toastr["error"]( e.response.data.errors['user_id'][0]);
              }
              if(e.response.data.errors['verification_link']){
                let link = '<a target="_blank" href="'+e.response.data.errors['verification_link'][0]+'">Haz click aqui para iniciar la verificación de identidad</a>';
                toastr['info'](link);
              }
              //toastr["info"](e.response.data.errors['upgrade_link'][0]? '<a target="_blank" href="'+e.response.data.errors['upgrade_link'][0]+'">Haz click aqui para mejorar tu membresia</a>': '');
              if(e.response.data.errors['upgrade_link']){
                let link = '<a target="_blank" href="'+e.response.data.errors['upgrade_link'][0]+'">Haz click aqui para mejorar tu membresia</a>';
                toastr['info'](link);
              }
              if(e.response.data.errors['verification']){
                console.log('deberia imprimir el toastr de verificacion');
                toastr["info"](e.response.data.errors['verification'][0]);
              }
              /*
              for (let field in e.response.data.errors) {
                toastr["error"](e.response.data.errors[field][0]? e.response.data.errors[field][0]: '');
              }
              */
              /*toastr.options.timeOut = 0;
              toastr.options.extendedTimeOut = 0;*/
              //toastr.options.closeButton = true;
              //toastr.info("<a target='_blank' href='/users/"+this.$attrs.upgrademembershipurl+"/memberships-upgrade'>Haz click aquí para mejorar tu membresía</a>", "uHomie", {timeOut: 15000, extendedTimeout: 10000, closeButton: true, onClick: function() { console.log('clicked'); } });
              break;
            }
            case 302: {
              console.log('error 302');
              break;
            }
            default: {
              toastr["error"]("Ha ocurrido un error inesperado.");
            }
          }

          
        })
    },
    filtrarPerfil: function(perfilId) {
      this.basic.profile.value = perfilId

      this.fetchFilteredProperties()
    },
    geolocate: function() {
      navigator.geolocation.getCurrentPosition(position => {
      this.center = {
        lat: position.coords.latitude,
        lng: position.coords.longitude
      };
      });
    },
    selectMarker(m){
      this.centerMap = m.position; 
      this.selectedMarker = m;
    },
    fetchFiltersData: function() {
      //Moved to store
      store.dispatch('explore/fetchBasicData')
      .then(() => {
        //termino
        window.location.reload()
      })
    },
    fetchInitialProperties: function() {

       axios.get(propertiesUrl, {
         params : {
           limit: this.limit
         }
       })
       .then((response) => {
         this.properties = response.data.properties;
       });
    },
    // Propiedad cambia con el precio de arriendo
    fetchFilteredProperties: function(el = null, offset = 0, onSuccess = null) {

      this.loading = true
      this.explore = true

      console.log(this.basic.type_user)

      axios.get(propertiesUrl, {
        params : {
           limit: this.limit,
           offset: offset,
           city: this.basic.city.value,
           village: this.basic.village.value,
           priceRange: this.priceSlider.value,
           scoring: this.scoringSlider.value,
           date: this.date,
           membership: this.basic.membership.value,
           propertyType: this.basic.propertyType.value,
           profile: this.basic.profile.value,
           type_user: this.basic.type_user.value,
           type_stay: this.basic.type_stay.value,
           features: {
             rooms: this.features.rooms,
             bathrooms: this.features.bath,
             meters: this.features.meters,
             propertyVerified: this.features.verifiedProperty,
             pets: this.features.pets,
             parking: this.features.parking,
             furnished: this.features.furnished,
             cellar: this.features.cellar,
             insurance: this.features.insurance
           },
           unitAmenities: this.unitServices.selected,
           condoAmenity: this.condoServices.selected, 
           address: this.address
        }
      })
       .then((response) => {        
        this.address = undefined

        console.log(response)

        if (onSuccess !== null) {
           onSuccess(response);
        } else {
          if(this.basic.city.value > 0 && this.basic.village.value > 0 && this.fromOut) {
            //this.basic.village.value = 0
            this.fromOut = false
            this.fetchFilteredProperties()
          } else {
            if(response.data.properties && response.data.properties.length == 0){
              toastr['info']('No hemos encontrado propiedades !');
            }
            this.properties = response.data.properties;
            this.loadMore = true
            this.offset = 0
          }          
         }
         this.loading = false
       });
    },
    fetchVillages: function(filterTo = false) {

      store.dispatch('explore/fetchVillage', this.basic.city.value)
      
      if(filterTo) {
        this.fetchFilteredProperties();
      }
    },
    // Muestra las propiedades
    loadMoreProperties: function() {
      this.loadMoreLoading = true;
      this.offset += this.limit;

      const vm = this
      this.fetchFilteredProperties(null, this.offset, function(response) {
        
        if(response.data.properties.length > 0) {
          vm.properties = vm.properties.concat(response.data.properties)
        } else {
          vm.loadMore = false
        }
        vm.loadMoreLoading = false;
      });
    },
    getParameterByName: function(name) {
      name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
      var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
      results = regex.exec(location.search);
      return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
    },
    filterRoom() {
      this.features.rooms = !this.features.rooms ? 1 : 0
      this.setFeaturesValue('rooms', this.features.rooms)
    },
    filterOficinas() {
      var oficinaId = this.basic.propertyType.value == 5 ? 0 : 5
      this.basic.propertyType.value = oficinaId
      this.fetchFilteredProperties()
      store.commit('explore/setPropertyTypeValue', oficinaId)
    },
    filterCellar() {
      this.features.cellar = this.features.cellar ? false : true
      this.setFeaturesValue('cellar', this.features.cellar)
    },
    filterParking() {
      this.features.parking = this.features.parking ? false : true
      this.setFeaturesValue('parking', this.features.parking)
    },
  },
  computed: {
    filtersActive() {
      return store.getters['explore/active']
    },
    basic() {
      return store.getters['explore/basic']
    },
    features() {
      return store.getters['explore/features']
    },
    unitServices() {
      return store.getters['explore/unitServices']
    },
    condoServices() {
      return store.getters['explore/condoServices']
    },
    fechas_ocupadas_computado: function(){
      if( !this.fechas_ocupadas ) return null;
      else {
        let arreglo = [];
        this.fechas_ocupadas.forEach(function(elem){
          arreglo.push({start: elem, end: elem});
        });
        return arreglo;
      }
    },    
  }
});

var carousels = bulmaCarousel.attach();
var accordions = bulmaAccordion.attach();