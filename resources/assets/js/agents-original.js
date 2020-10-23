import Select2 from 'v-select2-component';
import vueSlider from 'vue-slider-component';
import VueNumericInput from 'vue-numeric-input';
import Property from './components/Property.vue';
import Axios from "axios";
import StarRating from 'vue-star-rating';

import getdata from '../js/profiles/getdata';
import Menus from './components/Menus.vue';

// *************************************** REVISANDO ******************************

import AgentProfile from './components/components-profile/agent/AgentProfile.vue';   // REVISAR - Confilctos con página principal

// *************************************** REVISANDO ******************************
/*----------  Begin Chat  ----------*/
import ChatMessages from './components/components-profile/chat/ChatMessages';
/*----------   End Chat   ----------*/


import AgentHoldings from './components/components-profile/agent/AgentHoldings.vue';
import AgentMyForm from './components/components-profile/agent/AgentMyForm.vue';     // REVISAR - Confilctos con página principal
import AgentMembership from './components/components-profile/agent/AgentMembership';
import AgentBuyers from './components/components-profile/agent/AgentBuyers.vue';
import AgentSuggested from './components/components-profile/agent/AgentSuggested';
import AgentConfig from './components/components-profile/agent/AgentConfig';            // REVISAR - Confilctos con página principal

import AgentProject from './components/components-profile/agent/AgentProject';

import VueProgressBar from 'vue-progressbar'; // Permite ver el menú principal del perfil

Vue.component('select2', Select2);

//
const imagesDir = document.getElementById('images-dir').value;
const basicDataUrl = '/explorar/basic-filters';
const propertiesUrl = '/explorar/get-properties';       // Propiedades
// const projectsUrl = '/users/profile/agent/get-project';         // Proyectos
const agentsUrl = '/users/profile/agent/get-info';
const villagesUrl = '/get-villages';

Vue.use(VueProgressBar, {

    thickness: '5px',
    transition: {
        speed: '0.2s',
        opacity: '0.6s',
        termination: 300
    },
    autoRevert: true,
    inverse: false,
    height: '3px',

})

const router = new VueRouter({
    linkActiveClass: 'selected',
    routes: [
        {path: '/', component: AgentProfile },           // Da fallas en pág principal --- Revisar
        {path: '/holdings', component: AgentHoldings },
        {path: '/holdings/edit/:idProject', component: AgentProject, props: true },
        {path: '/forms', component: AgentMyForm },       // Da fallas en pág principal
        {path: '/membership', component: AgentMembership },
        {path: '/messages', component: ChatMessages },
        {path: '/buyers', component: AgentBuyers },
        {path: '/services', component: AgentSuggested },
        {path: '/configs', component: AgentConfig },     // Da fallas en pág principal
        // {path: '/project', component: AgentProject },

    ]

});

