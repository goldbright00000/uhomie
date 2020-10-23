<template>
    <tr class="postul-app">
        <td style="vertical-align:middle" class="has-text-centered">
            <div :class="info.status">
                <span><b>{{ info.status_text }}</b></span>
            </div>
        </td>
        <td style="vertical-align:middle">
            <div class="media">
                <div class="media-left">
                    <img :src="info.tenant.photo || imagesDir + '/roles/avatar-uhomie.png'" style="width:34px;height:34px; border-radius: 50%;"/>
                </div>
                <div class="media-content" style="border-left: 3px solid #ffdf7a; height: 30px;">
                    <img @click="$router.push('/holdings/postulates/' + $route.params.idProperty + '/tenant/' + info.id)" :src="imagesDir+'/yelow-seach.png'" style="cursor: pointer;"/>
                </div>
            </div>
            <div class="tenant-info">
                <div> {{ info.tenant.firstname }} </div>
                <div> {{ info.tenant.lastname }} </div>
            </div>
        </td>
        <td class="has-text-centered">
            <div class="columns">
                <div class="column">
                    <div class="profiles">
                        <div class="scoring">
                            <div :class="info.tenant.logscoring + ' tooltip'" :data-tooltip="scoring_message.clasification"  style="display: inline;">
                                <p><b>{{ info.tenant.score }}</b></p>
                                <img :src="imagesDir+'/posit.png'"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </td>
        <td class="has-text-centered" style="vertical-align:middle">
            <a target="_blank" href="https://uhomiehelp.zendesk.com/hc/es-419/articles/360029656251-Puntaje-Scoring-UHOMIE-Zonas-de-Clasificaci%C3%B3n-">
                <div>{{scoring_message.clasification}}</div>
                <div>{{scoring_message.recommendation}}</div>
                <div><b>{{scoring_message.risk}}</b> <i class="fa fa-info-circle"></i></div>
            </a>
        </td>
        <td style="vertical-align:middle" class="has-text-centered">
            <p v-if="!info.applied_days">{{moment(info.created_at).locale('es').format('LLL') }}</p>
            <p v-for="(item,index) in info.applied_days" v-bind:key="index">{{ moment(item).locale('es').format('LL') }}</p>
        </td>
        <td style="vertical-align:middle" class="has-text-centered">
            <div v-if="tenant">
                <div class="has-text-centered">
                    <div @click="toChat(info.tenant.id)" :class="{'is-hidden': info.tenant.chat == false }"><span class="tooltip" :data-tooltip="'Chatear con ' + info.tenant.firstname + ' ' + info.tenant.lastname"><img :src="imagesDir+'/contact-chat.png'"/></span></div>
                    <div @click="copyToClipboard('email')" :class="{'is-hidden': info.tenant.email == false }"><span class="tooltip" :data-tooltip="info.tenant.email"><img @click="copyToClipboard('email')" :src="imagesDir+'/contact-email.png'"/></span></div>
                    <div @click="copyToClipboard('phone')" :class="{'is-hidden': info.tenant.phone == false }"><span class="tooltip" :data-tooltip="'(+' + info.tenant.phone_code + ')-' + info.tenant.phone"><img  @click="copyToClipboard('phone')" :src="imagesDir+'/contact-phone.png'"/></span></div>
                </div>

                <input type="hidden" :value="info.tenant.email" :id="'email_contact_'+info.tenant.id">
                <input type="hidden" :value="info.tenant.phone" :id="'phone_contact_'+info.tenant.id">
            </div>
            <div v-if="!tenant">
                <div class="has-text-centered">
                    <div @click="toChat(info.owner.id)" :class="{'is-hidden': info.owner.chat == false }"><span class="tooltip" :data-tooltip="'Chatear con ' + info.owner.firstname + ' ' + info.owner.lastname"><img :src="imagesDir+'/contact-chat.png'"/></span></div>
                    <div @click="copyToClipboard('email')" :class="{'is-hidden': info.owner.email == false }"><span class="tooltip" :data-tooltip="info.owner.email"><img @click="copyToClipboard('email')" :src="imagesDir+'/contact-email.png'"/></span></div>
                    <div @click="copyToClipboard('phone')" :class="{'is-hidden': info.owner.phone == false }"><span class="tooltip" :data-tooltip="'(+' + info.owner.phone_code + ')-' + info.owner.phone"><img  @click="copyToClipboard('phone')" :src="imagesDir+'/contact-phone.png'"/></span></div>
                </div>

                <input type="hidden" :value="info.owner.email" :id="'email_contact_'+info.owner.id">
                <input type="hidden" :value="info.owner.phone" :id="'phone_contact_'+info.owner.id">
            </div>
        </td>
        <td style="vertical-align:middle" :class="info.enabled == false ? 'has-text-centered gray-disabled' : 'has-text-centered'">
            <div v-if="info.state <= 2">
                <div class="columns is-vcentered has-text-centered" v-if="info.enabled">
                    <div class="column" style="padding: 0;" v-if="type_stay == 'LONG_STAY'">
                        <a class="tooltip" data-tooltip="Contrato Borrador" target="_blank" :href="'/stream/borrador_contrato/'+info.owner.id+'/'+info.tenant.id+'/'+info.property_id">
                            <img :src="imagesDir+'/icons/contratook.png'"/>
                        </a>
                    </div>
                    <div class="column" style="padding: 0;" v-if="info.state == 0">
                        <div><span class="tooltip is-tooltip-left" data-tooltip="Aprobar Postulación"><img :src="imagesDir+'/icons/ok.png'" @click="toApplyPostulant()" style="cursor: pointer;"/></span></div>
                        <div><span class="tooltip is-tooltip-left" data-tooltip="Rechazar Postulación"><img :src="imagesDir+'/icons/no.png'" @click="toCancelPostulant()" style="cursor: pointer;"/></span></div>
                    </div>
                </div>
                <div class="columns is-vcentered has-text-centered" v-else>
                    <div class="column" style="padding: 0;">
                        <img :src="imagesDir+'/icons/contratos.png'" @click="toMembership()" style="cursor: pointer;"/>
                    </div>
                </div>
            </div>
            <div v-if="info.state == 3">
                <div class="columns is-vcentered has-text-centered">
                    <div class="column" style="padding: 0;">
                        <ModalVideoRecording :postul_id="info.id"></ModalVideoRecording>
                        <!--<span class="tooltip" data-tooltip="Verificar">
                            <img :src="imagesDir+'/icono-reembolso.png'"/>
                        </span>-->
                    </div>
                </div>
            </div>
            <div v-if="info.state == 4">
                <div class="columns is-vcentered has-text-centered">
                    <div class="column">
                        <a class="button is-outlined is-basic" href="/users/profile/owner#/contracts">Ver contrato</a>
                    </div>
                </div>
            </div>
        </td>
        <div class="overlay" v-show="toApplyPostul">
            <div class="verification-box">
                <div class="has-text-centered">
                    <img :src="imagesDir+'/foco.png'">
                </div>
                <div style="background-color: white;padding:10px;width: 400px; text-align: center">
                    <h4>Atención!</h4>
                    <p>Desea <b>aprobar</b> la postulación <b>{{info.tenant.firstname + ' ' + info.tenant.lastname}}</b>, debera esperar a la confirmación de arrendatario para seguir el proceso de arriendo.</p>
                </div>
                <div style="padding: 5px 0; display: flex; justify-content: space-between;">
                    <button style="background-color: transparent;
                        border: 1px solid white;
                        padding: 10px 30px;
                        color: white;
                        font-size: 14px;
                        font-weight: 400;" v-on:click="toApplyPostul= false">VOLVER</button>
                    <button style="background-color: transparent;
                        border: 1px solid white;
                        padding: 10px 30px;
                        color: white;
                        font-size: 14px;
                        font-weight: 400;" @click="save_postulant_event(info.id,2)">CONTINUAR</button>
                </div>
            </div>
        </div>
        <div class="overlay" v-show="toCancelPostul">
            <div class="verification-box">
                <div class="has-text-centered">
                    <img :src="imagesDir+'/foco.png'">
                </div>
                <div style="background-color: white;padding:10px;width: 400px; text-align: center">
                    <h4>Atención!</h4>
                    <p>Desea <b>rechazar</b> la postulación <b>{{info.tenant.firstname + ' ' + info.tenant.lastname}}</b>, al realizar esta opción inhabilitara procesos con el postulante.</p>
                </div>
                <div style="padding: 5px 0; display: flex; justify-content: space-between;">
                    <button style="background-color: transparent;
                        border: 1px solid white;
                        padding: 10px 30px;
                        color: white;
                        font-size: 14px;
                        font-weight: 400;" v-on:click="toCancelPostul= false">VOLVER</button>
                    <button style="background-color: transparent;
                        border: 1px solid white;
                        padding: 10px 30px;
                        color: white;
                        font-size: 14px;
                        font-weight: 400;" @click="save_postulant_event(info.id,1)">CONTINUAR</button>
                </div>
            </div>
        </div>
    </tr>
    
