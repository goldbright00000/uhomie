<template>
  <div>
    <loading :active.sync="isLoading" :can-cancel="false" :is-full-page="fullPage" :loader="loader" :color="loaderColor"></loading>
    <md-table v-model="payments" :table-header-color="tableHeaderColor">
      <md-table-row slot="md-table-row" slot-scope="{ item }">
        <md-table-cell md-label="Usuario">
          <span v-if="item.user"> {{ item.user.firstname + " " + item.user.lastname }} </span>
        </md-table-cell>
        <md-table-cell md-label="Metodo">{{ item.method }}</md-table-cell>
        <md-table-cell md-label="Detalles">{{ item.details }}</md-table-cell>
        <md-table-cell md-label="Monto">{{ item.amount }}</md-table-cell>
        <md-table-cell md-label="IVA">{{ item.iva }}</md-table-cell>
        <md-table-cell md-label="Total">{{ item.total }}</md-table-cell>
        <md-table-cell md-label="Estado">
          <b>{{ statusName[item.status] }}</b>
        </md-table-cell>
      </md-table-row>
    </md-table>
  </div>
</template>
<script>
import axios from 'axios'
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';
export default {
  name: "payments-table",
  props: ['tableHeaderColor'],
  components:{
    Loading
  },
  data() {
    return {
      payments: [{}],
      isLoading: false,
      fullPage: true,
      loader: "dots",
      loaderColor: "#1ac036",
      statusName: {
        0: 'Creado',
        1: 'Aprobado',
        2: 'Rechazado',
        3: 'Reembolsado',
        4: 'Cancelado'
      }
    };
  },
  methods: {
    getPayments() {
      const vm = this
      vm.isLoading = true;
      const url = '/adm/payments'
      axios.get(url)
      .then((response) => {
        vm.isLoading = false
        vm.payments = response.data.records
        console.log(vm.payments)
      });
    }
  },
  created() {
    //do something after creating vue instance
    this.getPayments()
  }
};
</script>