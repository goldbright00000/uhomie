<template>
  <div>
    <div class="pageloader" v-if="loading">
      <span class="title">Cargando Propiedad</span>
    </div>
    <div class="tenant-contracts" v-if="!loading">
      <div class="columns" style="padding-bottom: 20px; border-bottom: 1px solid #ccc;">
        <div class="column is-4">
          <div>
            <div class="column is-12 line-down">
              <span>{{ newProperty ? 'Nueva Propiedad' : 'Propiedad Id: ' + idProperty}}</span>
            </div>
          </div>
        </div>
        <div class="column is-8">
          <div class="has-text-right">
            <router-link to="/holdings" class="button is-outlined is-primary">
                <span class="icon">
                    &lt;
                </span>
                <span>Volver</span>
            </router-link>
          </div>
          <div style="padding: 10px 0px;" class="has-text-centered is-size-7">
            <div class="columns">
              <div class="column is-4 is-offset-8">
                <p>Fecha de Publicación</p>
                <p>{{moment(info.created_at).locale('es').format('LLL')}}</p>
              </div>
            </div>
          </div>
          <div class="has-text-right">
              <div class="columns">
                <div class="column">
                  <a v-if="info.status != 2" @click="leased" class="button is-outlined is-primary is-fullwidth">Arrendada</a>
                  <a v-if="info.status == 2" @click="leased" class="button is-outlined is-primary is-fullwidth">Re-arrendar</a>
                </div>
                <div class="column">
                  <a v-if="info.status != 1 && info.status != 2" @click="paused" class="button is-outlined is-primary is-fullwidth">Pausar</a>
                  <a v-if="info.status != 0 && info.status != 2" @click="publish" class="button is-outlined is-primary is-fullwidth">Publicar</a>
                </div>
                <div class="column"><a @click="deleted" class="button is-outlined is-primary is-fullwidth">Eliminar</a></div>
              </div>
          </div>
        </div>
      </div>

      <div class="columns">
        <div class="column is-8">
          <div class="columns is-inline" style="padding-bottom: 20px;">
            <div class="column">
              <div class="field">
                <div class="label">
                  <span>Disponible para arrendar:</span>
                </div>
                <div class="control">
                  <label class="radio">
                    <input type="radio" name="available" value="1" :checked="info.available" v-model="info.available">
                    Si
                  </label>
                  <label class="radio">
                    <input type="radio" name="available" value="0" :checked="!info.available" v-model="info.available">
                    No
                  </label>
                </div>
              </div>
            </div>
            <div class="column" v-if="info.is_project == 1">
              <div class="field">
                <label class="label">Precio de compra desde:</label>
                <div class="control">
                  <div class="control has-icons-right">
                    <vue-numeric class="input" separator="." v-model="info.rent"></vue-numeric>
                    <span class="icon is-small is-right">UF</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="column" v-if="info.is_project == 1">
              <div class="field">
                <label class="label">Precio de compra hasta:</label>
                <div class="control">
                  <div class="control has-icons-right">
                    <vue-numeric class="input" separator="." v-model="info.rent_up"></vue-numeric>
                    <span class="icon is-small is-right">UF</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="column" v-if="typeProperty == 0 && info.is_project == 0">
              <div class="field">
                <label class="label">{{info.type_stay == 'LONG_STAY' ? 'Precio de arriendo:' : 'Precio base de arriendo por noche:'}}</label>
                <div class="control">
                  <div class="control has-icons-left has-icons-right">
                    <vue-numeric class="input" separator="." v-model="info.rent"></vue-numeric>
                    <span class="icon is-small is-left">$</span>
                    <span class="icon is-small is-right">CLP</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="column" v-if=" info.type_stay == 'SHORT_STAY' ">
              <div class="field">
                <label class="label">Tarifa por Limpieza:</label>
                <div class="control">
                  <div class="control has-icons-left has-icons-right">
                    <vue-numeric class="input" separator="." v-model="info.cleaning_rate"></vue-numeric>
                    <span class="icon is-small is-left">$</span>
                    <span class="icon is-small is-right">CLP</span>
                  </div>
                </div>
              </div>
            </div>
            <div v-if="typeProperty == 1 && info.is_project == 0">
              <div class="column">
                <div class="field">
                  <label class="label">{{info.type_stay == 'LONG_STAY' ? 'Precio de arriendo:' : 'Precio base de arriendo por noche:'}}</label>
                  <div class="control">
                    <div class="control has-icons-left has-icons-right">
                      <vue-numeric class="input" separator="." v-model="info.rent"></vue-numeric>
                      <span class="icon is-small is-left">$</span>
                      <span class="icon is-small is-right">CLP</span>
                    </div>
                  </div>
                </div>
              </div>

              <div class="column">
                <div class="field">
                  <div class="label">
                    <span>Plazo minimo de arriendo (años):</span>
                  </div>
                  <div class="control">
                    <input class="input" type="number" min="1" max="3" required v-model="info.term_year" style="max-width: 100px;">
                  </div>
                </div>
              </div>

              <div class="column">
                <div class="field">
                  <label class="label">Precio de Año 1:</label>
                  <div class="control">
                    <div class="control has-icons-left has-icons-right">
                      <vue-numeric class="input" separator="." v-model="info.rent_year_1"></vue-numeric>
                      <span class="icon is-small is-left">$</span>
                      <span class="icon is-small is-right">UF</span>
                    </div>
                  </div>
                </div>
              </div>

              <div class="column">
                <div class="field">
                  <label class="label">Precio de Año 2:</label>
                  <div class="control">
                    <div class="control has-icons-left has-icons-right">
                      <vue-numeric class="input" separator="." v-model="info.rent_year_2"></vue-numeric>
                      <span class="icon is-small is-left">$</span>
                      <span class="icon is-small is-right">UF</span>
                    </div>
                  </div>
                </div>
              </div>

              <div class="column">
                <div class="field">
                  <label class="label">Precio de Año 3:</label>
                  <div class="control">
                    <div class="control has-icons-left has-icons-right">
                      <vue-numeric class="input" separator="." v-model="info.rent_year_3"></vue-numeric>
                      <span class="icon is-small is-left">$</span>
                      <span class="icon is-small is-right">UF</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="column">
              <div class="field">
                <label class="label">Nombre de tu Propiedad:</label>
                <div class="control">
                  <input class="input" type="text" required v-model="info.name" @keyup="setName">
                </div>
              </div>
            </div>

            <div class="column">
              <div class="field">
                <label class="label">Tipo de Propiedad:</label>
                <div class="control">
                  <div class="select is-info.personal" v-if="filters">
                    <select v-model="info.property_type_id">
                      <option
                        v-for="item in filters.property_type.options"
                        :value="item.id"
                        :selected="item.id == toInt(info.property_type_id)" :key="item.id"
                      >{{item.text}}</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>

            <div class="column" v-if="info.is_project == 1">
              <div class="field">
                <div class="label">
                  <span>Condición de la Propiedad:</span>
                </div>
                <div class="control">
                  <label class="radio">                                    
                      <input type="radio" :checked="info.condition == 1" v-model="info.condition" value="1" required>
                      Terminado
                  </label>
                  <label class="radio">
                      <input type="radio" :checked="info.condition == 0" v-model="info.condition" value="0" required>
                      En obra
                  </label>
                  <label class="radio">									
                      <input type="radio" :checked="info.condition == 2" v-model="info.condition" value="2" required>
                      Otro
                  </label>
                </div>
              </div>
            </div>

            <div class="column" v-if="info.is_project == 0">
              <div class="field">
                <div class="label">
                  <span>Condición de la Propiedad:</span>
                </div>
                <div class="control">

                  <div class="control">
                    <label class="radio">                                    
                        <input type="radio" :checked="info.condition == 1" v-model="info.condition" value="1" required>
                        Nueva
                    </label>
                    <label class="radio">
                        <input type="radio" :checked="info.condition == 0" v-model="info.condition" value="0" required>
                        Usada
                    </label>
                  </div>
                </div>
              </div>
            </div>

            <div class="column">
              <div class="label">
                <span>Descripción de tu Propiedad:</span>
              </div>
              <div class="control">
                <textarea rows="6" style="width: 100%;padding: 6px;" v-model="info.description"></textarea>
              </div>
            </div>

            <div class="column" v-if="info.is_project == 0">
              <div class="field">
                <div class="label">
                  <span>Amoblado:</span>
                </div>
                <div class="control">
                  <label class="radio">
                    <input type="radio" name="furnished" value="1" :checked="info.furnished" v-model="info.furnished">
                    Si
                  </label>
                  <label class="radio">
                    <input type="radio" name="furnished" value="0" :checked="!info.furnished" v-model="info.furnished">
                    No
                  </label>
                </div>
              </div>
            </div>

            <div class="column" v-if="info.furnished == '1'">
              <div class="label">
                <span>Descripción de Amoblado:</span>
              </div>
              <div class="control">
                <textarea rows="6" style="width: 100%;padding: 6px;" v-model="info.furnished_description"></textarea>
              </div>
            </div>

            <myform-address :editing="true" v-bind:filters="filters" v-bind:info="info"></myform-address>
          </div>
        </div>
        <div class="column is-4">
          <div class="is-pulled-right" style="padding: 10px">
            <img
              class="save_img"
              :src="imagesDir+'/icono_guardar.png'"
              @click="save_event(['properties',''])"
            >
          </div>
        </div>
        
      </div>

      <panel-up-down
        title="Fotos"
        map_zone="common"
        @save_event="save_photos_event([$event,'photos',{id:info.id}])"
        style="border-top: 1px solid #000;"
      >
        <template slot-scope="data">
          <!--<div class="columns">
            <div class="column is-12">
              <holding-photo :propertyid="info.id" @photoChange="addPhoto($event, 0)" :photo="cover" :spaces="filters.photos_spaces.options"></holding-photo>
            </div>
          </div>
          <div class="columns is-multiline">
            <div class="column is-4" v-for="(photo, index) in photos" :key="index">
              <holding-photo :propertyid="info.id" @photoChange="addPhoto($event, index)" :photo="photo" :spaces="filters.photos_spaces.options"></holding-photo>
            </div>
          </div>-->
          <property-photo :editing="data.editing" :id="info.id" :photos="info.photos" :spaces="filters.photos_spaces.options"></property-photo>
        </template>
      </panel-up-down>
      <panel-up-down
        v-if="info.property_type_id != '1'"
        title="Detalles"
        map_zone="details"
        @save_event="save_event($event)"
        style="border-top: 1px solid #000;"
      >
        <template slot-scope="data">
          <holding-details :editing="data.editing" v-bind:filters="filters" v-bind:info="info"></holding-details>
        </template>
      </panel-up-down>

      <panel-up-down
        v-if="info.is_project == 0"
        title="Condiciones"
        map_zone="conditions"
        @save_event="save_event($event)"
        style="border-top: 1px solid #000;"
      >
        <template slot-scope="data">
          <holding-conditions :editing="data.editing" v-bind:filters="filters" v-bind:info="info"></holding-conditions>
        </template>
      </panel-up-down>

      <panel-up-down
        v-if="info.type_stay == 'SHORT_STAY' && info.property_type_id != 6"
        title="Servicios Basicos"
        map_zone="unit"
        @save_event="save_data_prop([$event,'amenities',{id:info.id}])"
        style="border-top: 1px solid #000;"
      >
        <template slot-scope="data">
          <square-list
            :editing="data.editing"
            @change="changeAmenities($event)"
            :items="filters.basic_services"
            :values="info.amenities"
          ></square-list>
        </template>
      </panel-up-down>

      <panel-up-down
        v-if="info.type_stay == 'SHORT_STAY' && info.property_type_id != 6"
        title="Reglas de la Propiedad"
        map_zone="unit"
        @save_event="save_data_prop([$event,'amenities',{id:info.id}])"
        style="border-top: 1px solid #000;"
      >
        <template slot-scope="data">
          <square-list
            :editing="data.editing"
            @change="changeAmenities($event)"
            :items="filters.rules_amenities"
            :values="info.amenities"
          ></square-list>
        </template>
      </panel-up-down>

      <panel-up-down
        v-if="info.type_stay == 'SHORT_STAY' && info.property_type_id != 6"
        title="Detalles de la Propiedad"
        map_zone="unit"
        @save_event="save_data_prop([$event,'amenities',{id:info.id}])"
        style="border-top: 1px solid #000;"
      >
        <template slot-scope="data">
          <square-list
            :editing="data.editing"
            @change="changeAmenities($event)"
            :items="filters.details_amenities"
            :values="info.amenities"
          ></square-list>
        </template>
      </panel-up-down>

      <panel-up-down
        v-if="info.property_type_id != 6"
        title="Servicios de la Unidad"
        map_zone="unit"
        @save_event="save_data_prop([$event,'amenities',{id:info.id}])"
        style="border-top: 1px solid #000;"
      >
        <template slot-scope="data">
          <square-list
            :editing="data.editing"
            @change="changeAmenities($event)"
            :items="filters.property_amenities"
            :values="info.amenities"
          ></square-list>
        </template>
      </panel-up-down>
      <panel-up-down
        v-if="info.property_type_id != 6"
        title="Servicios del Condominio o Edificio"
        map_zone="common"
        @save_event="save_data_prop([$event,'amenities',{id:info.id}])"
        style="border-top: 1px solid #000;"
      >
        <template slot-scope="data">
          
          <square-list
            :editing="data.editing"
            @change="changeAmenities($event)"
            :items="filters.common_amenities"
            :values="info.amenities"
          ></square-list>
        </template>
      </panel-up-down>

      <panel-up-down
        v-if="info.property_type_id == 6"
        title="Posesiones de la Propiedad"
        map_zone="unit"
        @save_event="save_data_prop([$event,'amenities',{id:info.id}])"
        style="border-top: 1px solid #000;"
      >
        <template slot-scope="data">
          <square-list
            :editing="data.editing"
            @change="changeAmenities($event)"
            :items="filters.possessions"
            :values="info.amenities"
          ></square-list>
        </template>
      </panel-up-down>

      <panel-up-down
        v-if="info.property_type_id != '1'"
        title="Perfil de arrendatario"
        map_zone="tenant"
        @save_event="save_event($event)"
        style="border-top: 1px solid #000;"
      >
        <template slot-scope="data">
          <holding-tenant :editing="data.editing" v-bind:filters="filters" v-bind:info="info"></holding-tenant>
        </template>
      </panel-up-down>

      <panel-up-down
        v-if="info.property_type_id != '1'"
        title="Documentos de Soporte"
        map_zone="documents"
        @save_event="save_event($event)"
        style="border-top: 1px solid #000;"
      >
        <template slot-scope="data">
          <holding-documents :editing="data.editing" v-bind:filters="filters" v-bind:info="info" @filedata="fileData"></holding-documents>
          
        </template>
      </panel-up-down>

      <panel-up-down v-if="info.type_stay == 'LONG_STAY'"
        title="Agenda"
        map_zone="schedule"
        @save_event="save_event($event)"
        style="border-top: 1px solid #000;"
      >
        <template slot-scope="data">
          <myform-schedule :editing="data.editing" v-bind:info="info"></myform-schedule>
        </template>
      </panel-up-down>
      
      <panel-up-down v-if="info.type_stay == 'SHORT_STAY'"
        title="Dias Ocupados"
        map_zone="schedule"
        @save_event="save_event($event)"
        style="border-top: 1px solid #000;"
      >
        <template slot-scope="data">
          <busy-days :editing="data.editing" v-bind:info="info"></busy-days>
        </template>
      </panel-up-down>

      <panel-up-down
        v-if="info.type_stay == 'LONG_STAY' && info.is_project == 0"
        title="Gestion de Propiedad"
        map_zone="management"
        @save_event="save_event($event)"
        style="border-top: 1px solid #000;"
      >
        <template slot-scope="data">
          <holding-management :editing="data.editing" v-bind:info="info"></holding-management>
        </template>
      </panel-up-down>

      <br>

    </div>
  </div>
