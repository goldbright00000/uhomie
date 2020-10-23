<template>
  <form @submit.prevent="saveFeatures">
    <img v-if="form.name == 'Basic'" src="/images/logo_basic.png" style="width: 120px">
    <img v-if="form.name == 'Select'" src="/images/logo_select.png" style="width: 120px">
    <img v-if="form.name == 'Premium'" src="/images/logo_premium.png" style="width: 120px">
    <md-card>
      <md-card-header :data-background-color="dataBackgroundColor">        
        <h4 class="title">Membresia {{ form.name }} del Rol <b>{{ form.role }}</b></h4>
        <p class="category">Modificar Caraterísticas de esta Membresia</p>
      </md-card-header>

      <md-card-content>
        <loading :active.sync="isLoading" :can-cancel="false" :is-full-page="fullPage" :loader="loader" :color="loaderColor"></loading>
        <br>

        <div v-for="(item,i) in items_features">
          <div class="md-layout md-gutter md-alignment-center-center" style="border-bottom: 1px solid #ddd;">
            <div class="md-layout-item md-size-5"></div>
            <div class="md-layout-item" style="font-weight: bold;">

              <div v-if="item.type == 'number'">
                <p>{{ item.human_name }}</p>
              </div>

              <div v-if="item.type == 'change_status'">
                <p>{{item.human_name}}</p>
              </div>
              <div v-if="item.type == 'radio'" >
                <p>{{ item.human_name }}</p>
              </div>
            </div>
            <div class="md-layout-item">

              <div v-if="item.type == 'number'">
                <md-field>
                  <md-input v-bind:value="item.value" type="number" step="any" v-model="form.features_parsed[i].value" min="0"></md-input>
                </md-field>
              </div>
              <div v-if="item.type == 'unlimited'">
                <md-field>
                  <md-input v-bind:value="item.value" type="number" step="any" v-model="form.features_parsed[i].value" min="-1"></md-input>
                  <md-switch v-model="form.features_parsed[i].value" value="-1" >Ilimitado</md-switch>
                </md-field>
              </div>

              <div v-if="item.type == 'change_status'">
                  <md-switch v-model="form.features_parsed[i].value" ></md-switch>
              </div>
              <div v-if="item.type == 'radio'" >
                <div v-for="radio_option in item.radio_options.options">
                  <md-radio v-model="form.features_parsed[i].value" :value="radio_option.value" >
                    {{ radio_option.key }}
                  </md-radio>
                </div>
              </div>

            </div>
            <div class="md-layout-item md-size-5"></div>
          </div>
        </div>
      </md-card-content>
      <md-card-actions>
        <div class="md-layout-item md-size-100 text-center">
          <md-button type="button" @click="$router.go(-1)" class="md-info" >Atras</md-button>
          <md-button type="submit" class="md-success" >Guardar</md-button>
        </div>
      </md-card-actions>
    </md-card>
    <md-snackbar :md-active.sync="membershipSaved">Membresia {{ lastMembership }} actualizada con exito!</md-snackbar>
  </form>
</template>
<script>
import axios from 'axios'
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';
const $lang = require("../../locale/es.json")

const csrf_token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

axios.defaults.headers.common = {
  'X-Requested-With': 'XMLHttpRequest',
  'X-CSRF-TOKEN' : csrf_token
};

