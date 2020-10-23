<template>
    <div class="columns is-inline" style="padding-bottom: 20px;">
        <div class="field">
            <div class="label-field">
                <img :src="imagesDir+'/icono-casa.png'">
                <span>Tipo de propiedad que prefieres</span>
            </div>
            <div class="select">

                <select v-model="info.property_type" :disabled="!editing">
                    <option v-for="item in filters.property_type.options" :value="item.id" :selected="item.id==info.property_type">{{item.text}}</option>
                </select>

            </div>
        </div>
        <div class="field">
            <div class="label-field">
                <img :src="imagesDir+'/icono-estrella.png'">
                <span>Condición</span>
            </div>
            <div class="select">

                <select v-model="info.property_condition" :disabled="!editing">
                    <option v-for="item in filters.property_condition.options" :value="item.id" :selected="item.id==info.property_condition">{{item.text}}</option>
                </select>

            </div>
        </div>
        <div class="field">
            <div class="label-field">
                <img :src="imagesDir+'/icono-familia.png'">
                <span>Busco propiedad para</span>
            </div>
            <div class="select">

                <select v-model="info.property_for" :disabled="!editing">
                    <option v-for="item in filters.property_for.options" :value="item.id" :selected="item.id==info.property_for">{{item.text}}</option>
                </select>

            </div>
        </div>
        <div class="field">
            <div class="label-field">
                <img :src="imagesDir+'/icono-amoblado.png'">
                <span>Amoblado</span>
            </div>
            <div class="control">
                <label class="radio">
                    <input type="radio" name="furnished" value="1" :checked="info.furnished" v-model="info.furnished" :disabled="!editing">
                    Si
                </label>
                <label class="radio">
                    <input type="radio" name="furnished" value="0" :checked="!info.furnished" v-model="info.furnished" :disabled="!editing">
                    No
                </label>
            </div>
        </div>
        <div class="field">
            <div class="label-field">
                <img :src="imagesDir+'/icono-mascota.png'">
                <span>Tienes Mascotas?</span>
            </div>
            <div class="control">
                <label class="radio">
                    <input type="radio" name="pet_preference" value="dog" :checked="info.pet_preference=='dog'" v-model="info.pet_preference" :disabled="!editing">
                    <img :src="imagesDir+'/icono-perro.png'">
                </label>
                <label class="radio">
                    <input type="radio" name="pet_preference" value="cat" :checked="info.pet_preference=='cat'" v-model="info.pet_preference" :disabled="!editing">
                    <img :src="imagesDir+'/icono-gato.png'">
                </label>
                <label class="radio">
                    <input type="radio" name="pet_preference" value="other" :checked="info.pet_preference=='other'" v-model="info.pet_preference" :disabled="!editing">
                    Otro
                </label>
                <label class="radio">
                    <input type="radio" name="pet_preference" value="no" :checked="info.pet_preference=='no'" v-model="info.pet_preference" :disabled="!editing">
                    No
                </label>
            </div>
        </div>
        <div class="field">
            <div class="label-field">
                <img :src="imagesDir+'/icono-fumar.png'">
                <span>Fumas?</span>
            </div>
            <div class="control">
                <label class="radio">
                    <input type="radio" name="smoking_allowed" value="1" :checked="info.smoking_allowed" v-model="info.smoking_allowed" :disabled="!editing">
                    Si
                </label>
                <label class="radio">
                    <input type="radio" name="smoking_allowed" value="0" :checked="!info.smoking_allowed" v-model="info.smoking_allowed" :disabled="!editing">
                    No
                </label>
            </div>
        </div>
    </div>
</template>

<script>

    const imagesDir = document.getElementById('images-dir').value;
    export default {
        name: 'MyformHolding',
        props: {
            editing: Boolean,
            info: Object,
            filters: Object,
        },
        data() {
            return {
                imagesDir : imagesDir
            }
        }
    }
</script>
