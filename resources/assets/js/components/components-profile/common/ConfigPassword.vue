<template>
    <div>
        <div class="column" v-if="editing">
            <article class="message is-success is-small">
                <div class="message-body">
                    La contraseña debe de cumplir con las siguientes especificaciones:
                    <ol style="margin-left: 12px">
                        <li>Poseer mas de 8 caracteres</li>
                        <li>Poseer caracteres numericos</li>
                        <li>Poseer mayusculas y minusculas</li>
                        <li>Poseer caracteres especiales (!$#%)</li>
                    </ol>
                </div>
            </article>
        </div>

        <div class="column">
            <div class="field">
                <label class="label">Contraseña Nueva:</label>
                <div class="control">
                    <input class="input" type="password" v-model="password" @input="changePassword" placeholder="Nueva" :disabled="!editing">
                </div>
            </div>
        </div>

        <div class="column">
            <div class="field">
                <label class="label">Confirmar Contraseña:</label>
                <div class="control">
                    <input class="input" type="password" placeholder="Confirmar" @input="changePassword" v-model="confir_password" :disabled="!editing">
                </div>
            </div>
        </div>

        
    </div>
</template>
<script>
export default {
    name: 'ConfigPassword',
    props: {
        editing: {
            type: Boolean,
            default: true
        },
        info: Object
    },
    data() {
        return {
            confir_password: '',
            password: '',
        }
    },
    methods: {
        changePassword(){
            this.info.password = this.password;
            this.info.confir_password = this.confir_password
        }
    }
    
}
</script>
