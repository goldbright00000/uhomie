<template>
  <div>
    <form id="employedDataForm" @submit.prevent="save">
      <div class="md-layout-item md-small-size-100 md-size-100">
        <h3 class="md-title"> Trabajo Actual</h3>
      </div>
      <loading :active.sync="isLoading" :can-cancel="false" :is-full-page="fullPage" :loader="loader" :color="loaderColor"></loading>
      <div class="md-layout-item md-small-size-100 md-size-100">
        <md-field>
          <label>Cargo</label>
          <md-input v-model="form.position" type="text" name="position" id="position" ></md-input>
        </md-field>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100">
        <md-field>
          <label>Empresa</label>
          <md-input v-model="form.company" type="text" name="company" id="company" ></md-input>
        </md-field>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100">
        <md-field >
          <md-select v-model="form.job_type" name="job_type" placeholder="Tipo de trabajo">
            <md-option value="FullTime">Full Time</md-option>
            <md-option value="PartTime">Part Time</md-option>
            <md-option value="Indefinido">Indefinido</md-option>
            <md-option value="Por Proyecto">Por Proyecto</md-option>
            <md-option value="Por Honorarios">Por Honorarios</md-option>
            <md-option value="Freelancer">Freelancer</md-option>
          </md-select>
        </md-field>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100">
        <md-datepicker md-immediately v-model="form.worked_from_date" name="worked_from_date" id="worked_from_date" placeholder="Fecha de Ingreso"> <label>Fecha de Ingreso</label> </md-datepicker>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100">
        <md-field>
          <label>Salario Liquido Percibido</label>
          <md-input v-model="form.amount" type="text" name="amount" id="amount" @input="sum" ></md-input>
        </md-field>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100">
        <h3 class="md-title"> Ingreso mensual adicional</h3>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100">
        <md-field >
          <md-select  v-model="form.other_type" name="other_type" placeholder="Tipo de Ingreso" @md-selected="changeTypeIncome" >
            <div v-for="(item,i) in other_income_types" >
              <md-option :value="parseInt(i)">{{ item }}</md-option>
            </div>
          </md-select>
        </md-field>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100" v-if="form.other_type !== 0">
        <md-field>
          <label>Monto Adicional</label>
          <md-input v-model="form.income" type="text" name="income" id="income" @change="sum" @input="sum"></md-input>
        </md-field>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100">
        <h3 class="md-title"> Sumario Financiero</h3>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100">
        <md-field>
          <md-input disabled v-model="form.total_amount" type="text" name="total_amount" id="total_amount" ></md-input>
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
    name: "employed-data-form",
    components:{
      Loading
    },
    data() {
      return {
        form:{
          userId:null,
          employment_type:null,
          position:null,
          company:null,
          job_type:null,
          worked_from_date:null,
          amount:null,
          invoice:null,
          other_type:null,
          income:null,
          total_amount:null
        },
        user:null,
        other_income_types: [
          "No tengo ingresos adicionales",
          "Ingresos propios adicionales",
          "Ingresos de compañero de arriendo",
          "Esposa(o)"
        ],
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
        const saveDataUrl = '/adm/users/tenant-registration/employment-data'
        axios.post(saveDataUrl, {
          userId: vm.form.userId,
          employment_type: 1,
          position: vm.form.position,
          company:vm.form.company,
          job_type:vm.form.job_type,
          worked_from_date: vm.form.worked_from_date,
          amount:vm.form.amount,
          invoice:vm.form.invoice,
          other_type:vm.form.other_type,
          income:vm.form.income
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
              employment_type: vm.user.employment_type,
              position: vm.user.position,
              company:vm.user.company,
              job_type:vm.user.job_type,
              worked_from_date: vm.user.worked_from_date,
              amount:vm.user.amount,
              invoice:parseInt(vm.user.invoice),
              other_type:parseInt(vm.user.other_income_type),
              income:vm.user.other_income_amount
            }
            if (vm.form.other_type == 0 ) {
              vm.form.income = 0
            }
            vm.sum()
          }else{
            vm.$router.push('/users/create')
          }
        });
      },
      changeTypeIncome(value) {
        const vm = this
        if (value == 0) {
          vm.form.income = 0
          vm.sum()
        }
      },
      sum(){
        const vm = this
        vm.form.total_amount = parseInt(vm.form.amount) + parseInt(vm.form.income)
      }
    },
    created() {
      this.getUserData()
    }
  }
</script>
