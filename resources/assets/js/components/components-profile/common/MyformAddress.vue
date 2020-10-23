<template>
    <div class="columns is-inline" style="padding-bottom: 20px;">
        <div class="column">
            <div class="field">
                <label class="label">Ciudad:</label>
                <div class="control">
                    <select2 class="select" v-model="info.city_id" :options="filters.cities.options" :disabled="!editing"></select2>
                </div>
            </div>
        </div>
        <div class="column">
            <div class="field">
                <label class="label">Direcci&oacute;n: </label>
                <div class="control">
                    <input id="prueba" type="hidden">
                    <GmapAutocomplete id="_direccion" class="input" type="text"
                        placeholder="Escriba una Dirección"
                        :select-first-on-enter="true"
                        @place_changed="setPlace"
                        :disabled="!editing">
                    </GmapAutocomplete>
                </div>
            </div>
        </div>
        <div class="column">
            <div class="field">
                <label class="label">Casa / Apto / Piso:</label>
                <div class="control">
                    <input class="input" type="text" placeholder="Nombre" v-model="info.address_details" :disabled="!editing">
                </div>
            </div>
        </div>
        <div class="column">
            <div class="field">
                
                <GmapMap
                    :zoom="16"
                    map-type-id="roadmap"
                    style="width: 100%; height: 300px"
                    :center="{lat: latLng.lat, lng: latLng.lng}">
                    
                    <GmapMarker
                        :position="{lat: latLng.lat, lng: latLng.lng}"
                        />
                </GmapMap>
                <input type="hidden" v-model="info.latitude"/>
                <input type="hidden" v-model="info.longitude"/>
                <input type="hidden" v-model="info.address"/>
                <input type="hidden" v-model="info.id"/>
            </div>
        </div>
    </div>
</template>

<script>

import * as VueGoogleMaps from 'vue2-google-maps';

import Select2 from 'v-select2-component';

Vue.component('select2', Select2);

Vue.use(VueGoogleMaps, {
  load: {
    key: 'AIzaSyDTKRiKb5oaS7Z13QezK4K0V9XQI99UHiI',
    libraries: 'places',
  },
  installComponents: true,
});

    export default {
        name: 'MyformAddress',
        components: {
            VueGoogleMaps
        },
        props: {
            editing: Boolean,
            info: Object,
            filters: Object
        },
        data() {
            return {
                latLng: {
                    lat: parseFloat(this.info.latitude),
                    lng: parseFloat(this.info.longitude)
                },
                address: ''
            }
            
        },
        mounted() {
            document.getElementById("_direccion").value = this.info.address;
        },
        methods: {
            setPlace(place) {
                if (!place) return

                this.latLng = {
                lat: place.geometry.location.lat(),
                lng: place.geometry.location.lng(),
                };
                this.info.latitude = place.geometry.location.lat();
                this.info.longitude = place.geometry.location.lng();
                this.info.address = document.getElementById("_direccion").value;
            }
        }
    }
        

</script>

<style>

</style>