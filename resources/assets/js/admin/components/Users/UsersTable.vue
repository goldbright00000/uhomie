<template>
  <div>
    <loading :active.sync="isLoading" :can-cancel="false" :is-full-page="fullPage" :loader="loader" :color="loaderColor"></loading>
    <md-table v-model="users" :table-header-color="tableHeaderColor">
      <md-table-row slot="md-table-row" slot-scope="{ item }">
        <md-table-cell md-label="Nombre">{{ item.firstname }}</md-table-cell>
        <md-table-cell md-label="Apellido">{{ item.lastname }}</md-table-cell>
        <md-table-cell md-label="Email">{{ item.email }}</md-table-cell>
        <md-table-cell md-label="Rol">
          <ul>
            <div v-for="(membership,i) in item.memberships"  >
                <li style="text-align:left;">
                  <router-link :to="{path: '/memberships/' + membership.id + '/users/' + item.id }">
                    <span v-if="membership.name == 'Default'" >
                      {{ membership.role.name }} - Perfil Incompleto
                    </span>
                    <span v-else >
                      {{ membership.role.name }} - {{ membership.name }}
                    </span>
                  </router-link>
                </li>
            </div>
          </ul>
        </md-table-cell>
        <md-table-cell md-label="Opciones" v-if="userIsAdmin">
          <md-button  class="md-just-icon md-simple md-primary">
            <router-link :to="{path: '/users/' + item.id + '/edit'}"><md-icon>edit</md-icon></router-link>
            <md-tooltip md-direction="top">Editar</md-tooltip>
          </md-button>
          <md-button  class="md-just-icon md-simple md-danger" >
            <router-link :to="{path: '/users/' + item.id + '/delete'}"><md-icon>close</md-icon></router-link>
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
const usersUrl = '/adm/users';
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';

export default {
  name: "users-table",
  props: {
    tableHeaderColor: {
      type: String,
      default: ""
    }, 
    usersList: {}
  },
  components:{
    Loading
  },
  computed: {
    userIsAdmin: function() {
      return user_role == 1;
    }
  },
  data() {
    return {
      selected: [],
      users: [],
      isLoading: false,
      fullPage: true,
      loader: "dots",
      loaderColor: "#1ac036"

    };
  },
  watch: {
    usersList: function() {
      this.users = this.usersList
    }
  },
  methods: {
      fetch: function () {
        this.isLoading = true;
        axios.get(usersUrl)
        .then((response) => {
          this.isLoading = false
          this.users = response.data.records
          console.log(this.users)
        });
      }
  },
  created() {
      this.fetch();
  }
};
</script>
