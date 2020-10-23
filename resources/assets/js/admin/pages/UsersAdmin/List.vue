<template>
  <div class="content">
      <div class="md-layout-item md-medium-size-100 md-xsmall-size-100 md-size-100">
        <md-card>
          <md-card-header data-background-color="black">
            <h4 class="title">Listado de Usuarios</h4>
            <p class="category">Usuarios backend</p>
          </md-card-header>
          <md-card-content>
            <loading :active.sync="isLoading" :can-cancel="false" :is-full-page="fullPage" :loader="loader" :color="loaderColor"></loading>
				    <md-table v-model="users" table-header-color="black">
				      <md-table-row slot="md-table-row" slot-scope="{ item }">
				        <md-table-cell md-label="Nombre">{{ item.name }}</md-table-cell>
				        <md-table-cell md-label="Email">{{ item.email }}</md-table-cell>
				        <md-table-cell md-label="Role">
				        	{{ roleIs(item.role) }}
				        </md-table-cell>
				        <md-table-cell md-label="Opciones">
				          <md-button  class="md-just-icon md-simple md-primary">
				            <router-link :to="{path: '/users-admin/' + item.id + '/edit'}"><md-icon>edit</md-icon></router-link>
				            <md-tooltip md-direction="top">Editar</md-tooltip>
				          </md-button>
				          <md-button  class="md-just-icon md-simple md-danger" >
				            <router-link :to="{path: '/users-admin/' + item.id + '/delete'}"><md-icon>close</md-icon></router-link>
				            <md-tooltip md-direction="top">Eliminar</md-tooltip>
				          </md-button>
				        </md-table-cell>
				      </md-table-row>
				    </md-table>
          </md-card-content>
        </md-card>
      </div>
    <md-button class="md-fab flotante md-fab-bottom-right md-success md-icon-button" aria-label="Agregar Usuario" to="/users-admin/create">
      <md-icon>add</md-icon>
      <md-tooltip md-direction="top">Nuevo Usuario</md-tooltip>
    </md-button>
  </div>
</template>

<script>
import axios from 'axios'
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';

export default {
  components: {
  	Loading
  },
  data: () => ({
    bottomPosition: 'md-bottom-right',
    selected: [],
    users: [],
    isLoading: false,
    fullPage: true,
    loader: "dots",
    loaderColor: "#1ac036",
    tableHeaderColor: {
      type: String,
      default: ""
    },
    rolesName: ['Admin', 'Gerencial','Ejecutivo']
  }),
  created() {
    this.fetch();
  },
  methods: {
    roleIs(role) {
      return this.rolesName[role-1] ? this.rolesName[role-1] : role
    },
  	fetch: function () {
      this.isLoading = true;
      axios.get('/adm/users-admin')
      .then((response) => {
        this.isLoading = false
        this.users = response.data.records
      });
    }
  }
};
</script>
<style media="screen" lang="css">

  .cursor-pointer {
    cursor: pointer;
  }

.md-button.md-fab.flotante {
    right: 30px;
    bottom: 30px;
    position: fixed;
    z-index: 4;
    height: 55px;
    min-width: 55px;
    width: 55px;
    line-height: 55px;
}
</style>
