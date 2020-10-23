<template>
    <div class="content">
        <div class="md-layout">
            <div class="md-layout-item md-medium-size-100 md-xsmall-size-100 md-size-100">
                <md-card>
                    <md-card-header data-background-color="black">
                        <h4 class="title">Cupones</h4>
                        <p class="category">Listado de todos los cupones</p>
                    </md-card-header>
                    <md-card-content>
                        <md-table>
                            <md-table-row>
                                <md-table-head md-numeric>ID</md-table-head>
                                <md-table-head>Nombre</md-table-head>
                                <md-table-head>Rol</md-table-head>
                                <md-table-head>Membresia</md-table-head>
                                <md-table-head>Codigo</md-table-head>
                                <md-table-head>Cantidad</md-table-head>
                                <md-table-head>Habilitado</md-table-head>
                                <md-table-head>Acciones</md-table-head>
                            </md-table-row>

                            <md-table-row v-for="coupon in coupons" :key="coupon.coupon.id">
                                <md-table-cell md-numeric>{{coupon.coupon.id}}</md-table-cell>
                                <md-table-cell>{{coupon.coupon.name}}</md-table-cell>
                                <md-table-cell>{{coupon.role.name}}</md-table-cell>
                                <md-table-cell>{{coupon.membership.name}}</md-table-cell>
                                <md-table-cell>{{coupon.coupon.code}}</md-table-cell>
                                <md-table-cell>{{coupon.coupon.quantity}}</md-table-cell>
                                <md-table-cell>{{coupon.coupon.enabled == 1 ? 'Si' : 'No'}}</md-table-cell>
                                <md-table-cell>
                                    <a @click="$router.push('/coupons/show/'+coupon.coupon.id)"><md-icon>visibility</md-icon></a>
                                    <a @click="$router.push('/coupons/edit/'+coupon.coupon.id)"><md-icon>edit</md-icon></a>
                                </md-table-cell>
                            </md-table-row>
                        </md-table>
                    </md-card-content>
                </md-card>
            </div>
        </div>
        <md-button class="md-fab flotante md-fab-bottom-right md-success md-icon-button" aria-label="Add a Coupon" to="/coupons/create">
        <md-icon>add</md-icon>
        <md-tooltip md-direction="top" >Nuevo Cupon</md-tooltip>
        </md-button>
    </div>
</template>
<script>
import axios from 'axios'
export default {
    name:'List',
    data: () => ({
        coupons: [],
        urlCoupons: '/adm/coupons'
    }),
    methods:{
        getCoupons(){
            const vm = this
            axios.get(vm.urlCoupons)
            .then(function(response) {
                console.log(response)
                vm.coupons = response.data
            })
        }
    },
    mounted(){
        this.getCoupons()
    }
        
}
</script>