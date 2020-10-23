<template>
    <div class="content">
        <div class="md-layout">
            <div class="md-layout-item md-medium-size-100 md-xsmall-size-100 md-size-100">
                <md-card>
                    <md-card-header data-background-color="black">
                        <h4 class="title">Editar Cupon</h4>
                        <p class="category">Formulario de edición de Cupon</p>
                    </md-card-header>
                    <md-card-content>
                        <form novalidate class="md-layout" @submit.prevent="validateCoupon">
                            <md-card class="md-layout-item md-size-50 md-small-size-100">
                                <md-card-content>
                                    <div class="md-layout-item md-small-size-100">
                                        <md-field :class="getValidationClass('name')">
                                            <label for="name">Nombre del Cupon</label>
                                            <md-input name="name" id="name" autocomplete="false" v-model="form.name" :disabled="sending" />
                                            <span class="md-error" v-if="!$v.form.name.required">Se requiere un nombre de cupon</span>
                                            <span class="md-error" v-else-if="!$v.form.name.minlength">El nombre del cupon es muy corto</span>
                                        </md-field>
                                    </div>
                                    <div class="md-layout-item md-small-size-100">
                                        <md-field :class="getValidationClass('quantity')">
                                            <label>Cantidad de Usos</label>
                                            <md-input type="number" maxlength="2" id="quantity" name="quantity" autocomplete="false" v-model="form.quantity"  :disabled="sending"></md-input>
                                            <span class="md-error" v-if="!$v.form.quantity.required">La cantidad de usos es requerido</span>
                                            <span class="md-error" v-else-if="!$v.form.code.maxlength">La cantidad de usos es muy larga</span>
                                        </md-field>
                                    </div>

                                    <div class="md-layout-item md-small-size-100">
                                        <md-field :class="getValidationClass('enabled')">
                                            <label for="enabled">Habilitado</label>
                                            <md-select name="enabled" v-model="form.enabled" id="enabled" :disabled="sending">
                                                <md-option value="1">Si</md-option>
                                                <md-option value="0">No</md-option>
                                            </md-select>
                                            <span class="md-error" v-if="!$v.form.enabled.required">El estatus de habilitación es requerido</span>
                                        </md-field>
                                    </div>

                                    <div class="md-layout-item md-small-size-100">
                                        <md-field :class="getValidationClass('code')">
                                            <label for="code">Codigo del Cupon</label>
                                            <md-input name="code" id="code"  maxlength="8" minlength="8" autocomplete="off" v-model="form.code" :disabled="sending" />
                                            <span class="md-error" v-if="!$v.form.code.required">Se requiere un codigo de cupon</span>
                                            <span class="md-error" v-else-if="!$v.form.code.minlength">El codigo del cupon es muy corto</span>
                                            <span class="md-error" v-else-if="!$v.form.code.maxlength">El codigo del cupon es muy largo</span>
                                        </md-field>
                                    </div>

                                    <div class="md-layout-item md-small-size-100">
                                        <md-field :class="getValidationClass('role')">
                                            <label for="role">Rol</label>
                                            <md-select name="role" id="role" v-model="form.role" :disabled="sending">
                                                <md-option value="tenants">Arrendatario</md-option>
                                                <md-option value="owners">Propietario</md-option>
                                                <md-option value="agents">Agente</md-option>
                                                <md-option value="services">Servicio</md-option>
                                            </md-select>
                                            <span class="md-error" v-if="!$v.form.role.required">El rol de usuario es requerido</span>
                                        </md-field>
                                    </div>

                                    <div class="md-layout-item md-small-size-100">
                                        <md-field :class="getValidationClass('membership')">
                                            <label for="membership">Membresia</label>
                                            <md-select name="membership" id="membership" v-model="form.membership" :disabled="sending">
                                                <md-option value="Basic">Basic</md-option>
                                                <md-option value="Select">Select</md-option>
                                                <md-option value="Premium">Premium</md-option>
                                            </md-select>
                                            <span class="md-error" v-if="!$v.form.membership.required">La membresia es requerida</span>
                                        </md-field>
                                    </div>
                                </md-card-content>

                                <md-progress-bar md-mode="indeterminate" v-if="sending" />

                                <md-card-actions>
                                    <md-button type="submit" class="md-primary" :disabled="sending">Modificar Cupon</md-button>
                                </md-card-actions>
                            </md-card>

                            <md-snackbar :md-active.sync="couponSaved">Cupon Modificado exitosamente</md-snackbar>
                        </form>
                    </md-card-content>
                </md-card>
            </div>
        </div>
    </div>
</template>
<script>

import axios from 'axios'
import { validationMixin } from 'vuelidate'
import {required, email, minLength, maxLength} from 'vuelidate/lib/validators'

export default {
    name:'Edit',
    mixins: [validationMixin],
    data: () => ({
      form: {
        name: null,
        quantity: null,
        enabled: null,
        code: null,
        role: null,
        membership: null,
      },
      couponSaved: false,
      sending: false,
      saveDataUrl: '/adm/coupons/update',
      getDataUrl: '/adm/coupons/'
    }),
    validations: {
      form: {
            name: {
                required,
                minLength: minLength(3)
            },
            quantity: {
                required,
                maxLength: maxLength(2)
            },
            enabled: {
                required,
            },
            code:{
                required,
                minLength: minLength(8),
                maxLength: maxLength(8)
            },
            role:{
                required,
            },
            membership:{
                required,
            },
        }
        
    },
    computed:{
        idCoupon(){
            return this.$route.params.couponId
        }
    },
    methods: {
        getValidationClass (fieldName) {
            const field = this.$v.form[fieldName]

            if (field) {
            return {
                'md-invalid': field.$invalid && field.$dirty
            }
            }
        },
        clearForm () {
            this.$v.$reset()
            this.form.name = null
            this.form.quantity = null
            this.form.enabled = null
            this.form.code = null
            this.form.role = null
            this.form.membership = null
        },
        saveCoupon () {
            this.sending = true

            const vm = this;
            axios.post(vm.saveDataUrl, {
                id:vm.idCoupon,
                name:vm.form.name,
                quantity:vm.form.quantity,
                enabled:vm.form.enabled,
                code:vm.form.code,
                role:vm.form.role,
                membership:vm.form.membership
            })
            .then(function(response) {
                console.log(response)
                vm.couponSaved = true
                vm.sending = false
                vm.$router.push('/coupons')
            })
            .catch(function(error) {
                this.sending = false
                console.log(error)
            });
        },
        getCoupon(){
            const vm = this
            axios.get(vm.getDataUrl+vm.idCoupon)
            .then(function(response) {
                console.log(response)
                vm.form = response.data
            }).catch(function(error) {
                console.log(error)
            })
        },
        validateCoupon () {
            this.$v.$touch()

            if (!this.$v.$invalid) {
            this.saveCoupon()
            }
        }
    },
    mounted(){
        this.getCoupon()
    }
}
</script>