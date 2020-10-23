<template>
  <div>
    <loading :active.sync="isLoading" :can-cancel="false" :is-full-page="fullPage" :loader="loader" :color="loaderColor"></loading>
    <div class="md-layout">
      <div class="md-layout-item md-medium-size-100 md-size-100">
        <div class="md-layout-item md-small-size-100 md-size-100">
          <h3 class="md-title"> Datos Laborales</h3>
        </div>
        <div class="md-layout-item md-small-size-100 md-size-100">
          <md-field >
            <md-select v-model="employment_type_selected" name="employment_type" id="employment_type" placeholder="Tipo de Trabajo">
              <md-option value="1">Empleado</md-option>
              <md-option value="2">Cuenta Propia</md-option>
              <md-option value="3">Desempleado</md-option>
            </md-select>
          </md-field>
        </div>
      </div>
      <div class="md-layout-item md-medium-size-100 md-size-100">
        <employed-data-form v-if="employment_type_selected == 1"/>
        <owner-data-form v-if="employment_type_selected == 2"/>
        <unemployed-data-form v-if="employment_type_selected == 3"/>
      </div>
    </div>
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
  import {
    EmployedDataForm,
    OwnerDataForm,
    UnemployedDataForm
  } from '../Tenant'
  export default {
    name: "second-step",
    components:{
      EmployedDataForm,
      OwnerDataForm,
      UnemployedDataForm,
      Loading
    },
    data() {
      return {
        user:null,
        employment_type_selected:null,
        isLoading: false,
        fullPage: true,
        loader: "dots",
        loaderColor: "#1ac036",
        dataSaved: false,
      }
    },
    methods: {
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
            vm.employment_type_selected = vm.user.employment_type
          }else{
            vm.$router.push('/users/create')
          }
        });
      }
    },
    created() {
      this.getUserData()
    }
  }
</script>
