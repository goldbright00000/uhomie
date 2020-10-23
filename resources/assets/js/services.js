import Menus from './components/Menus.vue';


import ServiceProfile from './components/components-profile/service/ServiceProfile.vue';
import ServiceServices from './components/components-profile/service/ServiceServices.vue';
import ServiceService from './components/components-profile/service/ServiceService.vue';
import ServiceMyForm from './components/components-profile/service/ServiceMyForm.vue';
import ServiceMembership from './components/components-profile/service/ServiceMembership';
import ServiceContracts from './components/components-profile/service/ServiceContracts';
// import ServicesMessages from './components/components-profile/service/ServiceMessages';
import ServiceConfig from './components/components-profile/service/ServiceConfig';
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
        {path: '/', component: ServiceProfile },
        //{path: '/holdings/edit/:idProperty', component: AvalHolding, props: true },
        {path: '/services', component: ServiceServices },
        {path: '/service/:idService', component: ServiceService, props: true},
        {path: '/forms', component: ServiceMyForm },
        {path: '/membership', component: ServiceMembership },
        {path: '/payments', component: Payments },
        {path: '/order/:idOrder', component: OrderPayment,  props: true},
        {path: '/contracts', component: ServiceContracts },
        {path: '/messages', component: ChatMessages },
        {path: '/configs', component: ServiceConfig },
    ]

});
const profiles = new Vue({
    router,
    extends : getdata,

    el: '#profiles',
    components: {
        Menus,
        ServiceProfile,
        ServiceMyForm,
        ServiceMembership,
        ServiceContracts,
        ChatMessages,
        ServiceConfig,
        ServiceServices,
        //AvalHolding,

    },
    data() {

        return {

            /* DS: PROPERTIES ONLY SAMPLE */

            filtersUrl: 'service/get-filters',
            dataUrl: 'service/get-info',

            saveUrl: 'service/save-data',
            loading: true,
            /* PROFILE */

            filters: {
                banks: {
                    value: 0,
                    options: []
                },
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


            avalprofile: {
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
                        ico: imagesDir + '/menu10.png',
                        route: '/services',
                        title: 'Mis Servicios',
                    },
                    {
                        ico: imagesDir + '/menu04.png',
                        route: '/forms',
                        title: 'Mi Formulario',
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
        //Llevado a services-explore.js
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