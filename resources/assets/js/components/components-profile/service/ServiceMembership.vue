<template>
    <div class="service-membership">
        <div class="columns">
            <div class="column is-12">
                <div class="membership-info">
                    <div class="column line-down">
                        <span>Mi membresía</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="columns">
            <div class="column is-7">
                <div style="position:relative;width:100%;margin:auto;overflow:hidden;">
                    <div style="width:100%; overflow:auto;">
                        <table class="plans-table table is-fullwidth is-size-7" style="width: 100%; overflow-x: auto;">
                            <tr>
                                <td class="has-text-centered"  style="vertical-align:middle">
                                    <div>
                                        <a class="button is-primary" v-if="!cambiar" :href="'/users/service/membership-checkout-update?membership='+ info.membership_data.id">Renovar</a>
                                        <a class="button is-primary" v-if="!cambiar" @click="cambiar = true">Cambiar</a>
                                        <a class="button is-primary" v-if="cambiar" @click="cambiar = false">Cerrar</a>
                                    </div>
                                    <div class="has-text-centered">
                                        <div>Vencimiento de membresia actual:</div>
                                        <div>{{ moment(info.membership_data.pivot.expires_at).locale('es').format('LL') }}</div>
                                    </div>
                                </td>
                                <td v-for="membership of info.memberships" :key="membership.id" :class="'border-bottom border-'+membership.name.toLowerCase()" v-if="cambiar ? info.membership_data.id != membership.id : info.membership_data.id == membership.id" style="vertical-align:bottom">
                                    <img :src="imagesDir+'/logo_'+membership.name.toLowerCase()+'.png'">
                                </td>
                            </tr>
                            <tr>
                                <td class="td-title">Su plan incluye</td>
                                <td class="price" v-for="membership of info.memberships" :key="membership.id" v-if="cambiar ? info.membership_data.id != membership.id : info.membership_data.id == membership.id">
                                    <span>$ {{ JSON.parse(membership.features).package_amount }} <span>+ IVA</span></span>
                                    <span> (CLP)</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="border-bottom" style="height: 42px">Cantidad de Postulaciones Recibidas</td>
                                <td class="value" v-for="membership of info.memberships" :key="membership.id" v-if="cambiar ? info.membership_data.id != membership.id : info.membership_data.id == membership.id">
                                    <img v-if="JSON.parse(membership.features).services_counts == '-1'" :src="imagesDir+'/icono_infinito_'+membership.name.toLowerCase()+'.png'">
                                    <img v-if="JSON.parse(membership.features).services_counts == '0'" :src="imagesDir+'/icono_cruz_'+membership.name.toLowerCase()+'.png'">
                                    <span v-if="JSON.parse(membership.features).services_counts != '-1' &&
                                    JSON.parse(membership.features).services_counts != '0'">{{ JSON.parse(membership.features).services_counts }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="border-bottom" style="height: 42px">Cantidad de Fotos por Proyecto</td>
                                <td class="value" v-for="membership of info.memberships" :key="membership.id" v-if="cambiar ? info.membership_data.id != membership.id : info.membership_data.id == membership.id">
                                    <img v-if="JSON.parse(membership.features).photos_per_project == -1" :src="imagesDir+'/icono_infinito_'+membership.name.toLowerCase()+'.png'">
                                    <img v-if="JSON.parse(membership.features).photos_per_project == 0" :src="imagesDir+'/icono_cruz_'+membership.name.toLowerCase()+'.png'">
                                    <span v-if="JSON.parse(membership.features).photos_per_project != -1 &&
                                    JSON.parse(membership.features).photos_per_project != 0">{{ JSON.parse(membership.features).photos_per_project }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="border-bottom">Cantidad de Videos en la publicación</td>
                                <td class="value" v-for="membership of info.memberships" :key="membership.id" v-if="cambiar ? info.membership_data.id != membership.id : info.membership_data.id == membership.id">
                                    <img v-if="JSON.parse(membership.features).videos_per_project == -1" :src="imagesDir+'/icono_infinito_'+membership.name.toLowerCase()+'.png'">
                                    <img v-if="JSON.parse(membership.features).videos_per_project == 0" :src="imagesDir+'/icono_cruz_'+membership.name.toLowerCase()+'.png'">
                                    <span v-if="JSON.parse(membership.features).videos_per_project != -1 &&
                                    JSON.parse(membership.features).videos_per_project != 0">{{ JSON.parse(membership.features).videos_per_project }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="border-bottom">Cantidad de Servicios Principales</td>
                                <td class="value" v-for="membership of info.memberships" :key="membership.id" v-if="cambiar ? info.membership_data.id != membership.id : info.membership_data.id == membership.id">
                                    <img v-if="JSON.parse(membership.features).main_services == -1" :src="imagesDir+'/icono_infinito_'+membership.name.toLowerCase()+'.png'">
                                    <img v-if="JSON.parse(membership.features).main_services == 0" :src="imagesDir+'/icono_cruz_'+membership.name.toLowerCase()+'.png'">
                                    <span v-if="JSON.parse(membership.features).main_services != -1 &&
                                    JSON.parse(membership.features).main_services != 0">{{ JSON.parse(membership.features).main_services }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="border-bottom">Cantidad de Servicios Secundarios</td>
                                <td class="value" v-for="membership of info.memberships" :key="membership.id" v-if="cambiar ? info.membership_data.id != membership.id : info.membership_data.id == membership.id">
                                    <img v-if="JSON.parse(membership.features).secondary_services == -1" :src="imagesDir+'/icono_infinito_'+membership.name.toLowerCase()+'.png'">
                                    <img v-if="JSON.parse(membership.features).secondary_services == 0" :src="imagesDir+'/icono_cruz_'+membership.name.toLowerCase()+'.png'">
                                    <span v-if="JSON.parse(membership.features).secondary_services != -1 &&
                                    JSON.parse(membership.features).secondary_services != 0">{{ JSON.parse(membership.features).secondary_services }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="border-bottom">Días continuos de membresía</td>
                                <td class="value" v-for="membership of info.memberships" :key="membership.id" v-if="cambiar ? info.membership_data.id != membership.id : info.membership_data.id == membership.id">{{ JSON.parse(membership.features).project_due_days }}</td>
                            </tr>
                            <tr>
                                <td class="border-bottom">Contacto c/ clientes</td>
                                <td class="value" v-for="membership of info.memberships" :key="membership.id" v-if="cambiar ? info.membership_data.id != membership.id : info.membership_data.id == membership.id">
                                    {{ contact(JSON.parse(membership.features).owner_contact) }}
                                </td>
                            </tr>
                            <tr>
                                <td class="border-bottom" style="height: 42px">Publicidad en zonas especiales WEB</td>
                                <td class="value" v-for="membership of info.memberships" :key="membership.id" v-if="cambiar ? info.membership_data.id != membership.id : info.membership_data.id == membership.id">
                                    <img v-if="JSON.parse(membership.features).public_support != 0" :src="imagesDir+'/icono_ok_'+membership.name.toLowerCase()+'.png'">
                                    <img v-if="JSON.parse(membership.features).public_support == 0" :src="imagesDir+'/icono_cruz_'+membership.name.toLowerCase()+'.png'">
                                </td>
                            </tr>
                            <tr>
                                <td class="border-bottom">Recomendaciones UHOMIE</td>
                                <td class="value" v-for="membership of info.memberships" :key="membership.id" v-if="cambiar ? info.membership_data.id != membership.id : info.membership_data.id == membership.id">
                                    <img v-if="JSON.parse(membership.features).recommendations == '0'" :src="imagesDir+'/icono_cruz_'+membership.name.toLowerCase()+'.png'">
                                    <span v-if="JSON.parse(membership.features).recommendations != '0'">{{recommendations(JSON.parse(membership.features).recommendations)}}</span>
                                </td>
                            </tr>
                            <tr v-if="cambiar">
                                <td>Acciones</td>
                                <td class="value" v-for="membership of info.memberships" :key="membership.id" v-if="info.membership_data.id != membership.id">
                                    <a :href="'/users/service/membership-checkout-update?membership='+ membership.id" :class="'button is-outlined is-' + membership.name.toLowerCase() + ' '">Seleccionar</a>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    //    import Postul from '../../Postul.vue';

    import ServiceCore from './ServiceCore'
    const imagesDir = document.getElementById('images-dir').value;

    export default {
        extends: ServiceCore,
        components: {

        },
        name: 'ServiceMembership',
        props: {

        },
        data() {
            return {
                imagesDir: imagesDir,
                cambiar: false
            }
        },
        computed: {
            features: function () {
                return JSON.parse(this.info.membership_data.features)
            },
        },
        filters: {
            truncate: function(value, length) {
                return value.length > length
                ? value.substr(0, length - 1) + "..."
                : value;
            },
            money: function(value) {
                return window.globals.filters.moneyFormat(value, 0);
            }
        },
        methods: {
            contact: function(e) {

                switch (e) {
                    case 2:
                        return 'correo + chat + tel'
                        break;
                    case 1:
                        return 'correo + tel'                       
                        break;
                
                    default:
                        return 'correo'
                        break;
                }

                return contact
            },
            recommendations: function (e) {

                switch (e) {
                    case 0:
                        return ''
                        break;
                    case 1:
                        return 'Mailing'                       
                        break;
                
                    case 2:
                        return 'Mailing, Campañas,Push de Notificaciones, Redes sociales'
                        break;
                }

                return recommendation
            }
        }
    }
</script>
