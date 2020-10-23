<template>
  <div>
    <loading :active.sync="isLoading" :can-cancel="false" :is-full-page="fullPage" :loader="loader" :color="loaderColor"></loading>
    <form id="basicDataForm" @submit.prevent="save">
      <div class="md-layout-item md-small-size-100 md-size-100" v-if="edit">
        <h3 class="md-title"> Informacion Basica </h3>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100">
        <md-field >
          <md-select v-model="form.owner" name="owner" :placeholder="isProject ? 'Dueño' : 'Propietario'" >
            <div v-for="item in owners" :key="item.key">
              <md-option :value="item.id">{{ item.firstname }}</md-option>
            </div>
          </md-select>
        </md-field>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100">
        <md-field>
          <label>Nombre</label>
          <md-input v-model="form.name" type="text" name="name" id="name" ></md-input>
        </md-field>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100">
        <md-field >
          <md-select v-model="form.property_type" name="property_type" placeholder="Tipo de Propiedad">
            <div v-for="item in propertyTypes" :key="item.key">
              <md-option :value="item.id">{{ item.name }}</md-option>
            </div>
          </md-select>
        </md-field>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100" >
        <label>Condicion</label>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100" >
        <md-radio v-model="form.property_condition" value="0" class="md-primary">Nueva</md-radio>
        <md-radio v-model="form.property_condition" value="1" class="md-primary">Usada</md-radio>
      </div>
      <div class="md-layout-item md-size-100">
        <md-field maxlength="5">
          <label for="description">Descripcion de la Propiedad</label>
          <md-textarea v-model="form.description" name="description" id="description" ></md-textarea>
        </md-field>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100">
        <md-button v-if="!edit" type="button" @click="$router.go(-1)" class="md-danger" :disabled="sending">Atras</md-button>
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
    name: "basic-data-form",
    props: ['saveDataUrl','edit', 'isProject'],
    components:{
      Loading
    },
    data(){
      return {
        form:{
          propertyId:null,
          owner:null,
          name:null,
          property_type:null,
          property_condition:null,
          description:null,
        },
        owners:null,
        property:null,
        propertyTypes:null,
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
          userId: vm.form.owner,
          name: vm.form.name,
          property_type: vm.form.property_type,
          condition: vm.form.property_condition,
          description: vm.form.description,
        })
        .then(function(response) {
          vm.loading = false
          vm.dataSaved = true
          
          if ( vm.edit ) {
            vm.getOwners()
            vm.getProperty()
            vm.getPropertyTypes()
          }else{
            vm.$router.push('/properties/'+ response.data.property.id +'/edit')
          }
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
          vm.owner = response.data.owner
          vm.property_type = response.data.property_type
          vm.form = {
            propertyId: vm.property.id,
            owner: vm.owner.id,
            name: vm.property.name,
            property_type: vm.property_type.id,
            property_condition: vm.property.condition,
            description: vm.property.description
          }
        });
      },
      getPropertyTypes() {
        const vm = this;
        const url = '/adm/properties/property-types'
        vm.isLoading = true
        axios.get(url)
        .then((response) => {
          vm.isLoading = false
          vm.propertyTypes = response.data.records
        });
      },
      getOwners() {
        const vm = this;
        const url = '/adm/users/owners'
        vm.isLoading = true
        axios.get(url)
        .then((response) => {
          vm.isLoading = false
          vm.owners = response.data.records
        });
      }
    },
    created() {
      const vm = this;
      if ( vm.edit ) {
        vm.getProperty()
      }
      vm.getPropertyTypes()
      vm.getOwners()
    }
  }
</script>
