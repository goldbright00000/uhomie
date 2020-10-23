<template>
  <div>
    <form id="collateralDataForm" @submit.prevent="save">
      <div class="md-layout-item md-small-size-100 md-size-100">
        <h3 class="md-title"> Informacion del Aval </h3>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100">
        <md-field>
          <label>Nombre</label>
          <md-input v-model="form.firstname" type="text" name="firstname" id="firstname" ></md-input>
        </md-field>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100">
        <md-field>
          <label>Apellido</label>
          <md-input v-model="form.lastname" type="text" name="lastname" id="lastname" ></md-input>
        </md-field>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100">
        <md-field>
          <label>Correo Electronico</label>
          <md-input v-model="form.email" type="text" name="email" id="email" ></md-input>
        </md-field>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100" >
        <md-field>
          <label>Confirmar Correo</label>
          <md-input v-model="form.emailConfirm" type="email" name="emailConfirm" id="emailConfirm" ></md-input>
        </md-field>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100">
        <md-button type="submit" class="md-info" >Guardar</md-button>
      </div>
    </form>
  </div>
</template>
<script>
  import axios from 'axios'
  import Loading from 'vue-loading-overlay';
  import 'vue-loading-overlay/dist/vue-loading.css';
  const csrf_token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  axios.defaults.headers.common = {
    'X-Requested-With': 'XMLHttpRequest',
    'X-CSRF-TOKEN' : csrf_token
  };
  export default {
    name: "collateral-data-form",
    components:{
      Loading
    },
    data(){
      return {
        form:{
          collateralId: null,
          firstname: null,
          lastname: null,
          email: null,
          emailConfirm: null
        },
        user:null,
        isLoading: false,
        fullPage: true,
        loader: "dots",
        loaderColor: "#1ac036",
        dataSaved: false,
      }
    },
    methods: {
      save() {
        const vm = this;
        vm.isLoading = true
        const saveDataUrl = '/adm/users/tenant-registration/collateral-data'
        axios.post(saveDataUrl, {
          collateral: 1,
          collateralId: vm.form.id,
          firstname: vm.form.firstname,
          lastname: vm.form.lastname,
          email: vm.form.email,
          emailConfirm: vm.form.email
        })
        .then(function(response) {
          vm.getUserData()
          vm.getCollateralData()
          vm.dataSaved = true
        })
        .catch(function(error) {
          console.log(error);
        });
      },
      getUserData() {
        const vm = this;
        const userId = JSON.parse(vm.$route.params.userId)
        var url = '/adm/users/' + userId
        vm.isLoading = true
        axios.get(url)
        .then((response) => {
          vm.isLoading = false
          if ( response.data.user !== null ) {
            vm.user = response.data.user
          }else{
            vm.$router.push('/users/create')
          }
        });
      },
      getCollateralData() {
        const vm = this;
        const userId = JSON.parse(vm.$route.params.userId)
        var url = '/adm/users/' + userId + '/collateral'
        vm.isLoading = true
        axios.get(url)
        .then((response) => {
          vm.isLoading = false
          if ( response.data.user !== null ) {
            vm.form = {
              collateralId: response.data.collateral.id,
              firstname: response.data.collateral.firstname,
              lastname: response.data.collateral.lastname,
              email: response.data.collateral.email,
              emailConfirm: response.data.collateral.email
            }
          }else{
            vm.$router.push('/users/create')
          }
        });
      }
    },
    created() {
      this.getUserData()
      this.getCollateralData()
    }
  }
</script>
