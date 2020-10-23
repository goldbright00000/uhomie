<template>
  <div>
    <v-toolbar dark fixed app>
      <v-toolbar-side-icon 
        @click.stop="drawer = !drawer"></v-toolbar-side-icon>
      <v-toolbar-title>uHomie | admin</v-toolbar-title>

      <v-spacer></v-spacer>

      <v-btn icon href="/adminlogout">
        <v-icon>logout</v-icon>
      </v-btn>
    </v-toolbar>

    <v-navigation-drawer
      v-model="drawer"
      dark
      app
    >
      <v-list>
        <v-list-tile
          v-for="(l, i) in menu"
          :key="i"
          :to="{ name: l.route }"
          v-if="roleMenu(l.role)"
        >
          <v-list-tile-action>
            <v-icon v-html="l.icon"></v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title 
              v-html="l.name"
              class="text-uppercase font-weight-light white--text"
              ></v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>
      </v-list>
    </v-navigation-drawer>

    <v-content>
      <transition name="fade" mode="out-in">
        <router-view></router-view>
      </transition>
    </v-content>

  </div>
</template>

<script>
  const user_role = window.ROLE;

export default {
  components: {
    
  },
  data() {
    return {
      drawer: true,
      menu: [
        { name: 'Dashboard', icon: 'dashboard', route: 'Dashboard' , role: [1,2,3]},
        { name: 'Usuarios', icon: 'person', route: 'Usuarios' , role: [1,2,3]},
        { name: 'Propiedades', icon: 'house', route: 'Propiedades' , role: [1,2]},
        { name: 'Propiedades Ejecutivas', icon: 'house', route: 'PropiedadesEjecutivas' , role: [3]},
        { name: 'Proyectos', icon: 'work', route: 'Proyectos' , role: [1,2]},
        { name: 'Servicios', icon: 'build', route: 'Services' , role: [1,2]},
        { name: 'Membresías', icon: 'vpn_key', route: 'Membresias' , role: [1,2]},
        { name: 'Pagos', icon: 'content_paste', route: 'Pagos' , role: [1,2]},
        { name: 'Scoring', icon: 'star_border', route: 'Scoring' , role: [1,2]},
        { name: 'Contratos', icon: 'assignment', route: 'Contratos' , role: [1,2]},
        { name: 'Cupones', icon: 'receipt', route: 'Cupones', auth: true, role: [1]},
        { name: 'Usuarios Admin', icon: 'https', route: 'UsuariosAdmin', auth: true, role: [1]},
        { name: 'Configuraciones', icon: 'build', route: 'Configuraciones', auth: true, role: [1]},
        { name: 'User Admin', icon: 'build', route: 'UserAdmin', auth: true, role: [1]},
      ],
      user_role: user_role
    }
  },
  computed: {
    userIsAdmin: function() {
      return user_role == 1;
    },
  },
  methods:{
    roleMenu: function(roles){
      var result = roles.filter(role => role == user_role);
      if(result.length == 1){
        return true
      } else {
        return false
      }
    }  
  }
};
</script>

<style lang="scss">
  .fade-enter-active,
  .fade-leave-active {
    transition: opacity 0.1s;
  }

  .fade-enter,
    .fade-leave-to
      /* .fade-leave-active in <2.1.8 */

   {
    opacity: 0;
  }
</style>