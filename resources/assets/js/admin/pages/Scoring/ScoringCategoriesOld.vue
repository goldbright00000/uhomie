<template>
  <div class="content">
    <div class="md-layout">

      <div class="md-layout-item md-medium-size-100 md-xsmall-size-100 md-size-50">
        <md-card>
          <md-card-header data-background-color="green">
            <div class="md-layout md-gutter">
              <div class="md-layout-item">
                <h4 class="title">Categorias de <strong>{{ scoring.name }}</strong>  </h4>
                <h4 class="title">Puntos: <strong>{{ scoring.points_scoring }}</strong>  </h4>
                <p class="category">Listado de Categorias</p>
              </div>
              <div class="md-layout-item md-size-40">
                <md-button class="md-raised md-primary" aria-label="Nueva Categoria" :to="{path: '/scoring/' + $route.params.scoringId + '/categories/add'}">Nueva Categoria</md-button>
              </div>
            </div>
          </md-card-header>
          <md-card-content>
            <!-- <scoring-categories-table table-header-color="green"></scoring-categories-table> -->
            <div>
              <loading :active.sync="isLoading" :can-cancel="false" :is-full-page="fullPage" :loader="loader" :color="loaderColor"></loading>
              <md-table v-model="categories" table-header-color="green" @md-selected="onSelect" style="height: 60vh;overflow-x: hidden;" >
                <md-table-row slot="md-table-row" slot-scope="{ item }" md-selectable="single">
                  <md-table-cell md-label="Nombre" md-sort-by="name">{{ item.name }}</md-table-cell>
                  <md-table-cell md-label="Retroalimentacion" md-sort-by="feed_back">{{ item.feed_back }}</md-table-cell>
                  <md-table-cell md-label="Puntos" md-sort-by="points_scoring">{{ item.points_scoring }}</md-table-cell>
                  <md-table-cell md-label="Opciones">
                    <md-button  class="md-just-icon md-simple md-primary">
                      <router-link :to="{path: '/scoring/' + $route.params.scoringId + '/categories/' + item.id + '/edit'}"><md-icon>edit</md-icon></router-link>
                      <md-tooltip md-direction="top">Editar</md-tooltip>
                    </md-button>
                  </md-table-cell>
                </md-table-row>
              </md-table>
            </div>
          </md-card-content>
        </md-card>
      </div>
      <div class="md-layout-item md-medium-size-100 md-xsmall-size-100 md-size-50">
        <md-card>
          <md-card-header data-background-color="green">
            <div class="md-layout md-gutter">
              <div class="md-layout-item">
                <h4 class="title" v-if="categorySelected !== null">Detalles de <strong>{{ categorySelected.name }}</strong> </h4>
                <h4 class="title" v-if="categorySelected !== null">Puntos: <strong>{{ categorySelected.points_scoring }}</strong>  </h4>
                <p class="category">Detalles de la Categoria</p>
              </div>
              <div class="md-layout-item md-size-40">
                <md-button v-if="categorySelected !== null" class="md-raised md-primary" aria-label="Agregar Detalle" :to="{path: '/scoring/' + $route.params.scoringId + '/categories/' + categorySelected.id + '/details/add'}">Nuevo Detalle</md-button>
              </div>
            </div>
          </md-card-header>
          <md-card-content>
            <div v-if="categorySelected !== null">
              <loading :active.sync="isLoading" :can-cancel="false" :is-full-page="fullPage" :loader="loader" :color="loaderColor"></loading>
              <md-table v-model="category_details" table-header-color="green" class="md-scrollbar" style="height: 60vh;" >
                <md-table-row slot="md-table-row" slot-scope="{ item }"  >
                  <md-table-cell md-label="Nombre" md-sort-by="name">{{ item.name }}</md-table-cell>
                  <md-table-cell md-label="Retroalimentacion" md-sort-by="feed_back">{{ item.feed_back }}</md-table-cell>
                  <md-table-cell md-label="Puntos" md-sort-by="points">{{ item.points }}</md-table-cell>
                  <md-table-cell md-label="Opciones">
                    <md-button  class="md-just-icon md-simple md-primary">
                      <router-link :to="{path: '/scoring/' + $route.params.scoringId + '/categories/' + categorySelected.id + '/details/' + item.id + '/edit'}"><md-icon>edit</md-icon></router-link>
                      <md-tooltip md-direction="top">Editar</md-tooltip>
                    </md-button>
                  </md-table-cell>
                </md-table-row>
              </md-table>
            </div>
          </md-card-content>
        </md-card>
      </div>
    </div>
    <md-button class="md-fab flotante md-fab-bottom-right md-success md-icon-button" aria-label="Regresar" type="button" @click="$router.go(-1)" >
      <md-icon>keyboard_backspace</md-icon>
      <md-tooltip md-direction="top">Regresar</md-tooltip>
    </md-button>
  </div>

</template>

<script>
// import { ScoringCategoriesTable } from "../../components";
import axios from 'axios'
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';
export default {
  // components: {
  //   ScoringCategoriesTable
  // }
  components:{
    Loading
  },
  data() {
    return {
      categories: [],
      category_details: null,
      isLoading: false,
      fullPage: true,
      loader: "dots",
      loaderColor: "#1ac036",
      categorySelected: null,
      scoring: null
    };
  },
  created() {
      this.getScoringCategories();
  },
  methods: {
      getScoringCategories: function () {
        const vm = this
        vm.isLoading = true;
        const scoringId = JSON.parse(vm.$route.params.scoringId)
        const scoringCategoriesUrl = '/adm/scoring/' + scoringId + '/categories'
        axios.get(scoringCategoriesUrl)
        .then((response) => {
          vm.isLoading = false;
          vm.categories = response.data.records
          vm.scoring = response.data.scoring
          vm.category_details = null
        });
      },
      onSelect (item) {
        const vm = this
        vm.categorySelected = item
        const scoringId = JSON.parse(vm.$route.params.scoringId)
        const url = '/adm/scoring/' + scoringId + '/categories/' + vm.categorySelected.id + '/details'
        axios.get(url)
        .then((response) => {
          vm.isLoading = false;
          vm.category_details = null
          vm.category_details = response.data.records
        });
      }
  }
};
</script>
<style media="screen" lang="css">
.md-button.md-fab.flotante {
    right: 60px;
    bottom: 30px;
    position: fixed;
    z-index: 4;
    height: 55px;
    min-width: 55px;
    width: 55px;
    line-height: 55px;
}
</style>
