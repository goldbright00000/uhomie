<template>
    <div class="modal is-active" v-show="contact">
        <div class="modal-background" v-on:click="closeToContact"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">Contactar</p>
                <button class="delete" aria-label="close" v-on:click="closeToContact"></button>
            </header>
            <section class="modal-card-body">
                <div v-if="tenant">
                    <article class="message">
                        <div class="message-body">
                            <div class="columns">
                                <div class="column is-two-thirds"><p>Actualmente posees una membresia del tipo <b>{{tenant.membership_name}}</b> el cual te permitira realizar las siguientes acciones</p></div>
                                <div class="column has-text-centered"><img :src="imagesDir + membershipOwnerLogo(tenant.membership_name)"></div>
                            </div>
                        </div>
                    </article>
                    <article class="message is-primary is-small">
                        <div class="message-body">
                            Puedes chatear con el dueño <strong>{{owner.firstname + ' ' + owner.lastname}}</strong>, a traves del siguiente <a v-on:click="toChat(owner.id)"><strong>Enlace</strong></a>.
                        </div>
                    </article>
                    <article class="message is-primary is-small" v-if="owner.email">
                        <div class="message-body">
                            Puedes enviarle un correo electronico a travez de la siguiente dirección <strong>{{owner.email}}</strong>.
                        </div>
                    </article>
                    <article class="message is-primary is-small" v-if="owner.phone">
                        <div class="message-body">
                            Puedes hablar con el dueño a travez del siguiente numero telefonico <strong>+{{owner.phone_code + '-' + owner.phone}}</strong>.
                        </div>
                    </article>
                    <article class="message is-warning is-small" v-if="!owner.email && !owner.phone">
                        <div class="message-body">
                            Para conocer datos como el correo electonico o el telefono deberas mejorar tu membresia a traves del siguiente enlace <a href="/users/tenant/memberships-update"><strong>Enlace</strong></a>.
                        </div>
                    </article>
                    <article class="message is-warning is-small" v-else-if="!owner.phone">
                        <div class="message-body">
                            Para conocer el dato del telefono deberas mejorar tu membresia a traves del siguiente enlace <a href="/users/tenant/memberships-update"><strong>Enlace</strong></a>.
                        </div>
                    </article>
                </div>
                <div v-else>
                    <article class="message is-danger">
                        <div class="message-body">
                           Usted debe ingresar como un usuario arrendatario para ver los datos de contacto.
                        </div>
                    </article>
                </div>
            </section>
            <footer class="modal-card-foot">
                <button class="button" v-on:click="closeToContact">Cerrar</button>
            </footer>
        </div>
    </div>
</template>

<script>
const imagesDir = document.getElementById('images-dir').value;
export default {
    name: 'contact',
    props: {
        contact: Boolean,
        owner: Object,
        tenant: Object
    },
    data(){
        return {
            imagesDir: imagesDir,
        }
    },
    methods:{
        closeToContact: function(){
            this.$parent.contact = false
        },
        membershipOwnerLogo: function(name) {
            switch (name) {
                case 'Basic':{
                    return '/explore-details/basic.png';}
                case 'Select':{
                    return '/explore-details/select.png';}
                case 'Premium':{
                    return '/explore-details/premium.png';}
            
                default:{
                    return '/explore-details/basic.png';}
            }
        },
        toChat: function(id) {
            const self = this
            axios.post('/users/tenant/conversation', { contact_id: id }).then(function(res){
                window.location = '/users/profile/tenant#/messages'                    
            })
        },
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
</style>