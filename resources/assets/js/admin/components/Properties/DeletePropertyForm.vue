<template>
  <form  novalidate @submit.prevent="deleteProperty">
    <md-card>
      <md-card-content>
        <div class="md-layout">
          <loading :active.sync="isLoading" :can-cancel="false" :is-full-page="fullPage" :loader="loader" :color="loaderColor"></loading>
          <h4 class="title">Seguro que desea eliminar la propiedad {{ form.name }}?</h4>
          <div class="md-layout-item md-size-100 text-right">
            <md-button class="md-raised md-primary" type="button" @click="$router.go(-1)">Cancelar</md-button>
            <md-button class="md-raised md-danger" type="submit">Eliminar Propiedad</md-button>
          </div>
        </div>
      </md-card-content>
    </md-card>
    <md-snackbar :md-active.sync="propertyDeleted">La Propiedad {{ lastProperty }} ha sido eliminada con exito!</md-snackbar>
  </form>
</template>

<script>
  import axios from 'axios'
  import Loading from 'vue-loading-overlay';
  const csrf_token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  axios.defaults.headers.common = {
      'X-Requested-With': 'XMLHttpRequest',
      'X-CSRF-TOKEN' : csrf_token
  };

  export default {
    name: "delete-property-form",
    components:{
      Loading
    },
    props: {
      dataBackgroundColor: {
        type: String,
        default: ""
      }
    },
    data: () => ({
      form: {
        propertyId: null,
        name: null
      },
      propertyDeleted: false,
      sending: false,
      lastProperty: null,
      isLoading: false,
      fullPage: true,
      loader: "dots",
      loaderColor: "#1ac036",

    }),
    methods: {
      clearForm () {
        this.form.name = null
      },
      deleteProperty() {
        const vm = this;
        vm.isLoading = true
        const url = '/adm/properties/'+ vm.form.propertyId +'/delete';
        axios.post(url, {
          propertyId : vm.form.propertyId
        })
        .then(function(response) {
          vm.lastProperty = `${vm.form.name}`
          vm.propertyDeleted = true
          vm.isLoading = false
          vm.clearForm()
          vm.$router.push('/properties')
        })
        .catch(function(error) {
          console.log(error);
        });
      },
      loadDataProperty() {
        const vm = this;
        vm.isLoading = true
        const propertyId = JSON.parse(vm.$route.params.propertyId)
        const url = '/adm/properties/' + propertyId
        axios.get(url)
        .then((response) => {
          vm.isLoading = false
          if (response.data.property !== null) {
              vm.form = { propertyId : response.data.property.id , name: response.data.property.name }
          }else{
              vm.$router.push('/properties')
          }
        });
      }
    },
    mounted() {
      //do something after mounting vue instance
    },
    created() {
      //do something after creating vue instance
      this.loadDataProperty()
    }
  }
</script>
