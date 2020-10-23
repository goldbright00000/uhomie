<template>
    <div class="columns is-inline" style="padding-bottom: 20px;" v-if="info">
        <div class="column">
            <div class="field">
                <label class="label">¿Necesitas Factura?</label>
                <div class="control">
                    <label class="radio">
                        <input type="radio" v-model="info.invoice" value="1" :checked="info.visit"  :disabled="!editing" required>
                        Si
                    </label>
                    <label class="radio">
                        <input type="radio" v-model="info.invoice" value="0" :checked="!info.visit" :disabled="!editing" required>
                        No
                    </label>
                </div>
            </div>
        </div>
        <div class="column">
            <div class="field">
                <label class="label">Razón Social de la Empresa</label>
                <div class="control">
                    <input class="input" type="text" v-model="info.name" placeholder="Nombre de la Empresa" :disabled="!editing">
                </div>
            </div>
        </div>
        <div class="column">
            <div class="field">
                <label class="label">Giro</label>
                <div class="control">
                    <input class="input" type="text" v-model="info.giro" placeholder="Giro" :disabled="!editing">
                </div>
            </div>
        </div>
        <div class="column">
            <div class="field">
                <label class="label">RUT</label>
                <div class="control">
                    <input class="input" type="text" v-model="info.rut" placeholder="RUT" :disabled="!editing">
                </div>
            </div>
        </div>
        <div class="column">
            <div class="field">
                <label class="label">Telefono fijo <small>(Opcional)</small></label>
                <div class="control">
                    <input class="input" type="text" v-model="info.phone" placeholder="Telefono" :disabled="!editing">
                </div>
            </div>
        </div>
        <div class="column">
            <div class="field">
                <label class="label">Celular <small>(Opcional)</small></label>
                <div class="control">
                    <input class="input" type="text" v-model="info.cell_phone" placeholder="Celular" :disabled="!editing">
                </div>
            </div>
        </div>
        <div class="column">
            <div class="field">
                <label class="label">Email</label>
                <div class="control">
                    <input class="input" type="text" v-model="info.email" placeholder="Correo Electronico" :disabled="!editing">
                </div>
            </div>
        </div>
        <div class="column">
            <div class="field">
                <label class="label">Pagina Web</label>
                <div class="control">
                    <input class="input" type="text" v-model="info.website" placeholder="Pagina Web" :disabled="!editing">
                </div>
            </div>
        </div>
        <div class="column">
            <div class="field">
                <label class="label">Logo</label>
                <div class="control">
                    <logo-photo v-bind:info="info" v-bind:logo="logo" v-bind:save_logo="save_logo" v-bind:get_logo="get_logo" v-bind:del_logo="del_logo"></logo-photo>
                </div>
            </div>
        </div>
        <div class="column">
            <div class="field">
                <label class="label">Descripción</label>
                <div class="control">
                    <textarea rows="6" style="width: 100%;padding: 6px;" v-model="info.description" :disabled="!editing"></textarea>
                </div>
            </div>
        </div>
    </div>
</template>
<script>

import LogoPhoto from './LogoPhoto';

export default {
    name: 'MyformCompany',
    props: {
        info: Object,
        logo: Array,
        editing: {
            type: Boolean,
            default: false
        },
        del_logo: String,
        get_logo: String,
        save_logo: String
    },
    components: {
        LogoPhoto
    },
    data() {
        return {
        }
    },
    methods: {
       
    },
}
</script>
<style>
</style>