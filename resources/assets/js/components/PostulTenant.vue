<template>
    <tr class="postul-app">
        <td style="vertical-align:middle" class="border-bottom">
            <input type="checkbox"/>
        </td>
        <td style="vertical-align:middle" >
            <div class="postul-status border-bottom">
                <div class="media">  
                    <div class="media-content">
                        <span :class="info.postulation.status">{{ info.postulation.text }}</span>
                    </div>
                </div>
            </div>
        </td>
        <td style="vertical-align:middle">
            <div class="columns is-gapless is-vcentered border-bottom">
                <div style="margin-right: 5px" class="column">
                    <a target="_blank" :href="['/explorar/'+info.id+'/'+info.slug]">
                        <img :src="info.photos[0].path.charAt(0) == '/' ? info.photos[0].path : '/'+info.photos[0].path " style="width:100px;height:75px;"/>
                    </a>
                </div>
                <div class="column" style="margin-right: 5px">
                    <p class="price" :class="info.owner_membership.name.toLowerCase()">${{ info.rent | money }} CLP</p>
                    <span>{{ info.address }}</span>
                    <span style="margin-bottom: 2px">{{ info.address_details }}</span>
                </div>
            </div>
        </td>
        <td style="vertical-align:middle" class="scoring">
            <div class="border-bottom">
                <div :class="info.owner_membership.name.toLowerCase()">
                    <p>{{ info.scoring }}</p>
                    <img :src="imagesDir+'/posit.png'"/>
                </div>
            </div>
        </td>
        <td style="vertical-align:middle" v-if="info.postulation.days">
            <div :class="['border-bottom',{ tooltip: (info.postulation.days != null)}]" data-tooltip="Dias seleccionados" >
                <p v-for="(item,key) in info.postulation.days" :key="key">{{ moment(item).locale('es').format('LL') }}</p>
            </div>
        </td>
        <td style="vertical-align:middle" v-if="!info.postulation.days">
            <div class="border-bottom tooltip" data-tooltip="Fecha de postulación">
            <p>Postulaste:</p>
            <p>{{ info.postulation.created_at }}</p>
            </div>
        </td>
        <td style="vertical-align:middle">
            <div class="border-bottom" v-if="!tenant">
                <!--<div :class="{'is-hidden': info.owner.chat == false }"><span class="tooltip" :data-tooltip="'Chatear con ' + info.owner.firstname + ' ' + info.owner.lastname"><img :src="imagesDir+'/contact-chat.png'"/></span></div>--> 
                <img @click="toChat(info.owner_id)" :class="{'is-hidden': info.owner_chat == false }" :src="imagesDir+'/contact-chat.png'"/>
                <a :href="info.owner_email == 'Mejora tu membresia para acceder a este dato' ? '#': 'mailto:'+info.owner_email"><img @click="copyToClipboard('email')" :class="{'is-hidden': info.owner_email == false }" :src="imagesDir+'/contact-email.png'"
                     :title="info.owner_email"/></a>
                <a :href="info.owner_phone == 'Mejora tu membresia para acceder a este dato'? '#' : 'tel:'+info.owner_phone"><img @click="copyToClipboard('phone')" :class="{'is-hidden': info.owner_phone == false }" :src="imagesDir+'/contact-phone.png'"
                     :title="info.owner_phone"/></a>

                <input type="hidden" :value="info.owner_email" :id="'email_contact_'+info.id">
                <input type="hidden" :value="info.owner_phone" :id="'phone_contact_'+info.id">
            </div>
            <div class="border-bottom" v-if="tenant">
                <div :class="{'is-hidden': info.owner_chat == false}" ><img :src="imagesDir+'/contact-chat.png'"></div>
                <img @click="toChat(info.owner_id)" :class="{'is-hidden': contact.chat == false }" :src="imagesDir+'/contact-chat.png'"/>
                <br>
                <a :href="'malito:'+info.owner_email">
                <img @click="copyToClipboard('email')" :class="{'is-hidden': info.owner_email == false }" :src="imagesDir+'/contact-email.png'"
                     :title="info.owner_email"/>
                </a>
                <a :href="'tel:'+info.owner_phone">
                <img @click="copyToClipboard('phone')" :class="{'is-hidden': info.owner_phone == false }" :src="imagesDir+'/contact-phone.png'"
                     :title="info.owner_phone"/>
                </a>
                <input type="hidden" :value="info.postulation.tenant.email" :id="'email_contact_'+info.id">
                <input type="hidden" :value="info.postulation.tenant.phone" :id="'phone_contact_'+info.id">
            </div>
        </td>
        <td style="vertical-align:middle" >
            <div class="columns is-vcentered has-text-centered "  v-if="info.postulation.status == 'approved' && info.type_stay == 'LONG_STAY'" :class="info.enabled == false ? 'has-text-centered gray-disabled tooltip is-tooltip-left is-tooltip-multiline' : 'has-text-centered tooltip is-tooltip-left is-tooltip-multiline'" :data-tooltip="'Condiciones de arriendo:  Tiempo de Arriendo: 12 Meses, Meses de garantía: 2 meses, Meses de adelanto: 1 mes, Fecha disponible: 12/12/2019, Exige Aval: Si'">
                <div class="column tooltip" style="padding: 0;" data-tooltip="Descargar pre-contrato">
                    <a target="_blank" :href="'/stream/borrador_contrato/'+info.owner_id+'/'+info.tenant.id+'/'+info.id"><img :src="imagesDir+'/icons/contratook.png'"/></a>
                </div>
                <div class="column" style="padding: 0;">
                    <div class="tooltip" data-tooltip="Ir a Pagar"><img :src="imagesDir+'/icons/ok_green.png'" @click="toPayRent" style="cursor: pointer;margin-bottom: 20px;"></div>
                    <div class="tooltip" data-tooltip="Rechazar"><img :src="imagesDir+'/icons/no_red.png'"  style="cursor: pointer;"/></div>
                </div>
                
            </div>
            <div class="columns is-vcentered has-text-centered "  v-if="info.postulation.status == 'approved' && info.type_stay == 'SHORT_STAY'" :class="info.enabled == false ? 'has-text-centered gray-disabled  ' : 'has-text-centered  '">
                
                <div class="column" style="padding: 0;">
                    <div class="tooltip" data-tooltip="Ir a Pagar"><img :src="imagesDir+'/icons/ok_green.png'" @click="toPayRent" style="cursor: pointer;margin-bottom: 20px;"></div>
                    <div class="tooltip" data-tooltip="Rechazar"><img :src="imagesDir+'/icons/no_red.png'"  style="cursor: pointer;"/></div>
                </div>
                
            </div>
            <div class="columns is-vcentered has-text-centered" v-if="info.postulation.status == 'waiting_for_acceptance'" :class="info.enabled == false ? 'has-text-centered gray-disabled tooltip is-tooltip-left is-tooltip-multiline' : 'has-text-centered tooltip is-tooltip-left is-tooltip-multiline'" :data-tooltip="'Condiciones de arriendo:  Tiempo de Arriendo: 12 Meses, Meses de garantía: 2 meses, Meses de adelanto: 1 mes, Fecha disponible: 12/12/2019, Exige Aval: Si'">
                <div class="column" style="padding: 0;">
                    <a target="_blank" :href="'/stream/borrador_contrato/'+info.owner_id+'/'+info.tenant.id+'/'+info.id" ><img :src="imagesDir+'/icons/contratos.png'" style="cursor: pointer;"/></a>
                </div>
            </div>
            <div class="columns is-vcentered has-text-centered" v-if="info.postulation.status == 'paid_out'" >
                <div class="column" style="padding: 0;">
                    <ModalVideoRecording :postul_id="info.postulation.integer_distinct"></ModalVideoRecording>
                </div>
            </div>
            <div class="columns is-vcentered has-text-centered" v-if="info.postulation.status == 'verified'" >
                <div class="column" style="padding: 0;">
                    <a class="button is-outlined is-basic" href="/users/profile/tenant#/contracts">Ver contrato</a>
                </div>
            </div>
        </td>
    </tr>
