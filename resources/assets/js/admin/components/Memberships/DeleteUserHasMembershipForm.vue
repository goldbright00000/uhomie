<template>
  <form  novalidate @submit.prevent="detachUserHasMembership">
    <md-card>
      <md-card-content>
        <div class="md-layout">
          <loading :active.sync="isLoading" :can-cancel="false" :is-full-page="fullPage" :loader="loader" :color="loaderColor"></loading>
          <h4 class="title">Desea eliminar la Membresia al Usuario <strong>{{ user.firstname }}</strong>  </h4>
          <div class="md-layout-item md-size-100 text-right">

            <md-button class="md-raised md-primary" type="button" @click="$router.go(-1)">Cancelar</md-button>
            <md-button class="md-raised md-danger" type="submit">Eliminar</md-button>
          </div>
        </div>
      </md-card-content>
    </md-card>
  </form>
</template>

<script>
  import axios from 'axios'
  import Loading from 'vue-loading-overlay';
  const csrf_token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  axios.defaults.headers.common = {
      'X-Requested-With': 'XMLHttpRequest',
      'X-CSRF-TOKEN' : csrf_token
  };
  export default {
    name: "delete-user-has-membership-form",
    components:{
      Loading
    },
    props: ['dataBackgroundColor','user'],
    data: () => ({
      isLoading: false,
      fullPage: true,
      loader: "dots",
      loaderColor: "#1ac036",
    }),
    methods: {
      detachUserHasMembership() {
        const vm = this;
        vm.isLoading = true
        const membershipId = JSON.parse(vm.$route.params.membershipId);
        const userId = JSON.parse(vm.$route.params.userId);
        const detachUrl = '/adm/memberships/'+membershipId+'/users/'+ userId + '/delete'
        axios.post(detachUrl,{
          roleId: vm.user.membership.role.id
        })
        .then(function(response) {
          vm.isLoading = false
          // vm.$router.push('/memberships/' + membershipId + '/users')
          vm.$router.go(-2);
        })
        .catch(function(error) {
          console.log(error);
        });
      }
    }
  }
</script>
