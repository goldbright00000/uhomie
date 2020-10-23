<template>
  <div>
    <loading :active.sync="isLoading" :can-cancel="false" :is-full-page="fullPage" :loader="loader" :color="loaderColor"></loading>
    <md-table v-model="contracts" :table-header-color="tableHeaderColor">
      <md-table-row slot="md-table-row" slot-scope="{ item }">
        <md-table-cell md-label="Id">{{ item.id }}</md-table-cell>
        <md-table-cell md-label="Estado">
          {{ item.status }}
        </md-table-cell>
        <md-table-cell md-label="Propiedad">
          <a v-if="item.property_link" :href="item.property_link">
            <md-icon>house</md-icon>
          </a>
        </md-table-cell>
        <md-table-cell md-label="Contrato">
          <a :href="item.path_file ? item.path_file : item.path_file_pre">
            <md-icon>description</md-icon>
          </a>
        </md-table-cell>
        <md-table-cell md-label="Arrendatario">
          <span v-if="item.tenant">
            {{ statusCode(item.tenant.status_code) }}
          </span>
        </md-table-cell>
        <md-table-cell md-label="Dueño">
          <span v-if="item.owner">
            {{ statusCode(item.owner.status_code) }}
          </span>
        </md-table-cell>
        <md-table-cell md-label="Aval">
          <span v-if="item.collateral">
            {{ statusCode(item.collateral.status_code) }}
          </span>
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
  name: "contracts-table",
  props: ['tableHeaderColor'],
  components:{
    Loading
  },
  data() {
    return {
      contracts: [],
      isLoading: false,
      fullPage: true,
      loader: "dots",
      loaderColor: "#1ac036"
    };
  },
  methods: {
    getContracts() {
      const vm = this
      vm.isLoading = true;

      const url = '/adm/contracts'

      axios.get(url)
      .then((response) => {
        vm.isLoading = false
        vm.contracts = response.data.records
      });
    },

    statusCode(status) {
      //translate status
      switch(status) {
        case 'awaiting_signature': {
          return 'Esperando firma';
        }
        case 'signed': {
          return 'Firmado';
        }
        case 'declined': {
          return 'Rechazado';
        }
        default: {
          return status
        }
      }
    }
  },
  created() {    
    this.getContracts()
  }
};
</script>