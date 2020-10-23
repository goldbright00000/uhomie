<template>
    <div class="owner-myform ihuomi-info">
        <panel-up-down title="Mis Datos Personales" :open="true" map_zone="profile" @save_event="save_event($event)">
            <template slot-scope="data">
                <myform-personal :editing="data.editing" v-bind:filters="filters" v-bind:info="info"></myform-personal>
            </template>
        </panel-up-down>

        <panel-up-down title="Mi dirección" map_zone="address" @save_event="save_event($event)">
            <template slot-scope="data">
            <myform-address :editing="data.editing" v-bind:filters="filters" v-bind:info="info"></myform-address>
            </template>
        </panel-up-down>

    </div>
</template>

<script>


    //const imagesDir = document.getElementById('images-dir').value;

    import PanelUpDown from '../../PanelUpDown';
    import MyformPersonal from '../common/MyformPersonal';
    import MyformProps from '../common/MyformProps';
    import MyformAddress from '../common/MyformAddress';
    import datasheet from '../../../profiles/datasheet';

    export default {

        extends: datasheet,
        components: {
            PanelUpDown,
            MyformAddress,
            MyformPersonal,
            MyformProps,
        
        },
        name: 'OwnerMyForm',
        props: {
        },
        computed: {
            info() {
                return this.$parent.info;
            },
            filters() {
                return this.$parent.filters;
            }
        },
        data() {
            return {
                section: 0, // section que estará activa

                saveUrl: 'owner/save-data',

                mapping: {
                    profile: ['firstname', 'lastname', 'country_id', 'document_type', 'document_number', 'birthdate', 'civil_status_id'],
                    address: ['city_id', 'address', 'address_details','latitude','longitude'],
                    employee: ['employment_type','position', 'company', 'job_type', 'worked_from_date', 'worked_to_date', 'amount', 'other_income_type', 'other_income_amount','saves','save_amount','invoice','last_invoice_amount','afp'],
                    preferences: ['expenses_limit', 'common_expenses_limit', 'warranty_months_quantity', 'months_advance_quantity', 'move_date', 'tenanting_months_quantity'],
                    holding: ['property_type', 'property_condition', 'property_for', 'furnished', 'pet_preference', 'smoking_allowed'],
                }

            }
        },
        methods: {
            putupordown: function (w) {
                this.$set(this.accordion, w, !this.accordion[w]);

            }
        }
    }
</script>
