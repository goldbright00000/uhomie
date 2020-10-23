<template>
    <div class="columns is-inline" style="padding-bottom: 20px;">
        <div class="field">
            <div class="label-field">
                <img :src="imagesDir+'/icono_dinero.png'">
                <span>Límite gasto mensual arriendo</span>
            </div>
            <div class="control has-icons-left has-icons-right">
                <input class="input monto_formato_decimales" type="text" autocomplete="off"  v-model="info.expenses_limit" :disabled="!editing">
                <span class="icon is-small is-left">
                                    $
                                </span>
                <span class="icon is-small is-right">
                                    CLP
                                </span>
            </div>
        </div>
        <div class="field">
            <div class="label-field">
                <img :src="imagesDir+'/icono_dinero.png'">
                <span>Límite para gastos comunes</span>
            </div>
            <div class="control has-icons-left has-icons-right">
                <input class="input monto_formato_decimales" type="text" v-model="info.common_expenses_limit" :disabled="!editing">
                <span class="icon is-small is-left">
                                    $
                                </span>
                <span class="icon is-small is-right">
                                    CLP
                                </span>
            </div>
        </div>
        <div class="field">
            <div class="label-field">
                <img :src="imagesDir+'/icono_numero.png'">
                <span>¿Cuántos meses de garantía puedes dar?</span>
            </div>
            <div class="select">

                <select v-model="info.warranty_months_quantity" :disabled="!editing">
                    <option v-for="item in filters.months.options" :value="item.id" :selected="item.id==info.warranty_months_quantity">{{item.text}}</option>
                </select>

            </div>
        </div>
        <div class="field">
            <div class="label-field">
                <img :src="imagesDir+'/icono_numero.png'">
                <span>¿Cuántos meses de adelanto?</span>
            </div>
            <div class="select">
                <select v-model="info.months_advance_quantity" :disabled="!editing">
                    <option v-for="item in filters.months.options" :value="item.id" :selected="item.id==info.months_advance_quantity">{{item.text}}</option>
                </select>

            </div>
        </div>
        <div class="field">
            <div class="label-field">
                <img :src="imagesDir+'/icono-calendario-azul.png'">
                <span>¿Qué día quieres mudarte?</span>
            </div>
            <input type="date" autocomplete="off"  class="input date" v-model="info.move_date" :disabled="!editing">
        </div>
        <div class="field">
            <div class="label-field">
                <img :src="imagesDir+'/icono_numero.png'">
                <span>¿Por cuánto tiempo quieres arrendar?</span>
            </div>
            <div class="select">

                <select v-model="info.tenanting_months_quantity" :disabled="!editing">
                    <option v-for="item in filters.months.options" :value="item.id" :selected="item.id==info.tenanting_months_quantity">{{item.text}}</option>
                    <option value="18" :selected="info.id==18">18 Meses</option>
                    <option value="24" :selected="info.id==24">24 Meses</option>
                    <option value="36" :selected="info.id==36">36 Meses</option>
                </select>

            </div>
        </div>


    </div>
</template>

<script>

    const imagesDir = document.getElementById('images-dir').value;
    export default {
        name: 'MyformPreferences',
        props: {
            editing:false,
            info: Object,
            filters: Object,
        },
        data() {
            return {
                imagesDir: imagesDir            }
        }
    }
</script>
