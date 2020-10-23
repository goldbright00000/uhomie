<template>
    <div class="service-list">
        <div class="columns" style="margin: 0">
            <div class="column has-text-right">
                <router-link to="/services"><i class="fa fa-chevron-left"></i> Volver</router-link>
            </div>
        </div>
        <panel-up-down title="Datos de mi servicio" :open="true" map_zone="service" @save_event="save_event($event)">
            <template slot-scope="data">
                <myform-service :editing="data.editing" v-bind:filters="filters" v-bind:info="info"></myform-service>
            </template>
        </panel-up-down>
        <div class="columns is-multiline">
            <div class="column">
                <service-photo v-bind:info="info" v-bind:photo="info.photo" v-bind:membership="membership"></service-photo>
            </div>
        </div>
    </div>
</template>

<script>
const imagesDir = document.getElementById('images-dir').value;
import MyformService from '../common/MyformService.vue';
import PanelUpDown from '../../PanelUpDown';
import ServicePhoto from "../common/ServicePhoto";
import datasheet from '../../../profiles/datasheet';
export default {
    extends: datasheet,
    name: 'ServiceService',
    components: {
        MyformService,
        PanelUpDown,
        ServicePhoto
    },
    computed: {
        info() {
            var info = this.$parent.info
            var w = this.$route.params.idService
            var nfo = info.services
            for(var t in nfo){
                if (nfo[t].id==w) {
                    return nfo[t]
                }
            }
            return ;
        },
        membership(){
            return JSON.parse(this.$parent.info.membership_data.features).photos_per_project
        },
        filters(){
            return this.$parent.filters
        }
    },
    data() {
        return {
            imagesDir: imagesDir,
            saveUrl: "service/save-service",
            mapping: {
                service: ['id','list_id','type_id','description']
            },
        }
    },
    methods: {
        
    },
    mounted() {
    },
    created() {
    },
}
</script>
