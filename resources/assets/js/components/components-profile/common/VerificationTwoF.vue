<template>
    <div class="overlay" :class="{'hidden': !open}">
        <div class="verification-box" v-show="!sent">
            <div class="has-text-centered">
                <img :src="imagesDir+'/foco.png'">
            </div>
            <div style="background-color: white;padding:10px;width: 400px; text-align: center">
                <h4>Atención!</h4>
                <p>Para realizar este cambio es necesario que ingreses una clave de verificacion que te enviaremos por alguno de estos medios:</p>
                <div style="display: flex;
    justify-content: space-around;">
                    <div>
                        <label for="email_verification" class="radio">
                            <input v-model="method" id="email_verification" type="radio" value="mail" checked>
                            Mail
                        </label>
                    </div>
                    <div>
                        <label for="phone_verification" class="radio">
                            <input v-model="method" id="phone_verification" type="radio" value="phone">
                            Telefono
                        </label>
                    </div>
                </div>
            </div>
            <div style="padding: 5px 0; display: flex; justify-content: space-between;">
                <button class="button is-danger is-inverted is-outlined" @click="$emit('back')">VOLVER</button>
                <button :class="loading ? 'button is-primary is-inverted is-outlined is-loading' : 'button is-primary is-inverted is-outlined'" v-on:click="loading = true" @click="sendVerificationCode">CONTINUAR</button>
            </div>
        </div>
        <div class="verification-box" v-show="sent">
            <div class="has-text-centered">
                <img :src="imagesDir+'/foco.png'">
            </div>
            <div style="background-color: white;padding:10px;width: 400px; text-align: center">
                <p>Ingresa el codigo de validación que recibiste</p>
                
                <div id="token-code1"></div>
                <input ref="token" type="hidden" name="token">
            </div>
            <div style="padding: 5px 0; display: flex; justify-content: space-between;">
                <button class="button is-danger is-inverted is-outlined"  @click="sent = false">VOLVER</button>
                <button :class="loading ? 'button is-primary is-inverted is-outlined is-loading' : 'button is-primary is-inverted is-outlined'" v-on:click="loading = true" @click="verifyCode">CONTINUAR</button>
            </div>
        </div>
    </div>
</template>

<script>

require('jquery-pinlogin/src/jquery.pinlogin');
const imagesDir = document.getElementById('images-dir').value;

export default {
    name: "VerificationTwoF",
    props: {
        open: {
            default: false,
            type: Boolean,
        }
    },
    data () {
        return {
            method: 'phone',
            sent: false,
            byMail: false,
            loading: false,
            imagesDir: imagesDir
        }
    },
    mounted () {
        $('#token-code1').pinlogin({
            fields: 7,
            hideinput: false,
            reset: false,
            complete: function(pin) {
                $('input[name=token]').val(pin);
            }
        });
    },
    methods: {
        sendVerificationCode: function() {
            var url = '';
            if (this.method == 'phone') url = '2f-sms-start';
            if (this.method == 'mail'){
                url = 'tercera-clave-mail';
                this.byMail = true;
            } 

            axios.post('/users/'+ url).then((result) => {
                console.log(result)
                if(result.data.success) this.sent = true
                this.loading = false
            }).catch((err) => {
                this.sent = false
                this.loading = false
            });
        },
        verifyCode: function() {
            var token = this.$refs.token.value;

            if (token.length) {
                if(this.byMail){
                    axios.post('/users/verificar-tercera-clave', { token: token }).then((result) => {
                    console.log(result)
                    if(result.data.success != "Token verification failed."){
                        this.$emit('verify')
                        this.byMail = false;
                        this.loading = false;
                    } else {
                        this.sent = false;
                        this.open = false;
                        this.byMail = false;
                        this.loading = false;
                        this.$emit('back')
                        toastr['error']('El código ingresado es incorrecto');
                    }
                    }).catch((err) => {
                        console.log(err)
                    });
                } else {
                    axios.post('/users/2f-verify-token', { token: token }).then((result) => {
                        console.log(result)
                        if(result.data.success != "Token verification failed."){
                            this.$emit('verify')
                        } else {
                            this.sent = false;
                            this.open = false;
                            this.$emit('back')
                            toastr['error']('El código ingresado es incorrecto');
                        }
                    }).catch((err) => {
                        console.log(err)
                    });
                }
                    

            }
        }
    }
}
</script>

<style>
    .overlay {
        position: fixed;
        z-index: 999999999999999999999;
        width: 100%;
        height: 100vh;
        top: 0;
        left: 0;
        background-color: rgba(2, 2, 2, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .overlay.hidden {
        display: none;
    }

    .pinlogin {
        margin: 40px 10px
    }

    .pinlogin input {
        width: 30px;
        height: 30px;
        margin: 5px;
        text-align: center;
        font-size: 18px;
    }
</style>
