import Select2 from 'v-select2-component';
import vueSlider from 'vue-slider-component';
import VueNumericInput from 'vue-numeric-input';
import Property from './components/Property.vue';
import Agent from './components/Agent.vue';
import Axios from 'axios';
import StarRating from 'vue-star-rating';

//
const imagesDir = document.getElementById('images-dir').value;
const basicDataUrl = '/agentes/get-filters';
const propertiesUrl = '/agentes/get-properties';       // Propiedades
const villagesUrl = '/get-villages';
const agentsUrl = '/agentes/get-agents';
const getProjectsUrl = '/agentes/get-projects';


const agents = new Vue({
  el: '#agents',
  components: {
    Select2,
    StarRating,
    vueSlider,
    VueNumericInput,
    Property,
    Agent
  },
  data() {
    return {
      filtersUrl: `agent/get-filters`,
      dataUrl: `agent/get-info`,
      saveUrl : 'agent/save-data',
      loading: true,
      showAgents: true,
      agents: [],
      projects: [],
      properties: [],
      offset: 0,
      offsetProperties:0,
      offsetAgents: 0,
      limitAgent: 6,
      limit: 6,
      limitProperties: 4,
      loading: true,
      loadMore: true,
      loadMoreLoading: false,
      loadMorePropertiesLoading: false,
      loadMoreAgentLoading: false,
      agent_id: 0,
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
        propertyType: {
          value: 0,
          options: [{id: 0, text: 'Todas'}]
        },
        projectStatus: {
          value: -1,
          options: [
            { id: -1, text: 'Todas'},
            { id: 0, text: 'En obra'},
            { id: 1, text: 'Terminado'},
            { id: 2, text: 'Otro'}
          ]
        }
      },
      priceSlider: {
        value: [0, 1000000000],
        min: 0,
        max: 1000000000,
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
    }
  },
  created: function () {
    //this.fetchFilteredProjects();
    this.fetchAgents();
    //this.loadMoreAgents();
    //this.loadMoreProperties();
    // this.fetchProjects();
    this.fetchFiltersData();
    this.fetchInitialProjects();
    this.fetchInitialProperties();
  },
  mounted: function() {    

  },
  methods:
  {
    filterAgent: function(agent) {
      this.agent_id = agent;
      console.log("filter");
      this.offset = 0;
      this.offsetProperties = 0;
      this.limit = 3;
      this.limitProperties = 2;
      this.loadMore = true;
      this.loadMoreLoading = false;
      this.loadMorePropertiesLoading = false;
      this.fetchFilteredProjects();
      this.fetchFilteredProperties();
    },
    // Método para mostrar información en los select2 de la página principal
    fetchFiltersData: function() {
      Axios.get(basicDataUrl)
       .then((response) => {
        this.basic.city.options = this.basic.city.options.concat(response.data.cities);
        this.basic.village.options = this.basic.village.options.concat(response.data.villages);
        this.basic.membership.options = this.basic.membership.options.concat(response.data.memberships);
        this.basic.propertyType.options = this.basic.propertyType.options.concat(response.data.propertyTypes);
        // this.basic.projectStatus.options = this.basic.projectStatus.options.concat(response.data.projectStatus);
       });
    },
    
    fetchAgents: function() {
      this.loadMoreAgentLoading = true
      Axios.get(agentsUrl, {
        params: {
          offset: this.offsetAgents,
          limit: this.limitAgent
        }
      }).then(response => {
        this.loadMoreAgentLoading = false
        this.agents = response.data.records;
        console.log(this.agents)
      });
    },

    // fetchProjects: function(){
    //     Axios.get(projectsUrl).then(response => {
    //         this.projects = response.data.projects;
    //     });
    // },

    fetchInitialProjects: function() {
      this.loadMoreLoading = true
      Axios.get(getProjectsUrl, {
         params : {
            offset: this.offset,
            limit: this.limit
         }
       })
       .then((response) => {
        console.log(response)
        this.loadMoreLoading = false
        this.projects = response.data.projects;
        this.loading = false
        if(this.projects.length < this.limit) {
          this.loadMore = false
        }
       })
       .catch((err) => {
        this.loading = false
        toastr['red']('Algo sucedió, por favor vuelva a intentarlo.')
       })
    },
    fetchInitialProperties: function() {

      axios.get(propertiesUrl, {
        params : {
          limit: this.limitProperties
        }
      })
      .then((response) => {
        this.properties = response.data.properties;
        if(this.projects.length < this.limit) {
          this.loadMoreProperties2 = true
        }
      });
    },

    fetchFilteredAgents: function(el = null, offsetAgents = 0, onSuccess = null) {
      const vm = this;
      this.loading = true;

      Axios.get(agentsUrl, {
        params: {
          offset: this.offsetAgents,
          limit: this.limitAgent
        }
      }).then((response) => {
        console.log(response.data)

        if (onSuccess !== null) {
          onSuccess(response);
        } else {
          if(response.data.records && response.data.records.length == 0){
            toastr['info']('No hemos encontrado agentes !');
          }
          this.agents = response.data.records;
        }

        if(response.data.records.length < this.limitAgent) {
          vm.loadMoreAgentLoading = true
        }        

        this.loading = false
      })
      .catch((err) => {
        this.loading = false
        console.log(err)
        toastr['warning']('Algo sucedió, por favor vuelva a intentarlo.')
      })
    },

    // Propiedad cambia con el precio de arriendo - Con el slider - Y según params
    fetchFilteredProjects: function(el = null, offset = 0, onSuccess = null) {
      const vm = this

      //vm.showAgents = false
      this.loading = true

      console.log(this.basic.membership.value)

      Axios.get(getProjectsUrl, {
         params : {
          limit: this.limit,
          offset: this.offset,
          city: this.basic.city.value,
          village: this.basic.village.value,
          priceRange: this.priceSlider.value,
          membership: this.basic.membership.value,
          propertyType: this.basic.propertyType.value,
          projectStatus: this.basic.projectStatus.value >= 0 ? this.basic.projectStatus.value : 0,
          agent: this.agent_id
         }
      })
      .then((response) => {
        console.log(response.data)

        if (onSuccess !== null) {
          onSuccess(response);
        } else {
          if(response.data.projects && response.data.projects.length == 0){
            toastr['info']('No hemos encontrado proyectos !');
          }
          this.projects = response.data.projects;
        }

        if(response.data.projects.length < this.limit) {
          vm.loadMore = false
        }        

        this.loading = false
      })
      .catch((err) => {
        this.loading = false
        console.log(err)
        toastr['warning']('Algo sucedió, por favor vuelva a intentarlo.')
      })
    },

    fetchFilteredProperties: function(el = null, offsetProperties = 0, onSuccess = null) {

      this.loading = true
      this.explore = true

      axios.get(propertiesUrl, {
        params : {
          limit: this.limitProperties,
          offset: this.offsetProperties,
          city: this.basic.city.value,
          village: this.basic.village.value,
          priceRange: this.priceSlider.value,
          membership: this.basic.membership.value,
          propertyType: this.basic.propertyType.value,
          projectStatus: this.basic.projectStatus.value >= 0 ? this.basic.projectStatus.value : 0,
          agent: this.agent_id
        }
      })
       .then((response) => {
        
        this.address = undefined

        if (onSuccess !== null) {
           onSuccess(response);
        } else {
            if(response.data.properties && response.data.properties.length == 0){
              toastr['info']('No hemos encontrado propiedades !');
            }
            this.properties = response.data.properties;
        }
        if(response.data.properties.length < this.limitProperties) {
          this.loadMorePropertiesLoading = true
        } 
        this.loading = false
       });
    },

    fetchVillages: function() {
      Axios.get(villagesUrl, {
        params: {
          city: this.basic.city.value
        }
      }).then((response) => {
        this.basic.village.options = [{id: 0, text: 'Todas'}]
        this.basic.village.options = this.basic.village.options.concat(response.data.villages);

      });
       this.fetchFilteredProjects();
    },

    // Muestra las propiedades
    loadMoreProperties: function() {
       this.offset += this.limit;
       const vm = this;
       this.fetchFilteredProjects(null, this.offset, function(response) {
         vm.projects = vm.projects.concat(response.data.projects);
       });
    },

    loadMoreAgents: function() {
      this.loading = true;
      this.offsetAgents += this.limitAgent;
      const vm = this;
      this.fetchFilteredAgents(null, this.offsetAgents, function(response) {
          vm.agents = vm.agents.concat(response.data.records);
      });
      vm.loading = false;
    },

    loadMoreProperties2: function() {
      this.loading = true;
      this.offsetProperties += this.limitProperties;

      const vm = this
      this.fetchFilteredProperties(null, this.offsetProperties, function(response) {
        
        if(response.data.properties.length > 0) {
          vm.properties = vm.properties.concat(response.data.properties)
        } else {
          vm.loadMorePropertiesLoading = true
        }
        vm.loading = false;
      });
    },
    getParameterByName: function(name) {
       name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
       var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
       results = regex.exec(location.search);
       return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
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
  },
})