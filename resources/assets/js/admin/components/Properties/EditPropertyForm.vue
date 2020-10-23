<template>
  <div>
    <form novalidate class="md-layout" @submit.prevent="validateProperty">
      <md-card class="md-layout-item md-size-80 md-small-size-100">
        <md-card-header :data-background-color="dataBackgroundColor">
          <h4 class="title">Editar Propiedad : {{ form.name }}</h4>
          <p class="category">Actualizar datos de la propiedad</p>
        </md-card-header>
        <md-card-content>
          <div class="md-layout">
            <loading :active.sync="isLoading" :can-cancel="false" :is-full-page="fullPage" :loader="loader" :color="loaderColor"></loading>
            <div class="md-layout-item md-small-size-100 md-size-100">
              <md-field :class="getValidationClass('name')">
                <label for="name">Nombre</label>
                <md-input name="name" id="name" v-model="form.name" :disabled="sending" />
                <span class="md-error" v-if="!$v.form.name.required">El Nombre es requerido</span>
                <span class="md-error" v-else-if="!$v.form.name.minlength">Nombre Invalido</span>
              </md-field>
            </div>
            <div class="md-layout-item md-size-100">
              <md-field maxlength="5" :class="getValidationClass('description')">
                <label for="description">Descripcion de la propiedad</label>
                <md-textarea v-model="form.description" name="description" id="description" :disabled="sending" ></md-textarea>
                <span class="md-error" v-if="!$v.form.description.required">Descripcion de la propiedad requerida</span>
                <span class="md-error" v-else-if="!$v.form.description.minlength">Descripcion invalida</span>
              </md-field>
            </div>
          </div>
        </md-card-content>
        <md-card-actions>
          <md-button type="button" @click="$router.go(-1)" class="md-info" :disabled="sending">Atras</md-button>
          <md-button type="submit" class="md-success" :disabled="sending">Guardar</md-button>
        </md-card-actions>
      </md-card>

      <md-snackbar :md-active.sync="propertySaved">Los datos de la Propiedad han sido actualizados con exito!</md-snackbar>
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
    minLength,
    maxLength
  } from 'vuelidate/lib/validators'
  const csrf_token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  axios.defaults.headers.common = {
      'X-Requested-With': 'XMLHttpRequest',
      'X-CSRF-TOKEN' : csrf_token
  };

  export default {
    name: "edit-property-form",
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
        propertyId: null,
        name: null,
        description: null
      },
      propertySaved: false,
      sending: false,
      lastProperty: null,
      isLoading: false,
      fullPage: true,
      loader: "dots",
      loaderColor: "#1ac036",
    }),
    // object validate vuelidate
    validations: {
      form: {
        name: {
          required,
          minLength: minLength(3)
        },
        description: {
          required,
          minLength: minLength(3)
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
        this.form.name = null
        this.form.description = null
      },
      saveDataProperty() {
        const vm = this;
        vm.isLoading = true
        const saveDataPropertyUrl = '/adm/properties/'+ vm.form.propertyId +'/update';
        debugger;
        axios.post(saveDataPropertyUrl, {
          propertyId : vm.form.propertyId,
          name : vm.form.name,
          description : vm.form.description
        })
        .then(function(response) {
          vm.lastProperty = `${vm.form.name}`
          vm.propertySaved = true
          vm.isLoading = false
          // vm.clearForm()
        })
        .catch(function(error) {
          console.log(error);
        });
      },
      validateProperty () {
        this.$v.$touch()
        if (!this.$v.$invalid) {
          this.saveDataProperty()
        }
      },
      loadDataProperty() {
        const vm = this;
        vm.isLoading = true
        const propertyId = JSON.parse(vm.$route.params.propertyId)
        const url = '/adm/properties/' + propertyId
        axios.get(url)
        .then((response) => {
          vm.isLoading = false
          if (response.data.property !== null) {
            vm.form = { propertyId : response.data.property.id , name: response.data.property.name, description: response.data.property.description }
          }else{
              vm.$router.push('/properties')
          }
        });
      }
    },
    mounted() {
      //do something after mounting vue instance
    },
    created() {
      //do something after creating vue instance
      this.loadDataProperty()
    }
  }
</script>
