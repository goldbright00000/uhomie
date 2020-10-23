<template>
    <div class="tenant-holdings">
        <div class="columns" style="padding-bottom: 20px;">
            <div class="column is-12 line-down">
                <span>Mis Propiedades Favoritas</span>

            </div>
        </div>

        <div class="columns is-multiline property-list">
            <div class="column is-6" v-for="property in info" :key="property.id">
                <propertyFavorite v-bind="property"></propertyFavorite>
            </div>
        </div>


    </div>
</template>

<script>


    const imagesDir = document.getElementById('images-dir').value;
    import PropertyFavorite from '../../PropertyFavorite.vue';
    import GetingData from '../common/GetingData';

    export default {
        extends : GetingData,
        components: {
            PropertyFavorite
        },
        name: 'TenantHoldings',
        props: {},
        created() {
            this.getData(2)
        },
        data() {
            return {
                info: []
                ,
                imagesDir: imagesDir
            }
        }
    }
</script>
