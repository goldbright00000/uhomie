<template>
  <form  novalidate @submit.prevent="deleteUser">
    <md-card>
      <md-card-content>
        <div class="md-layout">
          <loading :active.sync="isLoading" :can-cancel="false" :is-full-page="fullPage" :loader="loader" :color="loaderColor"></loading>
          <h4 class="title">Seguro que desea eliminar el usario {{ form.firstName + ' ' + form.lastName }}?</h4>
          <div class="md-layout-item md-size-100 text-right">
            <md-button class="md-raised md-primary" type="button" @click="$router.go(-1)">Cancelar</md-button>
            <md-button class="md-raised md-danger" type="submit">Eliminar Usuario</md-button>
          </div>
        </div>
      </md-card-content>
    </md-card>
    <md-snackbar :md-active.sync="userDeleted">El usuario {{ lastUser }} ha sido eliminado con exito!</md-snackbar>
  </form>
</template>

<script>
  import axios from 'axios'
  import Loading from 'vue-loading-overlay';

  const loadDataUserUrl = '/adm/users';

  const csrf_token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  axios.defaults.headers.common = {
      'X-Requested-With': 'XMLHttpRequest',
      'X-CSRF-TOKEN' : csrf_token
  };

  export default {
    name: "delete-user-form",
    components:{
      Loading
    },
    props: {
      dataBackgroundColor: {
        type: String,
        default: ""
      }
    },
    data: () => ({
      form: {
        userId: null,
        firstName: null,
        lastName: null,
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
        this.form.firstName = null
        this.form.lastName = null
        this.form.email = null
      },
      deleteUser() {
        const vm = this;
        vm.isLoading = true
        const url = '/adm/users/' + vm.form.userId + '/delete'
        axios.post(url, {
          userId : vm.form.userId
        })
        .then(function(response) {
          vm.lastUser = `${vm.form.firstName} ${vm.form.lastName}`
          vm.userDeleted = true
          vm.isLoading = false
          vm.clearForm()
          vm.$router.push('/users')
        })
        .catch(function(error) {
          console.log(error);
        });
      },
      loadDataUser() {
        const vm = this;
        vm.isLoading = true
        const userId = JSON.parse(vm.$route.params.userId)
        var url = loadDataUserUrl + '/' + userId
        axios.get(url)
        .then((response) => {
          vm.isLoading = false
          if (response.data.user !== null) {
              vm.form = { userId : response.data.user.id , firstName: response.data.user.firstname, lastName: response.data.user.lastname, email:response.data.user.email }
          }else{
              vm.$router.push('/users')
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
