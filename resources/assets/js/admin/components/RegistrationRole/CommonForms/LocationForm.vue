<template>
  <div>
    <form id="locationDataForm" @submit.prevent="save">
      <loading :active.sync="isLoading" :can-cancel="false" :is-full-page="fullPage" :loader="loader" :color="loaderColor"></loading>
      <div class="md-layout-item md-small-size-100 md-size-100">
        <h3 class="md-title"> Dirección</h3>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100">
        <md-field >
          <md-select v-model="form.city" name="city" id="city" placeholder="Ciudad">
            <div v-for="item in cities">
              <md-option :value="item.id" >{{ item.name }}</md-option>
            </div>
          </md-select>
        </md-field>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100">
        <md-field>
          <label>Dirección</label>
          <md-input v-model="form.address" type="text" name="address" id="address" ></md-input>
        </md-field>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100">
        <md-field>
          <label>Casa / Apto / Piso</label>
          <md-input v-model="form.address_details" type="text" name="address_details" id="address_details" ></md-input>
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
    props: ['saveDataUrl'],
    components:{
      Loading
    },
    data(){
      return {
        form:{
          userId: null,
          city: null,
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
            vm.form = {
              userId: vm.user.id,
              city: vm.user.city_id,
              address: vm.user.address,
              address_details: vm.user.address_details,
              latitude: vm.user.latitude,
              longitude: vm.user.longitude
            }
          }else{
            vm.$router.push('/users/create')
          }
        });
      },
      getCities(){
        const vm = this
        const url = '/adm/cities'
        vm.isLoading = true
        axios.get(url)
        .then((response) => {
          vm.isLoading = false
          vm.cities = response.data.records
        });
      }
    },
    created() {
      this.getCities()
      this.getUserData()
    }
  }
</script>
