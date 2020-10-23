<template>
  <div class="content">
    <div class="md-layout">
      <div class="md-layout-item md-size-15"></div>
      <div class="md-layout-item md-medium-size-100 md-size-70">
        <form  novalidate @submit.prevent="deleteUser">
          <md-card>
            <md-card-content>
              <div class="md-layout">
                <loading :active.sync="isLoading" :can-cancel="false" :is-full-page="fullPage" :loader="loader" :color="loaderColor"></loading>
                <h4 class="title">Seguro que desea eliminar el usario {{ form.name + ' :: ' + form.email }}?</h4>
                <div class="md-layout-item md-size-100 text-right">
                  <md-button class="md-raised md-primary" type="button" @click="$router.go(-1)">Cancelar</md-button>
                  <md-button class="md-raised md-danger" type="submit">Eliminar Usuario</md-button>
                </div>
              </div>
            </md-card-content>
          </md-card>
          <md-snackbar :md-active.sync="userDeleted">El usuario {{ lastUser }} ha sido eliminado con exito!</md-snackbar>
        </form>
      </div>
    </div>
  </div>
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
    components:{
      Loading
    },
    props: {

    },
    data: () => ({
      form: {
        userId: null,
        name: null,
        email: null,
      },
      userDeleted: false,
      sending: false,
      lastUser: null,
      isLoading: false,
      fullPage: true,
      loader: "dots",
      loaderColor: "#1ac036",

    }),
    methods: {
      clearForm () {
        this.form.name = null
        this.form.email = null
      },
      deleteUser() {
        const vm = this;
        vm.isLoading = true
        const url = '/adm/users-admin/' + vm.form.userId + '/delete'
        axios.post(url, {
          userId : vm.form.userId
        })
        .then(function(response) {
          vm.lastUser = `${vm.form.firstName} ${vm.form.lastName}`
          vm.userDeleted = true
          vm.isLoading = false
          vm.clearForm()
          vm.$router.push('/users-admin')
        })
        .catch(function(error) {
          console.log(error);
        });
      },
      loadDataUser() {
        const vm = this;
        vm.isLoading = true
        const userId = JSON.parse(vm.$route.params.userId)
        var url = '/adm/users-admin/' + userId
        axios.get(url)
        .then((response) => {
          vm.isLoading = false
          if (response.data.user !== null) {
              vm.form = { userId : response.data.user.id , name: response.data.user.name, email: response.data.user.email }
          }else{
              vm.$router.push('/users-admin')
          }
        });
      }
    },
    mounted() {
      //do something after mounting vue instance
    },
    created() {
      //do something after creating vue instance
      this.loadDataUser()
    }
  }
</script>

