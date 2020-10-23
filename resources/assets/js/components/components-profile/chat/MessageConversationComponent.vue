<template>
    <li :class="writtenByMe ? 'self level-right' : 'self level-left'">
        <div :class="writtenByMe? 'at-right':'at-left'">
            <div class="media" style="padding-top: 5px; padding-bottom: 5px;">
                <div v-if="!writtenByMe" class="media-left'">
                    <img class="avatar" :src="message.from || '/images/roles/avatar-uhomie.png'"/></div>
                <div class="media-content" :style="writtenByMe ? 'background-color:#E9E9E9;' : ''">
                {{ message.content }}
                    <p style="font-size: .7em; text-align: right;">
                        {{ created }}
                    </p>
                </div>
                <div v-if="writtenByMe" class="media-right'">
                    <img class="avatar" :src="message.from || '/images/roles/avatar-uhomie.png'"/></div>
            </div>
        </div>
    </li>
</template>

<script>
const imagesDir = document.getElementById('images-dir').value;
    export default {
        props: {
            writtenByMe: Boolean,
            message: Object
        },
        data() {
            return {
                
            };
        },
        mounted() {
        },
        computed: {
            created() {
                return moment(this.message.created_at, "YYYY-MM-DD hh:mm:ss")
                        .locale('es').calendar();
            },
        }
    }
</script>
