<template>
  <div>
    <form id="ownerDataForm" @submit.prevent="save">
      <div class="md-layout-item md-small-size-100 md-size-100">
        <h3 class="md-title"> Ingresos personales</h3>
      </div>

      <loading :active.sync="isLoading" :can-cancel="false" :is-full-page="fullPage" :loader="loader" :color="loaderColor"></loading>
      <div class="md-layout-item md-small-size-100 md-size-100">
        <md-field>
          <label>Dedicacion</label>
          <md-input v-model="form.position" type="text" name="position" id="position" ></md-input>
        </md-field>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100" >
        <label>Ahorros</label>
        <md-radio v-model="form.saves" value="1" class="md-primary" @change="changeSaves">Si</md-radio>
        <md-radio v-model="form.saves" value="0" class="md-primary" @change="changeSaves" >No</md-radio>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100" v-if="form.saves == '1'">
        <md-field>
          <label>Monto Ahorrado</label>
          <md-input v-model="form.save_amount" type="text" name="save_amount" id="save_amount" @input="sum" ></md-input>
        </md-field>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100" >
        <label>Facturas de Pagos por Servicios</label>
        <md-radio v-model="form.invoice" value="1" class="md-primary" @change="changeInvoice">Si</md-radio>
        <md-radio v-model="form.invoice" value="0" class="md-primary" @change="changeInvoice">No</md-radio>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100" v-if="form.invoice == '1'">
        <md-field>
          <label>Monto de la ultima factura por servicio</label>
          <md-input v-model="form.last_invoice_amount" type="text" name="last_invoice_amount" id="last_invoice_amount" @input="sum" ></md-input>
        </md-field>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100">
        <label>Cotizaciones AFP</label>
        <md-radio v-model="form.afp" value="1" class="md-primary">Si</md-radio>
        <md-radio v-model="form.afp" value="0" class="md-primary">No</md-radio>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100">
        <h3 class="md-title"> Ingreso mensual adicional</h3>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100">
        <md-field >
          <md-select v-model="form.other_type" name="other_type" placeholder="Tipo de Ingreso" @md-selected="changeTypeIncome"  >
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
    name: "owner-data-form",
    components:{
      Loading
    },
    data() {
      return {
        form:{
          userId:null,
          employment_type:null,
          position:null,
          invoice:null,
          last_invoice_amount:null,
          other_type:null,
          income:null,
          afp:null,
          saves:null,
          save_amount:null,
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
          employment_type: 2,
          position: vm.form.position,
          invoice:vm.form.invoice,
          last_invoice_amount:vm.form.last_invoice_amount,
          other_type:vm.form.other_type,
          income:vm.form.income,
          afp:vm.form.afp,
          saves:vm.form.saves,
          save_amount:vm.form.save_amount
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
              invoice:vm.user.invoice,
              other_type:parseInt(vm.user.other_income_type),
              income:vm.user.other_income_amount,
              last_invoice_amount:vm.user.last_invoice_amount,
              afp:vm.user.afp,
              saves:vm.user.saves,
              save_amount:vm.user.save_amount
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
      changeSaves(value) {
        const vm = this
        if (value == 0) {
          vm.form.save_amount = 0
          vm.sum()
        }
      },
      changeInvoice(value) {
        const vm = this
        if (value == 0) {
          vm.form.last_invoice_amount = 0
          vm.sum()
        }
      },
      sum(){
        const vm = this
        vm.form.total_amount = parseInt(vm.form.income) + parseInt(vm.form.last_invoice_amount) + parseInt(vm.form.save_amount)
      }
    },
    created() {
      this.getUserData()
    }
  }
</script>