</template>
<!--
<template>
    <div class="postul-app columns">
        <div class="column is-1 postul-status">
            <div class="media">
                <div class="media-left">
                    <input type="checkbox"/>
                </div>
            </div>
        </div>
        <div class="column is-2 postul-status">
            <div class="media">
                <div class="media-content">
                    <span :class="info.postulation.status">{{ info.postulation.text }}</span>
                </div>
            </div>
        </div>
        
        <div class="column">

            <div class="media border-bottom">
                <div v-if="!tenant && info.photos != null" class="media-left ">
                    <a :href="['/explorar/'+info.id+'/'+info.slug]">
                        <img :src="info.photos[0].path.charAt(0) == '/' ? info.photos[0].path : '/'+info.photos[0].path " style="width:100px;height:75px;"/>
                    </a>
                </div>
                <div class="media-content holding-info postul-status">
                    <p class="price" :class="info.owner_membership.name.toLowerCase()">${{ info.rent | money }} CLP</p>
                    
                    <span>{{ info.address }}</span>
                    
                    <span style="margin-bottom: 2px">{{ info.address_details }}</span>
                </div>
            </div>

        </div>
        <div class="column is-1 scoring">
            <div class="border-bottom">
                <div :class="info.owner_membership.name.toLowerCase()">
                    <p>{{ info.scoring }}</p>
                    <img :src="imagesDir+'/posit.png'"/>
                </div>
            </div>
        </div>
        <div class="column is-2  postul-status">
            <div v-if="info.postulation && info.postulation.created_at" class="border-bottom">
                <p>Aplicaste</p>
                <p>{{ info.postulation.created_at }}</p>
            </div>
        </div>
        <div class="column is-1 contacts">
            <div class="border-bottom" v-if="!tenant">
                <img @click="toChat" :class="{'is-hidden': contact.chat == false }" :src="imagesDir+'/contact-chat.png'"/>
                <img @click="copyToClipboard('email')" :class="{'is-hidden': info.owner_email == false }" :src="imagesDir+'/contact-email.png'"
                     :title="info.owner_email"/>
                <img @click="copyToClipboard('phone')" :class="{'is-hidden': info.owner_phone == false }" :src="imagesDir+'/contact-phone.png'"
                     :title="info.owner_phone"/>

                <input type="hidden" :value="info.owner_email" :id="'email_contact_'+info.id">
                <input type="hidden" :value="info.owner_phone" :id="'phone_contact_'+info.id">
            </div>
            <div class="border-bottom" v-if="tenant">
                <img @click="toChat" :class="{'is-hidden': contact.chat == false }" :src="imagesDir+'/contact-chat.png'"/>
                <img @click="copyToClipboard('email')" :class="{'is-hidden': info.owner_email == false }" :src="imagesDir+'/contact-email.png'"
                     :title="info.owner_email"/>
                <img @click="copyToClipboard('phone')" :class="{'is-hidden': info.owner_phone == false }" :src="imagesDir+'/contact-phone.png'"
                     :title="info.owner_phone"/>

                <input type="hidden" :value="info.postulation.tenant.email" :id="'email_contact_'+info.id">
                <input type="hidden" :value="info.postulation.tenant.phone" :id="'phone_contact_'+info.id">
            </div>
        </div>
        <div class="column is-1">
            
                <div class="border-bottom" >
                    <img :class="{'is-hidden': contact.email == false }" :src="imagesDir+'/document.png'"
                        :title="'hola'"/>
                </div>
            
        </div>
    </div>

