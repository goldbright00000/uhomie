<template>
  <div>
    <form id="companyLocationForm" @submit.prevent="save">
      <loading :active.sync="isLoading" :can-cancel="false" :is-full-page="fullPage" :loader="loader" :color="loaderColor"></loading>
      <div class="md-layout-item md-small-size-100 md-size-100">
        <h3 class="md-title"> Dirección</h3>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100" >
        <label>Usar Dirección del Usuario</label>
        <md-radio v-model="form.personal_address" value="1" class="md-primary" @change="changePersonalAddress">Si</md-radio>
        <md-radio v-model="form.personal_address" value="0" class="md-primary" @change="changePersonalAddress">No</md-radio>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100">
        <md-field >
          <md-select v-model="form.city" name="city" id="city" placeholder="Ciudad" :disabled="form.personal_address == 1">
            <div v-for="item in cities">
              <md-option :value="item.id" >{{ item.name }}</md-option>
            </div>
          </md-select>
        </md-field>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100">
        <md-field>
          <label>Dirección</label>
          <md-input v-model="form.address" type="text" name="address" id="address"  :disabled="form.personal_address == 1"></md-input>
        </md-field>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100">
        <md-field>
          <label>Casa / Apto / Piso</label>
          <md-input v-model="form.address_details" type="text" name="address_details" id="address_details"  :disabled="form.personal_address == 1"></md-input>
        </md-field>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100">
        <md-button type="submit" class="md-info" >Guardar</md-button>
      </div>
    </form>
    <md-snackbar :md-active.sync="dataSaved">Datos guardados exitosamente!</md-snackbar>
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
    name: "location-form",
    props: ['saveDataUrl','companyType'],
    components:{
      Loading
    },
    data(){
      return {
        form:{
          userId: null,
          city: null,
          personal_address: null,
          address: null,
          address_details:null,
          latitude:null,
          longitude:null
        },
        cities:null,
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
        axios.post(vm.saveDataUrl, {
          userId: vm.form.userId,
          city: vm.form.city,
          personal_address: vm.form.personal_address,
          address: vm.form.address,
          address_details: vm.form.address_details,
          latitude: vm.form.latitude,
          longitude: vm.form.longitude
        })
        .then(function(response) {
          vm.getUserData()
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
      getCities(){
        const vm = this
        const url = '/adm/users/cities'
        vm.isLoading = true
        axios.get(url)
        .then((response) => {
          vm.isLoading = false
          vm.cities = response.data.records
        });
      },
      getCompanyData() {
        const vm = this;
        const userId = JSON.parse(vm.$route.params.userId)
        vm.isLoading = true
        var url = '/adm/users/' + userId + '/companies/' + vm.companyType
        axios.get(url)
        .then((response) => {
          vm.isLoading = false
          var resp = response.data.company
          vm.form = {
            userId: userId,
            personal_address: resp.personal_address,
            city: resp.city_id,
            address: resp.address,
            address_details: resp.address_details,
            latitude: resp.latitude,
            longitude: resp.longitude
          }
        });
      },
      changePersonalAddress(value) {
        const vm = this;
        if (value == 1) {
          vm.form.city = vm.user.city_id
          vm.form.address = vm.user.address
          vm.form.address_details = vm.user.address_details
          vm.form.latitude = vm.user.latitude
          vm.form.longitude = vm.user.longitude
        }
      }
    },
    created() {
      this.getCities()
      this.getUserData()
      this.getCompanyData()
    }
  }
</script>
