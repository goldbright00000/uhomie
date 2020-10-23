<template>
    <div class="owner-membership">
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
                                    <a class="button is-primary" v-if="!cambiar" :href="'/users/owner/membership-checkout-update?membership='+ info.membership_data.id" >Renovar</a>
                                    <a class="button is-primary" v-if="!cambiar" @click="cambiar = true">Cambiar</a>
                                    <a class="button is-primary" v-if="cambiar" @click="cambiar = false">Cerrar</a>
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
                                    <img v-if="JSON.parse(membership.features).applications_received_count == '-1'" :src="imagesDir+'/icono_infinito_'+membership.name.toLowerCase()+'.png'">
                                    <img v-if="JSON.parse(membership.features).applications_received_count == '0'" :src="imagesDir+'/icono_cruz_'+membership.name.toLowerCase()+'.png'">
                                    <span v-if="JSON.parse(membership.features).applications_received_count != '-1' &&
                                    JSON.parse(membership.features).applications_received_count != '0'">{{ JSON.parse(membership.features).applications_received_count }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="border-bottom" style="height: 42px">Cantidad de Propiedades a Publicar</td>
                                <td class="value" v-for="membership of info.memberships" :key="membership.id" v-if="cambiar ? info.membership_data.id != membership.id : info.membership_data.id == membership.id">
                                    <img v-if="JSON.parse(membership.features).properties_count == '-1'" :src="imagesDir+'/icono_infinito_'+membership.name.toLowerCase()+'.png'">
                                    <img v-if="JSON.parse(membership.features).properties_count == '0'" :src="imagesDir+'/icono_cruz_'+membership.name.toLowerCase()+'.png'">
                                    <span v-if="JSON.parse(membership.features).properties_count != '-1' &&
                                    JSON.parse(membership.features).properties_count != '0'">{{ JSON.parse(membership.features).properties_count }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="border-bottom" style="height: 42px">Scoring UHOMIE</td>
                                <td class="value" v-for="membership of info.memberships" :key="membership.id" v-if="cambiar ? info.membership_data.id != membership.id : info.membership_data.id == membership.id">
                                    <img v-if="JSON.parse(membership.features).score_display == '1'" :src="imagesDir+'/icono_ok_'+membership.name.toLowerCase()+'.png'">
                                    <img v-if="JSON.parse(membership.features).score_display == '0'" :src="imagesDir+'/icono_cruz_'+membership.name.toLowerCase()+'.png'">
                                </td>
                            </tr>
                            <tr>
                                <td class="border-bottom" style="height: 42px">%Comisión x Arriendo en c/Propiedad</td>
                                <td class="value" v-for="membership of info.memberships" :key="membership.id" v-if="cambiar ? info.membership_data.id != membership.id : info.membership_data.id == membership.id">
                                    <span>{{ JSON.parse(membership.features).tenanting_fee }}%</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="border-bottom">Contacto c/ clientes</td>
                                <td class="value" v-for="membership of info.memberships" :key="membership.id" v-if="cambiar ? info.membership_data.id != membership.id : info.membership_data.id == membership.id">
                                    {{ contact(JSON.parse(membership.features).owner_contact) }}
                                </td>
                            </tr>
                            <tr>
                                <td class="border-bottom">Días continuos de membresía</td>
                                <td class="value" v-for="membership of info.memberships" :key="membership.id" v-if="cambiar ? info.membership_data.id != membership.id : info.membership_data.id == membership.id">30</td>
                            </tr>
                            <tr>
                                <td style="height: 42px" class="border-bottom">Sugerencias a Potenciales clientes</td>
                                <td class="value" v-for="membership of info.memberships" :key="membership.id" v-if="cambiar ? info.membership_data.id != membership.id : info.membership_data.id == membership.id">
                                    <img v-if="JSON.parse(membership.features).suggestions_to_tenants == '1'" :src="imagesDir+'/icono_ok_'+membership.name.toLowerCase()+'.png'">
                                    <img v-if="JSON.parse(membership.features).suggestions_to_tenants == '0'" :src="imagesDir+'/icono_cruz_'+membership.name.toLowerCase()+'.png'">
                                </td>
                            </tr>
                            <tr>
                                <td class="border-bottom">Recomendaciones UHOMIE</td>
                                <td class="value" v-for="membership of info.memberships" :key="membership.id" v-if="cambiar ? info.membership_data.id != membership.id : info.membership_data.id == membership.id">
                                    <img v-if="JSON.parse(membership.features).recommendations == '0'" :src="imagesDir+'/icono_cruz_'+membership.name.toLowerCase()+'.png'">
                                    <span v-if="JSON.parse(membership.features).recommendations != '0'">{{ recommendations(JSON.parse(membership.features).recommendations) }}</span>
                                </td>
                            </tr>
                            <tr v-if="cambiar">
                                <td>Acciones</td>
                                <td class="value" v-for="membership of info.memberships" :key="membership.id" v-if="info.membership_data.id != membership.id">
                                    <a :href="'/users/owner/membership-checkout-update?membership='+ membership.id" :class="'button is-outlined is-' + membership.name.toLowerCase() + ' '">Seleccionar</a>
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
    const imagesDir = document.getElementById('images-dir').value;
    import OwnerCore from './OwnerCore';

    export default {
        extends: OwnerCore,
        components: {

        },
        name: 'OwnerMembership',
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
                        return 'Telefono + Correo + Chat'
                        break;
                    case 1:
                        return 'Correo + Chat'                       
                        break;
                
                    default:
                        return 'Chat'
                        break;
                }
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
                        return 'Mailing, Campañas, Push de Notificaciones, Redes sociales'
                        break;
                }
            }
        }
    }
</script>
