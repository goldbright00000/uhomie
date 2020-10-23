<template>
    <div class="service-myform ihuomi-info">

        <panel-up-down title="Mis Datos Personales" :open="true" map_zone="profile"
                      @save_event="save_event($event)" @cancel_edit="cancel_edit" @edit_event="edit_event">
            <template slot-scope="data">
                <myform-personal :editing="data.editing" v-bind:filters="filters" v-bind:info="info"></myform-personal>
            </template>
        </panel-up-down>

        <panel-up-down title="Mi dirección" map_zone="address" @save_event="save_event($event)" @cancel_edit="cancel_edit" @edit_event="edit_event">
            <template slot-scope="data">
                <myform-address :editing="data.editing" v-bind:filters="filters" v-bind:info="info"></myform-address>
            </template>
        </panel-up-down>

        <panel-up-down title="Tu empresa" map_zone="company" @save_event="save_event($event)" @cancel_edit="cancel_edit" @edit_event="edit_event">
            <template slot-scope="data">
                <myform-company :editing="data.editing" v-bind:info="info.company" v-bind:logo="info.logo" v-bind:save_logo="info.logo_save" v-bind:get_logo="info.logo_get" v-bind:del_logo="info.logo_del"></myform-company>
            </template>
        </panel-up-down>
    </div>
</template>

<script>


    const imagesDir = document.getElementById('images-dir').value;

    import PanelUpDown from '../../PanelUpDown';
    import MyformPersonal from '../common/MyformPersonal';
    import MyformAddress from '../common/MyformAddress';
    import datasheet from '../../../profiles/datasheet';
    import MyformCompany from '../common/MyformCompany';
    import MyformAddressCompany from '../common/MyformAddressCompany';


    export default {

        extends: datasheet,
        components: {
            MyformAddress,
            MyformPersonal,
            PanelUpDown,
            MyformCompany,
            MyformAddressCompany
        },
        name: 'ServiceMyForm',
        computed: {
            info() {
                return this.$parent.info;
            },
            filters() {
                return this.$parent.filters;
            },
        },
        data() {
            return {
                saveUrl: 'service/save-data',
                saveCompany: 'service/save-company',
                mapping: {
                    profile: ['firstname', 'lastname', 'phone', 'email', 'country_id', 'document_type', 'document_number', 'birthdate', 'civil_status_id'],
                    address: ['city_id', 'address', 'address_details','latitude','longitude'],
                    company: ['name', 'invoice', 'giro', 'rut', 'phone', 'cell_phone', 'email', 'website', 'description'],

                }

            }
        },
        methods: {

        }
    }
</script>
