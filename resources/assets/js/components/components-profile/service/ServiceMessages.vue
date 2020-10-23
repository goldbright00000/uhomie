<template>
    <div class="service-messages">
        <div class="columns is-multiline">
            <div class="column is-12">
                <div class="chat-list">
                    <div v-for="(chat,k) in chats" @click="clickOnChatList(k)">
                        <div :class="['media', (k==currentChat)?'current-chat':'']">
                            <div class="media-left">
                                <img :src="chat.image"/>
                            </div>
                            <div class="media-content">
                                <p class="chat-nick"><b>{{ chat.name }}</b></p>
                                <p :time="chat.time">Hoy</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="column is-12 chat-content" v-for="cont,i in chats" :class="(i==currentChat)?'':'is-hidden'">
                <div :class="(k%2==0)?'at-left':'at-right'" v-for="line,k in cont.chat">
                    <div class="media">
                        <div v-if="k%2==0" class="media-left'"><img class="avatar" :src="line.avatar"/></div>
                        <div class="media-content"> {{ line.text }}</div>
                        <div v-if="k%2==1" class="media-right'"><img class="avatar" :src="line.avatar"/></div>
                    </div>
                </div>
            </div>

            <div class="column is-12 chat-input">
                <div class="columns">
                    <div class="column is-10 control">
                        <input class="input"/>
                    </div>
                    <div class="column is-2">
                        <button class="button is-outlined is-primary" >Enviar</button>
                    </div>
                </div>
            </div>


        </div>
    </div>
</template>

<script>


    const imagesDir = document.getElementById('images-dir').value;
    var time = new Date();
    var now = time.getTime();

    export default {
        components: {
            
        },
        name: 'ServiceMessages',
        props: {},
        data() {
            return {

                chats: [
                    {
                        image: imagesDir + '/avatars/user01.jpg',
                        name: 'Marcos Luis',
                        time: now,
                        chat: [
                            {
                                avatar: imagesDir + '/avatars/user02.jpg',
                                time: '08:21',
                                user: 'yo',
                                text: 'Puedes?'
                            },
                            {
                                avatar: imagesDir + '/avatars/user01.jpg',
                                time: '08:22',
                                user: 'tu',
                                text: 'Si, claro que puedo?'
                            },
                            {
                                avatar: imagesDir + '/avatars/user01.jpg',
                                time: '08:22',
                                user: 'tu',
                                text: 'Cómo te sientes?'
                            },
                            {
                                avatar: imagesDir + '/avatars/user01.jpg',
                                time: '08:22',
                                user: 'tu',
                                text: 'Excelente...'
                            },

                        ]
                    },
                    {
                        image: imagesDir + '/avatars/user02.jpg',
                        name: 'Raul',
                        time: now,
                        chat: [
                            {
                                avatar: imagesDir + '/avatars/user01.jpg',
                                time: '08:21',
                                user: 'yo',
                                text: 'Esta es otra conversacion'
                            },
                            {
                                avatar: imagesDir + '/avatars/user03.jpg',
                                time: '08:22',
                                user: 'tu',
                                text: 'Barbaro...'
                            },
                            {
                                avatar: imagesDir + '/avatars/user04.jpg',
                                time: '08:22',
                                user: 'tu',
                                text: 'Falta menos...'
                            },
                            {
                                avatar: imagesDir + '/avatars/user05.jpg',
                                time: '08:22',
                                user: 'tu',
                                text: 'Estamos cerca...'
                            },

                        ]
                    }
                ],

                currentChat: 0,
                imagesDir: imagesDir,
                now: now,


            }
        },
        methods: {
            clickOnChatList: function (w) {
                this.currentChat = w;
            }
        }
    }
</script>
