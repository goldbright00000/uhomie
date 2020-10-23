<template>
    <div class="columns is-inline" style="padding-bottom: 20px;">

        <div class="column">
            <div class="field">
                <label class="label">Cambiar Numero de Teléfono:</label>
                <div class="control">
                    <vue-tel-input style="max-width: 250px;" placeholder="9XXXXXXXX" :max-len="largo_chile" inputClasses="input noborder" @input="onInputPhone" v-model="info.phone" :enabledCountryCode="false" :disabledFormatting="true" :default-country="getIso2(info.phone_code)" :disabled="!editing"></vue-tel-input>
                    <input type="hidden" v-model="info.phone_code" :disabled="!editing"/>
                    <input type="hidden" v-model="info.phone_verified" :disabled="!editing"/>
                </div>
            </div>
        </div>

        <div class="column">
            <div class="field">
                <label class="label">Cambiar Correo Electronico:</label>
                <div class="control">
                    <input class="input" type="text" placeholder="Email" v-model="info.email" @input="clickEmail" :disabled="!editing">
                    <input type="hidden" v-model="info.mail_verified" :disabled="!editing"/>
                </div>
            </div>
        </div>


    </div>
</template>

<script>

    import { VueTelInput } from 'vue-tel-input'
    //import 'vue-tel-input/dist/vue-tel-input.css';
    import AllCountries from '../../../profiles/AllCountries.js';

    export default {

        name: 'ConfigAccount',
        props: {
            editing: Boolean,
            info: Object,
        },
        components: {
            VueTelInput,
        },
        data() {
            return {
                countries: AllCountries,
                largo_chile: 9
            }
        },
        mounted(){
            
        },
        methods: {
            getIso2: function(w) {
                var nfo=this.countries;
                for(var t in nfo){
                    if (nfo[t].dialCode==w) {
                        return nfo[t].iso2.toLowerCase();
                    }
                }
                return {};
            },
            onInputPhone(number, objeto) {
                this.info.phone_code = objeto.country.dialCode
                this.info.phone_verified = 2
            },
            clickEmail(){
                this.info.mail_verified = 2
            }
        }
    }
</script>
<style>
.vue-tel-input[data-v-656744fc]{
    border: 0;
    border-radius: 0;
}
</style>
