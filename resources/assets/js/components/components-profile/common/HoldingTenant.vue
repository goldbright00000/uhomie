<template>
    <div class="columns is-inline" style="padding-bottom: 20px;">
        <square-list :editing="editing" @change="changePropFor($event)" :items="filters.property_for" :type="info.property_type_id" :values="info.properties_for"></square-list>
        <div v-if="typeProperty == 0">
            <div class="field">
                <div class="label-field">

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

                    <span>¿Aceptas que fumen?</span>
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

            <!--<div class="field">
                <div class="label-field">

                    <span>¿Aceptas nacionales chilenos con RUT?</span>
                </div>
                <div class="control">
                    <label class="radio">
                        <input type="radio" name="nationals_with_rut" value="1" :checked="info.nationals_with_rut" v-model="info.nationals_with_rut" :disabled="!editing">
                        Si
                    </label>
                    <label class="radio">
                        <input type="radio" name="nationals_with_rut" value="0" :checked="!info.nationals_with_rut" v-model="info.nationals_with_rut" :disabled="!editing">
                        No
                    </label>
                </div>
            </div>

            <div class="field">
                <div class="label-field">

                    <span>¿Aceptas extranjeros con RUT?</span>
                </div>
                <div class="control">
                    <label class="radio">
                        <input type="radio" name="foreigners_with_rut" value="1" :checked="info.foreigners_with_rut" v-model="info.foreigners_with_rut" :disabled="!editing">
                        Si
                    </label>
                    <label class="radio">
                        <input type="radio" name="foreigners_with_rut" value="0" :checked="!info.foreigners_with_rut" v-model="info.foreigners_with_rut" :disabled="!editing">
                        No
                    </label>
                </div>
            </div>

            <div class="field">
                <div class="label-field">

                    <span>¿Aceptas extranjeros con Pasaporte?</span>
                </div>
                <div class="control">
                    <label class="radio">
                        <input type="radio" name="foreigners_with_passport" value="1" :checked="info.foreigners_with_passport" v-model="info.foreigners_with_passport" :disabled="!editing">
                        Si
                    </label>
                    <label class="radio">
                        <input type="radio" name="foreigners_with_passport" value="0" :checked="!info.foreigners_with_passport" v-model="info.foreigners_with_passport" :disabled="!editing">
                        No
                    </label>
                </div>
            </div>-->
        </div>
        <!--
        <div style="margin-top: 2rem;" v-if="info.type_stay == 'SHORT_STAY'">
            <div class="field">
                <div class="label-field">

                    <span>Adecuado para Niños de 2 a 12 años</span>
                </div>
                <div class="control">
                    <label class="radio">
                        <input type="radio" name="allow_small_child" value="1" :checked="info.allow_small_child == '1'" v-model="info.allow_small_child" :disabled="!editing">
                        Si
                    </label>
                    <label class="radio">
                        <input type="radio" name="allow_small_child" value="0" :checked="info.allow_small_child == '0'" v-model="info.allow_small_child" :disabled="!editing">
                        No
                    </label>
                </div>
            </div>
            <div class="field">
                <div class="label-field">

                    <span>Adecuado para bebés hasta 2 años</span>
                </div>
                <div class="control">
                    <label class="radio">
                        <input type="radio" name="allow_baby" value="1" :checked="info.allow_baby == '1'" v-model="info.allow_baby" :disabled="!editing">
                        Si
                    </label>
                    <label class="radio">
                        <input type="radio" name="allow_baby" value="0" :checked="info.allow_baby == '0'" v-model="info.allow_baby" :disabled="!editing">
                        No
                    </label>
                </div>
            </div>
            <div class="field">
                <div class="label-field">

                    <span>Se permiten Fiestas o Eventos</span>
                </div>
                <div class="control">
                    <label class="radio">
                        <input type="radio" name="allow_parties" value="1" :checked="info.allow_parties == '1'" v-model="info.allow_parties" :disabled="!editing">
                        Si
                    </label>
                    <label class="radio">
                        <input type="radio" name="allow_parties" value="0" :checked="info.allow_parties == '0'" v-model="info.allow_parties" :disabled="!editing">
                        No
                    </label>
                </div>
            </div>
            <div class="field">
                <div class="label-field">

                    <span>Es necesario utilizar escaleras</span>
                </div>
                <div class="control">
                    <label class="radio">
                        <input type="radio" name="use_stairs" value="1" :checked="info.use_stairs == '1'" v-model="info.use_stairs" :disabled="!editing">
                        Si
                    </label>
                    <label class="radio">
                        <input type="radio" name="use_stairs" value="0" :checked="info.use_stairs == '0'" v-model="info.use_stairs" :disabled="!editing">
                        No
                    </label>
                </div>
            </div>
            <div class="field">
                <div class="label-field">

                    <span>Puede haber ruido</span>
                </div>
                <div class="control">
                    <label class="radio">
                        <input type="radio" name="there_could_be_noise" value="1" :checked="info.there_could_be_noise == '1'" v-model="info.there_could_be_noise" :disabled="!editing">
                        Si
                    </label>
                    <label class="radio">
                        <input type="radio" name="there_could_be_noise" value="0" :checked="info.there_could_be_noise == '0'" v-model="info.there_could_be_noise" :disabled="!editing">
                        No
                    </label>
                </div>
            </div>
            <div class="field">
                <div class="label-field">

                    <span>Hay zonas comunes que se comparten con otros huéspedes</span>
                </div>
                <div class="control">
                    <label class="radio">
                        <input type="radio" name="common_zones" value="1" :checked="info.common_zones == '1'" v-model="info.common_zones" :disabled="!editing">
                        Si
                    </label>
                    <label class="radio">
                        <input type="radio" name="common_zones" value="0" :checked="info.common_zones == '0'" v-model="info.common_zones" :disabled="!editing">
                        No
                    </label>
                </div>
            </div>
            <div class="field">
                <div class="label-field">

                    <span>Limitaciones de servicios</span>
                </div>
                <div class="control">
                    <label class="radio">
                        <input type="radio" name="services_limited" value="1" :checked="info.services_limited == '1'" v-model="info.services_limited" :disabled="!editing">
                        Si
                    </label>
                    <label class="radio">
                        <input type="radio" name="services_limited" value="0" :checked="info.services_limited == '0'" v-model="info.services_limited" :disabled="!editing">
                        No
                    </label>
                </div>
            </div>
            <div class="field">
                <div class="label-field">

                    <span>Dispositivos de vigilancia o de grabación en la vivienda</span>
                </div>
                <div class="control">
                    <label class="radio">
                        <input type="radio" name="survellaince_camera" value="1" :checked="info.survellaince_camera == '1'" v-model="info.survellaince_camera" :disabled="!editing">
                        Si
                    </label>
                    <label class="radio">
                        <input type="radio" name="survellaince_camera" value="0" :checked="info.survellaince_camera == '0'" v-model="info.survellaince_camera" :disabled="!editing">
                        No
                    </label>
                </div>
            </div>
            <div class="field">
                <div class="label-field">

                    <span>Armas en la vivienda</span>
                </div>
                <div class="control">
                    <label class="radio">
                        <input type="radio" name="weaponry" value="1" :checked="info.weaponry == '1'" v-model="info.weaponry" :disabled="!editing">
                        Si
                    </label>
                    <label class="radio">
                        <input type="radio" name="weaponry" value="0" :checked="info.weaponry == '0'" v-model="info.weaponry" :disabled="!editing">
                        No
                    </label>
                </div>
            </div>
            <div class="field">
                <div class="label-field">

                    <span>Animales peligrosos en la vivienda</span>
                </div>
                <div class="control">
                    <label class="radio">
                        <input type="radio" name="dangerous_animals" value="1" :checked="info.dangerous_animals == '1'" v-model="info.dangerous_animals" :disabled="!editing">
                        Si
                    </label>
                    <label class="radio">
                        <input type="radio" name="dangerous_animals" value="0" :checked="info.dangerous_animals == '0'" v-model="info.dangerous_animals" :disabled="!editing">
                        No
                    </label>
                </div>
            </div>
            <div class="field">
                <div class="label-field">

                    <span>Hay mascotas en la propiedad</span>
                </div>
                <div class="control">
                    <label class="radio">
                        <input type="radio" name="pets_friendly" value="1" :checked="info.pets_friendly == '1'" v-model="info.pets_friendly" :disabled="!editing">
                        Si
                    </label>
                    <label class="radio">
                        <input type="radio" name="pets_friendly" value="0" :checked="info.pets_friendly == '0'" v-model="info.pets_friendly" :disabled="!editing">
                        No
                    </label>
                </div>
            </div>
        </div>-->
    </div>
</template>

<script>

    const imagesDir = document.getElementById('images-dir').value;

    import SquareList from '../../SquareList';


    export default {
        name: 'HoldingTenant',
        components : {
            SquareList
        },
        props: {
            editing: Boolean,
            info: Object,
            filters: Object,
        },
        data() {
            return {
                imagesDir: imagesDir
            }
        },
        methods: {
            changePropFor : function(w) {
                this.info.properties_for = w
            }
        },
        computed:{
            typeProperty(){
                if(this.info.property_type_id == 1 || this.info.property_type_id == 2 || this.info.property_type_id == 3){
                    return 0;
                }
                if(this.info.property_type_id == 4 || this.info.property_type_id == 5){
                    return 1;
                }
            },
        }
    }
</script>