export default {
  name: "edit-membership-form",
  props: ['dataBackgroundColor','membershipId'],
  components:{
    Loading
  },
  data() {
    return {
      items_features: null,
      array_features_type: null,
      form: {},
      radio_options: null,
      membershipSaved: false,
      sending: false,
      lastMembership: null,
      isLoading: false,
      fullPage: true,
      loader: "dots",
      loaderColor: "#1ac036"
    };
  },
  methods:{
    processArray(self) {
      var keys = Object.keys(self.form.features);
      var arrayFeatures = []
      for(var i=0; i<keys.length; i++){
        var key = keys[i];
        arrayFeatures.push({ "key" : key, "human_name" : null, "value" : self.form.features[key],"type" : null,"radio_options" : null,"radio_option_selected" : null })

        if ( self.array_features_type.change_status.includes(key) ) {
          arrayFeatures[i].type = "change_status"
        }else if ( self.array_features_type.number.includes(key) ) {
          arrayFeatures[i].type = "number"
        }else if ( self.array_features_type.unlimited.includes(key) ) {
          arrayFeatures[i].type = "unlimited"
        }else if ( self.array_features_type.radio.includes(key) ) {
          arrayFeatures[i].type = "radio"
          arrayFeatures[i].radio_option_selected = arrayFeatures[i].value
          for (var k = 0; k < self.radio_options.length; k++) {
            if (self.radio_options[k].radio_name == arrayFeatures[i].key ) {
              arrayFeatures[i].radio_options = self.radio_options[k]
            }
          }
        }

        for (var j = 0; j < $lang.features.length; j++) {
          if ( arrayFeatures[i].key == $lang.features[j].key ) {
            arrayFeatures[i].human_name = $lang.features[j].value
          }
        }
      }
      self.items_features = arrayFeatures
      self.form.features_parsed = arrayFeatures

      console.log(self.items_features)
    },
    loadFeaturesType(){
      this.array_features_type = {
        change_status : [
          "score_display",
          "display_all_properties",
          "suggestions_to_owners",
          "suggestions_to_tenants",
          "smart_agent",
          "public_support",
          "service_fee",
          "trust_seal",
          "owner_verification"
        ],
        number : [
          "commission",
          "tenanting_fee",
          "videos_per_project",
          "project_due_days",
          "photos_per_project",          
          "package_amount",
          "add_days",
          "add_zones",
          "main_services",
          "secondary_services"
        ],
        radio : [
          "owner_contact","recommendations"
        ],
        unlimited : [
          "services_counts",
          "application_count",
          "properties_count",
          "montly_publications",
          "applications_received_count"
        ]
      }
      this.radio_options = [
        {
          radio_name : "owner_contact",

          options : [
            { "key" : "Chat", "value" : 0 },
            { "key" : "Correo + Chat", "value" : 1 },
            { "key" : "Telefono + Correo + Chat", "value" : 2 }
          ]
        },
        {
          radio_name : "recommendations",
          options : [
            { "key" : "Ninguno", "value" : 0 },
            { "key" : "Mailing", "value" : 1 },
            { "key" : "Mailing, Campañas,Push de Notificaciones, Redes sociales", "value" : 2 }
          ]
        }
      ]
    },
    transformFeaturesArray(self){
      var arrayFeatures = new Object()
      for (var i = 0; i < self.form.features_parsed.length; i++) {

        arrayFeatures['' + self.form.features_parsed[i].key + ''] = self.form.features_parsed[i].value
      }
      self.form.features = arrayFeatures;
    },
    loadDataMembership() {
      const vm = this;
      vm.isLoading = true
      const membershipId = vm.membershipId
      var url = '/adm/memberships/' + membershipId
      axios.get(url)
      .then((response) => {
        vm.isLoading = false
        if (response.data.membership !== null) {

          vm.form = {
            id: response.data.membership.id,
            name: response.data.membership.name,
            role: response.data.membership.role,
            features: response.data.membership.features,
            features_parsed: null
          }
          vm.processArray(vm)
          vm.transformFeaturesArray(vm)

          console.log(response.data)
        }else{
          vm.$router.push('/memberships')
        }
      });
    },
    saveFeatures() {
      const self = this
      self.transformFeaturesArray( self )
      self.isLoading = true
      const url = '/adm/memberships/'+self.form.id+'/update';
      console.log(self.form)
      axios.post(url, {
        membershipId : self.form.id,
        features : self.form.features
      })
      .then(function(response) {
        self.lastMembership = `${self.form.name}`
        self.membershipSaved = true
        self.isLoading = false
      })
      .catch(function(error) {
        console.log(error);
      });
    },
  },
  created() {
    this.loadFeaturesType()
    this.loadDataMembership()
  }
};
</script>
<style>
</style>
