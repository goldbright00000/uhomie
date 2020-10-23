import Menus from './components/Menus.vue';


import OwnerProfile from './components/components-profile/owner/OwnerProfile.vue';
import OwnerPostulations from './components/components-profile/owner/OwnerPostulations.vue';
import OwnerHoldings from './components/components-profile/owner/OwnerHoldings.vue';
import OwnerMyForm from './components/components-profile/owner/OwnerMyForm.vue';
import OwnerMembership from './components/components-profile/owner/OwnerMembership';
import OwnerContracts from './components/components-profile/owner/OwnerContracts';
import OwnerInteresting from './components/components-profile/owner/OwnerInteresting';
import OwnerSuggested from './components/components-profile/owner/OwnerSuggested';
import OwnerConfig from './components/components-profile/owner/OwnerConfig';
import OwnerTenantProfile from './components/components-profile/owner/OwnerTenantProfile';
import OwnerHolding from './components/components-profile/owner/OwnerHolding';
import OwnerHoldingPostulate from './components/components-profile/owner/OwnerHoldingPostulate';
import OwnerHoldingSchedules from './components/components-profile/owner/OwnerHoldingSchedules';
import OwnerSchedules from './components/components-profile/owner/OwnerSchedules';
import OrderPayment from './components/components-profile/common/OrderPayment';
import Payments from './components/components-profile/common/Payments';

/*----------  Begin Chat  ----------*/
import ChatMessages from './components/components-profile/chat/ChatMessages';
/*----------   End Chat   ----------*/


import getdata from '../js/profiles/getdata';


import VueProgressBar from 'vue-progressbar'


// commonjs require
// NOTE: default needed after require
//var Editor = require('@tinymce/tinymce-vue').default;

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
        {path: '/', component: OwnerProfile },
        {path: '/holdings/edit/:idProperty', component: OwnerHolding, props: true },
        {path: '/holdings/postulates/:idProperty', component: OwnerHoldingPostulate, props: true },
        {path: '/holdings/postulates/:idProperty/tenant/:idPostulate', component: OwnerTenantProfile, props: true},
        {path: '/holdings/schedules/:idProperty', component: OwnerHoldingSchedules, props: true },
        {path: '/holdings', component: OwnerHoldings },
        {path: '/forms', component: OwnerMyForm },
        {path: '/schedules', component: OwnerSchedules},
        {path: '/membership', component: OwnerMembership },
        {path: '/payments', component: Payments },
        {path: '/order/:idOrder', component: OrderPayment,  props: true},
        {path: '/contracts', component: OwnerContracts },
        {path: '/messages', component: ChatMessages },
        {path: '/projects', component: OwnerInteresting },
        {path: '/services', component: OwnerSuggested },
        {path: '/configs', component: OwnerConfig },
    ]

});
const profiles = new Vue({
    router,
    extends : getdata,
    el: '#profiles',
    components: {
        Menus,
        OwnerProfile,
        OwnerHoldings,
        OwnerMyForm,
        OwnerMembership,
        OwnerContracts,
        ChatMessages,
        OwnerInteresting,
        OwnerSuggested,
        OwnerConfig,
        OwnerPostulations,
        OwnerTenantProfile,
        OwnerHoldingSchedules,
        Payments,
        // OwnerHolding,

    },
    data() {

        return {

            filtersUrl: `owner/get-filters`,
            dataUrl: `owner/get-info`,

            saveUrl: 'owner/save-data',
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
                        title: 'Mis Propiedades Registradas',
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
