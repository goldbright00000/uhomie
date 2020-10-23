<template>
  <div>
    <form id="tenantingPreferencesForm" @submit.prevent="save">
      <div class="md-layout-item md-small-size-100 md-size-100">
        <h3 class="md-title"> Preferencias de visita</h3>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100">
        <span >Coordinar Visitas</span>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100" >
        <md-radio v-model="form.visit" value="1" class="md-primary" >Si</md-radio>
        <md-radio v-model="form.visit" value="0" class="md-primary" >No</md-radio>
      </div>
      <div class="md-layout-item md-small-size-100 md-size-100" v-if="form.visit == 1">
        <div class="md-layout-item md-small-size-100 md-size-100">
          <span >Seleccione dias disponible para que potenciales arrendatarios visiten la propiedad.</span>
        </div>
        <div class="md-layout-item md-small-size-100 md-size-100">
          <md-datepicker md-immediately v-model="form.visit_from_date" name="visit_from_date" id="visit_from_date" placeholder="Desde"> <label>De</label> </md-datepicker>
        </div>
        <div class="md-layout-item md-small-size-100 md-size-100">
          <md-datepicker md-immediately v-model="form.visit_to_date" name="visit_to_date" id="visit_to_date" placeholder="Hasta"> <label>Hasta</label> </md-datepicker>
        </div>
        <div class="md-layout-item md-small-size-100 md-size-100" >

          <span>Elija un Rango de Horario</span>
        </div>
        <div class="md-layout-item md-small-size-100 md-size-100" >
          <md-radio v-model="form.schedule_range" value="1" class="md-primary" >Mañana 09Am - 12m </md-radio>
        </div>
        <div class="md-layout-item md-small-size-100 md-size-100" >
          <md-radio v-model="form.schedule_range" value="2" class="md-primary" >Mediodia 12m - 03Pm</md-radio>
        </div>
        <div class="md-layout-item md-small-size-100 md-size-100" >
          <md-radio v-model="form.schedule_range" value="3" class="md-primary" >Tarde 03Pm - 07Pm</md-radio>
        </div>
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
    name: "visit-preferences-form",
    props:["saveDataUrl"],
    components:{
      Loading
    },
    data(){
      return {
        form:{
          visit:null,
          visit_from_date:null,
          visit_to_date:null,
          schedule_range:null
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
          visit: vm.form.visit,
          visit_from_date: vm.form.visit_from_date,
          visit_to_date: vm.form.visit_to_date,
          schedule_range: vm.form.schedule_range
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
            visit: vm.property.visit,
            visit_from_date: vm.property.visit_from_date,
            visit_to_date: vm.property.visit_to_date,
            schedule_range: vm.property.schedule_range
          }
        });
      }
    },
    created() {
      this.getPropertyData()
    }
  }
</script>