</template>

<script>
import datasheet from "../../../../js/profiles/datasheet";
import PanelUpDown from "../../PanelUpDown";
import SquareList from "../../SquareList";
import MyformAddress from "../common/MyformAddress";
import HoldingPhoto from "../common/HoldingPhoto";
import PropertyPhoto from "../common/PropertyPhoto";
import HoldingDetails from "../common/HoldingDetails";
import HoldingConditions from "../common/HoldingConditions";
import HoldingTenant from "../common/HoldingTenant";
import HoldingDocuments from "../common/HoldingDocuments";
import HoldingManagement from "../common/HoldingManagement";
import MyformSchedule from "../common/MyformSchedule";
import BusyDays from "../common/BusyDays";
import Holding from "../../Holding";
import axios from "axios";
import VueNumeric from 'vue-numeric';

export default {
  extends: datasheet,

  components: {
    Holding,
    SquareList,
    BusyDays,
    MyformAddress,
    PanelUpDown,
    HoldingDetails,
    HoldingConditions,
    HoldingTenant,
    HoldingDocuments,
    HoldingManagement,
    MyformSchedule,
    VueNumeric,
    HoldingPhoto,
    PropertyPhoto
  },
  name: "OwnerHolding",
  props: {
    prop: Object
  },
  computed: {
    typeProperty(){
      if(this.info.property_type_id == 1 || this.info.property_type_id == 2 || this.info.property_type_id == 3 || this.info.property_type_id == 6 || this.info.property_type_id == 7 || this.info.property_type_id == 8){
        return 0;
      }
      if(this.info.property_type_id == 4 || this.info.property_type_id == 5){
        return 1;
      }
    },
    newProperty() {
      return !this.idProperty || this.idProperty === 0;
    },
    idProperty() {
      return this.$route.params.idProperty;
    },
    filters() {
        return this.$parent.filters;
    },
    info() {
      /*var info = this.$parent.info
      var w = this.$route.params.idProperty
      var nfo = info.properties
      for(var t in nfo){
          if (nfo[t].id==w) {
              return nfo[t]
          }
      }
      return ;*/

      return this.$parent.info.properties.filter(property => property.id == this.$route.params.idProperty)[0];
        
    },
    cover() {
      return this.info.photos.find((x) => {
        return x.cover == '1'  
      })
    },
    photos() {
      let photos = this.info.photos.filter((x) => { return x.cover == '0'}).map((x) => { if(x.space == null) x.space = { id: 0 }; return x })
      console.log(photos)
      if (!photos.length) {
        this.info.photos.push(
          { id: null, space: { id: 0 }, path: null, cover: 0 },
          { id: null, space: { id: 0 }, path: null, cover: 0 },
          { id: null, space: { id: 0 }, path: null, cover: 0 },
          { id: null, space: { id: 0 }, path: null, cover: 0 }
        )
        photos = this.info.photos.filter((x) => { return x.cover == '0'})
      }

      return photos
    }
  },
  watch: {
    idProperty(newId, oldId) {
      if (newId != null && newId !== oldId) {
        this.getData();
      }
    },
    'info.photos': function(newV, Old) {
    }
  },
  data() {
    return {
      //info: null,
      loading: false,
      saveUrl: "owner/save-prop",
      mapping: {
        properties: [
          "id",
          "available",
          "rent",
          "rent_up",
          "term_year",
          "rent_year_1",
          "rent_year_2",
          "rent_year_3",
          "name",
          "property_type_id",
          "condition",
          "furnished",
          "furnished_description",
          "description",
          "address",
          "address_details",
          "city_id",
          "latitude",
          "longitude",
          "cleaning_rate"
        ],

        details: [
          "id",
          "public_parking",
          "private_parking",
          "terrace",
          "garden",
          "pool",
          "meters",
          "bathrooms",
          "bedrooms",
          "cellar",
          "cellar_description",
          "level",
          "room_enablement",
          "civil_work",
          "arquitecture_project",
          "work_electric_water",
          "building_name",
          "rooms",
          "meeting_room",
          "single_beds",
          "double_beds",
          "exclusions",
          "lot_number"
        ],

        conditions: [
          "id",
          "common_expenses_limit",
          "warranty_months_quantity",
          "months_advance_quantity",
          "available_date",
          "tenanting_months_quantity",
          "collateral_require",
          "anexo_conditions",
          "special_sale", 
          "week_sale", 
          "month_sale", 
          "checkin_hour", 
          "minimum_nights",
          "tenanting_insurance",
          "warranty_ticket",
          "warranty_ticket_price",
          "penalty_fees",

        ],

        tenant: [
          "id",
          "properties_for",
          "pet_preference",
          "smoking_allowed",
          "nationals_with_rut",
          "foreigners_with_rut",
          "foreigners_with_passport",
          "allow_small_child",
          "allow_baby",
          "allow_parties",
          "use_stairs",
          "there_could_be_noise",
          "common_zones",
          "services_limited",
          "survellaince_camera",
          "weaponry",
          "dangerous_animals",
          "pets_friendly"
        ],

        schedule: [
          'id',
          'visit',
          'visit_from_date',
          'visit_to_date',
          'schedule_range',
          'schedule_dates',
        ],

        busy_days: [
          'schedule_dates'
        ],

        management: [
          'id',
          'manage'
        ],

        documents: [
          
        ]
      }
    };
  },
  methods: {
    toInt: function(n) {
      return parseInt(n)
    },
    addPhoto: function(e, i) {
      if(e.file) e.photo.file = e.file
      this.info.photos.splice(i + 1, 1, e.photo)
      let l = 0
      this.photos.forEach(e => {
        if (e.id !== null && e.file && e.file !== null) {
          console.log('tiene id')
          l = l + 1
        } else if (e.file && e.file !== null) {
          l = l + 1
          
          console.log('tiene file')
        }
      });  
      
      if(l == this.info.photos.length - 1) this.info.photos.push({ id: null, space: { id: 0 }, path: null, cover: 0 })
    },
    setName: function() {
      var value = this.info.name.split(' ')
      value = value.map(function(e) {
          return e.charAt(0).toUpperCase() + e.slice(1)
      })
      this.info.name = value.join(' ')
      this.info = this.info
    },
    changeAmenities: function(arr) {
      this.info.amenities = arr;
    },
    getData() {
      if (this.idProperty == null) {
        return;
      }
      this.loading = true;
      const secret = sessionStorage.getItem("secret");
      const id = this.idProperty;
      axios.defaults.headers.common = {
        Authorization: "Bearer " + secret
      };
      axios
        .get(`/properties/${id}`)
        .then(resp => {
          this.info = resp.data;
          this.loading = false;
        })
        .catch(() => {
            toastr['error']('Ha Ocurrido un error obteniendo la propiedad');
        });
    },
    publish() {
      if (this.idProperty == null) {
        return;
      }
      this.loading = true;
      const id = this.idProperty;
      axios
        .get(`owner/publish-property/${id}`)
        .then(resp => {
          this.info.status = '0';
          this.info.statusname = 'publicado'
          this.loading = false;
        })
        .catch(() => {
            toastr['error'](err);
        });
    },
    paused() {
      if (this.idProperty == null) {
        return;
      }
      this.loading = true;
      const id = this.idProperty;
      axios
        .get(`owner/pause-property/${id}`)
        .then(resp => {
          this.info.status = '1';
          this.info.statusname = 'en-pausa';
          this.loading = false;
          toastr['success']('Se ha cambiado el estatus exitosamente');
        })
        .catch(() => {
            toastr['error'](err);
        });
    },
    leased() {
      if (this.idProperty == null) {
        return;
      }
      this.loading = true;
      const id = this.idProperty;
      axios
        .get(`owner/leased-property/${id}`)
        .then(resp => {
          this.info.status = resp.data.info.status;
          this.info.available = resp.data.info.available;
          this.loading = false;
          toastr['success']('Se ha cambiado el estatus exitosamente');
        })
        .catch(() => {
            toastr['error'](err);
        });
    },
    deleted() {
      if (this.idProperty == null) {
        return;
      }
      this.loading = true;
      const id = this.idProperty;
      axios
        .get(`owner/delete-property/${id}`)
        .then(resp => {
          toastr.success('Se ha eliminado la propiedad');
          //this.$parent.info.properties.filter(property => property.id != resp.data.id);
          for (var i = 0; i < this.$parent.info.properties.length; i++) {
            if (this.$parent.info.properties[i].id == resp.data.id) {
              this.$parent.info.properties.splice(i, 1);
              break;
            }
          }
          this.$router.push('/holdings');
        })
        .catch((err) => {
            toastr['error'](err);
        });
    },
    fileData(value){
      console.log(value.file)
      for (var i = 0; i < this.info.files.length; i++) {
        if (this.info.files[i].name == value.file.name) {
          this.info.files.splice(i, 1);
          this.info.files.push(value.file);
          break;
        }
      }
    },
  },
  mounted() {
  }
};
</script>