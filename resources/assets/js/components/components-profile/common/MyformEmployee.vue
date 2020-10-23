<template>
    <div class="columns is-inline" style="padding-bottom: 20px;">




        <div class="field">
            <div class="label-field">
                <img :src="imagesDir+'/icono_trabajo.png'">
                <span>¿De qué trabajas?</span>
            </div>
            <div class="control select has-icons-right">
                <select v-model="info.employment_type" :disabled="!editing || !change_employment">


                    <option v-for="item in filters.employment.options" :value="item.id"
                            :selected="item.id==info.employment_type">{{item.text}}
                    </option>
                </select>

            </div>
            <div v-if="editing && !change_employment && info.role !== 5" class="at-right has-field-right">
                <img :src="imagesDir+'/mini-edit.png'">
                <a @click="change_employment=true">Cambiar tipo empleo</a>
            </div>
        </div>

        <div class="column line-down" v-if="info.employment_type==3">
            Tu último trabajo
        </div>

        <div v-if="info.employment_type!=2">
            <div class="field">
                <div class="label-field">
                    <img :src="imagesDir+'/icono-empleo.png'">
                    <span>Cargo</span>
                </div>
                <input type="text" autocomplete="off" name="position" class="input" required v-model="info.position"
                       :disabled="!editing">
            </div>
            <div class="field">
                <div class="label-field">
                    <img :src="imagesDir+'/icono-empresa.png'">
                    <span>Empresa</span>
                </div>
                <input type="text" autocomplete="off" name="company" class="input" value="" required
                       v-model="info.company"
                       :disabled="!editing">
            </div>
            <div class="field">
                <div class="label-field">
                    <img :src="imagesDir+'/icono_trabajo.png'">
                    <span>Tipo de trabajo</span>
                </div>
                <div class="select">
                    <select v-model="info.job_type" :disabled="!editing">
                        <option v-for="item in filters.job_type.options" :value="item.id"
                                :selected="item.id==info.job_type">{{item.text}}
                        </option>
                    </select>
                </div>
            </div>
            <div class="field">
                <div class="label-field">
                    <img :src="imagesDir+'/icono-calendario-azul.png'">
                    <span>De</span>
                    <input type="date" class="input date" required v-model="info.worked_from_date" :disabled="!editing">
                </div>
                <div class="label-field">
                    <img :src="imagesDir+'/icono-calendario-azul.png'">
                    <span>Hasta</span>
                    <input v-if="info.employment_type==3" type="date" class="input date" required
                           v-model="info.worked_to_date" :disabled="!editing">
                    <div v-if="info.employment_type!=3" class="">
                        <span class="with-line">(Actualidad)</span>
                    </div>

                </div>
            </div>
            <div class="field">
                <div class="label-field">
                    <img :src="imagesDir+'/icono_dinero.png'">
                    <span>Salario líquido percibido</span>
                </div>
                <div class="control has-icons-left has-icons-right">
                    <input class="input monto_formato_decimales" type="text" required v-model="info.amount"
                           :disabled="!editing">
                    <span class="icon is-small is-left">
                                    $
                                </span>
                    <span class="icon is-small is-right">
                                    CLP
                                </span>
                </div>
            </div>
            <div v-if="info.employment_type==3">
                
                <div class="field">
                    <div class="label-field">
                        <img :src="imagesDir+'/icono-chancho.png'">
                        <span>¿Posee ahorros?</span>
                    </div>
                    <div class="control">
                        <label class="radio">
                            <input type="radio" name="have_saves" value="1" :checked="info.saves" v-model="info.saves" :disabled="!editing">
                            Si
                        </label>
                        <label class="radio">
                            <input type="radio" name="have_saves" value="0" :checked="!info.saves" v-model="info.saves" :disabled="!editing">
                            No
                        </label>
                    </div>
                </div>

                <div class="field row-income">
                    <div class="label-field">
                        <img :src="imagesDir+'/icono_dinero.png'">
                        <span>Indique el monto ahorrado</span>
                    </div>
                    <div class="control has-icons-left has-icons-right">
                        <input class="input monto_formato_decimales" type="text" v-model="info.save_amount"
                            :disabled="!editing || info.saves==0">
                        <span class="icon is-small is-left">
                                        $
                                    </span>
                        <span class="icon is-small is-right">
                                        CLP
                                    </span>
                    </div>
                </div>

            </div>
        </div>

        <!-- CUENTA PROPIA -->
        <div v-else>
            <div class="field">
                <div class="label-field">

                    <span>¿A que te dedicas?</span>
                </div>
                <input type="text" autocomplete="off" name="position" class="input" required v-model="info.position"
                       :disabled="!editing">
            </div>

            <div class="field">
                <div class="label-field">
                    <img :src="imagesDir+'/icono-chancho.png'">
                    <span>¿Posee ahorros?</span>
                </div>
                <div class="control">
                    <label class="radio">
                        <input type="radio" name="have_saves" value="1" :checked="info.saves" v-model="info.saves" :disabled="!editing">
                        Si
                    </label>
                    <label class="radio">
                        <input type="radio" name="have_saves" value="0" :checked="!info.saves" v-model="info.saves" :disabled="!editing">
                        No
                    </label>
                </div>
            </div>

            <div class="field row-income">
                <div class="label-field">
                    <img :src="imagesDir+'/icono_dinero.png'">
                    <span>Indique el monto ahorrado</span>
                </div>
                <div class="control has-icons-left has-icons-right">
                    <input class="input monto_formato_decimales" type="text" v-model="info.save_amount"
                           :disabled="!editing || info.saves==0">
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
                    <img :src="imagesDir+'/icono-chancho.png'">
                    <span>¿Posee Boletas de Honorarios o facturas de pago por tus servicios?</span>
                </div>
                <div class="control">
                    <label class="radio">
                        <input type="radio" name="have_invoice" value="1" :checked="info.invoice" v-model="info.invoice" :disabled="!editing">
                        Si
                    </label>
                    <label class="radio">
                        <input type="radio" name="have_invoice" value="0" :checked="!info.invoice" v-model="info.invoice" :disabled="!editing">
                        No
                    </label>
                </div>
            </div>

            <div class="field row-income">
                <div class="label-field">
                    <img :src="imagesDir+'/icono_dinero.png'">
                    <span>Indique el monto de Honorarios</span>
                </div>
                <div class="control has-icons-left has-icons-right">
                    <input class="input monto_formato_decimales" type="text" v-model="info.last_invoice_amount"
                           :disabled="!editing || info.invoice==0">
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
                    <img :src="imagesDir+'/icono_id.png'">
                    <span>¿Cotizas en AFP?</span>
                </div>
                <div class="control">
                    <label class="radio">
                        <input type="radio" name="have_afp" value="1" :checked="info.afp" v-model="info.afp" :disabled="!editing">
                        Si
                    </label>
                    <label class="radio">
                        <input type="radio" name="have_afp" value="0" :checked="!info.afp" v-model="info.afp" :disabled="!editing">
                        No
                    </label>
                </div>
            </div>


        </div>

        <div class="column line-down">
            <span>Ingreso mensual adicional</span>

        </div>
        <br>

        <div class="field">
            <div class="label-field">
                <img :src="imagesDir+'/icono_dinero.png'">
                <span>Tipo de ingreso</span>
            </div>
            <div class="select">
                <select v-model="info.other_income_type" :disabled="!editing">
                    <option v-for="item in filters.others_income.options" :value="item.id"
                            :selected="item.id==info.other_income_type">{{item.text}}
                    </option>
                </select>
            </div>
        </div>
        <div class="field row-income">
            <div class="label-field">
                <img :src="imagesDir+'/icono_dinero.png'">
                <span>Monto adicional</span>
            </div>
            <div class="control has-icons-left has-icons-right">
                <input class="input monto_formato_decimales" type="text" v-model="info.other_income_amount"
                       :disabled="!editing || info.other_income_type==0">
                <span class="icon is-small is-left">
                                    $
                                </span>
                <span class="icon is-small is-right">
                                    CLP
                                </span>
            </div>
        </div>
        <div class="column line-down">
            <span>Sumario Financiero</span>

        </div>
        <br>
        <div class="field">
            <div class="label-field">
                <img :src="imagesDir+'/icono_dinero.png'">
                <span>Liquidez total</span>
                <span>(Ingresos mensuales + Ingresos adicionales)</span>
            </div>
            <div class="control has-icons-left has-icons-right">
                <input class="input" type="text" readonly
                       :value="parseFloat(info.other_income_amount)+parseFloat(info.amount)" :disabled="!editing">
                <span class="icon is-small is-left">
                                    $
                                </span>
                <span class="icon is-small is-right">
                                    CLP
                                </span>
            </div>
        </div>


    </div>
</template>

<script>

    const imagesDir = document.getElementById('images-dir').value;
    export default {
        name: 'MyformJob',
        props: {
            editing: false,
            info: Object,
            filters: Object,
        },
        data() {
            return {
                change_employment: false,
                imagesDir: imagesDir
            }
        },
        watch: {
            editing: function (val) {
                if (!val) this.change_employment = val
            },

            'info.employment_type' : function(val){
                if (this.editing) {
                    this.info.amount = 0
                    this.info.last_invoice_amount = 0
                    this.info.save_amount = 0
                    this.info.other_income_amount = 0
                }
            },

            'info.invoice': function (val) {

                if (val=='0') this.info.last_invoice_amount = 0
            },

            'info.saves': function (val) {

                if (val=='0') this.info.save_amount = 0
            },

            'info.other_income_type' : function(val){
                if (val=='0') this.info.other_income_amount = 0
            }

        }
    }
</script>
