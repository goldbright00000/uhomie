<template>
  <div>
    <md-table v-model="role.memberships" v-if="role.role_hidden==0" >
      <md-table-row slot="md-table-row" slot-scope="{ item }"  >
        <md-table-cell md-label="Membresía">

          <img v-if="item.name == 'Basic'" src="/images/logo_basic.png" style="width: 80px">
          <img v-if="item.name == 'Select'" src="/images/logo_select.png" style="width: 80px">
          <img v-if="item.name == 'Premium'" src="/images/logo_premium.png" style="width: 80px">
          
          <i>| {{ item.name }}</i>
        </md-table-cell>
        <md-table-cell md-label="Usuarios">
          <md-button class="md-just-icon md-simple md-primary" :to="{path: '/memberships/' + item.id + '/users'}">
              {{ item.u_count }}
              <md-icon>person</md-icon>
            <md-tooltip md-direction="top" v-if="item.u_count > 1 || item.u_count == 0">{{ item.u_count }} Usuarios</md-tooltip>
            <md-tooltip md-direction="top" v-else>{{ item.u_count }} Usuario</md-tooltip>
          </md-button>
        </md-table-cell>
        <md-table-cell md-label="Editar membresía" v-if="userIsAdmin">
          <md-button  class="md-just-icon md-simple md-info" >
            <router-link :to="{path: '/memberships/' + item.id + '/edit/' }">
              <md-icon>edit</md-icon>
            </router-link>
            <md-tooltip md-direction="top">Modificar Membresia</md-tooltip>
          </md-button>
        </md-table-cell>
      </md-table-row>
    </md-table>
  </div>
</template>
<script>
  const user_role = window.ROLE;
  
export default {
  name: "memberships-table",
  props: ['role'],
  computed: {
    userIsAdmin: function() {
      return user_role == 1;
    }
  },
};
</script>
