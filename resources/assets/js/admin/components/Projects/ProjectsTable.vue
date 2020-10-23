<template>
  <div>
    <md-table v-model="properties">
      <md-table-row slot="md-table-row" slot-scope="{ item }">
        <md-table-cell md-label="Nombre">{{ item.name }}</md-table-cell>
        <md-table-cell md-label="Dueño">
          {{ item.owner_firstname + " " + item.owner_lastname }}
        </md-table-cell>
        <md-table-cell md-label="Agente">
          {{ item.agente }}
        </md-table-cell>
        <md-table-cell md-label="Empresa">
          {{ item.company }}
        </md-table-cell>
        <md-table-cell md-label="Estado">
          {{ item.condition_label }}
        </md-table-cell>
        <md-table-cell md-label="Opciones" v-if="userIsAdmin">
          <md-button  class="md-just-icon md-simple md-primary">
            <router-link :to="{path: '/projects/' + item.id + '/edit'}"><md-icon>edit</md-icon></router-link>
            <md-tooltip md-direction="top">Editar</md-tooltip>
          </md-button>
          <md-button  class="md-just-icon md-simple md-danger" >
            <router-link :to="{path: '/properties/' + item.id + '/delete'}"><md-icon>close</md-icon></router-link>
            <md-tooltip md-direction="top">Eliminar</md-tooltip>
          </md-button>
        </md-table-cell>
      </md-table-row>
    </md-table>
  </div>
</template>
<script>
  const user_role = window.ROLE;

import axios from 'axios'

export default {
  name: "properties-table",
  props: ["tableHeaderColor", "propertiesList"],
  data() {
    return {
      properties: [{}]
    };
  },
  watch: {
    propertiesList: function() {
      this.properties = this.propertiesList
    }
  },
  computed: {
    userIsAdmin: function() {
      return user_role == 1;
    }
  },
  methods: {
      getProperties() {
        const propertiesUrl = '/adm/projects';
        const vm = this
        axios.get(propertiesUrl)
        .then((response) => {
          vm.properties = response.data.records
          console.log(vm.properties)
        });
      },
  },
  created() {
      this.getProperties();
  }
};
</script>
