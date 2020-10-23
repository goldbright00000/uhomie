<template>
  <div>
    <form id="tenantingPreferencesForm" @submit.prevent="save">
      <div class="md-layout-item md-small-size-100 md-size-100">
        <h3 class="md-title"> Preferencias de Arriendo</h3>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100">
        <md-field >
          <md-select v-model="form.property_type" name="property_type" placeholder="Tipo de Propiedad">
            <div v-for="item in property_types">
              <md-option :value="item.id">{{ item.name }}</md-option>
            </div>
          </md-select>
        </md-field>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100">
        <md-field >
          <md-select  v-model="form.property_condition" name="property_condition" placeholder="Condicion">
            <div v-for="(item,i) in property_conditions" >
              <md-option :value="i">{{ item }}</md-option>
            </div>
          </md-select>
        </md-field>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100">
        <md-field >
          <md-select  v-model="form.property_for" name="property_for" placeholder="Propiedad Para">
            <div v-for="item in properties_for" >
              <md-option :value="item.id">{{ item.name }}</md-option>
            </div>
          </md-select>
        </md-field>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100">
        <label>Amoblado</label>
        <md-radio  v-model="form.furnished" value="1" class="md-primary">Si</md-radio>
        <md-radio  v-model="form.furnished" value="0" class="md-primary">No</md-radio>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100">
        <label>Posee Mascotas</label>
        <md-checkbox v-model="form.pet_preference" value="dog"  class="md-primary">Perros</md-checkbox>
        <md-checkbox v-model="form.pet_preference" value="cat"  class="md-primary">Gatos</md-checkbox>
        <md-checkbox v-model="form.pet_preference" value="other"  class="md-primary">Otros</md-checkbox>
        <md-checkbox v-model="form.pet_preference" value="no"  class="md-primary">No posee</md-checkbox>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100">
        <label>Fumador</label>
        <md-radio  v-model="form.smoking_allowed" value="1" class="md-primary">Si</md-radio>
        <md-radio  v-model="form.smoking_allowed" value="0" class="md-primary">No</md-radio>
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
    name: "tenanting-preferences-form",
    components:{
      Loading
    },
    data(){
      return {
        form:{
          userId: null,
          property_type:null,
          property_condition:null,
          property_for:null,
          furnished:null,
          pet_preference:null,
          smoking_allowed:null
        },
        user:null,
        property_types: null,
        property_conditions: [
          "Nuevo", "Usado", "Indiferente"
        ],
        properties_for: null,
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
        const saveDataUrl = '/adm/users/tenant-registration/tenanting-preferences'
        axios.post(saveDataUrl, {
          userId: vm.form.userId,
          property_type:vm.form.property_type,
          property_condition:vm.form.property_condition,
          property_for:vm.form.property_for,
          furnished:vm.form.furnished,
          pet_preference:vm.form.pet_preference,
          smoking_allowed:vm.form.smoking_allowed
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
              userId:  vm.user.id,
              property_type: vm.user.property_type,
              property_condition: vm.user.property_condition,
              property_for: vm.user.property_for,
              furnished: vm.user.furnished,
              pet_preference: vm.user.pet_preference,
              smoking_allowed: vm.user.smoking_allowed
            };
          }else{
            vm.$router.push('/users/create')
          }
        });
      },
      getPropertyTypes() {
        const vm = this;
        var url = '/adm/properties/property-types'
        vm.isLoading = true
        axios.get(url)
        .then((response) => {
          vm.property_types = response.data.records
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
      //do something after creating vue instance
      this.getUserData()
      this.getPropertyTypes()
      this.getPropertiesFor()
    }
  }
</script>
