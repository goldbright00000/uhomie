<template>
  <div>
    <md-table v-model="usersMembershipsDefault" md-card >
      <md-table-toolbar>
        <h1 class="md-title">Usuarios con Perfiles Incompletos</h1>
      </md-table-toolbar>
      <md-table-row slot="md-table-row" slot-scope="{ item }"  >
        <md-table-cell md-label="Nombre">{{ item.role_name }}</md-table-cell>
        <md-table-cell md-label="Usuarios">{{ item.u_count }}</md-table-cell>
      </md-table-row>
    </md-table>
  </div>
</template>
<script>
export default {
  name: "users-memberships-default-table",
  props: ['usersMembershipsDefault']
};
</script>
