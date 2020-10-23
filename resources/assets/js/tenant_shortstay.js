import getdata from '../js/profiles/getdata';
import Menus from './components/Menus.vue';


import TenantProfile from './components/components-profile/tenant/TenantProfile.vue';
import TenantPostulations from './components/components-profile/tenant/TenantPostulations.vue';
import TenantHoldings from './components/components-profile/tenant/TenantHoldings.vue';
import TenantMyForm from './components/components-profile/tenant/TenantMyForm.vue';
import TenantMembership from './components/components-profile/tenant/TenantMembership';
import TenantContracts from './components/components-profile/tenant/TenantContracts';
import TenantInteresting from './components/components-profile/tenant/TenantInteresting';
import TenantSuggested from './components/components-profile/tenant/TenantSuggested';
import TenantSchedules from './components/components-profile/tenant/TenantSchedules';
import TenantConfig from './components/components-profile/tenant/TenantConfig';
import OrderPayment from './components/components-profile/common/OrderPayment';
import Payments from './components/components-profile/common/Payments';


import Tooltip from 'vue-bulma-tooltip';

/*----------  Begin Chat  ----------*/
import ChatMessages from './components/components-profile/chat/ChatMessages';
/*----------   End Chat   ----------*/

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
        {path: '/', component: TenantProfile },
        {path: '/postulations', component: TenantPostulations },
        {path: '/properties', component: TenantHoldings },
        {path: '/forms', component: TenantMyForm },
        {path: '/membership', component: TenantMembership },
        {path: '/order/:idOrder', component: OrderPayment,  props: true},
        {path: '/payments', component: Payments },
        {path: '/schedules', component: TenantSchedules },
        {path: '/contracts', component: TenantContracts },
        {path: '/messages', component: ChatMessages },
        {path: '/projects', component: TenantInteresting },
        {path: '/services', component: TenantSuggested },
        {path: '/configs', component: TenantConfig },
        
    ]
});

const tenant = new Vue({
    router,
    extends: getdata,
    el: '#profiles',

    props: {},
    components: {

        Menus,

        TenantProfile,
        TenantPostulations,
        TenantHoldings,
        TenantMyForm,
        TenantMembership,
        TenantContracts,
        ChatMessages,
        TenantInteresting,
        TenantSchedules,
        TenantSuggested,
        TenantConfig,
        Tooltip,
        Payments,
    },

    methods: { //
        setmenuindex: function (w) {
            this.menuIndex = w;
        }
    },
    data() {

        return {

            filtersUrl: `tenant/get-filters`,
            dataUrl: `tenant/get-info`,

                saveUrl : 'tenant/save-data',
                saveEmploymentUrl: 'tenant/save-employment',
                
                loading: true,
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

                employment: {
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

                },


                /* DS MENUS */

                menuIndex: -1, // Menu inicial por defecto -1 (luego se actualiza en mounted)

                menus: {
                    menu_title: 'Tu perfil',
                    items:
                        [
                            {
                                ico: imagesDir + '/menu01.png',
                                route: '/',
                                title: 'Mi perfil',
                            },
                            {
                                ico: imagesDir + '/menu02.png',
                                route: '/postulations',
                                title: 'Mis postulaciones',
                            },
                            {
                                ico: imagesDir + '/menu03.png',
                                route: '/properties',
                                title: 'Mis Propiedades Favoritas',
                            },
                            {
                                ico: imagesDir + '/menu04.png',
                                route: '/forms',
                                title: 'Mi formulario',
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
                                ico: imagesDir + '/menu06.png',
                                route: '/contracts',
                                title: 'Mis Contratos',
                            },
                            {
                                ico: imagesDir + '/menu08.png',
                                route: '/messages',
                                title: 'Mi Mensajería',
                            },
                            {
                                ico: imagesDir + '/menu09.png',
                                route: '/projects',
                                title: 'Proyectos que podrían interesarte',
                            },
                            {
                                ico: imagesDir + '/menu10.png',
                                route: '/services',
                                title: 'Servicios Sugeridos uHomie',
                            },
                            {
                                ico: imagesDir + '/menu11.png',
                                route: '/configs',
                                title: 'Configuración Cuenta & Verificación Identidad',
                            }/*,
                    {
                        ico : 'clock',
                        title : 'Mis postulaciones',
                    },*/


                        ]
                },

                }
    },

    created: function () {
        this.fetchFiltersData();
        this.fetchInitialServices();
    },

    mounted() {

        /*this.fetchFiltersData();
        this.fetchInitialServices();*/

        this.menuIndex = 0; // se llama el watch en el component para cargar datos
    }
});

