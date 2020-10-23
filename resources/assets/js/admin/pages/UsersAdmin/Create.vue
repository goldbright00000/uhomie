<template>
  <div class="content">
    <div class="md-layout">
      <div class="md-layout-item md-medium-size-100 md-size-15"></div>
      <div class="md-layout-item md-medium-size-100 md-size-70">
        <form novalidate class="md-layout" @submit.prevent="validateUser">
          <md-card class="md-layout-item md-size-100">
            <md-card-header data-background-color="green">
              <h4 class="title">Crear Usuario backend</h4>
              <p class="category">Complete los datos del usuario</p>
            </md-card-header>
            <md-card-content>
              <div class="md-layout" >
                <loading :active.sync="isLoading" :can-cancel="false" :is-full-page="fullPage" :loader="loader" :color="loaderColor"></loading>
                <div class="md-layout-item md-small-size-100 md-size-70">
                  <md-field :class="getValidationClass('name')">
                    <label for="firstname">Nombre y apellido</label>
                    <md-input name="firstname" id="firstname" v-model="form.name" :disabled="sending" />
                    <span class="md-error" v-if="!$v.form.name.required">El Nombre es requerido</span>
                  </md-field>
                </div>
                <div class="md-layout-item md-small-size-100 md-size-70">
                  <md-field :class="getValidationClass('email')">
                    <label for="email">Correo</label>
                    <md-input v-model="form.email" type="email" name="email" id="email" :disabled="sending"></md-input>
                    <span class="md-error" v-if="!$v.form.email.required">Correo requerido</span>
                    <span class="md-error" v-else-if="!$v.form.email.email">Direccion de Correo invalida</span>
                  </md-field>
                </div>
                <md-switch v-model="form.isAdmin">Es admin</md-switch>
                <div class="md-layout-item md-small-size-100 md-size-70">
                  <md-field :class="getValidationClass('password')">
                    <label>Password</label>
                    <md-input v-model="form.password" type="password"></md-input>
                    <span class="md-error" v-if="!$v.form.password.required">Por favor escriba una contraseña</span>
                  </md-field>
                </div>
              </div>
            </md-card-content>
            <md-card-actions>
              <md-button type="button" @click="$router.go(-1)" class="md-info" :disabled="sending">Atras</md-button>
              <md-button type="submit" class="md-success" :disabled="sending">Guardar</md-button>
            </md-card-actions>
          </md-card>
          <md-snackbar :md-active.sync="userSaved">El usuario {{ lastUser }} ha sido registrado con exito!</md-snackbar>
        </form>
      </div>
    </div>

    <md-snackbar md-position="center" :md-duration="4000" :md-active.sync="snackbar" md-persistent>
      <span>{{ snackbarText }}</span>
    </md-snackbar>
  </div>
</template>

<script>
  import axios from 'axios'
  import Loading from 'vue-loading-overlay';
  import 'vue-loading-overlay/dist/vue-loading.css';
  import passwordValidator from 'password-validator';
  import { validationMixin } from 'vuelidate'
  import {withParams} from 'vuelidate/lib'
  import {
    required,
    email,
    minLength,
    maxLength,
    sameAs
  } from 'vuelidate/lib/validators'
  const csrf_token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  axios.defaults.headers.common = {
    'X-Requested-With': 'XMLHttpRequest',
    'X-CSRF-TOKEN' : csrf_token
  };
  const lengthPassword = withParams({type: 'lengthPassword'}, value => {
    return minLength(8)(value) && maxLength(20)(value)
  })
  const hasUppercase = withParams({type: 'hasUppercase'}, value => {
    const schema = new passwordValidator();
    schema.has().uppercase();
    return schema.validate(value)
  })
  const hasDigits = withParams({type: 'hasDigits'}, value => {
    const schema = new passwordValidator();
    schema.has().digits();
    return schema.validate(value)
  })
  const notSpaces = withParams({type: 'notSpaces'}, value => {
    const schema = new passwordValidator();
    schema.not().spaces();
    return schema.validate(value)
  })
  const hasSymbols = withParams({type: 'hasSymbols'}, value => {
    const schema = new passwordValidator();
    schema.has().symbols();
    return schema.validate(value)
  })
  export default {
    mixins: [validationMixin],
    components:{
      Loading
    },
    data: () => ({
      dataBackgroundColor: {
        type: String,
        default: ""
      },
      form: {
        name: null,
        email: null,
        password: null,
        isAdmin: false
      },
      userSaved: false,
      sending: false,
      lastUser: null,
      isLoading: false,
      fullPage: true,
      loader: "dots",
      loaderColor: "#1ac036",
      snackbar: false,
      snackbarText: ''
    }),
    // object validate vuelidate
    validations: {
      form: {
        name: {
          required,
          minLength: minLength(3)
        },
        email: {
          required,
          email
        },
        password: {
          required
        //   lengthPassword,
        //   hasUppercase,
        //   hasDigits,
        //   notSpaces,
        //   hasSymbols
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
        // this.form.password = null
        this.form.isAdmin = null
        this.form.email = null
      },
      saveUser () {
        const createUserUrl = '/adm/users-admin/create';
        const vm = this;        
        var path_redirect = null
        vm.isLoading = true
        
        axios.post(createUserUrl, {
          name : this.form.name,
          email : this.form.email,
          password : this.form.password,
          isAdmin : this.form.isAdmin
        })
        .then(function(response) {
          if(response.data.operation) {
            vm.lastUser = `${vm.form.name}`
            vm.userSaved = true
            vm.isLoading = false
            vm.clearForm()
            vm.$router.push("/users-admin/")
          } else {
            vm.snackbarText = response.data.message
            vm.isLoading = false
            vm.snackbar = true            
          }
        })
        .catch(function(error) {
          console.log(error);
        });
      },
      validateUser () {
        this.$v.$touch()
        if (!this.$v.$invalid) {
          this.saveUser()
        }
      }
    },
    created() {
      //do something after creating vue instance
    }
  }
</script>
