<template>
    <div class="chat-list">
        <contact-component 
            v-for="conversation in conversations"
            :key="conversation.id"
            :conversation="conversation"
            :selected="selectedConversationId === conversation.id"
            @click.native="selectConversation(conversation)">                    
        </contact-component>
    </div>
        
</template>

<script>

    import ContactComponent from './ContactComponent';

    export default {
        components: {
            ContactComponent,
        },
        props: {
            conversations: Array
        },
        data() {
            return {
                selectedConversationId: null
            };
        },
        mounted() {
        },
        methods: {
            selectConversation(conversation) {
                this.selectedConversationId = conversation.id;
                this.$emit('conversationSelected', conversation);
            }
        }
    }
</script>