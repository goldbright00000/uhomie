<template>
  <div>
    <loading :active.sync="isLoading" :can-cancel="false" :is-full-page="fullPage" :loader="loader" :color="loaderColor"></loading>
    <form id="personalDataForm" @submit.prevent="save">
      <div class="md-layout-item md-small-size-100 md-size-100">
        <h3 class="md-title"> Informacion Basica </h3>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100">
        <md-field >
          <md-select v-model="form.country" name="country" placeholder="Nacionalidad">
            <div v-for="item in countries">
              <md-option :value="item.id" >{{ item.name }}</md-option>
            </div>
          </md-select>
        </md-field>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100">
        <md-field >
          <md-select v-model="form.doc_type" name="doc_type" id="doc_type" placeholder="Tipo de Documento">
            <md-option value="RUT_PROVISIONAL">RUT (Provisorio)</md-option>
            <md-option value="RUT">RUT (Residencia [Permanente])</md-option>
            <md-option value="PASSPORT">Pasaporte</md-option>
          </md-select>
        </md-field>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100">
        <md-field>
          <label>Numero de documento</label>
          <md-input v-model="form.document_number" type="text" name="document_number" id="document_number" ></md-input>
        </md-field>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100">
        <md-datepicker md-immediately placeholder="Fecha de Nacimiento" v-model="form.birthdate" name="birthdate" id="birthdate"> <label>Fecha de Nacimiento</label> </md-datepicker>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100">
        <md-field >
          <md-select v-model="form.civil_status" name="civil_status" id="civil_status" placeholder="Estado Civil">
            <div v-for="item in civilStatus">
              <md-option :value="item.id">{{ item.name }}</md-option>
            </div>
          </md-select>
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
    name: "personal-data-form",
    props: ['saveDataUrl'],
    components:{
      Loading
    },
    data(){
      return {
        form:{
          userId: null,
          country: null,
          doc_type: null,
          document_number:null,
          birthdate:null,
          civil_status:null
        },
        user:null,
        countries:null,
        civilStatus:null,
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
          userId: vm.user.id,
          nationality: vm.form.country,
          doc_type: vm.form.doc_type,
          document_number: vm.form.document_number,
          birthdate: vm.form.birthdate,
          civil_status: vm.form.civil_status
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
        vm.isLoading = true
        var url = '/adm/users/' + userId
        axios.get(url)
        .then((response) => {
          vm.isLoading = false
          if ( response.data.user !== null ) {
            vm.isLoading = false
            vm.user = response.data.user
            vm.form = {
              userId: vm.user.id,
              country: vm.user.country_id,
              doc_type: vm.user.document_type,
              document_number: vm.user.document_number,
              birthdate: vm.user.birthdate,
              civil_status: vm.user.civil_status_id
            }
          }else{
            vm.$router.push('/users/create')
          }
        });
      },
      getCountries(){
        const vm = this
        const url = '/adm/countries'
        vm.isLoading = true
        axios.get(url)
        .then((response) => {
          vm.isLoading = false
          vm.countries = response.data.records
        });
      },
      getCivilStatus(){
        const vm = this
        const url = '/adm/civil-status'
        vm.isLoading = true
        axios.get(url)
        .then((response) => {
          vm.isLoading = false
          vm.civilStatus = response.data.records
        });
      }
    },
    created() {
      this.getCountries()
      this.getCivilStatus()
      this.getUserData()
    }
  }
</script>
