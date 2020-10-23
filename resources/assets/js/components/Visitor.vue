<template>
    <tr class="postul-app">
        <td style="vertical-align:middle" class="has-text-centered">
            <div :class="statusStyle">
                <span><b>{{ statusName }}</b></span>
            </div>
        </td>
        <td style="vertical-align:middle">
            
            <div class="tenant-info">
                <div> {{ info.firstname }} </div>
                <div> {{ info.lastname }} </div>
            </div>
        </td>
        <td class="has-text-centered" style="vertical-align:middle">
            {{ moment(info.schedule_date).locale('es').format('LL')  }}
        </td>
        <td style="vertical-align:middle" class="has-text-centered">
            <div v-if="info.schedule_range == '9-12'">
                <label>
                    <div>
                        <p>Mañana</p>
                        <p><b>09-12</b></p>
                    </div>
                </label>
            </div>
            <div v-if="info.schedule_range == '12-3'">
                <label>
                    <div>
                        <p>Mediodía</p>
                        <p><b>12-15</b></p>
                    </div>
                </label>
            </div>
            <div v-if="info.schedule_range == '3-7'">
                <label>
                    <div>
                        <p>Tarde</p>
                        <p><b>15-19</b></p>
                    </div>
                </label>
            </div>
        </td>
        <td style="vertical-align:middle" class="has-text-centered">
           <div v-if="info.schedule_state == '0'" class="buttons has-addons is-centered">
                <span @click="scheduleState(info.id, '2',info.property_id)" class="button is-primary is-small is-outlined">Aceptar</span>
                <span @click="scheduleState(info.id, '1',info.property_id)" class="button is-warning is-small is-outlined">Denegar</span>
            </div>
            <div v-if="info.schedule_state == 2">
                <!--<i v-if="info.schedule_state == 2" class="fa fa-check fa-2x"></i>
                <i v-if="info.schedule_state == 1" class="fa fa-times fa-2x"></i>
                <i v-if="info.schedule_state == 4" class="fa fa-minus fa-2x"></i>-->
                <div class="has-text-centered">
                    <div v-if="info.chat != false" @click="toChat(info.id)"><span class="tooltip" :data-tooltip="'Chatear con ' + info.firstname + ' ' + info.lastname"><img :src="imagesDir+'/contact-chat.png'"/></span></div>
                    <div v-if="info.email != false" @click="copyToClipboard('email')"><span class="tooltip" :data-tooltip="info.email"><img @click="copyToClipboard('email')" :src="imagesDir+'/contact-email.png'"/></span></div>
                    <div v-if="info.phone != false" @click="copyToClipboard('phone')" :class="{'is-hidden': info.phone == false }"><span class="tooltip" :data-tooltip="'(+' + info.phone_code + ')-' + info.phone"><img  @click="copyToClipboard('phone')" :src="imagesDir+'/contact-phone.png'"/></span></div>
                </div>

                <input type="hidden" :value="info.email" :id="'email_contact_'+info.id">
                <input type="hidden" :value="info.phone" :id="'phone_contact_'+info.id">
            </div>
        </td>
    </tr>
    
</template>


<script>
    const _token = document.getElementById('_token').value;
    const imagesDir = document.getElementById('images-dir').value;

    import datasheet from '../profiles/datasheet';
    
    
    export default {
        name: 'Visitor',
        extends : datasheet,
        components: {
        },
        props: {
            info: Object,
        },
        data: function () {
            return {
                imagesDir: imagesDir,
            }
        },
        mounted() {

        },
        methods: {
            scheduleState(id, state ,property){
                const self = this
                axios.put('/schedules', { id: id, schedule_state: state, property: property})
                .then(function(res){
                    console.log(res)
                    self.info.schedule_state = state
                    toastr.success(res.data.message)
                })
                .catch((error) => {
                    console.log(error)
                });
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
            statusStyle(){
                switch(this.info.schedule_state){
                    case '0':
                        return 'waiting_for_acceptance'
                        break;
                    case '1':
                        return 'refused'
                        break;
                    case '2':
                        return 'approved'
                        break;
                    default:
                        return 'Cancelado'
                        break;
                }
            },
            statusName(){
                switch(this.info.schedule_state){
                    case '0':
                        return 'En Espera'
                        break;
                    case '1':
                        return 'Rechazado'
                        break;
                    case '2':
                        return 'Aprobado'
                        break;
                    default:
                        return 'Culminado'
                        break;
                }
            }
        },
        filters: {
        }
    }
</script>

<style>
.waiting_for_acceptance {
    border-bottom: 3px solid hsl(48, 100%, 67%);
}

.approved {
    border-bottom: 3px solid hsl(141, 71%, 48%);
}

.cancelado {
    border-bottom: 3px solid hsl(204, 86%, 53%);
}

.refused
{
    border-bottom: 3px solid hsl(348, 100%, 61%);
}

.option {
    padding: 0.4rem
}

i {
    color:#08b7ff;
}

</style>