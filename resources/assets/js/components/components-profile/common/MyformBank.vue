<template>
    <div class="myform-bank">
        <div class="columns is-inline" style="padding-bottom: 20px; margin: 0">
            <div class="column">
                <span>Los datos bancarios introducidos son de caracter personal.</span>
            </div>
            <div class="column">
                <div class="field">
                    <label class="label">Banco:</label>
                    <div class="control">
                        <select2 class="select" v-model="info.bank_id" :options="filters.banks.options" :disabled="!editing"></select2>
                    </div>
                </div>
            </div>
            <div class="column">
                <div class="field">
                    <label class="label">Tipo de Cuenta:</label>
                    <div class="control">
                        <select2 class="select" v-model="info.account_type" :options="typeAccount" :disabled="!editing"></select2>
                    </div>
                </div>
            </div>
            <div class="column">
                <div class="field">
                    <label class="label">Numero de cuenta:</label>
                    <div class="control">
                        <input type="text" class="input numeric" v-model="info.account_number" :disabled="!editing">
                    </div>
                </div>
            </div>
            <div class="column" v-if="info.role == 2">
                <div class="field">
                    <label class="label">Deseas que uhomie administre tus propiedades:</label>
                    <div class="control">
                        <button class="button is-primary" @click="$emit('management', {'value': true})" :disabled="!editing">Gestionar Propiedades</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
import VueNumeric from 'vue-numeric'
import Select2 from 'v-select2-component';

Vue.component('select2', Select2);

export default {
    name: 'MyformBank',
    props: {
        editing: false,
        info: Object,
        filters: Object
    },
    components: {
        VueNumeric,
    },
    data() {
        return {
            typeAccount: ['Cuenta de Ahorro', 'Cuenta Corriente', 'Cuenta RUT']
        }
    },
    mounted(){
        $(function(){
            $(".numeric").keydown(function(event){
                //alert(event.keyCode);
                if((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105) && event.keyCode !==190  && event.keyCode !==110 && event.keyCode !==8 && event.keyCode !==9  ){
                    return false;
                }
            });
        });
    }
}
</script>

<style>
.select2-container--default .select2-selection--single {
    border-top: 0;
    border-right: 0;
    border-left: 0;
    border-bottom: 1px solid black;
    border-radius: 0;
}

.select2-container--default .select2-selection--single-disabled {
    border-top: 0;
    border-right: 0;
    border-left: 0;
    border-bottom: 0;
    border-radius: 0;
}
</style>
