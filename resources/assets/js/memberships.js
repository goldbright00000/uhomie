var Vue = require('vue');
var Lang = require('vue-lang');

var locales = {
  "es": require("./locale/es.json")
}

const membershipsConfig = require("./memberships_config.json")

Vue.use(Lang, {lang: 'es', locales: locales})

const $lang = require("./locale/es.json")

import Axios from 'axios';

const membershipsUrl = '/get-memberships';
const imagesDir = document.getElementById('images-dir').value;
const membership = new Vue({
    el: '#memberships',
    components: {  },
    data() {
        return {
            memberships: null,
            membershipSelected: false,
            features:null,
            membershipsConfig: [
              { 
                "type" : "boolean",
                "key" : "display_all_properties" , 
                "order" : 3 
              },
              { 
                "type" : "number",
                "key" : "commission" , 
                "order" : 4 
              },
              { 
                "type" : "boolean",
                "key" : "suggestions_to_owners" , 
                "order" : 6 
              },
              { 
                "type" : "unlimited",
                "key" : "application_count" , 
                "order" : 1 
              },
              { 
                "type" : "unlimited",
                "key" : "properties_count" , 
                "order" : 2 
              },
              { 
                "type" : "boolean",
                "key" : "score_display" , 
                "order" : 3 
              },
              { 
                "type" : "unlimited",
                "key" : "applications_received_count" , 
                "order" : 5 
              },
              { 
                "type" : "number",
                "key" : "tenanting_fee" , 
                "order" : 5 
              },
              { 
                "type" : "boolean",
                "key" : "suggestions_to_tenants" , 
                "order" : 8 
              },
              { 
                "type" : "boolean",
                "key" : "owner_verification" , 
                "order" : 9 
              },
              { 
                "type" : "boolean",
                "key" : "smart_agent" , 
                "order" : 10 
              },
              { 
                "type" : "unlimited",
                "key" : "montly_publications" , 
                "order" : 1 
              },
              { 
                "type" : "number",
                "key" : "package_amount" , 
                "order" : 0 
              },
              { 
                "type" : "unlimited",
                "key" : "services_counts" , 
                "order" : 1 
              },
              { 
                "type" : "number",
                "key" : "photos_per_project" , 
                "order" : 2 
              },
              { 
                "type" : "number",
                "key" : "videos_per_project" , 
                "order" : 3 
              },
              { 
                "type" : "number",
                "key" : "project_due_days" , 
                "order" : 4 
              },
              { 
                "type" : "number",
                "key" : "add_days" , 
                "order" : 5 
              },
              { 
                "type" : "boolean",
                "key" : "owner_contact" , 
                "order" : 6 
              },
              { 
                "type" : "number",
                "key" : "add_zones" , 
                "order" : 7 
              },
              { 
                "type" : "boolean",
                "key" : "public_support" , 
                "order" : 8 
              },
              { 
                "type" : "boolean",
                "key" : "service_fee" , 
                "order" : 9 
              },
              { 
                "type" : "boolean",
                "key" : "trust_seal" , 
                "order" : 10 
              },
              { 
                "type" : "boolean",
                "key" : "recommendations" , 
                "order" : 11 
              }
            ]
        };
    },
    mounted: function() {},
    methods: {
        omitAllPlanCards: function(){
          $('.membership-item').each(function(index, membershipItem) {
            $(membershipItem).removeClass('selected').addClass('omit');
          });
        },
        removeOmitAllPlanCards: function(){
          $('.membership-item').each(function(index, membershipItem) {
            $(membershipItem).removeClass('omit').removeClass('selected');
          });
        },
        showPlanTable: function(accountType){
          this.getMemberships(accountType);
          $('.plans-section').show();
        },
        hidePlanTable: function(){
          $('.plans-section').hide();
        },
        clickPlanTable: function( event ){
          if ($(event.target).parents('.membership-item').hasClass('selected')) {
            this.removeOmitAllPlanCards();
            this.hidePlanTable();
            return;
          }
          let $membershipCard = $(event.target).parents('.membership-item');
          this.omitAllPlanCards();
          $membershipCard.removeClass('omit').addClass('selected');
          this.showPlanTable($membershipCard.attr('data-account-type'));
        },
        clickOverlay: function( event ){
          let $membershipCard = $(event.target).parents('.membership-item');
          this.omitAllPlanCards();
          $membershipCard.removeClass('omit').addClass('selected');
          this.getMemberships($membershipCard.attr('data-account-type'));
        },
        getMemberships: function( accountType ){
          axios.get(membershipsUrl, {
            params : {
              type_membership: accountType
            }
          })
          .then((response) => {
            this.memberships = response.data.memberships
            var keys = Object.keys(this.memberships[0].features);
            var arrayFeatures = []
            for(var i=0; i<keys.length; i++){
              var key = keys[i];
              arrayFeatures.push(
                {
                  key: key,
                  human_name: null,
                  select: null,
                  basic: null,
                  order: null,
                  type: null,
                  role: null
                }
              )
              for (var j = 0; j < $lang.features.length; j++) {
                if ( arrayFeatures[i].key == $lang.features[j].key ) {
                  arrayFeatures[i].human_name = $lang.features[j].value
                }
              }
              for (var j = 0; j < this.membershipsConfig.length; j++) {
                if ( arrayFeatures[i].key == this.membershipsConfig[j].key ) {
                  arrayFeatures[i].order = this.membershipsConfig[j].order
                  arrayFeatures[i].type = this.membershipsConfig[j].type
                  arrayFeatures[i].role = accountType
                }
              }
              for (var k = 0; k < this.memberships.length; k++) {

                  if ( this.memberships[k].name == "Basic" ) {
                      arrayFeatures[i].basic = this.memberships[k].features[arrayFeatures[i].key]
                  }
                  if ( this.memberships[k].name == "Select" ) {
                      arrayFeatures[i].select = this.memberships[k].features[arrayFeatures[i].key]
                  }
                  if ( this.memberships[k].name == "Premium" ) {
                      arrayFeatures[i].premium = this.memberships[k].features[arrayFeatures[i].key]
                  }
              }
            }
            this.features = arrayFeatures.sort(function(a, b){return a.order - b.order})
          });
        }
    }
});
