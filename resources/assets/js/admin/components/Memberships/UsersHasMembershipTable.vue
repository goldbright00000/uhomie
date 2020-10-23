<template>
  <div>
    <md-table v-model="users" :table-header-color="tableHeaderColor">
      <md-empty-state
        md-icon="person"
        md-label="Sin Usuarios"
        md-description="Cree usuarios de uHomie y asignele membresias desde este Admin Panel">
        <md-button class="md-primary md-raised" to="/memberships" >Regresar</md-button>
      </md-empty-state>
      <md-table-row slot="md-table-row" slot-scope="{ item }">
        <md-table-cell md-label="Nombre">{{ item.firstname }}</md-table-cell>
        <md-table-cell md-label="Opciones">
          <md-button  class="md-just-icon md-simple md-info" >
            <router-link :to="{path: '/memberships/' + membership.id + '/users/' + item.id }">
              <md-icon>settings</md-icon>
            </router-link>
            <md-tooltip md-direction="top">Modificar</md-tooltip>
          </md-button>
        </md-table-cell>
      </md-table-row>
    </md-table>
  </div>
</template>
<script>

export default {
  name: "users-has-membership-table",
  props: ['tableHeaderColor','membership','users'],
};
</script>
