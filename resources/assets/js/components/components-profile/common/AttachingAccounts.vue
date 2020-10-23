<template>
    <div class="columns is-inline" style="padding-bottom: 20px;">
        <div  class="column is-12">
            <!-- Main container -->
            <nav class="level" v-if="!info.providers.length">
                <!-- Left side -->
                <div class="level-left">
                    <div class="level-item">
                        <img src="/images/fb_logo.png" style="width: 30px;">
                    </div>
                    <div class="level-item">
                        Facebook
                    </div>
                </div>

                <!-- Right side -->
                <div class="level-right">
                    <button class="button is-primary" :disabled="deshabilitarFacebook" @click="vincularFacebook">Vincular</button>
                </div>
            </nav>
            <!-- Main container -->
            <nav class="level" v-if="info.providers.length">
                <!-- Left side -->
                <div class="level-left">
                    <div class="level-item">
                        <img src="/images/fb_logo.png" style="width: 30px;">
                    </div>
                    <div class="level-item">
                        Facebook
                    </div>
                </div>

                <!-- Right side -->
                <div class="level-right">
                    <button class="button is-primary" disabled >Vinculado</button>
                </div>
            </nav>
        </div>
    </div>
</template>

<script>
    export default {
        components : {

        },
        name: 'AttachingAccounts',
        props: {
            info: Object,
        },
        data() {
            return {
                deshabilitarFacebook: false
            }
        },
        methods : {
            vincularFacebook(){
                this.deshabilitarFacebook = true;
                let direccion = document.URL;
                window.location = '/vincular/facebook?return='+direccion;
            }
        }
    }
</script>