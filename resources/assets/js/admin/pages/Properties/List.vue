<template>
  <v-container>
    <div class="md-layout">
      <div @click="publishedList" class="md-layout-item md-medium-size-50 md-xsmall-size-50 md-size-25 cursor-pointer">
        <stats-card data-background-color="blue" md-medium>
          <template slot="header">
            <md-icon >store</md-icon>
          </template>
          <template slot="content">
            <p class="category">Publicadas</p>
            <h3 class="title">{{ descriptors.published }}</h3>
          </template>
          <template slot="footer">
          </template>
        </stats-card>
      </div>      
      <div @click="leasedList" class="md-layout-item md-medium-size-50 md-xsmall-size-50 md-size-25 cursor-pointer">
        <stats-card data-background-color="grey">
          <template slot="header">
            <md-icon >vpn_key</md-icon>
          </template>
          <template slot="content">
            <p class="category">Arrendadas</p>
            <h3 class="title">{{ descriptors.leased }}</h3>
          </template>
          <template slot="footer">
          </template>
        </stats-card>
      </div>
      <div @click="pausedList" class="md-layout-item md-medium-size-50 md-xsmall-size-50 md-size-25 cursor-pointer">
        <stats-card data-background-color="grey">
          <template slot="header">
            <md-icon >pause_circle_outline</md-icon>
          </template>
          <template slot="content">
            <p class="category">Pausadas</p>
            <h3 class="title">{{ descriptors.paused }}</h3>
          </template>
          <template slot="footer">
          </template>
        </stats-card>
      </div>
      <div @click="eliminatedList" class="md-layout-item md-medium-size-50 md-xsmall-size-50 md-size-25 cursor-pointer">
        <stats-card data-background-color="grey">
          <template slot="header">
            <md-icon >delete_outline</md-icon>
          </template>
          <template slot="content">
            <p class="category">Eliminadas</p>
            <h3 class="title">{{ descriptors.eliminated }}</h3>
          </template>
          <template slot="footer">
          </template>
        </stats-card>
      </div>
      <div class="md-layout-item md-medium-size-100 md-xsmall-size-100 md-size-100">
        <md-card>
          <md-card-header data-background-color="black">
            <h4 class="title"
            ><span>{{ filtro ? filtro : 'Propiedades' }}</span></h4>
            <p class="category">Listado de todas las propiedades {{ filtro }}</p>
          </md-card-header>
          <md-card-content>
            <properties-table 
              :properties-list="propertiesList"
              table-header-color="black"
            ></properties-table>
          </md-card-content>
        </md-card>
      </div>
    </div>
    <md-button class="md-fab flotante md-fab-bottom-right md-success md-icon-button" aria-label="Add a category" to="/properties/create">
      <md-icon>add</md-icon>
      <md-tooltip md-direction="top" >Nueva Propiedad</md-tooltip>
    </md-button>
  </v-container>
</template>

<script>
import axios from 'axios'

import { PropertiesTable } from "../../components";
import {
  StatsCard
} from "../../components";
export default {
  data () {
    return {
      descriptors: {
        published: undefined,
        paused: undefined,
        leased: undefined,
        eliminated: undefined
      },
      propertiesList: undefined,
      filtro: undefined
    }
  },
  created: function() {
    this.fetchDescriptors()
  },
  methods: {
    publishedList() {
      const propertiesUrl = '/adm/properties/list-published';
      axios.get(propertiesUrl)
      .then((response) => {
        this.propertiesList = response.data.records
        this.filtro = 'Publicadas'
      });
    },
    eliminatedList() {
      const propertiesUrl = '/adm/properties/list-eliminated';
      axios.get(propertiesUrl)
      .then((response) => {
        this.propertiesList = response.data.records
        this.filtro = 'Eliminadas'
      });
    },
    pausedList() {
      const propertiesUrl = '/adm/properties/list-paused';
      axios.get(propertiesUrl)
      .then((response) => {
        this.propertiesList = response.data.records
        this.filtro = 'Pausadas'
      });
    },
    leasedList() {
      const propertiesUrl = '/adm/properties/list-leased';
      axios.get(propertiesUrl)
      .then((response) => {
        this.propertiesList = response.data.records
        this.filtro = 'Arrendadas'
      });
    },
    fetchDescriptors() {
      const vm = this;

      const propertiesUrl = '/adm/properties/descriptors';
      vm.isLoading = true
      axios.get(propertiesUrl)
      .then((response) => {
        vm.isLoading = false
        this.descriptors = response.data
      });
    },
  },
  components: {
    PropertiesTable,
    StatsCard
  }
};
</script>
<style media="screen" lang="css">
  .cursor-pointer {
    cursor: pointer;
  }

.md-button.md-fab.flotante {
    right: 30px;
    bottom: 30px;
    position: fixed;
    z-index: 4;
    height: 55px;
    min-width: 55px;
    width: 55px;
    line-height: 55px;
}
</style>
