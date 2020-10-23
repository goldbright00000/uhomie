<template>
    <div class="tenant-myform ihuomi-info">

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

        <panel-up-down title="Mi Aval" map_zone="aval" :blocked_save="blockedSave"  @save_event="savingAval = true" @cancel_edit="cancel_edit" @edit_event="edit_event">
            <template slot-scope="data">
                <myform-aval :editing="data.editing" @blockingSave="blockedSave = $event" :saving="savingAval" v-bind:info="info.collateral"></myform-aval>
            </template>
        </panel-up-down>

        <panel-up-down title="Mi empleo" map_zone="employee" @save_event="save_event($event)" @cancel_edit="cancel_edit" @edit_event="edit_event">
            <template slot-scope="data">
                <myform-employee :editing="data.editing" v-bind:filters="filters" v-bind:info="info"></myform-employee>
            </template>
        </panel-up-down>

        <panel-up-down title="Mis preferencias de arriendo" map_zone="preferences" @save_event="save_event($event)" @cancel_edit="cancel_edit" @edit_event="edit_event">
            <template slot-scope="data">
                <myform-preferences :editing="data.editing" v-bind:filters="filters" v-bind:info="info"></myform-preferences>
            </template>
        </panel-up-down>

        <panel-up-down title="Tipo de propiedad buscada" map_zone="holding" @save_event="save_event($event)" @cancel_edit="cancel_edit" @edit_event="edit_event">
            <template slot-scope="data">
                <myform-holding :editing="data.editing" v-bind:filters="filters" v-bind:info="info"></myform-holding>
            </template>
        </panel-up-down>

    </div>
</template>

<script>


    const imagesDir = document.getElementById('images-dir').value;

    import PanelUpDown from '../../PanelUpDown';
    import MyformPersonal from '../common/MyformPersonal';
    import MyformAddress from '../common/MyformAddress';
    import MyformAval from '../common/MyformAval';
    import MyformEmployee from '../common/MyformEmployee';
    import MyformPreferences from '../common/MyformPreferences';
    import MyformHolding from '../common/MyformHolding';


    import datasheet from '../../../profiles/datasheet';


    export default {

        extends: datasheet,

        components: {
            MyformAddress,
            MyformPersonal,
            MyformAval,
            MyformEmployee,
            MyformPreferences,
            MyformHolding,
            PanelUpDown
        },
        name: 'TenantMyForm',
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

                
                saveUrl: 'tenant/save-data',
                blockedSave: false,
                savingAval: false,

                mapping: {
                    profile: ['firstname', 'lastname', /*'phone', 'email',*/ 'country_id', 'document_type', 'document_number', 'birthdate', 'civil_status_id'],
                    address: ['city_id', 'address', 'address_details','latitude','longitude'],
                    aval: ['collateral'],
                    employee: ['employment_type','position', 'company', 'job_type', 'worked_from_date', 'worked_to_date', 'amount', 'other_income_type', 'other_income_amount','saves','save_amount','invoice','last_invoice_amount','afp'],
                    preferences: ['expenses_limit', 'common_expenses_limit', 'warranty_months_quantity', 'months_advance_quantity', 'move_date', 'tenanting_months_quantity'],
                    holding: ['property_type', 'property_condition', 'property_for', 'furnished', 'pet_preference', 'smoking_allowed'],

                }

            }
        },
        methods: {

        }
    }
</script>
