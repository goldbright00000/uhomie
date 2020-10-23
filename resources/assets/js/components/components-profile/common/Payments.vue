<template>
    <div class="payments">
        <div class="columns">
            <div class="column is-12">
                <div class="tabs help-tabs">
                    <ul>
                        <li :class="[ tab === 'payments' ? 'is-active' : '']"><a @click="tab='payments'">Pagos</a></li>
                        <li :class="[ tab === 'bank' ? 'is-active' : '']"><a @click="tab='bank'">Banco</a></li>
                        <li :class="[ tab === 'deposits' ? 'is-active' : '']"><a @click="tab='deposits'">Depositos</a></li>
                    </ul>
                </div>
                <div>
                    <div v-if="tab ==='payments'">
                        <div class="columns">
                            <div class="column is-full" style="position:relative;width:100%;margin:auto;overflow:hidden;">
                                <div style="width:100%; overflow:auto;">
                                    <table class="table is-fullwidth is-size-7" style="width: 100%; overflow-x: auto;">
                                        <thead>
                                            <tr>
                                                <th class="has-text-centered">
                                                    Orden
                                                </th>
                                                <th>Estado</th>
                                                <th>Descripción</th>
                                                <th class="has-text-centered">
                                                    Monto
                                                </th>
                                                <th class="has-text-centered">
                                                    Total
                                                </th>
                                                <th>
                                                    Fecha
                                                </th>
                                                <th class="has-text-centered">
                                                    Acciones
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody v-if="info.payments && info.payments.length > 0">
                                            <payment v-for="item in info.payments" :key="item.id" v-bind:info="item"></payment>
                                        </tbody>
                                        <tbody v-else>
                                            <td colspan="7" class="has-text-centered">
                                                No hay pagos realizados
                                            </td>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-if="tab ==='bank'">
                        <panel-up-down title="Datos bancarios" :verification2f="verification2f" :open="true" map_zone="bank" @edit_event="openVerify = true" @save_event="save_event($event)">
                            <template slot-scope="data">
                                <myform-bank :editing="data.editing" v-bind:info="info" v-bind:filters="filters" @management="modalManage"></myform-bank>
                            </template>
                        </panel-up-down>
                    </div>
                    <div v-if="tab ==='deposits'">
                        <div>Depositos</div>
                    </div>
                    <verificationTwoF :open="openVerify" @back="openVerify = false" @verify="verification2f = 0; openVerify = false"></verificationTwoF>
                    <div :class="manage ? 'modal is-active' : 'modal'" id="manage" v-if="info.role == 2">
                        <div class="modal-background" @click="modalManage({'value': false})"></div>
                        <div class="modal-card">
                            <header class="modal-card-head">
                                <p class="modal-card-title">Gestion de Propiedades</p>
                            <button class="delete" aria-label="close" @click="modalManage({'value': false})"></button>
                            </header>
                            <section class="modal-card-body">
                                <div class="columns">
                                    <div class="column">
                                        <div style="margin-left: 20px;">
                                            <ol>
                                                <li value="1">Recaudación y liquidación de arriendo</li>
                                                <li value="2">Control mensual de pagos de cuentas de servicios y gastos comunes</li>
                                                <li value="3">Gestionar Mantención de preventiva de propiedades</li>
                                                <li value="4">Reparaciones de propiedad</li>
                                                <li value="5">Asesoria y cobranza judicial</li>
                                                <li value="6">Visitas periodicas para revisar el estado del inmueble</li>
                                                <li value="7">Recepcion de la propiedad</li>
                                                <li value="8">Liquidación de la garantia</li>
                                                <li value="9">Finiquito de arriendo</li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                                <div class="columns">
                                    <div class="column">
                                        <article class="message is-success">
                                            <div class="message-body">
                                                <strong>10%</strong> del canon mensual del arriendo.
                                            </div>
                                        </article>
                                    </div>
                                </div>
                                <div class="columns">
                                    <div class="column">
                                        <div class="label">
                                            <span>¿Deseas que tus propiedades las gestione Uhomie?</span>
                                        </div>
                                        <div class="control">
                                            <label class="radio">
                                                <input type="radio" v-model="management" name="pool" value="1">
                                                Si
                                            </label>
                                            <label class="radio">
                                                <input type="radio" v-model="management" name="pool" value="0">
                                                No
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </section>
                            <footer class="modal-card-foot">
                                <button :class="loading ? 'button is-success is-loading' : 'button is-success'" @click="applyManagement">Aceptar</button>
                                <button class="button" @click="modalManage({'value': false})">Cancelar</button>
                            </footer>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</template>

<script>

import PanelUpDown from '../../PanelUpDown';
import Payment from '../../Payment.vue';
import MyformBank from './MyformBank';
import datasheet from '../../../profiles/datasheet';
import VerificationTwoF from '../common/VerificationTwoF';

export default {
    extends: datasheet,
    name:'Payments',
    components:{
        Payment,
        MyformBank,
        PanelUpDown,
        VerificationTwoF
    },
    data() {
        return {
            tab: 'payments',
            openVerify: false,
            verification2f: 1,
            mapping: {
                bank: ['bank_id', 'account_type','account_number'],
            },
            manage: false,
            management: false,
            loading: false
        }
    },
    computed: {
        filters() {
            return this.$parent.filters;
        },
        info(){
            return this.$parent.info;
        },
        saveUrl(){
            return this.$parent.saveUrl;
        }
    },
    methods:{
        modalManage(args){
            if(args.value == true){
                this.manage = true;
            } else {
                this.manage = false;
            }
        },
        applyManagement(){
            this.loading = true
            axios.post('/apply-management', {
                manage: this.management,
            })
            .then(function (response) {
                setTimeout (location.reload(), 5000); 
                toastr.success(response.data.message);
            })
            .catch(function (error) {
                toastr.error('Ha ocurrido un error')
            });
        }
    }

}
</script>

<style lang="css" scoped>
    .help-content {
        background-color: white !important;
    }
    .help-tabs {
        margin-bottom: 10px !important;
    }
    .tabs li.is-active a {
        border-bottom-color: #000000;
        color: #08b7ff;
        border-bottom: 3px solid;
        font-weight: bold;
    }
</style>