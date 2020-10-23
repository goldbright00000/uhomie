<template>
  <form  novalidate @submit.prevent="deleteProject">
    <md-card>
      <md-card-content>
        <div class="md-layout">
          <loading :active.sync="isLoading" :can-cancel="false" :is-full-page="fullPage" :loader="loader" :color="loaderColor"></loading>
          <h4 class="title">Seguro que desea eliminar el Proyecto {{ form.name }}?</h4>
          <div class="md-layout-item md-size-100 text-right">
            <md-button class="md-raised md-primary" type="button" @click="$router.go(-1)">Cancelar</md-button>
            <md-button class="md-raised md-danger" type="submit">Eliminar Proyecto</md-button>
          </div>
        </div>
      </md-card-content>
    </md-card>
    <md-snackbar :md-active.sync="projectDeleted">El Proyecto {{ lastProject }} ha sido eliminado con exito!</md-snackbar>
  </form>
</template>

<script>
  import axios from 'axios'
  import Loading from 'vue-loading-overlay';

  const deleteProjectUrl = '/adm/projects/delete';
  const loadDataProjectUrl = '/adm/projects';

  const csrf_token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  axios.defaults.headers.common = {
      'X-Requested-With': 'XMLHttpRequest',
      'X-CSRF-TOKEN' : csrf_token
  };

  export default {
    name: "delete-project-form",
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
        projectId: null,
        name: null
      },
      projectDeleted: false,
      sending: false,
      lastProject: null,
      isLoading: false,
      fullPage: true,
      loader: "dots",
      loaderColor: "#1ac036",

    }),
    methods: {
      clearForm () {
        this.form.name = null
      },
      deleteProject() {
        const vm = this;
        vm.isLoading = true
        axios.post(deleteProjectUrl, {
          projectId : this.form.projectId
        })
        .then(function(response) {
          console.log(response)
          vm.lastProject = `${vm.form.name}`
          vm.projectDeleted = true
          vm.isLoading = false
          vm.clearForm()
          vm.$router.push('/projects')
        })
        .catch(function(error) {
          console.log(error);
        });
      },
      loadDataProject() {
        const vm = this;
        vm.isLoading = true
        const projectId = JSON.parse(vm.$route.params.projectId)
        var url = loadDataProjectUrl + '/' + projectId
        axios.get(url)
        .then((response) => {
          vm.isLoading = false
          if (response.data.project !== null) {
              vm.form = { projectId : response.data.project.id , name: response.data.project.name }
          }else{
              vm.$router.push('/projects')
          }
        });
      }
    },
    mounted() {
      //do something after mounting vue instance
    },
    created() {
      //do something after creating vue instance
      this.loadDataProject()
    }
  }
</script>
