<template>
  <div class="content">
    <div class="md-layout">
      <div class="md-layout-item md-medium-size-100 md-xsmall-size-100 md-size-100">
        <md-card>
          <md-card-header data-background-color="green">
            <h4 class="title">Usuarios con membresia {{ membership.name }} del Rol {{ membership.role.name }}</h4>
            <p class="category">Listado de usuarios por membresia</p>
          </md-card-header>
          <md-card-content>
            <users-has-membership-table table-header-color="green" :membership="membership" :users="users" ></users-has-membership-table>
          </md-card-content>
        </md-card>
      </div>
    </div>
    <md-button class="md-fab flotante md-fab-bottom-right md-success md-icon-button" aria-label="Regresar" to="/memberships" >
      <md-icon>keyboard_backspace</md-icon>
      <md-tooltip md-direction="top">Regresar</md-tooltip>
    </md-button>
  </div>
</template>
<script>
import axios from 'axios'
import { UsersHasMembershipTable } from "../../components";
export default {
  components: {
    UsersHasMembershipTable
  },
  data() {
    return {
      membership: {
        id: null,
        name: null,
        features: null,
        enabled: null,
        role_id: null,
        role: {
          id: null,
          name: null,
          slug: null,
          hidden: null,
          created_at: null,
          updated_at: null
        }
      },
      users: [{}]
    }
  },
  methods: {
      loadMembership() {
        const vm = this
        const membershipId = JSON.parse(vm.$route.params.membershipId)
        const url = '/adm/memberships/' + membershipId + '/users'
        axios.get(url)
        .then((response) => {
          vm.membership = response.data.membership
          vm.users = response.data.users
        });
      },
  },
  created() {
    this.loadMembership();
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
