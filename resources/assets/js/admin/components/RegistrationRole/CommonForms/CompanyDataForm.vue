<template>
  <div>
    <loading :active.sync="isLoading" :can-cancel="false" :is-full-page="fullPage" :loader="loader" :color="loaderColor"></loading>
    <form id="companyDataForm" @submit.prevent="save">
      <div class="md-layout-item md-small-size-100 md-size-100">
        <h3 class="md-title"> Informacion Legal</h3>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100" >
        <label>Usa Factura</label>
        <md-radio v-model="form.invoice" value="1" class="md-primary" @change="changeInvoice">Si</md-radio>
        <md-radio v-model="form.invoice" value="0" class="md-primary" @change="changeInvoice">No</md-radio>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100">
        <md-field>
          <label>Razon social de la empresa</label>
          <md-input v-model="form.name" type="text" name="name" id="name" :disabled="form.invoice == 0" ></md-input>
        </md-field>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100">
        <md-field>
          <label>Giro</label>
          <md-input v-model="form.giro" type="text" name="giro" id="giro" :disabled="form.invoice == 0" ></md-input>
        </md-field>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100">
        <md-field>
          <label>RUT</label>
          <md-input v-model="form.rut" type="text" name="rut" id="rut" :disabled="form.invoice == 0" ></md-input>
        </md-field>
      </div>
      <!-- Rut (Foto anverso) / Pasaporte (Foto Hoja Principal)  -->
      <div class="md-layout-item md-small-size-100 md-size-100">
        <md-field>
          <label>Rut (Foto anverso) / Pasaporte (Foto Hoja Principal)</label>
          <md-file v-model="form.id_front" accept="image/*" :disabled="form.invoice == 0" />
        </md-field>
      </div>
      <!-- Rut (Foto reverso) / Pasaporte (Foto Visado) -->
      <div class="md-layout-item md-small-size-100 md-size-100">
        <md-field>
          <label>Rut (Foto reverso) / Pasaporte (Foto Visado)</label>
          <md-file v-model="form.id_back" accept="image/*" :disabled="form.invoice == 0" />
        </md-field>
      </div>
      <!-- Logo (Opcional) -->
      <div class="md-layout-item md-small-size-100 md-size-100">
        <md-field>
          <label>Logo (Opcional)</label>
          <md-file v-model="form.image_logo" accept="image/*" :disabled="form.invoice == 0" />
        </md-field>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100">
        <md-field>
          <label>Telefono fijo (opcional)</label>
          <md-input v-model="form.phone" type="text" name="phone" id="phone" ></md-input>
        </md-field>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100">
        <md-field>
          <label>Pagina Web</label>
          <md-input v-model="form.website" type="text" name="website" id="website" ></md-input>
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
    name: "company-data-form",
    props: ['saveDataUrl','companyType'],
    components:{
      Loading
    },
    data(){
      return {
        form:{
          userId: null,
          invoice:null,
          name:null,
          giro:null,
          rut:null,
          id_front:null,
          id_back:null,
          image_logo:null,
          phone:null,
          website:null
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
          userId: vm.form.userId,
          invoice: vm.form.invoice,
          name: vm.form.name,
          giro: vm.form.giro,
          rut: vm.form.rut,
          id_front: vm.form.id_front,
          id_back: vm.form.id_back,
          image_logo: vm.form.image_logo,
          phone: vm.form.phone,
          website: vm.form.website
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
            vm.form.userId = vm.user.id
          }else{
            vm.$router.push('/users/create')
          }
        });
      },
      getCompanyData() {
        const vm = this;
        const userId = JSON.parse(vm.$route.params.userId)
        vm.isLoading = true
        var url = '/adm/users/' + userId + '/companies/' + vm.companyType
        axios.get(url)
        .then((response) => {
          vm.isLoading = false
          var resp = response.data.company
          vm.form = {
            invoice: resp.invoice,
            name: resp.name,
            giro: resp.giro,
            rut: resp.rut,
            id_front: resp.id_front,
            id_back: resp.id_back,
            image_logo: resp.image_logo,
            phone: resp.phone,
            website: resp.website
          }
        });
      },
      changeInvoice(value) {
        const vm = this;
        if (value == 0) {
          vm.form.name = null
          vm.form.giro = null
          vm.form.rut = null
          vm.form.id_front = null
          vm.form.id_back = null
          vm.form.image_logo = null
        }
      }
    },
    created() {
      this.getUserData()
      this.getCompanyData()
    }
  }
</script>
