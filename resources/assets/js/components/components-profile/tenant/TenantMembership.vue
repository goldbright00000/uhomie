<template>
    <div class="tenant-membership">
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
                                <td class="has-text-centered" style="vertical-align:middle">
                                    <a class="button is-primary" v-if="!cambiar" :href="'/users/tenant/membership-checkout-update?membership='+ info.membership_data.id" >Renovar</a>
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
                                <td class="border-bottom" style="height: 42px">Cantidad de Postulaciones Disponibles</td>
                                <td class="value" v-for="membership of info.memberships" :key="membership.id" v-if="cambiar ? info.membership_data.id != membership.id : info.membership_data.id == membership.id">
                                    <img v-if="JSON.parse(membership.features).applications_received_count == '-1'" :src="imagesDir+'/icono_infinito_'+membership.name.toLowerCase()+'.png'">
                                    <span v-if="JSON.parse(membership.features).applications_received_count != '-1'">{{ JSON.parse(membership.features).applications_received_count }}</span>
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
                                <td class="border-bottom" style="height: 42px">Cantidad de Propiedades a Visualizar</td>
                                <td class="value" v-for="membership of info.memberships" :key="membership.id" v-if="cambiar ? info.membership_data.id != membership.id : info.membership_data.id == membership.id">
                                    <img v-if="JSON.parse(membership.features).properties_count == '-1'" :src="imagesDir+'/icono_infinito_'+membership.name.toLowerCase()+'.png'">
                                    <img v-if="JSON.parse(membership.features).score_display == '1'" :src="imagesDir+'/icono_ok_'+membership.name.toLowerCase()+'.png'">
                                    <img v-if="JSON.parse(membership.features).properties_count == '0'" :src="imagesDir+'/icono_cruz_'+membership.name.toLowerCase()+'.png'">
                                    <span v-if="JSON.parse(membership.features).properties_count != '-1' &&
                                    JSON.parse(membership.features).properties_count != '0'">{{ JSON.parse(membership.features).properties_count }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="border-bottom">Comisión por Arriendo</td>
                                <td class="value" v-for="membership of info.memberships" :key="membership.id" v-if="cambiar ? info.membership_data.id != membership.id : info.membership_data.id == membership.id">
                                    gratis
                                </td>
                            </tr>
                            <tr>
                                <td class="border-bottom">Contacto c/ propietarios</td>
                                <td class="value" v-for="membership of info.memberships" :key="membership.id" v-if="cambiar ? info.membership_data.id != membership.id : info.membership_data.id == membership.id">
                                    {{ contact(JSON.parse(membership.features).owner_contact) }}
                                </td>
                            </tr>
                            <tr>
                                <td class="border-bottom">Días continuos de membresía</td>
                                <td class="value" v-for="membership of info.memberships" :key="membership.id" v-if="cambiar ? info.membership_data.id != membership.id : info.membership_data.id == membership.id">30</td>
                            </tr>
                            <tr>
                                <td style="height: 42px" class="border-bottom">Sugerencias a Potenciales propietarios</td>
                                <td class="value" v-for="membership of info.memberships" :key="membership.id" v-if="cambiar ? info.membership_data.id != membership.id : info.membership_data.id == membership.id">
                                    <img v-if="JSON.parse(membership.features).suggestions_to_owners == '1'" :src="imagesDir+'/icono_ok_'+membership.name.toLowerCase()+'.png'">
                                    <img v-if="JSON.parse(membership.features).suggestions_to_owners == '0'" :src="imagesDir+'/icono_cruz_'+membership.name.toLowerCase()+'.png'">
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
                                    <a :href="'/users/tenant/membership-checkout-update?membership='+ membership.id" :class="'button is-outlined is-' + membership.name.toLowerCase() + ' '">Seleccionar</a>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!--<div class="columns">
            <div class="column is-6">



                <table class="plans-table">
                    <tr>
                        <td></td>
                        <td class="border-bottom border-basic">
                            <img src="http://localhost:8000/images/logo_basic.png">
                        </td>
                        <!--
                        <td class="border-bottom border-premium">
                            <img src="http://localhost:8000/images/logo_premium.png">
                        </td>
                        
                    </tr>
                    <tr>
                        <td class="td-title">Su plan incluye</td>
                        <td class="price">
                            <span>$ {{ features.package_amount }} <span>+ IVA</span></span>
                            <span> (CLP)</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="height: 42px">Cantidad de Postulaciones Recibidas</td>
                        <td class="value">
                            <img v-if="features.applications_received_count == '-1'" :src="imagesDir+'/icono_infinito_'+info.membership_data.name.toLowerCase()+'.png'">
                            <img v-if="features.applications_received_count == '0'" :src="imagesDir+'/icono_cruz_'+info.membership_data.name.toLowerCase()+'.png'">
                            <span v-if="features.applications_received_count != '-1' &&
                            features.applications_received_count != '0'">{{ features.applications_received_count }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="height: 42px">Scoring UHOMIE</td>
                        <td class="value">

                            <img v-if="features.score_display == '1'" :src="imagesDir+'/icono_ok_'+info.membership_data.name.toLowerCase()+'.png'">
                            <img v-if="features.score_display == '0'" :src="imagesDir+'/icono_cruz_'+info.membership_data.name.toLowerCase()+'.png'">

                        </td>
                    </tr>
                    <tr>
                        <td style="height: 42px">Cantidad de Propiedades a Publicar</td>
                        <td class="value">

                            <img v-if="features.properties_count == -1" :src="imagesDir+'/icono_infinito_'+info.membership_data.name.toLowerCase()+'.png'">
                            <img v-if="features.properties_count == 0" :src="imagesDir+'/icono_cruz_'+info.membership_data.name.toLowerCase()+'.png'">
                            <span v-if="features.properties_count != -1 &&
                            features.properties_count != 0">{{ features.properties_count }}</span>

                        </td>
                    </tr>
                    <tr>
                        <td style="height: 42px">%Comisión x Arriendo en c/Propiedad</td>
                        <td class="value">

                            {{ features.commission == '0' ? 'gratis' : features.commission }} 

                        </td>
                    </tr>
                    <tr>
                        <td style="height: 42px">Contacto c/ clientes</td>
                        <td class="value">
                            {{ contact }}
                        </td>
                    </tr>
                    <tr>
                        <td style="height: 42px">Días continuos de membresía</td>
                        <td class="value">30</td>
                    </tr>
                    <tr>
                        <td style="height: 42px">Sugerencias a Potenciales clientes</td>
                        <td class="value">

                            <img v-if="features.suggestions_to_owners == '1'" :src="imagesDir+'/icono_ok_'+info.membership_data.name.toLowerCase()+'.png'">
                            <img v-if="features.suggestions_to_owners == '0'" :src="imagesDir+'/icono_cruz_'+info.membership_data.name.toLowerCase()+'.png'">

                        </td>
                    </tr>
                    <tr>
                        <td>Recomendaciones UHOMIE</td>
                        <td class="value">

                            <img v-if="features.recommendations == '0'" :src="imagesDir+'/icono_cruz_'+info.membership_data.name.toLowerCase()+'.png'">
                            {{ recommendations }}

                        </td>
                    </tr>
                </table>
            </div>
            <div class="column side-payment is-5 is-offset-1" v-if="renovar">

                <h2>Tu cupón</h2>
                <span>Si tienes un cupón de descuento <br>puedes canjearlo aquí.</span>
                <div class="coupon">
                    <input class="input" type="text" name="coupon" disabled>
                    <span><img :src="imagesDir+'/icono-tilde-azul.png'"> Cupón aplicado correctamente</span>
                </div>
                <h3 class="total-price before">$ 14.750 <span>+ IVA</span></h3>
                <h3 class="total-price">$ 12.290 <span>+ IVA</span></h3>
                <div class="control">
                    <label class="radio">
                        <input type="radio" name="payment-method" disabled>
                        <img :src="imagesDir+'/icono-visa.png'">
                        <img :src="imagesDir+'/icono-mastercard.png'">
                        <img :src="imagesDir+'/icono-americanexpress.png'">
                    </label>
                    <label class="radio">
                        <input type="radio" name="payment-method" checked>
                        <img :src="imagesDir+'/icono-paypal.png'">
                    </label>
                </div>
                <hr>
                <div id="paypal-button"></div>
            </div>
        </div>-->
    </div>
</template>

<script>

    const imagesDir = document.getElementById('images-dir').value;
    import TenantCore from './TenantCore';

    export default {
        extends: TenantCore,
        components: {

        },
        name: 'TenantMembership',
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
