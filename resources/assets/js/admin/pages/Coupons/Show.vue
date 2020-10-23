<template>
    <div class="content">
        <div class="md-layout">
            <div class="md-layout-item md-medium-size-100 md-xsmall-size-100 md-size-100">
                <md-card>
                    <md-card-header data-background-color="black">
                        <h4 class="title">Cupon - {{coupon.name}}</h4>
                        <p class="category">Información del Cupon</p>
                    </md-card-header>
                    <md-card-content>
                        <md-card class="md-layout-item md-size-100 md-small-size-100">
                            <md-card-content>
                                <div class="md-layout md-gutter">
                                    <div class="md-layout-item md-small-size-100">
                                        <md-field>
                                            <label for="id">Id del Cupon</label>
                                            <md-input name="id" id="id" v-model="coupon.id" disabled />
                                        </md-field>
                                    </div>
                                    <div class="md-layout-item md-small-size-100">
                                        <md-field>
                                            <label for="name">Nombre del Cupon</label>
                                            <md-input name="name" id="name" v-model="coupon.name" disabled />
                                        </md-field>
                                    </div>

                                    <div class="md-layout-item md-small-size-100">
                                        <md-field>
                                            <label>Cantidad de Usos</label>
                                            <md-input type="number" maxlength="2" id="quantity" name="quantity" v-model="coupon.quantity" disabled></md-input>
                                        </md-field>
                                    </div>

                                    <div class="md-layout-item md-small-size-100">
                                        <md-field>
                                            <label for="enabled">Habilitado</label>
                                            <md-select name="enabled" v-model="coupon.enabled" id="enabled" disabled>
                                                <md-option value="1">Si</md-option>
                                                <md-option value="0">No</md-option>
                                            </md-select>
                                        </md-field>
                                    </div>

                                    <div class="md-layout-item md-small-size-100">
                                        <md-field>
                                            <label for="code">Codigo del Cupon</label>
                                            <md-input name="code" id="code"  maxlength="8" minlength="8" autocomplete="off" v-model="coupon.code" disabled/>
                                        </md-field>
                                    </div>

                                    <div class="md-layout-item md-small-size-100">
                                        <md-field>
                                            <label for="role">Rol</label>
                                            <md-select name="role" id="role" v-model="coupon.role" disabled>
                                                <md-option value="tenants">Arrendatario</md-option>
                                                <md-option value="owners">Propietario</md-option>
                                                <md-option value="agents">Agente</md-option>
                                                <md-option value="services">Servicio</md-option>
                                            </md-select>
                                        </md-field>
                                    </div>

                                    <div class="md-layout-item md-small-size-100">
                                        <md-field>
                                            <label for="membership">Membresia</label>
                                            <md-select name="membership" id="membership" v-model="coupon.membership" disabled>
                                                <md-option value="Basic">Basic</md-option>
                                                <md-option value="Select">Select</md-option>
                                                <md-option value="Premium">Premium</md-option>
                                            </md-select>
                                        </md-field>
                                    </div>

                                    <div class="md-layout-item md-small-size-100">
                                        <md-field>
                                            <label for="created_at">Creación del Cupon</label>
                                            <md-input name="created_at" id="created_at" v-model="coupon.created_at" disabled />
                                        </md-field>
                                    </div>

                                    <div class="md-layout-item md-small-size-100">
                                        <md-field>
                                            <label for="updated_at">Modificación del Cupon</label>
                                            <md-input name="updated_at" id="updated_at" v-model="coupon.updated_at" disabled />
                                        </md-field>
                                    </div>

                                    <div class="md-layout-item md-small-size-100">
                                        <md-field>
                                            <label>Usado</label>
                                            <md-input type="number" maxlength="2" id="users" name="users" v-model="users.length" disabled></md-input>
                                        </md-field>
                                    </div>

                                </div>
                            </md-card-content>
                        </md-card>
                        <md-table v-model="users" :md-sort.sync="currentSort" :md-sort-order.sync="currentSortOrder" :md-sort-fn="customSort" md-card>
                            <md-table-toolbar>
                                <h2 class="md-title">Usuarios que han usado el Cupon</h2>
                            </md-table-toolbar>

                            <md-table-row slot="md-table-row" slot-scope="{ item }">
                                <md-table-cell md-label="ID" md-numeric>{{ item.id }}</md-table-cell>
                                <md-table-cell md-label="Nombre" md-sort-by="name">{{ item.name }}</md-table-cell>
                                <md-table-cell md-label="Email" md-sort-by="email">{{ item.email }}</md-table-cell>
                                <md-table-cell md-label="Telefono" md-sort-by="phone">{{ item.phone }}</md-table-cell>
                                <md-table-cell md-label="Creación" md-sort-by="created_at">{{ item.created_at.date }}</md-table-cell>
                            </md-table-row>
                        </md-table>
                    </md-card-content>
                </md-card>
            </div>
        </div>
    </div>
</template>
<script>
import axios from 'axios'
export default {
    name: 'Show',
    data: () => ({
        currentSort: 'name',
        currentSortOrder: 'asc',
        coupon: {},
        users: [],
        getDataUrl: '/adm/coupons/',
        getDataUsers: '/adm/coupons/users/'
    }),
    methods:{
        getCoupon(){
            const vm = this
            axios.get(vm.getDataUrl+vm.idCoupon)
            .then(function(response) {
                console.log(response)
                vm.coupon = response.data
            }).catch(function(error) {
                console.log(error)
            })
        },
        getUsers(){
            const vm = this
            axios.get(vm.getDataUsers+vm.idCoupon)
            .then(function(response) {
                console.log(response)
                vm.users = response.data
            }).catch(function(error) {
                console.log(error)
            })
        },
    },
    computed:{
        idCoupon(){
            return this.$route.params.couponId
        }
    },
    mounted(){
        this.getCoupon();
        this.getUsers();
    }
}
</script>