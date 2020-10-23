<template>
  <div>
    <loading :active.sync="isLoading" :can-cancel="false" :is-full-page="fullPage" :loader="loader" :color="loaderColor"></loading>
    <md-table v-model="scoring_list" :table-header-color="tableHeaderColor" >
      <md-table-row slot="md-table-row" slot-scope="{ item }" class="md-success">
        <md-table-cell md-label="Nombre" md-sort-by="name">{{ item.name }}</md-table-cell>
        <md-table-cell md-label="Descripcion" md-sort-by="description">{{ item.description }}</md-table-cell>
        <md-table-cell md-label="Puntos" md-sort-by="points_scoring">{{ item.points_scoring }}</md-table-cell>
        <md-table-cell md-label="Opciones" v-if="userIsAdmin">
          <md-button  class="md-just-icon md-simple md-primary">
            <router-link :to="{path: '/scoring/' + item.id + '/edit'}"><md-icon>edit</md-icon></router-link>
            <md-tooltip md-direction="top">Editar</md-tooltip>
          </md-button>
          <md-button  class="md-just-icon md-simple md-primary" >
            <router-link :to="{path: '/scoring/' + item.id + '/categories'}"><md-icon>settings</md-icon></router-link>
            <md-tooltip md-direction="top">Gestionar Categorias del Scoring</md-tooltip>
          </md-button>
        </md-table-cell>
      </md-table-row>
    </md-table>
  </div>
</template>
<script>
  const user_role = window.ROLE;

import axios from 'axios'
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';

const scoringUrl = '/adm/scoring';
export default {
  name: "scoring-table",
  props: {
    tableHeaderColor: {
      type: String,
      default: ""
    }
  },
  components:{
    Loading
  },
  computed: {
    userIsAdmin: function() {
      return user_role == 1;
    }
  },
  data() {
    return {

      scoring_list: [],
      isLoading: false,
      fullPage: true,
      loader: "dots",
      loaderColor: "#1ac036"
    };
  },
  created() {
      this.getScoringList();
  },
  methods: {
      getScoringList: function () {
        const vm = this
        vm.isLoading = true;
        axios.get(scoringUrl)
        .then((response) => {
          vm.isLoading = false;
          vm.scoring_list = response.data.records
        });
      },
  }
};
</script>
