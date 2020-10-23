<template>
    <div class="media">
        <div :class="selected ? 'media current-chat' : 'media'">
            <div class="media-left">
                <img :src="conversation.contact_photo || imagesDir + '/roles/avatar-uhomie.png'"/>
            </div>
            <div class="media-content">
                <p class="chat-nick">
                    <status-component :online="conversation.online" />
                    <b>{{ conversation.contact_name }}</b>
                </p>
                <p style="font-size: .6em;">{{ lastMessage }}</p>
                <p style="font-size: .6em; text-align: right;">{{ lastTime }}</p>
            </div>
        </div>
    </div>
</template>


<script>

    const imagesDir = document.getElementById('images-dir').value;

    import StatusComponent from './StatusComponent';

    export default {
        components: {
            StatusComponent,
        },
        props: {
            selected: Boolean,
            conversation: Object
        },
        data() {
            return {
                imagesDir: imagesDir
            };
        },
        mounted() {
        },
        computed: {
            lastTime() {
                return moment(this.conversation.last_time, "YYYY-MM-DD hh:mm:ss")
                        .locale('es').fromNow();
            },
            lastMessage() {
                if(this.conversation.last_message && this.conversation.last_message.length > 20)
                    return this.conversation.last_message.substring(0,20)+'...';
                else
                    return this.conversation.last_message;
            }
        }
    }
</script>

<style>

.chat-list .media {
    padding: 0;
}

.chat-list .media .media {
    border-bottom: 1px solid #ddd;
    padding: 10px;
    margin-right: 10px;
    cursor: pointer;
}


.chat-list .media.current-chat{
    border-bottom: none;
    margin-right: 0;
}
</style>

