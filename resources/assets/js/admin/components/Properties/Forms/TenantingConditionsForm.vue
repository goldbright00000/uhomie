<template>
  <div>
    <form id="tenantingConditionsForm" @submit.prevent="save">
      <div class="md-layout-item md-small-size-100 md-size-100">
        <h3 class="md-title"> Condiciones de Arriendo</h3>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100">
        <md-field>
          <label>Precio Mensual de Arriendo</label>
          <md-input v-model="form.rent" type="text" name="rent" id="rent" ></md-input>
        </md-field>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100">
        <md-field>
          <label>Monto de Gastos Comunes</label>
          <md-input v-model="form.common_expenses_limit" type="text" name="common_expenses_limit" id="common_expenses_limit"></md-input>
        </md-field>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100" >
        <label>Seguro de Arriendo</label>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100" >
        <md-radio v-model="form.tenanting_insurance" value="1" class="md-primary" >Si</md-radio>
        <md-radio v-model="form.tenanting_insurance" value="0" class="md-primary" >No</md-radio>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100">
        <md-field >
          <md-select v-model="form.warranty_months_quantity" name="warranty_months_quantity" placeholder="Meses de Garantia">
            <md-option value="1">1 Mes</md-option>
            <md-option value="2">2 Meses</md-option>
            <md-option value="3">3 Meses</md-option>
            <md-option value="4">4 Meses</md-option>
            <md-option value="5">5 Meses</md-option>
            <md-option value="6">6 Meses</md-option>
            <md-option value="7">7 Meses</md-option>
            <md-option value="8">8 Meses</md-option>
            <md-option value="9">9 Meses</md-option>
            <md-option value="10">10 Meses</md-option>
            <md-option value="11">11 Meses</md-option>
            <md-option value="12">12 Meses</md-option>
          </md-select>
        </md-field>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100">
        <md-field >
          <md-select  v-model="form.months_advance_quantity" name="months_advance_quantity" placeholder="Meses de Adelanto">
            <md-option value="1">1 Mes</md-option>
            <md-option value="2">2 Meses</md-option>
            <md-option value="3">3 Meses</md-option>
            <md-option value="4">4 Meses</md-option>
            <md-option value="5">5 Meses</md-option>
            <md-option value="6">6 Meses</md-option>
            <md-option value="7">7 Meses</md-option>
            <md-option value="8">8 Meses</md-option>
            <md-option value="9">9 Meses</md-option>
            <md-option value="10">10 Meses</md-option>
            <md-option value="11">11 Meses</md-option>
            <md-option value="12">12 Meses</md-option>
          </md-select>
        </md-field>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100">
        <md-datepicker md-immediately v-model="form.available_date" name="available_date" id="available_date" placeholder="Fecha de Mudanza"> <label>Fecha de Mudanza</label> </md-datepicker>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100">
        <md-field >
          <md-select  v-model="form.tenanting_months_quantity" name="tenanting_months_quantity" placeholder="Tiempo Minimo de Arriendo">
            <md-option value="1">1 Mes</md-option>
            <md-option value="2">2 Meses</md-option>
            <md-option value="3">3 Meses</md-option>
            <md-option value="4">4 Meses</md-option>
            <md-option value="5">5 Meses</md-option>
            <md-option value="6">6 Meses</md-option>
            <md-option value="7">7 Meses</md-option>
            <md-option value="8">8 Meses</md-option>
            <md-option value="9">9 Meses</md-option>
            <md-option value="10">10 Meses</md-option>
            <md-option value="11">11 Meses</md-option>
            <md-option value="12">12 Meses</md-option>
            <md-option value="18">18 Meses</md-option>
            <md-option value="24">24 Meses</md-option>
            <md-option value="36">36 Meses</md-option>
          </md-select>
        </md-field>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100" >
        <label>Aval</label>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100" >
        <md-radio v-model="form.collateral_require" value="1" class="md-primary" >Si</md-radio>
        <md-radio v-model="form.collateral_require" value="0" class="md-primary" >No</md-radio>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100" >
        <label>Amoblado</label>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100" >
        <md-radio v-model="form.furnished" value="1" class="md-primary" >Si</md-radio>
        <md-radio v-model="form.furnished" value="0" class="md-primary" >No</md-radio>
      </div>
      <div class="md-layout-item md-size-100" v-if="form.furnished == 1">
        <md-field maxlength="5">
          <label for="description">Por favor indique los muebles que posee en su propiedad</label>
          <md-textarea v-model="form.furnished_description" name="furnished_description" id="furnished_description" ></md-textarea>
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
    name: "tenanting-conditions-form",
    props:["saveDataUrl"],
    components:{
      Loading
    },
    data(){
      return {
        form:{
          propertyId: null,
          rent: null,
          common_expenses_limit: null,
          tenanting_insurance: null,
          warranty_months_quantity: null,
          months_advance_quantity: null,
          available_date: null,
          tenanting_months_quantity: null,
          collateral_require: null,
          furnished: null,
          furnished_description: null
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
          rent: vm.form.rent,
          common_expenses_limit: vm.form.common_expenses_limit,
          tenanting_insurance: vm.form.tenanting_insurance,
          warranty_months_quantity: vm.form.warranty_months_quantity,
          months_advance_quantity: vm.form.months_advance_quantity,
          available_date: vm.form.available_date,
          tenanting_months_quantity: vm.form.tenanting_months_quantity,
          collateral_require: vm.form.collateral_require,
          furnished: vm.form.furnished,
          furnished_description: vm.form.furnished_description
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
            rent: vm.property.rent,
            common_expenses_limit: vm.property.common_expenses_limit,
            tenanting_insurance: vm.property.tenanting_insurance,
            warranty_months_quantity: vm.property.warranty_months_quantity,
            months_advance_quantity: vm.property.months_advance_quantity,
            available_date: vm.property.available_date,
            tenanting_months_quantity: vm.property.tenanting_months_quantity,
            collateral_require: vm.property.collateral_require,
            furnished: vm.property.furnished,
            furnished_description: vm.property.furnished_description
          }
        });
      }
    },
    created() {
      this.getPropertyData()
    }
  }
</script>
