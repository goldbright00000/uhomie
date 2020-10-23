import bulmaCarousel from 'bulma-carousel/dist/js/bulma-carousel.min';
import Axios from 'axios';
import bulmaCalendar from 'bulma-calendar/dist/js/bulma-calendar.min.js';
import Select2 from 'v-select2-component';

import Service from './components/Service.vue';

import '../sass/animate.scss';

Vue.component('select2', Select2);
Vue.component('service', Service);

const imagesDir = document.getElementById('images-dir').value;

const filtersUrl = '/services-explore/get-filters';//route('get-basic-filters').url();
const servicesUrl = '/services-explore/get-services';
const servicesInitialUrl = '/services-explore/get-services-initial';
const villagesUrl = '/get-villages'; //route('fetch-villages').url();

const services = new Vue({
  el: '#services',
  components: {
    
  },
  data() {
    return {
      dataUrl: 'service/get-info',
      saveUrl: 'service/save-data',
      initial: true,
      filtersOn: false,
      filtrosShow: true,
      loadMoreLoading: false,
      loadMore: true,
      loading: true,
      servicesList: [],
      services: [],
      serviceInputValue: '',
      servicesInitial: [],
      offset: 0,
      limit: 8,
      date: '',
      basic: {
        city: {
          value: 0,
          options: [{id: 0, text: 'Todas'}]
        },
        village: {
          value: 0,
          options: [{id: 0, text: 'Todas'}]
        },
        membership: {
          value: 0,
          options: [{id: 0, text: 'Todas'}]
        },
        service: {
          value: null,
          options: [{id: 0, text: 'Todos'}]
        }
      },
    };
  },
  watch: {
    serviceInputValue: function() {
      //header search input:
      const vm = this
      this.basic.service.options.find((val) => {
        if(val.text == vm.serviceInputValue) {
          vm.basic.service.value = val.id
          vm.fetchFilteredServices()
          //Scroll down:
          vm.$refs.arrowDown.click()
        }
      })
    }
  },
  created () {
    //gasista
    //this.fetchServices(21)
    //electricista
    //this.fetchServices(3)
    //jardinero
    //this.fetchServices(6)
  },
  mounted: function() {
    this.fetchFiltersData()
    this.fetchInitialServices()
  },
  methods: {
    more() {
      //see more results
      this.offset = this.offset + this.limit
      this.fetchFilteredServices()
    },
    filterService(serviceListId) {
      this.offset = 0
      this.basic.service.value = serviceListId
      this.fetchFilteredServices()
    },
    filterServiceList: function() {
      console.log("hola")
      console.log(this.serviceInputValue)
    },
    fetchFiltersData: function() {
      // console.log('fetchFiltersData called');
      this.loading = true

      axios.get(filtersUrl)
       .then((response) => {
           this.basic.city.options = this.basic.city.options.concat(response.data.cities);
          // this.basic.city.value = response.data.cities[0].id;

          this.basic.village.options = this.basic.village.options.concat(response.data.villages);

          this.basic.membership.options = this.basic.membership.options.concat(response.data.memberships);
          // this.basic.membership.value = response.data.memberships[0].id;

          //Buscador
          this.servicesList = response.data.services_list
          //Filtros
          this.basic.service.options = this.basic.service.options.concat(response.data.services_list) 

          this.loading = false
        })
       .catch((err) => {
          console.log(err)
       })
    },
    fetchVillages: function() {
      this.fetchFilteredServices()

      axios.get(villagesUrl, {
         params: {
           city: this.basic.city.value
         }
       }).then((response) => {
         this.basic.village.options = [{id: 0, text: 'Todas'}]
         this.basic.village.options = this.basic.village.options.concat(response.data.villages);
         //this.basic.village.value = response.data.villages[0].id;        
       });
    },
    filters: function() {
      //change filters
      this.offset = 0
      this.fetchFilteredServices()
    },
    fetchInitialServices: function() {

      const vm = this
      axios.get(servicesUrl)
      .then((response) => {
        //console.log(response.data.services)
        vm.servicesInitial = response.data.services
      })
    },
    fetchServices: function(serviceListId) {
      const vm = this
      axios.get(servicesInitialUrl, {
        params : {
           limit: 2,
           offset: this.offset,
           serviceListId: serviceListId
        }
      })
      .then((response) => {
        if(response.data.services.lenght == 0) {
          toastr['info']('No hemos encontrado proyectos !');
        } else {
          vm.servicesInitial[serviceListId] = response.data.services
        }        
      })
    },
    fetchFilteredServices: function() {
      if(this.initial) {
        this.initial = false
      }
      axios.get(servicesUrl, {
        params : {
           limit: this.limit,
           offset: this.offset,
           city: this.basic.city.value,
           village: this.basic.village.value,           
           membership: this.basic.membership.value,
           serviceListId: this.basic.service.value
        }
      })
       .then((response) => {
        
        if(response.data.services.length == 0) {
          toastr['info']('No hemos encontrado proyectos !');
          this.services = []
        } else {
          this.services = response.data.services
        }
      })
    }
  }
});

var carousels = bulmaCarousel.attach();