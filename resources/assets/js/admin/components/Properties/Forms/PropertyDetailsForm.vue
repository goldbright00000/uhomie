<template>
  <div>
    <loading :active.sync="isLoading" :can-cancel="false" :is-full-page="fullPage" :loader="loader" :color="loaderColor"></loading>
    <form id="propertyDetailsForm" @submit.prevent="save">
      <div class="md-layout-item md-small-size-100 md-size-100">
        <h3 class="md-title"> Detalles Principales </h3>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100">
        <md-field>
          <label>Habitaciones</label>
          <md-input v-model="form.bedrooms" type="number" name="bedrooms" id="bedrooms" ></md-input>
        </md-field>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100">
        <md-field>
          <label>Baños</label>
          <md-input v-model="form.bathrooms" type="number" name="bathrooms" id="bathrooms" ></md-input>
        </md-field>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100">
        <md-field>
          <label>Metros Cuadrados</label>
          <md-input v-model="form.meters" type="number" name="meters" id="meters" ></md-input>
        </md-field>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100" >
        <label>Piscina</label>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100" >
        <md-radio v-model="form.pool" value="1" class="md-primary">Si</md-radio>
        <md-radio v-model="form.pool" value="0" class="md-primary">No</md-radio>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100" >
        <label>Jardin</label>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100" >
        <md-radio v-model="form.garden" value="1" class="md-primary">Si</md-radio>
        <md-radio v-model="form.garden" value="0" class="md-primary">No</md-radio>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100" >
        <label>Terraza</label>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100" >
        <md-radio v-model="form.terrace" value="1" class="md-primary">Si</md-radio>
        <md-radio v-model="form.terrace" value="0" class="md-primary">No</md-radio>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100">
        <md-field>
          <label>Puestos de Estacionamiento Publico</label>
          <md-input v-model="form.public_parking" type="number" name="public_parking" id="public_parking" ></md-input>
        </md-field>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100" >
        <label>Estacionamiento Publico</label>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100" >
        <md-radio v-model="form.private_parking" value="1" class="md-primary">Si</md-radio>
        <md-radio v-model="form.private_parking" value="0" class="md-primary">No</md-radio>
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
    name: "property-details-form",
    props: ['saveDataUrl'],
    components:{
      Loading
    },
    data(){
      return {
        form:{
          pool: null,
          garden: null,
          bedrooms: null,
          bathrooms: null,
          terrace: null,
          meters: null,
          private_parking: null,
          public_parking: null
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
        axios.post(vm.saveDataUrl, {
          propertyId: vm.form.propertyId,
          pool: vm.form.pool,
          garden: vm.form.garden,
          bedrooms: vm.form.bedrooms,
          bathrooms: vm.form.bathrooms,
          terrace: vm.form.terrace,
          meters: vm.form.meters,
          private_parking: vm.form.private_parking,
          public_parking: vm.form.public_parking
        })
        .then(function(response) {
          vm.isLoading = false
          vm.dataSaved = true
          vm.getPropertyData()
        })
        .catch(function(error) {
          console.log(error);
        });
      },
      getPropertyData() {
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
            pool: vm.property.pool,
            garden: vm.property.garden,
            bedrooms: vm.property.bedrooms,
            bathrooms: vm.property.bathrooms,
            terrace: vm.property.terrace,
            meters: vm.property.meters,
            private_parking: vm.property.private_parking,
            public_parking: vm.property.public_parking
          }
        });
      }
    },
    created() {
      this.getPropertyData()
    }
  }
</script>