const profiles = new Vue({
    router,
    extends : getdata,
    el: '#profiles',
    components: {
        Select2,
        StarRating,
        vueSlider,
        VueNumericInput,
        Property,
        Menus,
        AgentProfile,    // REVISAR - Confilctos con página principal
        AgentHoldings,
        AgentMyForm,     // REVISAR - Confilctos con página principal
        AgentMembership,
        ChatMessages,
        AgentBuyers,
        AgentSuggested,
        AgentConfig,        // REVISAR - Confilctos con página principal
        AgentProject,
    },

    data() {

        return {

            filtersUrl: `agent/get-filters`,
            dataUrl: `agent/get-info`,

            saveUrl : 'agent/save-data',
            loading: true,
            filters:
            {
                countries: {
                    value: 0,
                    options: []
                },
                rut_type: {
                    value: 0,
                    options: []
                },

                job_type: {
                    value: 0,
                    options: []
                },

                civilstatus: {
                    value: 0,
                    options: []
                },
                cities: {
                    value: 0,
                    options: []
                },
                others_income: {
                    value: 0,
                    options: []
                },
                months: {
                    value: 0,
                    options: []
                },
                property_type: {
                    value: 0,
                    options: []
                },

                property_for: {
                    value: 0,
                    options: []
                },

                property_condition: {
                    value: 0,
                    options: []
                },
                employment: {
                    value: 0,
                    options: []
                },

            },

            // properties: [],
            // agents: [],
            // projects: [],

            offset: 0,  // ???
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
                    value: 0,
                    options: [{id: 0, text: 'Todas'}]
                }
            },

            // Objeto Slider - Precio
            priceSlider: {
                value: [100000, 10000000],
                min: 100000,
                max: 10000000,
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

            info: {
                firstname: '',
                documents: {},
                job_type: '',
                country_id: '',
                document_type: '',
                civil_status_id:'',
                properties:{},
            },

            agentprofile: {

            },

            currProp : {
                id:0,
                rent:0,
            },

            menuIndex: 0,

            menus:
            {
                menu_title: 'Tu perfil',
                items: [
                    {
                        ico: imagesDir + '/menu01.png',
                        route: '/',
                        title: 'Mi perfil',
                    },
                    {
                        ico: imagesDir + '/menu02.png',
                        route: '/holdings',
                        title: 'Mis Proyectos',
                    },
                    {
                        ico: imagesDir + '/menu04.png',
                        route: '/forms',
                        title: 'Mis Formularios de Proyectos',
                    },
                    {
                        ico: imagesDir + '/menu05.png',
                        route: '/membership',
                        title: 'Mi membresía y Medios de Pago',
                    },
                    {
                        ico: imagesDir + '/menu08.png',
                        route: '/messages',
                        title: 'Mi Mensajería',
                    },
                    {
                        ico: imagesDir + '/menu03.png',
                        route: '/buyers',
                        title: 'Potenciales Compradores',
                    },
                    {
                        ico: imagesDir + '/menu10.png',
                        route: '/services',
                        title: 'Servicios Sugeridos uHomie',
                    },
                    {
                        ico: imagesDir + '/menu11.png',
                        route: '/configs',
                        title: 'Configuración de Mi Cuenta',
                    }

                ],
            },


        };
    },

    created: function () {
        this.fetchInitialServices();
        this.fetchFilteredProperties();
        this.fetchAgents();
        this.loadMoreAgents();
        this.loadMoreProperties();
        // this.fetchProjects();

    },

    mounted: function() {
        this.fetchFiltersData();
        var commune = this.getParameterByName('commune');
        if ( commune ) {
        }else{
            this.fetchInitialProperties();
        };
        this.menuIndex = 0;
    },

    methods:
    {
        edit_holding: function(w){
            this.currProp = this.getProp(w)
            this.menuIndex = 10;
        },

        getProp: function(w){
            var nfo=this.info.properties;
            for(var t in nfo){
                if (nfo[t].id==w) {
                    return nfo[t];
                }
            }
            return {};
        },

        setmenuindex: function (w) {
            this.menuIndex = w;
        },

        showTenantDetails: function (id) { // id del tenant
            this.menuIndex = 0;
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
            Axios.get(agentsUrl).then(response => {
                this.agents = response.data.agents;
            });
        },

        // fetchProjects: function(){
        //     Axios.get(projectsUrl).then(response => {
        //         this.projects = response.data.projects;
        //     });
        // },

        fetchInitialProperties: function() {
            Axios.get(propertiesUrl, {
                 params : {
                     limit: 4
                 }
             })
             .then((response) => {
                 this.properties = response.data.properties;
             });
        },

        // Propiedad cambia con el precio de arriendo - Con el slider - Y según params
        fetchFilteredProperties: function(el = null, offset = 0, onSuccess = null) {
            Axios.get(propertiesUrl, {
               params : {
                    limit: 4,
                    offset: 0,
                    // city: this.basic.city.value,
                    // village: this.basic.village.value,
                    priceRange: this.priceSlider.value, // Error
                    // membership: this.basic.membership.value,
                    propertyType: this.basic.propertyType.value,
               }
            })
            .then((response) => {
                if (onSuccess !== null) {
                    onSuccess(response);
                } else {
                    this.properties = response.data.properties;
                }
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
           this.fetchFilteredProperties();
        },

        // Muestra las propiedades
        loadMoreProperties: function() {
           this.offset += 4;
           this.fetchFilteredProperties(null, this.offset, function(response) {
               this.properties.push(response.data.properties);
           });
        },

        loadMoreAgents: function() {
            this.offset += 4;
            this.fetchAgents(null, this.offset, function(response) {
                this.agents.push(response.data.agents);
            });
        },

        getParameterByName: function(name) {
           name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
           var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
           results = regex.exec(location.search);
           return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
       },

    },


})


/*
RUTAS A TOMAR EN CUENTA:

Información General del Agent:  http://127.0.0.1:8000/users/profile/agent/get-info

Perfil del Agente:              http://127.0.0.1:8000/users/profile/agent

Propiedades:                    http://127.0.0.1:8000/explorar/get-properties

Proyectos:                      http://127.0.0.1:8000/agentes/get-projects

???????                         http://127.0.0.1:8000/users/agent/get-project-photos

??? Información General         http://127.0.0.1:8000/users/profile/agent/get-filters




*/
