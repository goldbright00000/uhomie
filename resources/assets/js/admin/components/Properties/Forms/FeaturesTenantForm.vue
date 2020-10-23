<template>
  <div>
    <loading :active.sync="isLoading" :can-cancel="false" :is-full-page="fullPage" :loader="loader" :color="loaderColor"></loading>
    <form id="basicDataForm" @submit.prevent="save">
      <div class="md-layout-item md-small-size-100 md-size-100" >
        <h3 class="md-title"> Características de Arrendatario </h3>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100" >
        <md-field>
          <label for="movies">Tipos</label>
          <md-select v-model="propertyForSelected" name="movies" id="movies" multiple>
            <div v-for="item in properties_for" >
              <md-option :value="item.id">{{ item.name }}</md-option>
            </div>
          </md-select>
        </md-field>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100" >
        <label>Mascotas</label>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100" >
        <md-checkbox v-model="form.pet_preference" value="dog"  class="md-primary">Perros</md-checkbox>
        <md-checkbox v-model="form.pet_preference" value="cat"  class="md-primary">Gatos</md-checkbox>
        <md-checkbox v-model="form.pet_preference" value="other"  class="md-primary">Otros</md-checkbox>
        <md-checkbox v-model="form.pet_preference" value="no"  class="md-primary">No posee</md-checkbox>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100" >
        <label>Fumador</label>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100" >
        <md-radio  v-model="form.smoking_allowed" value="1" class="md-primary">Si</md-radio>
        <md-radio  v-model="form.smoking_allowed" value="0" class="md-primary">No</md-radio>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100" >
        <label>Nacionales Chilenos</label>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100" >
        <md-radio v-model="form.nationals_with_rut" value="1" class="md-primary">Si</md-radio>
        <md-radio v-model="form.nationals_with_rut" value="0" class="md-primary">No</md-radio>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100" >
        <label>Extranjeros con RUT</label>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100" >
        <md-radio v-model="form.foreigners_with_rut" value="1" class="md-primary">Si</md-radio>
        <md-radio v-model="form.foreigners_with_rut" value="0" class="md-primary">No</md-radio>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100" >
        <label>Extranjeros con Pasaporte</label>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100" >
        <md-radio v-model="form.foreigners_with_passport" value="1" class="md-primary">Si</md-radio>
        <md-radio v-model="form.foreigners_with_passport" value="0" class="md-primary">No</md-radio>
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
    name: "features-tenant-form",
    props: ['saveDataUrl'],
    components:{
      Loading
    },
    data(){
      return {
        form:{
          propertyId:null,
          pet_preference:null,
          smoking_allowed:null,
          nationals_with_rut:null,
          foreigners_with_rut:null,
          foreigners_with_passport:null
        },
        propertyForSelected: [],
        properties_for:null,
        property:null,
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
          propertyId: vm.form.propertyId,
          pet_preference: vm.form.pet_preference,
          smoking_allowed: vm.form.smoking_allowed,
          nationals_with_rut: vm.form.nationals_with_rut,
          foreigners_with_rut: vm.form.foreigners_with_rut,
          foreigners_with_passport: vm.form.foreigners_with_rut,
          property_for: vm.propertyForSelected
        })
        .then(function(response) {
          vm.dataSaved = true
          vm.getPropertiesFor()
          vm.getProperty()
        })
        .catch(function(error) {
          console.log(error);
        });
      },
      getProperty() {
        const vm = this;
        const propertyId = JSON.parse(vm.$route.params.propertyId)
        const url = '/adm/properties/' + propertyId
        vm.isLoading = true
        axios.get(url)
        .then((response) => {
          vm.isLoading = false
          vm.property = response.data.property

          vm.form = {
            propertyId: vm.property.id,
            pet_preference: vm.property.pet_preference,
            smoking_allowed: vm.property.smoking_allowed,
            nationals_with_rut: vm.property.nationals_with_rut,
            foreigners_with_rut: vm.property.foreigners_with_rut,
            foreigners_with_passport: vm.property.foreigners_with_rut
          }
          console.log(response.data.property_for)
          for (var i = 0; i < response.data.property_for.length; i++) {
            // vm.propertyForSelected[i] = response.data.property_for[i].id
            console.log(response.data.property_for[i])
          }

        });
      },
      getPropertiesFor() {
        const vm = this;
        var url = '/adm/properties/properties-for'
        vm.isLoading = true
        axios.get(url)
        .then((response) => {
          vm.isLoading = false
          vm.properties_for = response.data.records
        });
      }
    },
    created() {
      this.getPropertiesFor()
      this.getProperty()
    }
  }
</script>
<style lang="scss" scoped>
  .md-field {
    max-width: 300px;
  }
</style>
