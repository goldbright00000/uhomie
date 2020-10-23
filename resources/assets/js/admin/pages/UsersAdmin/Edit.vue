<template>
  <div class="content">
    <div class="md-layout">
      <div class="md-layout-item md-medium-size-100 md-size-15"></div>
      <div class="md-layout-item md-medium-size-100 md-size-100">
        <form novalidate class="md-layout" @submit.prevent="validateUser">
          <md-card class="md-layout-item md-size-100">
            <md-card-header data-background-color="green">
              <h4 class="title">Editar Usuario backend</h4>
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
                  <md-field :class="getValidationClass('phone')">
                    <label for="phone">Telefono</label>
                    <md-input name="phone" id="phone" v-model="form.phone" :disabled="sending" />
                    <span class="md-error" v-if="!$v.form.phone.required">El Telefono es requerido</span>
                  </md-field>
                  <md-field :class="getValidationClass('address')">
                    <label for="address">Dirección</label>
                    <md-input name="address" id="address" v-model="form.address" :disabled="sending" />
                    <span class="md-error" v-if="!$v.form.address.required">La dirección es requerida</span>
                  </md-field>
                  <md-field :class="getValidationClass('email')">
                    <label for="email">Correo</label>
                    <md-input v-model="form.email" type="email" name="email" id="email" :disabled="sending"></md-input>
                    <span class="md-error" v-if="!$v.form.email.required">Correo requerido</span>
                    <span class="md-error" v-else-if="!$v.form.email.email">Direccion de Correo invalida</span>
                  </md-field>
                  <md-field>
                    <label for="role">Role</label>
                    <md-select v-model="form.role" name="role" id="role">
                      <md-option value="1">Administrador</md-option>
                      <md-option value="2">Gerencial</md-option>    
                      <md-option value="3">Ejecutivo</md-option>                
                    </md-select>
                  </md-field>
                  <md-field>
                    <label>Password</label>
                    <md-input v-model="form.password" type="password"></md-input>
                  </md-field>
                </div>
                <div class="md-layout-item md-small-size-100 md-size-30">
                  <md-card v-if="form.photo == null">
                    <vue-dropzone
                      ref="myVueDropzone"
                      id="dropzone"
                      :destroyDropzone="destroy"
                      v-on:vdropzone-success="showSuccessUpload"
                      v-on:vdropzone-error="showError('Ha ocurrido un error')"
                      v-on:vdropzone-max-files-exceeded="showError('Ha superado el limite de archivos a cargar')"
                      v-on:vdropzone-canceled="showSuccess('Se ha cancelado la imagen exitosamente')"
                      :options="dropzoneOptions"
                      >
                    </vue-dropzone>
                  </md-card>
                  
                  <md-card v-else>
                    <md-card-media>
                      <md-ripple>
                        <img :src="form.photo" alt="avatar">
                      </md-ripple>
                    </md-card-media>

                    <md-card-actions>
                      <md-button class="md-primary" @click="editPhoto()">editar</md-button>
                    </md-card-actions>
                  </md-card>
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
  import { validationMixin } from 'vuelidate';
  import {withParams} from 'vuelidate/lib'
  import {
    required,
    email,
    minLength,
    maxLength,
    sameAs
  } from 'vuelidate/lib/validators'
  import vue2Dropzone from 'vue2-dropzone'
  import 'vue2-dropzone/dist/vue2Dropzone.min.css'
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

  const lengthPassword = withParams({type: 'lengthPassword'}, value => {
    return minLength(8)(value) && maxLength(20)(value)
  })

  const hasSymbols = withParams({type: 'hasSymbols'}, value => {
    const schema = new passwordValidator();
    schema.has().symbols();
    return schema.validate(value)
  })
  export default {
    mixins: [validationMixin],
    components:{
      Loading,
      vueDropzone: vue2Dropzone
    },
    data: () => ({
      dataBackgroundColor: {
        type: String,
        default: ""
      },
      form: {
        userId: null,
        name: null,
        email: null,
        phone: null,
        address: null,
        photo: null,
        password: null,
        role: 0
      },
      userSaved: false,
      sending: false,
      lastUser: null,
      isLoading: false,
      fullPage: true,
      loader: "dots",
      loaderColor: "#1ac036",
      snackbar: false,
      snackbarText: '',
      dropzoneOptions: {},
      destroy: false
    }),
    // object validate vuelidate
    validations: {
      form: {
        name: {
          required,
          minLength: minLength(3)
        },
        phone: {
          required,
          minLength: minLength(7)
        },
        address: {
          required,
          minLength: minLength(10)
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
      loadDataUser() {
        const vm = this;
        vm.isLoading = true
        const userId = JSON.parse(vm.$route.params.userId)
        var url =  '/adm/users-admin/' + userId 
        axios.get(url)
        .then((response) => {
          vm.isLoading = false
          if (response.data.user !== null) {
            vm.user = response.data.user
            vm.form = {
              userId : response.data.user.id ,
              name: response.data.user.name,
              email: response.data.user.email,
              phone: response.data.user.phone,
              address: response.data.user.address,
              photo: response.data.user.photo,
              role: response.data.user.role
            }
          }else{
              vm.$router.push('/users-admin')
          }
        });
      },
      clearForm () {
        this.$v.$reset()
        this.form.name = null
        this.form.password = null
        this.form.role = null
        this.form.email = null
        this.form.phone = null
        this.form.address = null
        this.form.photo = null
      },
      saveUser () {
        const vm = this;
        const url = '/adm/users-admin/' + vm.form.userId + '/update'        
        var path_redirect = null
        vm.isLoading = true
        
        axios.post(url, {
          userId: this.form.userId,
          name : this.form.name,
          email : this.form.email,
          password : this.form.password,
          role : this.form.role,
          phone : this.form.phone,
          address : this.form.address,
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
      },
      dataOptionsDropzone(){
        this.dropzoneOptions = {
          params: {
              cover: 1,
              _token : csrf_token
          },
          url: '/adm/users-admin/'+ this.$route.params.userId +'/update-photo',
          thumbnailWidth: 200,
          headers: { "X-CSRF-TOKEN": csrf_token },
          maxFilesize: 8,
          renameFile: function(file) {
              var dt = new Date();
              var time = dt.getTime();
              return time+file.name;
          },
          acceptedFiles: ".jpeg,.jpg,.png,.gif",
          dictInvalidFileType: "Tipo de archivo no admitido",
          dictFileTooBig: 'Ha superado el limite de carga de imagen',
          resizeWidth: 1024,
          resizeHeight: 1024,
          resizeQuality: 0.8,
          resizeMethod: 'contain',
          //resizeMimeType: 'image/jpeg',
          dictDefaultMessage: 'Sube aqui la imagen cover de tu propiedad',
          addRemoveLinks: true,
          dictCancelUpload: 'Cancelar Subida',
          dictCancelUploadConfirmation: 'Desea cancelar la carga de imagen',
          dictRemoveFile: 'Quitar Imagen',
          timeout: 5000,
          maxFiles: 1,
          success: function(file, response) 
          {
              console.log(response);
              console.log(file);
          },
          error: function(file, response)
          {
              return false;
          },
        }
      },
      showError(m){
          this.snackbarText = m
          this.snackbar = true
          console.log(m);
      },
      showSuccess(m){
          console.log(m);
      },
      showSuccessUpload(file, response){
          this.destroy = true
          this.snackbarText = 'Se ha subido la imagen exitosamente'
          this.snackbar = true
          this.form.photo = response.url
      },
      editPhoto(){
          this.form.photo = null
      }
    },
    created() {
      this.loadDataUser();
      this.dataOptionsDropzone();
    }
  }
</script>
