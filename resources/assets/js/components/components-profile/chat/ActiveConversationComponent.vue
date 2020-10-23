<template>
    <div>
       <ul style="margin: 0px; padding: 0 px;" class="card-body-scroll">
            <message-conversation-component 
                v-for="message in messages" :key="message.id"
                :written-by-me="message.written_by_me"
                :message="message">
            </message-conversation-component>
        </ul>

        <form @submit.prevent="postMessage" autocomplete="off">
            <div class="column is-12 chat-input" >
                <div class="columns">
                    <div class="column is-10 control">
                        <input class="input" v-model="newMessage"
                            placeholder="Escribe un mensaje ...">
                        </input>
                    </div>
                    <div class="column is-2">
                        <button type="submit" class="button is-outlined is-primary" >Enviar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

</template>

<style> 
    .card-body-scroll {
        /*min-height: calc(100vh - 380px);
        max-height: calc(100vh - 380px);*/
        height: calc(100vh - 380px);
        overflow-y: auto;
    }
</style>

<script>

    import MessageConversationComponent from './MessageConversationComponent';

    export default {
        components: {
                    MessageConversationComponent
                },
        props: {
            contactId: Number,
            // contactName: String,
            // photo: String,
            messages: Array,
        },
        data() {
            return {
                newMessage: ''
            };
        },
        mounted() {

        },
        methods: {
            postMessage() {
                const params = {
                    to_id: this.contactId,
                    content: this.newMessage
                };
                axios.post('/chat/messages', params)
                .then((response) => {
                    if (response.data.success) {
                        this.newMessage = '';
                        const message = response.data.message;
                        message.written_by_me = true;
                        this.$emit('messageCreated', message);
                    }
                });
            },
            scrollToBottom() {
                const el = document.querySelector('.card-body-scroll');
                el.scrollTop = el.scrollHeight;
            }
        },
        updated() {
            this.scrollToBottom();
        }
    }
</script>