</template>
-->

<script>

    import ModalVideoRecording from './ModalVideoRecording.vue';

    const imagesDir = document.getElementById('images-dir').value;
    export default {
        name: 'PostulTenant',
        components: {
            ModalVideoRecording
        },
        props: {

            info: Object,
            tenant: {
                type: Boolean,
                default: false
            }

        },
        data: function () {
            return {
                imagesDir: imagesDir
            }
        },
        methods: {
            toPayRent: function(){
                window.location.href = this.info.property_payment_url;
            },
            copyToClipboard: function(i) {
                console.log(i+'_contact_'+this.info.id)
                var copyText = document.getElementById(i+'_contact_'+this.info.id)
                copyText.setAttribute('type', 'text')
                copyText.select()
                document.execCommand("copy")
                copyText.setAttribute('type', 'hidden')
                alert(copyText.value)
            },
            toChat: function(id) {
                const self = this
                axios.post('/users/tenant/conversation', { contact_id: id }).then(function(res){
                    self.$router.push('/messages')                    
                })
            },
        },
        computed: {
            contact: function() {
                if (!this.tenant) { 
                    var contact_level = JSON.parse(this.info.applied_membership.features).owner_contact
                } else {
                    var contact_level = JSON.parse(this.info.tenant_membership.features).owner_contact
                }

                var contact = {}

                switch (contact_level) {
                    case 2:
                        contact = {
                            email: true,
                            phone: true,
                            chat: true
                        }
                        
                        break;
                    case 1:
                        contact = {
                            email: true,
                            phone: true,
                            chat: false
                        }                        
                        break;
                
                    default:
                        contact = {
                            email: true,
                            phone: false,
                            chat: false
                        }
                        break;
                }

                console.log(contact)
                return contact
            }
        },
        filters: {
            truncate: function(value, length) {
                return value.length > length ?
                value.substr(0, length - 1) + "..."
                : value;
            },
            money: function(value) {
                return window.globals.filters.moneyFormat(value, 0);
            }
        }
    }
</script>

<style>
    input.hidden {
        visibility: hidden;
    }

    .scoring .basic img {
        background-color: #00B1D9;
    }
    .scoring .select_style img {
        background-color: #947AFB;
    }
    .scoring .premium img {
        background-color: #F981F6;
    }

    .media-content.holding-info .basic {
        color: #00B1D9;
        font-weight: 500;
    }
    .basic {
        color: #00B1D9 !important;
        font-weight: 700;
    }
    .media-content.holding-info .select_style {
        color: #947AFB;
        font-weight: 500;
    }
    .select_style {
        color: #947AFB !important;
        font-weight: 700;
    }
    .media-content.holding-info .premium{
        color: #F981F6;
        font-weight: 500;
    }
    .premium{
        color: #F981F6 !important;
        font-weight: 700;
    }
    .approved {
        border-bottom: 5px solid #1de6ba;
    }
    .waiting_for_acceptance {
        border-bottom: 5px solid #f7d16c;
    }
    .cancelado {
        border-bottom: 3px solid hsl(4, 94%, 19%);
    }
    .refused {
        border-bottom: 5px solid #d33830;
    }
    .paid_out {
        border-bottom: 5px solid #5751e3;
    }
    .verified {
        border-bottom: 5px solid #d52fe4;
    }
    .signed {
        border-bottom: 5px solid #43d102;
    }
</style>
