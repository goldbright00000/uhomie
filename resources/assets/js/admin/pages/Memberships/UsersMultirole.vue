<template>
  <div class="content">
    <div class="md-layout">
      <div class="md-layout-item md-medium-size-100 md-xsmall-size-100 md-size-100">
        <md-card>
          <md-card-header data-background-color="green">
            <h4 class="title">Usuarios con Multiples Perfiles</h4>
            <p class="category">Listado de usuarios con multiples perfiles</p>
          </md-card-header>
          <md-card-content>
            <users-multirole-table table-header-color="green" v-bind:users="users" ></users-multirole-table>
          </md-card-content>
        </md-card>
      </div>
    </div>
    <md-button class="md-fab flotante md-fab-bottom-right md-success md-icon-button" aria-label="Regresar" type="button" @click="$router.go(-1)" >
      <md-icon>keyboard_backspace</md-icon>
      <md-tooltip md-direction="top">Regresar</md-tooltip>
    </md-button>
  </div>
</template>

<script>
import { UsersMultiroleTable } from "../../components";
import axios from 'axios'
export default {
  components: {
    UsersMultiroleTable
  },
  data() {
    return {
      users: [{}]
    }
  },
  methods: {
      getUsers() {
        const vm = this
        const url = "/adm/memberships/descriptors/users-multirole"
        axios.get(url)
        .then((response) => {
          vm.users = response.data.users
        });
      }
  },
  mounted() {
    this.getUsers()
  }
};
</script>
<style media="screen" lang="css">
.md-button.md-fab.flotante {
    right: 60px;
    bottom: 30px;
    position: fixed;
    z-index: 4;
    height: 55px;
    min-width: 55px;
    width: 55px;
    line-height: 55px;
}
</style>
