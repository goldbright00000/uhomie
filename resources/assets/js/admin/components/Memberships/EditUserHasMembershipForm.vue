<template>
  <div>
    <form class="md-layout" @submit.prevent="saveUserHasMembership" >
      <md-card class="md-layout-item md-size-100 md-small-size-100">
        <md-card-header :data-background-color="dataBackgroundColor">
          <h4 class="title">Usuario : {{ form.firstname + " " + form.lastname }}</h4>
          <p class="category">Informacion del Usuario y la Membresia</p>
        </md-card-header>
        <md-card-content>
          <div class="md-layout">
            <loading :active.sync="isLoading" :can-cancel="false" :is-full-page="fullPage" :loader="loader" :color="loaderColor"></loading>
            <div class="md-layout-item md-small-size-100 md-size-50">
              <md-field >
                <label for="firstname">Nombre</label>
                <md-input name="firstname" id="firstname" :disabled="true" v-model="form.firstname" />
              </md-field>
            </div>
            <div class="md-layout-item md-small-size-100 md-size-50">
              <md-field >
                <label for="lastname">Apellido</label>
                <md-input name="lastname" id="lastname" :disabled="true" v-model="form.lastname" />
              </md-field>
            </div>
            <div class="md-layout-item md-small-size-100 md-size-100">
              <md-field >
                <label for="role_name">Rol</label>
                <md-input name="role_name" id="role_name" :disabled="true" v-model="form.membership.role.name" />
              </md-field>
            </div>
            <div class="md-layout-item md-small-size-100 md-size-100">
              <md-field >
                <md-select v-model="selected_membership_id" name="membership" id="membership" placeholder="Membresia">
                  <div v-for="item in memberships" v-if="item.role_id == form.membership.role.id" >
                    <md-option :value="item.id" > {{ item.name }} </md-option>
                  </div>
                </md-select>
                <md-button  class="md-just-icon md-simple md-default" >
                  <router-link :to="{path: '/memberships/' + form.membership.id + '/users/' + form.id + '/delete' }">
                    <md-icon>close</md-icon>
                  </router-link>
                  <md-tooltip md-direction="top">Eliminar Membresia</md-tooltip>
                </md-button>
              </md-field>
            </div>
            <div class="md-layout-item md-small-size-100 md-size-100">
              <md-datepicker md-immediately placeholder="Fecha de Compra" name="membership_purchased_at" id="membership_purchased_at" v-model="selected_purchased_at" :value="form.membership.purchased_at"  >
                <label>Fecha de Compra</label>
              </md-datepicker>
            </div>
            <div class="md-layout-item md-small-size-100 md-size-100">
              <md-datepicker md-immediately placeholder="Expira en la fecha" name="membership_expires_at" id="membership_expires_at" v-model="selected_expires_at" :value="form.membership.expires_at"  >
                <label>Expira en la fecha</label>
              </md-datepicker>
            </div>
          </div>
        </md-card-content>
        <md-card-actions>
          <md-button type="button" @click="$router.go(-1)" class="md-simple md-primary">
              Atras
          </md-button>
          <md-button type="submit" class="md-success" :disabled="sending">Guardar</md-button>
        </md-card-actions>
      </md-card>
      <md-snackbar :md-active.sync="dataSaved">Datos actualizados con exito!</md-snackbar>
    </form>
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
    name: "edit-user-has-membership-form",
    components:{
      Loading
    },
    props:["dataBackgroundColor"],
    data: () => ({
      form:{
        id:null,
        firstname:null,
        lastname:null,
        membership:{
          id:null,
          name:null,
          purchased_at:null,
          expires_at:null,
          role:{
            id:null,
            name:null,
          }
        }
      },
      selected_purchased_at: "",
      selected_expires_at: "",
      selected_membership_id:"",
      memberships:null,
      sending: false,
      isLoading: false,
      fullPage: true,
      loader: "dots",
      loaderColor: "#1ac036",
      dataSaved:false
    }),
    methods: {
      getData() {
        const vm = this;
        vm.isLoading = true
        const userId = JSON.parse(vm.$route.params.userId)
        const membershipId = JSON.parse(vm.$route.params.membershipId)
        const getDataUrl = '/adm/memberships/'+ membershipId +'/users/'+ userId
        axios.get(getDataUrl)
        .then((response) => {
          vm.isLoading = false
          var resp = response.data.user_has_membership
          if ( resp !== null ) {
            vm.form = resp
            vm.selected_membership_id = resp.membership.id
            vm.selected_purchased_at = resp.membership.pivot.purchased_at
            vm.selected_expires_at = resp.membership.pivot.expires_at
          }else{
            vm.$router.go(-1);
          }
        });
      },
      getMemberships() {
        const vm = this;
        vm.isLoading = true
        const membershipsUrl = '/adm/memberships'
        axios.get(membershipsUrl)
        .then((response) => {
          vm.isLoading = false
          vm.memberships = response.data.records
        });
      },
      saveUserHasMembership() {
        const vm = this;
        vm.isLoading = true
        const url = '/adm/memberships/' + vm.selected_membership_id + '/users/' + vm.form.id + '/update'
        axios.post(url,{
          userId : vm.form.id,
          membershipId : vm.form.membership.id,
          membershipIdSelected : vm.selected_membership_id,
          purchased_at : vm.selected_purchased_at,
          expires_at : vm.selected_expires_at
        })
        .then((response) => {
          vm.isLoading = false
          vm.dataSaved = true
          vm.$router.push('/memberships/' + vm.selected_membership_id + '/users/' + vm.form.id)
        });
      }
    },
    created() {
      //do something after mounting vue instance
      this.getData()
      this.getMemberships()
    }
  }
</script>

<style lang="scss" scoped>
  .md-progress-bar {
    position: absolute;
    top: 0;
    right: 0;
    left: 0;
  }
</style>