</template>


<script>
    const _token = document.getElementById('_token').value;
    const imagesDir = document.getElementById('images-dir').value;
    import datasheet from '../profiles/datasheet';
    import ModalVideoRecording from './ModalVideoRecording.vue';
    
    export default {
        name: 'Postul',
        extends : datasheet,
        components: {
            ModalVideoRecording
        },
        props: {

            info: Object,
            tenant: {
                type: Boolean,
                default: false
            },
            type_stay: String
        },
        data: function () {
            return {
                imagesDir: imagesDir,
                toApplyPostul: false,
                toApplyPostul: false,
                toCancelPostul: false,
                toShowMembership: false,
                
            }
        },
        mounted() {
        },
        methods: {
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
            toApplyPostulant: function() {
                this.toApplyPostul = true
            },
            toCancelPostulant: function() {
                this.toCancelPostul = true
            },
            toMembership: function() {
                this.$parent.toShowMembership = true
            },
            save_postulant_event(e,s){
                this.$Progress.start()
                if(this.toApplyPostul == true || this.toCancelPostul == true){
                    this.toApplyPostul = false
                    this.toCancelPostul = false
                }
                this.toShowPostul= false
                axios.post('owner/save-postulant', {
                    _token: _token,
                    id: e,
                    state: s
                    }).then((response) => {
                        if(s == 2) {
                            this.info.state = 2
                            this.info.status = 'approved'
                            this.info.status_text = 'APROBADO'
                            toastr.success('Has aprobado la postulación satisfactoriamente.')
                        }
                        if(s == 1) {
                            this.info.state = 1
                            this.info.status = 'refused'
                            this.info.status_text = 'RECHAZADO'
                            toastr.success('Has rechazado la postulación satisfactoriamente.')
                        }
                        this.$Progress.finish()
                    }).catch((error) => {
                        if(error.response) {
                            let info = error.response.data;
                            //let status = error.response.status;
                            this.$Progress.fail()
                            toastr.error(info)
                        } else {
                            console.log(error)
                        }
                });
            },
        },
        computed: {
            contact: function() {
                if (!this.tenant) { 
                    var contact_level = JSON.parse(this.info.applied_membership.features).owner_contact
                } else {
                    var contact_level = JSON.parse(this.info.owner_membership.features).owner_contact
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
            },
            scoring_message(){
                var scoring_message = {}
                if(this.info.tenant.score >= 0 && this.info.tenant.score <= 300){
                    scoring_message = {
                        clasification: 'El puntaje es muy bajo',
                        recommendation: 'no arrendar',
                        risk: 'Nivel de riesgo muy Alto'
                    };
                }
                if(this.info.tenant.score >= 301 && this.info.tenant.score <= 500){
                    scoring_message = {
                        clasification: 'El puntaje es bajo',
                        recommendation: 'no arrendar',
                        risk: 'Nivel de riesgo muy alto'
                    };
                }
                if(this.info.tenant.score >= 501 && this.info.tenant.score <= 800){
                    scoring_message = {
                        clasification: 'El puntaje es medio',
                        recommendation: 'no arrendar',
                        risk: 'Nivel de riesgo alto'
                    };
                }
                if(this.info.tenant.score >= 801 && this.info.tenant.score <= 900){
                    scoring_message = {
                        clasification: 'El puntaje es alto',
                        recommendation: 'arrendar',
                        risk: 'Nivel de riesgo medio'
                    };
                }
                if(this.info.tenant.score >= 901 && this.info.tenant.score <= 1100){
                    scoring_message = {
                        clasification: 'El puntaje es muy alto',
                        recommendation: 'arrendar',
                        risk: 'Nivel de riesgo bajo'
                    };
                    return ;
                }
                if(this.info.tenant.score >= 1101){
                    scoring_message = {
                        clasification: 'El puntaje es estelar',
                        recommendation: 'arrendar',
                        risk: 'Nivel de riesgo muy bajo.'
                    };
                }
                return scoring_message;
            }
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
        
    }
</script>

<style>
.waiting_for_acceptance {
    border-bottom: 3px solid hsl(48, 100%, 67%);
}

.approved {
    border-bottom: 3px solid hsl(141, 71%, 48%);
}

.signed {
    border-bottom: 3px solid hsl(204, 86%, 53%);
}

.refused
{
    border-bottom: 3px solid hsl(348, 100%, 61%);
}

.scoring .basic img {
    background-color: #00B1D9;
}
.scoring .selects img {
    background-color: #947AFB;
}
.scoring .premium img {
    background-color: #F981F6;
}
.profiles .scoring > div > p {
    position: absolute;
    font-size: 12px;
    width: 46px;
    margin-top: 20px;
    text-align: center;
    color: #fff;
    display: inline-block;
}

.gray-disabled {
    -webkit-filter: grayscale(100%);
    filter: grayscale(100%);
}


</style>
