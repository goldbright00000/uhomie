import Menus from './components/Menus.vue';


import AvalProfile from './components/components-profile/aval/AvalProfile.vue';
import AvalPostulations from './components/components-profile/aval/AvalPostulations.vue';
import AvalHoldings from './components/components-profile/aval/AvalHoldings.vue';
import AvalMyForm from './components/components-profile/aval/AvalMyForm.vue';
import AvalMembership from './components/components-profile/aval/AvalMembership';
import AvalContracts from './components/components-profile/aval/AvalContracts';
// import AvalMessages from './components/components-profile/aval/AvalMessages';
import AvalInteresting from './components/components-profile/aval/AvalInteresting';
import AvalSuggested from './components/components-profile/aval/AvalSuggested';
import AvalConfig from './components/components-profile/aval/AvalConfig';
import AvalTenantProfile from './components/components-profile/aval/AvalTenantProfile';
import AvalHolding from './components/components-profile/aval/AvalHolding';

/*----------  Begin Chat  ----------*/
// import TenantMessages from './components/components-profile/tenant/TenantMessages';
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


const imagesDir = document.getElementById('images-dir').value;

const router = new VueRouter({
    linkActiveClass: 'selected',
    routes: [
        {path: '/', component: AvalProfile },
        {path: '/holdings/edit/:idProperty', component: AvalHolding, props: true },
        {path: '/holdings', component: AvalHoldings },
        {path: '/forms', component: AvalMyForm },
        {path: '/membership', component: AvalMembership },
        {path: '/contracts', component: AvalContracts },
        {path: '/messages', component: ChatMessages },
        {path: '/projects', component: AvalInteresting },
        {path: '/services', component: AvalSuggested },
        {path: '/configs', component: AvalConfig },
    ]

});
const profiles = new Vue({
    router,
    extends : getdata,

    el: '#profiles',
    components: {
        Menus,
        AvalProfile,
        AvalHoldings,
        AvalMyForm,
        AvalMembership,
        AvalContracts,
        ChatMessages,
        AvalInteresting,
        AvalSuggested,
        AvalConfig,
        AvalPostulations,
        AvalTenantProfile,
        AvalHolding,

    },
    data() {

        return {

            /* DS: PROPERTIES ONLY SAMPLE */

            filtersUrl: 'aval/get-filters',
            dataUrl: 'aval/get-info',

            saveUrl: 'aval/save-data',
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
                        ico: imagesDir + '/menu03.png',
                        route: '/holdings',
                        title: 'Mis Propiedades Registradas',
                    },
                    {
                        ico: imagesDir + '/menu04.png',
                        route: '/forms',
                        title: 'Mis Formularios de propiedades',
                    },
                    {
                        ico: imagesDir + '/menu05.png',
                        route: '/membership',
                        title: 'Mi membresía y Medios de Pago',
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

    }
});
