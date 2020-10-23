<template>
  <div class="content">

    <div class="md-layout">
      <loading :active.sync="isLoading" :can-cancel="false" :is-full-page="fullPage" :loader="loader" :color="loaderColor"></loading>      
      <div class="md-layout-item md-medium-size-100 md-xsmall-size-100 md-size-100" >
        <md-card
          v-for="(role, i) in roles"
          :key="i"
          v-if="role.memberships.length > 0"
        >
          <md-card-header data-background-color="black">
            <h4 class="title">{{ role.role_name }}</h4>
          </md-card-header>
          <md-card-content>

            <memberships-table table-header-color="black" v-bind:role="role" ></memberships-table>
            
          </md-card-content>
        </md-card>

        <!--
        <users-memberships-default-table table-header-color="green" v-bind:usersMembershipsDefault="usersMembershipsDefault" ></users-memberships-default-table>
        -->
      </div>
      <div class="md-layout-item md-medium-size-50 md-xsmall-size-100 md-size-25">
        <stats-card data-background-color="black">
          <template slot="header">
            <md-icon >store</md-icon>
          </template>
          <template slot="content">
            <p class="category">Usuarios con multiperfil</p>

            <h3 class="title">
              <router-link
              :to="{path: '/memberships/users/multirole'}" >
              {{ countMultiRolesUsers }}
            </router-link>
            </h3>
          </template>
          <template slot="footer">
          </template>
        </stats-card>
      </div>
    </div>
  </div>
</template>
<script>
import { MembershipsTable,UsersMembershipsDefaultTable } from "../../components";
import axios from 'axios'
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';
import {
  StatsCard
} from "../../components";

export default {
  components: {
    Loading,
    StatsCard,
    MembershipsTable,
    UsersMembershipsDefaultTable
  },
  data(){
    return {
      isLoading: false,
      fullPage: true,
      loader: "dots",
      loaderColor: "#1ac036",
      roles: [],
      usersMembershipsDefault:[],
      countMultiRolesUsers:null
    }
  },
  methods: {
    getRoles() {
      const vm = this
      vm.isLoading = true;
      const membershipsUrl = '/adm/memberships/roles';
      axios.get(membershipsUrl)
      .then((response) => {
        vm.roles = response.data.records
        vm.isLoading = false;
      });
    },
    getDescriptors() {
      this.getMultiRolCount()
      this.getUsersDefault()
    },
    getMultiRolCount(){
      const vm = this
      vm.isLoading = true;
      const url = '/adm/memberships/descriptors/users-multirole';
      axios.get(url)
      .then((response) => {
        vm.countMultiRolesUsers = response.data.users_count
        vm.isLoading = false;
      });
    },
    getUsersDefault(){
      const vm = this
      vm.isLoading = true;
      const url = '/adm/memberships/descriptors/users-default'
      axios.get(url)
      .then((response) => {
        vm.usersMembershipsDefault = response.data.roles_memberships
        vm.isLoading = false;
      });
    }
  },
  created() {
    this.getRoles()
    this.getDescriptors()
  }
};
</script>
