<template>
    <div class="columns is-inline" style="padding-bottom: 20px;">

        <div class="column">
            <div class="field">
                <span class="label">Verificacion de correo:</span>
                <div class="control">
                   <!--<checkstatus @verifynow="verifynow('email')" :checked="info.mail_verified=='1'" :disabled="!editing"></checkstatus>-->
                   <div v-if="info.mail_verified=='1'" class="check-status">
                        <img :src="imagesDir+'/icono-ok.png'">
                        <i>Verificado</i>
                    </div>

                    <button v-else :class="email ? 'button is-outlined is-basic is-loading' : 'button is-outlined is-basic'" v-on:click="openV('email')">Verificar</button>
               </div>
            </div>
        </div>
        <div class="column">
            <div class="field">
                <span class="label">Verificacion de teléfono:</span>
                <div class="control">
                    <div v-if="info.phone_verified=='1'" class="check-status">
                        <img :src="imagesDir+'/icono-ok.png'">
                        <i>Verificado</i>
                    </div>

                    <button v-else :class="phone ? 'button is-outlined is-basic is-loading' : 'button is-outlined is-basic'" v-on:click="openV('phone')">Verificar</button>
                </div>
            </div>
        </div>

        <!--<div class="column">
            <div class="field">
                <span class="label">Pago Paypal:</span>
                <div class="control">
                    <checkstatus @verifynow="verifynow('payment')" :checked="false" :disabled="!editing"></checkstatus>
                </div>
            </div>
        </div>-->

        <div class="column">
            <div class="field">
                <span class="label">Verificación de Identidad Digital:</span>
                <div class="control">
                    <!--<checkstatus @verifynow="verifynow('identity')" :checked="false" :disabled="!editing"></checkstatus>
                    <checkstatus @verifynow="verifynow('identity')" :checked="info.documents.id_front!=null && info.documents.id_back!=null" :disabled="!editing"></checkstatus>-->
                    <IdentityVerification></IdentityVerification>
                    <span  style="align:left;font-size:10px;font-weigth:lighter;font-style:italic;">{{info.verifyng_attempts}} intentos restantes</span>
                </div>
            </div>
        </div>

        <div class="overlay" :class="{'hidden': !open}">
            <div class="verification-box" v-show="sent">
                <div>icono</div>
                <div style="background-color: white;padding:10px;width: 400px; text-align: center">
                    <p>Ingresa el codigo de validación que recibiste por {{message}}</p>
                    
                    <div id="token-code"></div>
                    <input ref="token" type="hidden" name="token">
                </div>
                <div style="padding: 5px 0; display: flex; justify-content: space-between;">
                    <button class="button is-danger is-inverted is-outlined" @click="vClose()">VOLVER</button>
                    <button :class="loading ? 'button is-primary is-inverted is-outlined is-loading' : 'button is-primary is-inverted is-outlined'" @click="verifyCode">CONTINUAR</button>
                </div>
            </div>
        </div>

    </div>
</template>

<script>

    require('jquery-pinlogin/src/jquery.pinlogin');
    const imagesDir = document.getElementById('images-dir').value;

    import Checkstatus from '../../Checkstatus';
    import IdentityVerification from '../../IdentityVerification';
    export default {
        components : {
            Checkstatus,
            IdentityVerification,
        },
        name: 'ConfigVerifications',
        props: {
            editing: Boolean,
            info: Object,
        },
        data() {
            return {
                method: 'phone',
                sent: false,
                byMail: false,
                imagesDir: imagesDir,
                open: false,
                verify: String,
                email: false,
                phone: false,
                loading: false,
                message: false
            }
        },
        methods : {
            openV: function(w) {
                
                console.log(w)
                var url = '';
                if (w == 'phone'){
                    this.phone = true
                    this.message = 'Telefono'
                    url = '2f-sms-start';
                    this.verify = w
                } 
                if (w == 'email'){
                    this.email = true
                    this.message = 'Correo'
                    url = 'tercera-clave-mail'
                    this.byMail = true
                    this.verify = w
                } 

                axios.post('/users/'+ url).then((result) => {
                    console.log(result)
                    if(result.data.success){
                        this.email = false
                        this.phone = false
                        this.open = true
                        this.sent = true
                    } 
                }).catch((err) => {
                    this.sent = false
                });
            },
            verifyCode: function() {
                var token = this.$refs.token.value;
                this.loading = true
                if (token.length) {
                    if(this.byMail){
                        axios.post('/users/verificar-tercera-clave', { token: token, verify: this.verify }).then((result) => {
                        console.log(result)
                        if(result.data.success != "Token verification failed."){
                            this.byMail = false
                            this.sent = false
                            this.open = false
                            this.loading = false
                            this.info.mail_verified = 1
                            toastr['success']('Se ha validado el Correo Electronico Exitosamente');
                        } else {
                            this.sent = false
                            this.open = false
                            this.byMail = false
                            this.loading = false
                            toastr['error']('El código ingresado es incorrecto');
                        }
                        }).catch((err) => {
                            console.log(err)
                        });
                    } else {
                        axios.post('/users/2f-verify-token', { token: token, verify: w }).then((result) => {
                            console.log(result)
                            if(result.data.success != "Token verification failed."){
                                this.sent = false
                                this.open = false
                                this.loading = false
                                this.info.phone_verified = 1
                                toastr['success']('Se ha validado el Numero Telefonico Exitosamente');
                            } else {
                                this.sent = false
                                this.open = false
                                this.loading = false
                                toastr['error']('El código ingresado es incorrecto');
                            }
                        }).catch((err) => {
                            console.log(err)
                        });
                    }
                        

                }
            },
            vClose(){
                this.send = false
                this.open = false
            }
        },
        mounted () {
            $('#token-code').pinlogin({
                fields: 7,
                hideinput: false,
                reset: false,
                complete: function(pin) {
                    $('input[name=token]').val(pin);
                }
            });
        },
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