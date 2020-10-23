<template>
  <div>
    <form novalidate class="md-layout" @submit.prevent="validateUser">
      <md-card class="md-layout-item md-size-100 md-small-size-60">
        <md-card-header :data-background-color="dataBackgroundColor">
          <h4 class="title">Crear Usuario</h4>
          <p class="category">Complete los datos del usuario</p>
        </md-card-header>
        <md-card-content>
          <div class="md-layout" >
            <loading :active.sync="isLoading" :can-cancel="false" :is-full-page="fullPage" :loader="loader" :color="loaderColor"></loading>
            <div class="md-layout-item md-small-size-100 md-size-100">
              <md-field >
                <md-select v-model="form.role_selected" name="roles" placeholder="Selecione un rol de usuario" md-dense >
                  <div v-for="role in roles">
                    <md-option :value="role.id">{{ role.name }}</md-option>
                  </div>

                </md-select>
                <span class="md-error">Seleccione un rol de usuario</span>
              </md-field>
            </div>
            <div class="md-layout-item md-small-size-100 md-size-50">
              <md-field :class="getValidationClass('firstName')">
                <label for="firstname">Nombre</label>
                <md-input name="firstname" id="firstname" v-model="form.firstName" :disabled="sending" />
                <span class="md-error" v-if="!$v.form.firstName.required">El Nombre es requerido</span>
                <span class="md-error" v-else-if="!$v.form.firstName.minlength">Nombre Invalido</span>
              </md-field>
            </div>
            <div class="md-layout-item md-small-size-100 md-size-50">
              <md-field :class="getValidationClass('lastName')">
                <label for="lastname">Apellido</label>
                <md-input name="lastname" id="lastname" v-model="form.lastName" :disabled="sending" />
                <span class="md-error" v-if="!$v.form.lastName.required">El Apellido es requerido</span>
                <span class="md-error" v-else-if="!$v.form.lastName.minlength">Apellido Ivalido</span>
              </md-field>
            </div>
            <div class="md-layout-item md-small-size-100 md-size-50">
              <md-field :class="getValidationClass('email')">
                <label for="email">Correo</label>
                <md-input v-model="form.email" type="email" name="email" id="email" :disabled="sending"></md-input>
                <span class="md-error" v-if="!$v.form.email.required">Correo requerido</span>
                <span class="md-error" v-else-if="!$v.form.email.email">Direccion de Correo invalida</span>
              </md-field>
            </div>
            <div class="md-layout-item md-small-size-100 md-size-50">
              <md-field :class="getValidationClass('emailConfirm')">
                <label for="emailConfirm">Confirmar Correo</label>
                <md-input v-model="form.emailConfirm" type="email" name="emailConfirm" id="emailConfirm" :disabled="sending"></md-input>
                <span class="md-error" v-if="!$v.form.emailConfirm.required">Correo requerido</span>
                <span class="md-error" v-else-if="!$v.form.emailConfirm.email">Direccion de Correo invalida</span>
                <span class="md-error" v-else-if="!$v.form.emailConfirm.sameAsEmail">Correos no coinciden</span>
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
    name: "create-user-form",
    mixins: [validationMixin],
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
        firstName: null,
        lastName: null,
        email: null,
        emailConfirm: null,
        // password: null,
        role_selected: null
      },
      userSaved: false,
      sending: false,
      lastUser: null,
      roles:null,
      isLoading: false,
      fullPage: true,
      loader: "dots",
      loaderColor: "#1ac036"
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
        },
        emailConfirm: {
          required,
          email,
          sameAsEmail: sameAs('email')
        },
        // password: {
        //   required,
        //   lengthPassword,
        //   hasUppercase,
        //   hasDigits,
        //   notSpaces,
        //   hasSymbols
        // }
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
        this.form.role_selected = null
        this.form.email = null
        this.form.emailConfirm = null
      },
      saveUser () {
        const createUserUrl = '/adm/users/create';
        const vm = this;
        const roles_path_redirect = [
          { name: "Arrendatario", role_id: 1, base_path: "tenant"  },
          { name: "Owner", role_id: 2, base_path: "owner"  },
          { name: "Agente", role_id: 3, base_path: "agent"  },
          { name: "Service", role_id: 4, base_path: "service"  },
          { name: "Aval", role_id: 5, base_path: "collateral"  }
        ]
        var path_redirect = null
        vm.isLoading = true
        for (var i = 0; i < roles_path_redirect.length; i++) {
          if (roles_path_redirect[i].role_id == this.form.role_selected ) {
              path_redirect = roles_path_redirect[i].base_path
          }
        }
        axios.post(createUserUrl, {
          firstname : this.form.firstName,
          lastname : this.form.lastName,
          // password : this.form.password,
          role : this.form.role_selected,
          email : this.form.email
        })
        .then(function(response) {
          vm.lastUser = `${vm.form.firstName} ${vm.form.lastName}`
          vm.userSaved = true
          vm.isLoading = false
          vm.clearForm()
          vm.$router.push("/users/" + response.data.user.id + "/roles/" + path_redirect)
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
      },
      loadRoles() {
        const vm = this
        const rolesUrl = '/adm/roles';
        axios.get(rolesUrl)
        .then((response) => {
            vm.roles = response.data.records
        });
      }
    },
    created() {
      //do something after creating vue instance
      this.loadRoles()
    }
  }
</script>
