<template>
  <div>
    <form novalidate class="md-layout" @submit.prevent="validateScoringCategoryDetail">
      <md-card class="md-layout-item md-size-80 md-small-size-100">
        <md-card-header :data-background-color="dataBackgroundColor">
          <h4 class="title">Editar Detalle de Categoria : {{ form.name }}</h4>
          <p class="category">Actualizar datos del detalle de Categoria</p>
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
            <div class="md-layout-item md-small-size-100 md-size-100">
              <md-field :class="getValidationClass('points')">
                <label for="points">Puntos</label>
                <md-input name="points" id="points" v-model="form.points" :disabled="sending" />
                <span class="md-error" v-if="!$v.form.points.required">Campo Puntos es requerido</span>
              </md-field>
            </div>
            <div class="md-layout-item md-small-size-100 md-size-100">
              <md-field :class="getValidationClass('feed_back')">
                <label for="feed_back">Retroalimentacion</label>
                <md-input name="feed_back" id="feed_back" v-model="form.feed_back" :disabled="sending" />
                <span class="md-error" v-if="!$v.form.feed_back.required">Campo Retroalimentacion es requerido</span>
              </md-field>
            </div>
            <div class="md-layout-item md-size-100">
              <md-field maxlength="5" :class="getValidationClass('description')">
                <label for="description">Descripcion</label>
                <md-textarea v-model="form.description" name="description" id="description" :disabled="sending" ></md-textarea>
                <span class="md-error" v-if="!$v.form.description.required">Descripcion del Proyecto requerida</span>
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

      <md-snackbar :md-active.sync="scoringCategoryDetailSaved">Los datos del detalle han sido actualizados con exito!</md-snackbar>
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
    name: "edit-scoring-category-detail-form",
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
        scoringId: null,
        name: null,
        feed_back: null,
        points: null,
        description: null
      },
      scoringCategoryDetailSaved: false,
      sending: false,
      lastScoringCategoryDetail: null,
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
        feed_back: {
          required
        },
        points: {
          required
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
        this.form.feed_back = null
        this.form.description = null
      },
      saveDataScoringCategory() {
        const vm = this;
        vm.isLoading = true
        const scoringId = JSON.parse(vm.$route.params.scoringId)
        const scoringCategoryId = JSON.parse(vm.$route.params.scoringCategoryId)
        const scoringCategoryDetailId = JSON.parse(vm.$route.params.detailId)
        var url = '/adm/scoring/' + scoringId + '/categories/' + scoringCategoryId + '/details/' + scoringCategoryDetailId + '/update'
        axios.post(url, {
          categoryDetailId : scoringCategoryDetailId,
          name : vm.form.name,
          feed_back : vm.form.feed_back,
          points : vm.form.points,
          description : vm.form.description
        })
        .then(function(response) {
          vm.lastScoringCategoryDetail = `${vm.form.name}`
          vm.scoringCategoryDetailSaved = true
          vm.isLoading = false
          // vm.clearForm()
        })
        .catch(function(error) {
          console.log(error);
        });
      },
      validateScoringCategoryDetail () {
        this.$v.$touch()
        if (!this.$v.$invalid) {
          this.saveDataScoringCategory()
        }
      },
      loadDataScoringCategory() {
        const vm = this;
        vm.isLoading = true
        const scoringId = JSON.parse(vm.$route.params.scoringId)
        const scoringCategoryId = JSON.parse(vm.$route.params.scoringCategoryId)
        const scoringCategoryDetailId = JSON.parse(vm.$route.params.detailId)
        var url = '/adm/scoring/' + scoringId + '/categories/' + scoringCategoryId + '/details/' + scoringCategoryDetailId
        axios.get(url)
        .then((response) => {
          vm.isLoading = false
          if (response.data.category_detail !== null) {
            vm.form = {
              detailId: response.data.category_detail.id,
              name: response.data.category_detail.name,
              feed_back: response.data.category_detail.feed_back,
              points: response.data.category_detail.points,
              description: response.data.category_detail.description
            }
          }else{
              vm.$router.go(-1)
          }
        });
      }
    },
    mounted() {
      //do something after mounting vue instance
    },
    created() {
      //do something after creating vue instance
      this.loadDataScoringCategory()
    }
  }
</script>
