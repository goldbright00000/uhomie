<template>
    <div class="tenant-messages">
        <div class="columns is-multiline" v-if="conversations.length">

            <div class="column is-12">
                <div>
                    <input class="input"
                        v-model="querySearch"
                        type="text"
                        placeholder="Buscar contacto...">
                    
                </div>
            </div>

            <div class="column is-12">
                <div>
                    <contact-list-component 
                        @conversationSelected="changeActiveConversation($event)"
                        :conversations="conversationsFiltered">
                    </contact-list-component>
                </div>
            </div>

            <div class="column is-12 chat-content">
                <active-conversation-component
                    v-if="selectedConversation"
                    :contact-id="parseInt(selectedConversation.contact_id)"
                    :contact-name="selectedConversation.contact_name"
                    :messages="messages"
                    @messageCreated="addMessage($event)">
                </active-conversation-component>
            </div>

        </div>
        <div class="columns is-multiline" v-else>
            <div class="column is-12">
                <div>
                    <p style="text-align: center">
                        <br>
                        No existen contactos ni conversaciones por el momento...
                        <br>
                        <br>
                        <img src="/images/errors/marca-de-agua.png" alt="uHomie">
                        <br>
                        <br>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    import ContactListComponent from './ContactListComponent';
    import ActiveConversationComponent from './ActiveConversationComponent';

    export default {
        components: {
            ContactListComponent,
            ActiveConversationComponent,
        },
        props: {
            userId: Number,
            roleId: Number
        },
        data() {
            return {
                selectedConversation: null,
                messages: [],
                conversations: [],
                querySearch: ''
            };
        },
        mounted() {
            this.getConversations();

            Echo.private(`users.${this.userId}`)
            .listen('MessageSent', (data) => {
                const message = data.message;
                message.written_by_me = false;        
                this.addMessage(message);
            });

            Echo.join('messenger')
            .here((users) => {
                users.forEach(user => this.changeStatus(user, true));
            })
            .joining(
                user => this.changeStatus(user, true)
            )
            .leaving(
                user => this.changeStatus(user, false)  
            );
        },
        methods: {
            selectConversation(conversation) {
                this.$emit('conversationSelected', conversation);
            },
            changeActiveConversation(conversation) {
                this.selectedConversation = conversation;
                this.getMessages();
            },
            getMessages() {
                axios.get(`/chat/messages?contact_id=${this.selectedConversation.contact_id}`)
                .then((response) => {
                    this.messages = response.data;
                });
            },
            addMessage(message) {
                const conversation = this.conversations.find((conversation) => {
                    return conversation.contact_id == message.from_id || 
                        conversation.contact_id == message.to_id;
                });

                const author = this.userId === message.from_id ? 'Tú' : conversation.contact_name.split(' ')[0];
                
                conversation.last_message = `${author}: ${message.content}`;
                conversation.last_time = message.created_at;

                if (this.selectedConversation.contact_id == message.from_id
                    || this.selectedConversation.contact_id == message.to_id)
                    this.messages.push(message);
            },
            getConversations() {
                axios.get('/chat/conversations')
                .then((response) => {
                    this.conversations = response.data;
                });
            },
            changeStatus(user, status) {
                const index = this.conversations.findIndex((conversation) => {
                    return conversation.contact_id == user.id;
                });
                if (index >= 0)
                    this.$set(this.conversations[index], 'online', status);
            }
        },
        computed: {
            conversationsFiltered() {
                return this.conversations.filter(
                    (conversation) => 
                        conversation.contact_name
                            .toLowerCase()
                            .includes(this.querySearch.toLowerCase())
                );
            }
        }
    }
</script>
