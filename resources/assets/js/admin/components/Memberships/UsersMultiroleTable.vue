<template>
  <div>
    <md-table v-model="users" md-card >
      <md-table-row slot="md-table-row" slot-scope="{ item }"  >
        <md-table-cell md-label="Nombre">{{ item.firstname }}</md-table-cell>
        <md-table-cell md-label="Apellido">{{ item.lastname }}</md-table-cell>
        <md-table-cell md-label="Perfiles">
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
      </md-table-row>
    </md-table>
  </div>
</template>
<script>
export default {
  name: "users-multirole-table",
  props: ['users']
};
</script>
