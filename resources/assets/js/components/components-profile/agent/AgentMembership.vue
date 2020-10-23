<template>
    <div class="agent-membership">
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
                                    <div>
                                        <a class="button is-primary" v-if="!cambiar" :href="'/users/agent/membership-checkout-update?membership='+ info.membership_data.id" >Renovar</a>
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
                                    <span>UF {{ JSON.parse(membership.features).package_amount }} <span>+ IVA</span></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="border-bottom" style="height: 42px">Cantidad Publicaciones Mensuales</td>
                                <td class="value" v-for="membership of info.memberships" :key="membership.id" v-if="cambiar ? info.membership_data.id != membership.id : info.membership_data.id == membership.id">
                                    {{ JSON.parse(membership.features).montly_publications }}
                                </td>
                            </tr>
                            <tr>
                                <td class="border-bottom" style="height: 42px">Cantidad de Fotos por Proyecto</td>
                                <td class="value" v-for="membership of info.memberships" :key="membership.id" v-if="cambiar ? info.membership_data.id != membership.id : info.membership_data.id == membership.id">
                                    {{ JSON.parse(membership.features).photos_per_project }}
                                </td>
                            </tr>
                            <tr>
                                <td class="border-bottom" style="height: 42px">Cantidad de Videos en la publicación</td>
                                <td class="value" v-for="membership of info.memberships" :key="membership.id" v-if="cambiar ? info.membership_data.id != membership.id : info.membership_data.id == membership.id">
                                    <img v-if="JSON.parse(membership.features).videos_per_project == 0" :src="imagesDir+'/icono_cruz_'+membership.name.toLowerCase()+'.png'">
                                    <span v-if="JSON.parse(membership.features).videos_per_project == 1">{{ JSON.parse(membership.features).videos_per_project }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="border-bottom">Días continuos de la publicación</td>
                                <td class="value" v-for="membership of info.memberships" :key="membership.id" v-if="cambiar ? info.membership_data.id != membership.id : info.membership_data.id == membership.id">
                                    {{ JSON.parse(membership.features).project_due_days }}
                                </td>
                            </tr>
                            <tr>
                                <td class="border-bottom">Contacto con Potenciales Clientes</td>
                                <td class="value" v-for="membership of info.memberships" :key="membership.id" v-if="cambiar ? info.membership_data.id != membership.id : info.membership_data.id == membership.id">
                                    {{ contact(JSON.parse(membership.features).owner_contact) }}
                                </td>
                            </tr>
                            <tr>
                                <td class="border-bottom">Publicidad en zonas especiales WEB</td>
                                <td class="value" v-for="membership of info.memberships" :key="membership.id" v-if="cambiar ? info.membership_data.id != membership.id : info.membership_data.id == membership.id">
                                    <img v-if="JSON.parse(membership.features).videos_per_project == 0" :src="imagesDir+'/icono_cruz_'+membership.name.toLowerCase()+'.png'">
                                    <img v-if="JSON.parse(membership.features).videos_per_project != 0" :src="imagesDir+'/icono_ok_'+membership.name.toLowerCase()+'.png'">
                                </td>
                            </tr>
                            <tr>
                                <td class="border-bottom">Recomendaciones UHOMIE</td>
                                <td class="value" v-for="membership of info.memberships" :key="membership.id" v-if="cambiar ? info.membership_data.id != membership.id : info.membership_data.id == membership.id">
                                    <img v-if="JSON.parse(membership.features).recommendations == ''" :src="imagesDir+'/icono_cruz_'+membership.name.toLowerCase()+'.png'">
                                    <span v-if="JSON.parse(membership.features).recommendations != ''">{{ recommendations(JSON.parse(membership.features).recommendations) }}</span>
                                </td>
                            </tr>
                            <tr v-if="cambiar">
                                <td>Acciones</td>
                                <td class="value" v-for="membership of info.memberships" :key="membership.id" v-if="info.membership_data.id != membership.id">
                                    <a :href="'/users/agent/membership-checkout-update?membership='+ membership.id" :class="'button is-outlined is-' + membership.name.toLowerCase() + ' '">Selecionar</a>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!--<div class="columns">
            <div class="column is-12">
                <div class="membership-info">
                    <div class="column line-down">
                        <span>Mi membres&iacute;a</span>
                    </div>
                    <br>
                    <div>
                        <img :src="imagesDir+'/logo_'+info.name+'.png'">
                        <a href="" class="button is-outlined is-primary">Renovar</a>                        
                        
                        <a href="#" class="button is-outlined is-primary">Cambiar</a>
                    </div>
                    <div>
                        Vencimiento: 10/10/2020
                    </div>
                </div>
            </div>
        </div>
        <div class="columns">
            <div class="column is-6">
                <table class="plans-table">
                    <tr>
                        <td class="td-title">Su plan incluye</td>
                        <td class="price">
                            <span>$ PRICE <span>+ IVA</span></span>
                            <span>(CLP)</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="border-bottom">Cantidad de Postulaciones disponibles</td>
                        <td class="value">                            
                        </td>
                    </tr>
                    <tr>
                        <td class="border-bottom">Scoring UHOMIE</td>
                        <td class="value">
                            <img :src="imagesDir+'/icono_ok_'+info.name+'.png'">
                        </td>
                    </tr>
                    <tr>
                        <td class="border-bottom">Cantidad de Propiedades a visualizar</td>
                        <td class="value">
                            <img :src="imagesDir+'/icono_ok_'+info.name+'.png'">
                        </td>
                    </tr>
                    <tr>
                        <td class="border-bottom">Comisión x Arriendo</td>
                        <td class="value">                            
                        </td>
                    </tr>
                    <tr>
                        <td class="border-bottom">Contacto c/ propietarios</td>
                        <td class="value">
                            {{ info.ownercontact }}
                        </td>
                    </tr>
                    <tr>
                        <td class="border-bottom">Días continuos de membresía</td>
                        <td class="value">30</td>
                    </tr>
                    <tr>
                        <td class="border-bottom">Sugerencias a Potenciales propietarios</td>
                        <td class="value">
                            <img :src="imagesDir+'/icono_ok_'+info.name+'.png'">
                        </td>
                    </tr>
                    <tr>
                        <td>Recomendaciones UHOMIE</td>
                        <td class="value">
                            <img :src="imagesDir+'/icono_cruz_'+info.name+'.png'">
                        </td>
                    </tr>
                </table>            
            </div>
            <div class="column side-payment is-5 is-offset-1">
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
    import AgentCore from './AgentCore';

    export default {
        extends: AgentCore,
        components: {
            

        },
        name: 'AgentMembership',
        props: {

        },
        data() {
            return {
                imagesDir: imagesDir,
                cambiar: false
            }
        },
        methods:{
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

                return contact
            },
            recommendations: function (e) {

                switch (e) {
                    case 1:
                        return 'Mailing'                       
                        break;
                
                    case 2:
                        return 'Mailing, Campañas,Push de Notificaciones, Redes sociales'
                        break;
                    default:
                        return ''
                        break;
                }
            }
        }
    }
</script>