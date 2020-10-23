<template>
  <div>
    <md-table v-model="services">
      <md-table-row slot="md-table-row" slot-scope="{ item }">
      	<md-table-cell md-label="Nombre">
        	{{ item.name }}
        </md-table-cell>
        <md-table-cell md-label="Compania">
        	{{ item.company.name }}
        </md-table-cell>
        <md-table-cell md-label="Dueño">
          {{ item.user.name }}<br/>
          <small>
          	<a :href="item.user.email" v-html="item.user.email"></a>
          </small>
        </md-table-cell>
        <md-table-cell md-label="Ciudad">
          {{ item.city }}
          <small>{{ item.address }}</small>
        </md-table-cell>
        <md-table-cell md-label="Descripcion">
          {{ item.description }}
        </md-table-cell>
        <md-table-cell md-label="Teléfono">
          {{ item.phone }}  <span v-if="item.cell_phone">| {{ item.cell_phone }}</span>
        </md-table-cell>
      </md-table-row>
    </md-table>
  </div>
</template>
<script>
  const user_role = window.ROLE;

import axios from 'axios'

export default {
  name: "services-table",
  props: ["tableHeaderColor"],
  data() {
    return {
      services: [{}]
    };
  },
  computed: {
    userIsAdmin: function() {
      return user_role == 1;
    }
  },
  methods: {
      getServices() {
        const servicesUrl = '/adm/services';
        const vm = this
        axios.get(servicesUrl)
        .then((response) => {
          vm.services = response.data.records
          console.log(vm.services)
        });
      },
  },
  created() {
      this.getServices();
  }
};
</script>
