<template>
  <div>
    <form id="paymentPreferencesForm" @submit.prevent="save">
      <div class="md-layout-item md-small-size-100 md-size-100">
        <h3 class="md-title"> Preferencias de Pago</h3>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100">
        <md-field>
          <label>Gasto Mensual de Arriendo</label>
          <md-input v-model="form.expenses_limit" type="text" name="expenses_limit" id="expenses_limit" ></md-input>
        </md-field>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100">
        <md-field>
          <label>Gastos Comunes</label>
          <md-input v-model="form.common_expenses_limit" type="text" name="common_expenses_limit" id="common_expenses_limit"></md-input>
        </md-field>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100" >
        <label>Pagar Seguro de Arriendo</label>
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
        <md-datepicker md-immediately v-model="form.move_date" name="move_date" id="move_date" placeholder="Fecha de Mudanza"> <label>Fecha de Mudanza</label> </md-datepicker>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100">
        <md-field >
          <md-select  v-model="form.tenanting_months_quantity" name="tenanting_months_quantity" placeholder="Tiempo de Arriendo">
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
    name: "payment-preferences-form",
    components:{
      Loading
    },
    data(){
      return {
        form:{
          userId: null,
          expenses_limit: null,
          common_expenses_limit: null,
          tenanting_insurance: null,
          warranty_months_quantity: null,
          move_date: null,
          tenanting_months_quantity: null,
          months_advance_quantity:null
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
        const saveDataUrl = '/adm/users/tenant-registration/payment-preferences'
        axios.post(saveDataUrl, {
          userId: vm.form.userId,
          expenses_limit: vm.form.expenses_limit,
          common_expenses_limit: vm.form.common_expenses_limit,
          tenanting_insurance: vm.form.tenanting_insurance,
          warranty_months_quantity: vm.form.warranty_months_quantity,
          move_date: vm.form.move_date,
          tenanting_months_quantity: vm.form.tenanting_months_quantity,
          months_advance_quantity:vm.form.months_advance_quantity
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
              expenses_limit: vm.user.expenses_limit,
              common_expenses_limit: vm.user.common_expenses_limit,
              tenanting_insurance: vm.user.tenanting_insurance,
              warranty_months_quantity: vm.user.warranty_months_quantity,
              move_date: vm.user.move_date,
              tenanting_months_quantity: vm.user.tenanting_months_quantity,
              months_advance_quantity:vm.user.months_advance_quantity
            };
          }else{
            vm.$router.push('/users/create')
          }
        });
      }
    },
    created() {
      //do something after creating vue instance
      this.getUserData()
    }
  }
</script>
