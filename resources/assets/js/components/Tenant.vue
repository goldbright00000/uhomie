<template>
    <tr class="tenant">
        <td style="vertical-align:middle">
            <div class="media">
                <div class="media-left">
                    <img :src="info.photo || imagesDir + '/roles/avatar-uhomie.png'" style="width:34px;height:34px; border-radius: 50%;"/>
                </div>
                <div class="media-content">
                    <div class="content">
                        <div> {{ info.firstname }} </div>
                        <div> {{ info.lastname }} </div>
                    </div>
                </div>
            </div>
        </td>
        <td class="has-text-centered" style="vertical-align:middle">
            <img :src="imagesDir+'/explore-details/'+info.membership_data.name.toLowerCase()+'.png'"/>
        </td>
        <td style="vertical-align:middle" class="has-text-centered">
            <div class="has-text-centered">
                <div><span class="tooltip" :data-tooltip="'Chatear con ' + info.firstname + ' ' + info.lastname"><img :src="imagesDir+'/contact-chat.png'"/></span></div>
                <div><span class="tooltip" :data-tooltip="info.email"><img @click="copyToClipboard('email')" :src="imagesDir+'/contact-email.png'"/></span></div>
                <div><span class="tooltip" :data-tooltip="'(+' + info.phone_code + ')-' + info.phone"><img  @click="copyToClipboard('phone')" :src="imagesDir+'/contact-phone.png'"/></span></div>
            </div>

            <input type="hidden" :value="info.email" :id="'email_contact_'+info.id">
            <input type="hidden" :value="info.phone" :id="'phone_contact_'+info.id">
        </td>
        <td style="vertical-align:middle" class="has-text-centered">
            <img @click="$router.push('/tenant/' + info.id)" :src="imagesDir+'/icono_lupa.png'" style="cursor: pointer;"/>
        </td>
    </tr>
    
</template>


<script>
    const _token = document.getElementById('_token').value;
    const imagesDir = document.getElementById('images-dir').value;
    import datasheet from '../profiles/datasheet';
    
    export default {
        name: 'Tenant',
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
            copyToClipboard: function(i) {
                console.log(i+'_contact_'+this.info.id)
                var copyText = document.getElementById(i+'_contact_'+this.info.id)
                copyText.setAttribute('type', 'text')
                copyText.select()
                document.execCommand("copy")
                copyText.setAttribute('type', 'hidden')
                alert(copyText.value)
            },
            toChat: function() {
                const self = this
                axios.post('/users/tenant/conversation', { contact_id: this.info.owner.id }).then(function(res){
                    self.$router.push('/messages')                    
                })
            },
        },
        computed: {
            
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
