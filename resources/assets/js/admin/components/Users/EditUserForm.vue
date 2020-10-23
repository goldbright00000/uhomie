<template>
  <div>
    <form novalidate class="md-layout" @submit.prevent="validateUser">
      <md-card class="md-layout-item md-size-100 md-small-size-100">
        <md-card-header :data-background-color="dataBackgroundColor">
          <h4 class="title">Editar Usuario : {{ form.firstName }}</h4>
          <p class="category">Actualizar datos de Usuario</p>
        </md-card-header>
        <md-card-content>
          <div class="md-layout">
            <loading :active.sync="isLoading" :can-cancel="false" :is-full-page="fullPage" :loader="loader" :color="loaderColor"></loading>
            <div class="md-layout-item md-small-size-100 md-size-50">
              <div class="md-layout-item md-medium-size-100 md-size-100">
                <h3 class="md-title">Informacion de Usuario</h3>
              </div>
              <div class="md-layout-item md-small-size-100 md-size-100">
                <md-field :class="getValidationClass('firstName')">
                  <label for="firstname">Nombre</label>
                  <md-input name="firstname" id="firstname" v-model="form.firstName" :disabled="sending" />
                  <span class="md-error" v-if="!$v.form.firstName.required">El Nombre es requerido</span>
                  <span class="md-error" v-else-if="!$v.form.firstName.minlength">Nombre Invalido</span>
                </md-field>
              </div>
              <div class="md-layout-item md-small-size-100 md-size-100">
                <md-field :class="getValidationClass('lastName')">
                  <label for="lastname">Apellido</label>
                  <md-input name="lastname" id="lastname" v-model="form.lastName" :disabled="sending" />
                  <span class="md-error" v-if="!$v.form.lastName.required">El Apellido es requerido</span>
                  <span class="md-error" v-else-if="!$v.form.lastName.minlength">Apellido Ivalido</span>
                </md-field>
              </div>
              <div class="md-layout-item md-small-size-100 md-size-100">
                <md-field :class="getValidationClass('email')">
                  <label for="email">Correo</label>
                  <md-input v-model="form.email" type="email" name="email" id="email" :disabled="sending"></md-input>
                  <span class="md-error" v-if="!$v.form.email.required">Correo requerido</span>
                  <span class="md-error" v-else-if="!$v.form.email.email">Direccion de Correo invalida</span>
                </md-field>
              </div>
              <div class="md-layout-item md-small-size-100 md-size-100">
                <span class="md-subheading"> Cambiar Estado </span>
              </div>
              <div class="md-layout-item md-small-size-100 md-size-100">
                <md-switch v-model="form.active" value="1" name="active" class="md-primary">Activar/Desactivar Usuario</md-switch>
              </div>
              <div class="md-layout-item md-small-size-100 md-size-100">
                <span class="md-subheading"> Telefono verificado </span>
              </div>
              <div class="md-layout-item md-small-size-100 md-size-100">
                <md-field>
                  <md-select name="phone_verified" v-model="form.phone_verified" id="phone_verified" :disabled="sending">
                      <md-option value="1">Si</md-option>
                      <md-option value="0">No</md-option>
                  </md-select>
                </md-field>
              </div>
              <div class="md-layout-item md-small-size-100 md-size-100">
                <md-button type="button" @click="$router.go(-1)" class="md-info" :disabled="sending">Atras</md-button>
                <md-button type="submit" class="md-success" :disabled="sending">Guardar</md-button>
              </div>
            </div>
            <div class="md-layout-item md-small-size-100 md-size-50">

              <div class="md-layout-item md-medium-size-100 md-size-100">
                <h3 class="md-title">Generar Contraseña</h3>
              </div>
              <div class="md-layout-item md-small-size-100 md-size-100">
                <span class="md-subheading">El sistema generara una nueva contraseña aleatoria al usuario y se le enviara al correo personal</span>
              </div>
              <div class="md-layout-item md-small-size-100 md-size-100">
                <md-button type="button" class="md-danger" @click="generatePassword" >Generar Contraseña</md-button>
              </div>
              <br>
              <div class="md-layout-item md-medium-size-100 md-size-100">
                <h3 class="md-title">Roles de Usuario</h3>
              </div>
              <div class="md-layout md-gutter ">
                <div class="md-layout-item md-layout md-gutter md-alignment-bottom-center">
                  <div class="md-layout-item" v-for="item in roles" @click="clickRole(item)" style="cursor: pointer;">
                      <md-avatar class="md-large">
                        <img :src="item.image_static_path" alt="People" v-if="!roles_user_array.includes(item.id)">
                        <img :src="item.image_active_path" alt="People" v-if="roles_user_array.includes(item.id)">
                        <md-tooltip md-direction="top">Rol de {{ item.name }}</md-tooltip>
                      </md-avatar>
                      <p class="md-body-1">{{item.name}}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </md-card-content>
      </md-card>
      <md-snackbar :md-active.sync="userSaved">El usuario {{ lastUser }} ha sido actualizado con exito!</md-snackbar>
      <md-snackbar :md-active.sync="passwordGenerated">Contraseña generada con exito!</md-snackbar>
    </form>
  </div>
</template>

