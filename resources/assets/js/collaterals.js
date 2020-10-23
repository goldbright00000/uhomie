import Menus from './components/Menus.vue';


import CollateralProfile from './components/components-profile/collateral/CollateralProfile.vue';
import CollateralMyForm from './components/components-profile/collateral/CollateralMyForm.vue';
import CollateralContracts from './components/components-profile/collateral/CollateralContracts';
import CollateralTenants from './components/components-profile/collateral/CollateralTenants';
import CollateralSuggested from './components/components-profile/owner/OwnerSuggested';
import CollateralConfig from './components/components-profile/collateral/CollateralConfig';
import OwnerInteresting from './components/components-profile/owner/OwnerInteresting';
import CollateralTenantProfile from './components/components-profile/collateral/CollateralTenantProfile';

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
        {path: '/', component: CollateralProfile },
        {path: '/forms', component: CollateralMyForm },
        {path: '/contracts', component: CollateralContracts },
        {path: '/tenants', component: CollateralTenants },
        {path: '/tenant/:idTenant', component: CollateralTenantProfile, props: true},
        {path: '/messages', component: ChatMessages },
        {path: '/projects', component: OwnerInteresting },
        {path: '/services', componentnent: CollateralSuggested },
        {path: '/configs', component: CollateralConfig },
    ]

});
const profiles = new Vue({
    router,
    extends : getdata,
    el: '#profiles',
    components: {
        Menus,
        CollateralProfile,
        CollateralMyForm,
        CollateralContracts,
        CollateralTenants,
        ChatMessages,
        OwnerInteresting,
        CollateralSuggested,
        CollateralConfig,

    },
    data() {

        return {

            filtersUrl: `collateral/get-filters`,
            dataUrl: `collateral/get-info`,

            saveUrl: 'collateral/save-data',
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
                }
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

            collateralprofile: {
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
                        ico: imagesDir + '/menu04.png',
                        route: '/forms',
                        title: 'Mi Formulario',
                    },
                    {
                        ico: imagesDir + '/menu06.png',
                        route: '/contracts',
                        title: 'Mis Contratos',
                    },
                    {
                        ico: imagesDir + '/menu06.png',
                        route: '/tenants',
                        title: 'Tus Arrendatarios',
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

    }
});
