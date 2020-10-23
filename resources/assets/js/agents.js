
import Menus from './components/Menus.vue';


import AgentProfile from './components/components-profile/agent/AgentProfile.vue';
//import AgentPostulations from './components/components-profile/agent/AgentPostulations.vue';
import AgentHoldings from './components/components-profile/agent/AgentHoldings.vue';
import AgentMyForm from './components/components-profile/agent/AgentMyForm.vue';
import AgentMembership from './components/components-profile/agent/AgentMembership';
//import AgentContracts from './components/components-profile/agent/AgentContracts';
//import AgentInteresting from './components/components-profile/agent/AgentInteresting';
import AgentSuggested from './components/components-profile/agent/AgentSuggested';
import AgentConfig from './components/components-profile/agent/AgentConfig';
//import AgentTenantProfile from './components/components-profile/agent/AgentTenantProfile';
import AgentHolding from './components/components-profile/agent/AgentHolding';
import AgentHoldingSchedule from './components/components-profile/agent/AgentHoldingSchedule';
import AgentHoldingPostulate from './components/components-profile/agent/AgentHoldingPostulate';
import AgentTenantProfile from './components/components-profile/agent/AgentTenantProfile';
import AgentSchedules from './components/components-profile/agent/AgentSchedules';
import OrderPayment from './components/components-profile/common/OrderPayment';
import Payments from './components/components-profile/common/Payments';

/*----------  Begin Chat  ----------*/
import ChatMessages from './components/components-profile/chat/ChatMessages';
/*----------   End Chat   ----------*/


import getdata from '../js/profiles/getdata';

import VueProgressBar from 'vue-progressbar'

const options = {
    //color: '#bffaf3',
    color: 'rgb(143, 255, 199)',
    //failedColor: '#874b4b',
    failedColor: 'red',
    thickness: '5px',
    transition: {
        speed: '0.2s',
        opacity: '0.6s',
        termination: 300
    },
    autoRevert: true,
    location: 'left',
    inverse: false,
    height: '3px',
}

Vue.use(VueProgressBar, {

    //color: '#bffaf3',
    //color: 'rgb(143, 255, 199)',
    //failedColor: '#874b4b',
    //failedColor: 'red',
    thickness: '5px',
    transition: {
        speed: '0.2s',
        opacity: '0.6s',
        termination: 300
    },
    autoRevert: true,
    //location: 'left',
    inverse: false,
    height: '3px',

})

import moment from 'moment';
Vue.prototype.moment = moment;

import VCalendar from 'v-calendar/lib/v-calendar.umd.min.js';;

// Use v-calendar & v-date-picker components
Vue.use(VCalendar, {
  componentPrefix: 'vc',  // Use <vc-calendar /> instead of <v-calendar />
  //...,// ...other defaults
});

const imagesDir = document.getElementById('images-dir').value;

const router = new VueRouter({
    linkActiveClass: 'selected',
    routes: [
        {path: '/', component: AgentProfile },
        {path: '/holdings/edit/:idProperty', component: AgentHolding, props: true },
        {path: '/holdings/schedules/:idProperty', component: AgentHoldingSchedule, props: true },
        {path: '/holdings/postulates/:idProperty', component: AgentHoldingPostulate, props: true },
        {path: '/holdings/postulates/:idProperty/tenant/:idPostulate', component: AgentTenantProfile, props: true},
        {path: '/holdings', component: AgentHoldings },
        {path: '/forms', component: AgentMyForm },
        {path: '/membership', component: AgentMembership },
        {path: '/payments', component: Payments },
        {path: '/order/:idOrder', component: OrderPayment,  props: true},
        //{path: '/contracts', component: OwnerContracts },
        {path: '/messages', component: ChatMessages },
        //{path: '/projects', component: AgentInteresting },
        {path: '/services', component: AgentSuggested },
        {path: '/configs', component: AgentConfig },
        {path: '/schedules', component: AgentSchedules}
    ]

});
const profiles = new Vue({
    router,
    extends : getdata,
    el: '#profiles',
    components: {
        Menus,
        AgentProfile,
        AgentHoldings,
        AgentMyForm,
        AgentMembership,
        //OwnerContracts,
        ChatMessages,
        //AgentInteresting,
        AgentSuggested,
        AgentConfig,
        AgentHoldingSchedule,
        //OwnerPostulations,
        AgentTenantProfile,
        Payments,
        // OwnerHolding,

    },
    data() {

        return {

            filtersUrl: `agent/get-filters`,
            dataUrl: `agent/get-info`,

            saveUrl: 'agent/save-data',
            loading: true,
            /* PROFILE */

            filters: {
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

            ownerprofile: {
            },

            /* DS MENUS */

            currProp : {
                id:0,
                rent:0,
            },

            menuIndex: 0, // Menu inicial por defecto (seria "Mi perfil")

            menus: {
                menu_title: 'Tu perfil',
                items: [
                    {
                        ico: imagesDir + '/menu01.png',
                        route: '/',
                        title: 'Mi perfil',
                    },
                    {
                        ico: imagesDir + '/menu03.png',
                        route: '/holdings',
                        title: 'Mis Proyectos Registrados',
                    },
                    {
                        ico: imagesDir + '/menu04.png',
                        route: '/forms',
                        title: 'Mi Formulario',
                    },
                    {
                        ico: imagesDir + '/icono-calendario-azul.png',
                        route: '/schedules',
                        title: 'Mi Agenda',
                    },
                    {
                        ico: imagesDir + '/menu05.png',
                        route: '/membership',
                        title: 'Mi membresía y Medios de Pago',
                    },
                    {
                        ico: imagesDir + '/icono-ahorro.png',
                        route: '/payments',
                        title: 'Banco y Pagos',
                    },
                    {
                        ico: imagesDir + '/menu08.png',
                        route: '/messages',
                        title: 'Mi Mensajería',
                    },
                    {
                        ico: imagesDir + '/menu11.png',
                        route: '/configs',
                        title: 'Configuración de mi cuenta',
                    }
                ]
            },

        };
    },

    created: function () {
        this.fetchFiltersData();
        this.fetchInitialServices();
    },

    methods: {

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
        }

    },
    mounted(){
        // urlDominio = `${window.location.protocol}//${window.location.host}`;
        // console.log('urlDominio');
    }
});
