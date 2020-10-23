<template>
    <div>
        <div :class="verify ? 'modal is-active' : 'modal'">
            <div class="modal-background" v-on:click="close()"></div>
            <div class="modal-card">
                <header class="modal-card-head">
                    <p class="modal-card-title">Propiedad Id: {{property.id}}</p>
                    <button class="delete" aria-label="close" v-on:click="close()"></button>
                </header>
                <section class="modal-card-body">
                    <div class="columns has-text-centered" v-if="error || success">
                        <div class="column">
                            <div v-if="success">
                                <div><i class="fa fa-check fa-5x has-text-success"></i></div>
                                <div>Proceso de validación terminado</div>
                            </div>
                            <div v-if="error">
                                <div><i class="fa fa-times fa-5x has-text-danger"></i></div>
                                <div>No se ha logrado validar la propiedad<b>{{property.name}}</b> con dirección <b>{{property.address != null ? property.address : ''}} {{property.address_details != null ? property.address_details : ''}}</b></div>
                            </div>
                            <div v-if="!error">
                                <article :class="c_alert == 200 ? 'message is-success is-small' : 'message is-danger is-small'">
                                    <div class="message-body">
                                        {{cert_message}}
                                    </div>
                                </article>
                                <article :class="e_alert == 200 ? 'message is-success is-small' : 'message is-danger is-small'">
                                    <div class="message-body">
                                        {{elect_message}}
                                    </div>
                                </article>
                                <article :class="w_alert == 200 ? 'message is-success is-small' : 'message is-danger is-small'">
                                    <div class="message-body">
                                        {{water_message}}
                                    </div>
                                </article>
                            </div>
                        </div>
                    </div>
                    <div class="columns is-vcentered is-mobile" v-if="message">
                        <div class="column is-2">
                            <div class="has-text-centered">
                                <img :src="imagesDir + '/ok.png'">
                            </div>
                        </div>
                        <div class="column">
                            <p>¿Al verificar el documento de propiedad de <b>{{property.name}}</b> con dirección <b>{{property.address != null ? property.address : ''}} {{property.address_details != null ? property.address_details : ''}}</b> su propiedad podra subir de scoring y se le dara la calificación de <b>Propiedad Verificada</b>?</p>
                        </div>
                    </div>
                </section>
                <footer class="modal-card-foot">
                    <button v-if="button" :class="loading ? 'button is-primary is-loading' : 'button is-primary'" v-on:click="loading = true" @click="verifyDoc()">Verificar</button>
                    <button class="button" @click="close()">Cerrar</button>
                </footer>
            </div>
        </div>
    </div>
</template>

<script>
import Axios from 'axios';
const imagesDir = document.getElementById('images-dir').value;
export default {
    name: 'VerifyDocsProperty',
    computed:{
        verify(){
            return this.$parent.verify;
        },
        certificade: function(){
            return this.property.files.filter(property => property.name == 'property_certificate')[0];
        },
        last_electricity_bill(){
            return this.property.files.filter(property => property.name == 'last_electricity_bill')[0];
        },
        last_water_bill(){
            return this.property.files.filter(property => property.name == 'last_water_bill')[0];
        }
    },
    props: {
        property: Object,
    },
    data() {
        return {
            imagesDir: imagesDir,
            loading: false,
            success: false,
            error: false,
            message: true,
            button: true,
            water_message: '',
            elect_message: '',
            cert_message: '',
            c_alert: '',
            e_alert: '',
            w_alert: ''
        }
    },
    methods:{
        close(){
            this.$emit('close', {'value': false});
            this.loading = false;
            this.success = false;
            this.error = false;
            this.message = true;
            this.button = true;
        },
        verifyDoc(){
            Axios.get('/verified-cbrs/'+this.property.id)
            .then(response => {
                console.log(response);
                this.loading = false;
                this.message = false;
                this.success = true;
                this.button = false;
                
                this.certificade.verified = response.data.certificade;
                this.last_electricity_bill = response.data.last_electricity_bill;
                this.last_water_bill = response.data.last_water_bill;
                this.water_message = response.data.water_message;
                this.elect_message = response.data.elect_message;
                this.cert_message = response.data.cert_message;
                this.c_alert = response.data.c_alert;
                this.e_alert = response.data.e_alert;
                this.w_alert = response.data.w_alert;
                this.property.verified = response.data.p_verified;
                this.$emit('verified', {'id' : this.property.id, 'p_verified' : response.data.p_verified});
            })
            .catch(response => {
                console.log(response);
                /*this.loading = false;
                this.message = false;
                this.button = false;
                this.error = true;
                //this.certificade.verified = '3';
                this.certificade.verified = response.data.certificade;
                this.last_electricity_bill = response.data.last_electricity_bill;
                this.last_water_bill = response.data.last_electricity_bill;
                
                this.water_message = response.data.water_message;
                this.elect_message = response.data.elect_message;
                this.cert_message = response.data.cert_message;
                this.c_alert = response.data.c_alert;
                this.e_alert = response.data.e_alert;
                this.w_alert = response.data.w_alert;
                this.property.verified = response.data.p_verified;
                this.$emit('verified', {'id' : this.property.id, 'p_verified' : response.data.p_verified});*/
            });
        }
    }
}
</script>