<script>
  import axios from 'axios'
  import Loading from 'vue-loading-overlay';
  import 'vue-loading-overlay/dist/vue-loading.css';
  import { validationMixin } from 'vuelidate'
  import {withParams} from 'vuelidate/lib'
  import {
    required,
    email,
    minLength,
    maxLength
  } from 'vuelidate/lib/validators'

  const customValidator = withParams({type: 'customValidator'}, value => {
    return minLength(5)(value) && maxLength(10)(value)
  })
  const csrf_token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  axios.defaults.headers.common = {
      'X-Requested-With': 'XMLHttpRequest',
      'X-CSRF-TOKEN' : csrf_token
  };
  export default {
    name: "edit-user-form",
    mixins: [validationMixin],
    components:{
      Loading
    },
    props: ["dataBackgroundColor"],
    data: () => ({
      form: {
        userId: null,
        firstName: null,
        lastName: null,
        email: null,
        active: 0,
        phone_verified: null,
      },
      roles: [
        {
          "id" : 1,
          "name" : "Arrendatario",
          "type" : "tenant",
          "image_static_path" : '/images/roles/arrendatario-static.png',
          "image_active_path" : '/images/roles/arrendatario-activo.png'
        },
        {
          "id" : 2,
          "name" : "Arrendador",
          "type" : "owner",
          "image_static_path" : '/images/roles/arrendador-static.png',
          "image_active_path" : '/images/roles/arrendador-activo.png'
        },
        {
          "id" : 3,
          "name" : "Agente",
          "type" : "agent",
          "image_static_path" : '/images/roles/agente-static.png',
          "image_active_path" : '/images/roles/agente-activo.png'
        },
        {
          "id" : 4,
          "name" : "Servicios",
          "type" : "service",
          "image_static_path" : '/images/roles/servicios-static.png',
          "image_active_path" : '/images/roles/servicios-activo.png'
        }
        // {
        //   "id" : 5,
        //   "name" : "Aval",
        //   "type" : "collateral",
        //   "image_static_path" : '/images/roles/aval-static.png',
        //   "image_active_path" : '/images/roles/aval-activo.png'
        // }
      ],
      roles_user:[],
      user: {},
      roles_user_array:[],
      userSaved: false,
      passwordGenerated: false,
      sending: false,
      lastUser: null,
      isLoading: false,
      fullPage: null,
      loader: "dots",
      loaderColor: "#1ac036",
    }),
    // object validate vuelidate
    validations: {
      form: {
        firstName: {
          required,
          minLength: minLength(3)
        },
        lastName: {
          required,
          minLength: minLength(3)
        },
        email: {
          required,
          email
        }
      }
    },
    methods: {
      getValidationClass (fieldName) {
        const field = this.$v.form[fieldName]
        if (field) {
          return {
            'md-invalid': field.$invalid && field.$dirty
          }
        }
      },
      clearForm () {
        this.$v.$reset()
        this.form.firstName = null
        this.form.lastName = null
        this.form.email = null
      },
      saveDataUser() {
        const vm = this;
        vm.isLoading = true
        const url = '/adm/users/' + vm.form.userId + '/update'
        axios.post(url, {
          userId : vm.form.userId,
          firstname : vm.form.firstName,
          lastname : vm.form.lastName,
          email : vm.form.email,
          active : vm.form.active,
          phone_verified: vm.form.phone_verified,
        })
        .then(function(response) {
          vm.lastUser = `${vm.form.firstName} ${vm.form.lastName}`
          vm.userSaved = true
          vm.isLoading = false
          vm.loadDataUser()
        })
        .catch(function(error) {
          console.log(error);
        });
      },
      generatePassword() {
        const vm = this;
        vm.isLoading = true
        const url = '/adm/users/' + vm.form.userId + '/generate-password'
        axios.post(url)
        .then(function(response) {
          vm.passwordGenerated = true
          vm.isLoading = false
        })
        .catch(function(error) {
          console.log(error);
        });
      },
      validateUser () {
        this.$v.$touch()
        if (!this.$v.$invalid) {
          this.saveDataUser()
        }
      },
      loadDataUser() {
        const vm = this;
        vm.isLoading = true
        const userId = JSON.parse(vm.$route.params.userId)
        var url =  '/adm/users/' + userId
        axios.get(url)
        .then((response) => {
          vm.isLoading = false
          if (response.data.user !== null) {
            vm.user = response.data.user
            vm.form = {
              userId : response.data.user.id ,
              firstName: response.data.user.firstname,
              lastName: response.data.user.lastname,
              email:response.data.user.email,
              active:response.data.user.active,
              phone_verified: response.data.user.phone_verified
            }
          }else{
              vm.$router.push('/users')
          }
        });
      },
      loadRoles() {
        const vm = this;
        vm.isLoading = true
        const userId = JSON.parse(vm.$route.params.userId)
        var url = '/adm/users/'+ userId +'/roles'
        axios.get(url)
        .then((response) => {
          vm.isLoading = false
          for (var i = 0; i < response.data.records.length; i++) {
            vm.roles_user_array.push(response.data.records[i].id);
          }
          vm.roles_user = response.data.records
        });
      },
      clickRole(role) {
        const vm = this
        if ( vm.roles_user_array.includes( role.id ) ) {
          vm.$router.push('/users/'+vm.form.userId+'/roles/'+role.type)
        }else {
          vm.isLoading = true
          const url = '/adm/users/' + vm.form.userId + '/new-role/' + role.id
          axios.post(url)
          .then(function(response) {
            vm.isLoading = false
            vm.$router.push('/users/'+vm.form.userId+'/roles/'+role.type)
          })
          .catch(function(error) {
            console.log(error);
          });
        }
      }
    },
    created() {
      this.loadDataUser()
      this.loadRoles()
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
  .separator + .separator {
    margin-top: 24px;
  }
</style>
