<template>
  <div>
    <div class="pageloader" v-if="loading">
      <span class="title">Cargando Propiedad</span>
    </div>
    <div class="tenant-contracts" v-if="!loading">
      <div class="columns" style="padding-bottom: 20px; border-bottom: 1px solid #ccc;">
        <div class="column is-8">
          <div>
            <div class="column is-12 line-down">
              <span>{{ newProperty ? 'Nueva Propiedad' : 'Propiedad Id: ' + idProperty}}</span>
            </div>
          </div>
          <br>
          <router-link to="/holdings">
            <span>&lt; Volver</span>
          </router-link>
        </div>
        <div class="column is-4">
          <div style="padding: 10px 0px;">Fecha de Publicación:</div>
          <a class="button is-outlined is-primary">Pausar</a> &nbsp;
          <a class="button is-outlined is-primary">Eliminar</a>
        </div>
      </div>

      <div class="columns">
        <div class="column is-8">
          <div class="columns is-inline" style="padding-bottom: 20px;">
            <div class="column">
              <div class="field">
                <label class="label">Precio de arriendo:</label>
                <div class="control">
                  <div class="control has-icons-left has-icons-right">
                    <input class="input" type="number" required v-model="info.rent">
                    <span class="icon is-small is-left">$</span>
                    <span class="icon is-small is-right">CLP</span>
                  </div>
                </div>
              </div>
            </div>

            <div class="column">
              <div class="field">
                <label class="label">Nombre de tu proyecto:</label>
                <div class="control">
                  <input class="input" type="text" required v-model="info.name">
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
                        :selected="item.id==info.property_type_id"
                      >{{item.text}}</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>

            <div class="column">
              <div class="field">
                <div class="label">
                  <span>Condición de la Propiedad:</span>
                </div>
                <div class="control">
                  <label class="radio">
                    <input
                      type="radio"
                      name="condition"
                      value="1"
                      :checked="info.condition"
                      v-model="info.condition"
                    >
                    Nueva
                  </label>
                  <label class="radio">
                    <input
                      type="radio"
                      name="condition"
                      value="0"
                      :checked="!info.condition"
                      v-model="info.condition"
                    >
                    Usada
                  </label>
                </div>
              </div>
            </div>

            <div class="column">
              <div class="field">
                <div class="label">
                  <span>Amoblado:</span>
                </div>
                <div class="control">
                  <label class="radio">
                    <input
                      type="radio"
                      name="furnished"
                      value="1"
                      :checked="info.furnished"
                      v-model="info.furnished"
                    >
                    Si
                  </label>
                  <label class="radio">
                    <input
                      type="radio"
                      name="furnished"
                      value="0"
                      :checked="!info.furnished"
                      v-model="info.furnished"
                    >
                    No
                  </label>
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
        title="Servicios de la Unidad"
        map_zone="unit"
        @save_event="save_event([$event,'amenities',{id:info.id}])"
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
        title="Servicios del Condominio o Edificio"
        map_zone="common"
        @save_event="save_event([$event,'amenities',{id:info.id}])"
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
        title="Documentos de Soporte"
        map_zone="documents"
        @save_event="save_event($event)"
        style="border-top: 1px solid #000;"
      >
        <template slot-scope="data">
          <holding-documents :editing="data.editing" v-bind:filters="filters" v-bind:info="info"></holding-documents>
        </template>
      </panel-up-down>
    </div>
  </div>
</template>

<script>
import datasheet from "../../../../js/profiles/datasheet";
import PanelUpDown from "../../PanelUpDown";
import SquareList from "../../SquareList";
import MyformAddress from "../common/MyformAddress";
import HoldingDetails from "../common/HoldingDetails";
import HoldingConditions from "../common/HoldingConditions";
import HoldingTenant from "../common/HoldingTenant";
import HoldingDocuments from "../common/HoldingDocuments";
import Holding from "../../Holding";
import axios from "axios";

export default {
  extends: datasheet,

  components: {
    Holding,
    SquareList,
    MyformAddress,
    PanelUpDown,
    HoldingDetails,
    HoldingConditions,
    HoldingTenant,
    HoldingDocuments
  },
  name: "OwnerHolding",
  props: {
    prop: Object
  },
  computed: {
    newProperty() {
      return !this.idProperty || this.idProperty === 0;
    },
    idProperty() {
      return this.$route.params.idProperty;
    },
    filters() {
        return this.$parent.filters;
    }
  },
  watch: {
    idProperty(newId, oldId) {
      if (newId != null && newId !== oldId) {
        this.getData();
      }
    }
  },
  data() {
    return {
      info: null,
      loading: true,
      saveUrl: "owner/save-prop",
      mapping: {
        properties: [
          "id",
          "rent",
          "name",
          "property_type_id",
          "condition",
          "furnished",
          "description",
          "address",
          "address_details",
          "city_id"
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
          "bedrooms"
        ],

        conditions: [],

        tenant: [
          "id",
          "properties_for",
          "pet_preference",
          "smoking_allowed",
          "nationals_with_rut",
          "foreigners_with_rut",
          "foreigners_with_passport"
        ]
      }
    };
  },
  methods: {
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
    }
  },
  mounted() {
    this.getData();
  }
};
</script>