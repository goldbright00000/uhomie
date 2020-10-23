<template>
<div>
    <div class="pageloader" v-if="loading">
      <span class="title">Cargando Propiedad</span>
    </div>

    <div class="collateral-project" v-if="!loading">        
        <div class="columns" style="padding-bottom: 20px; border-bottom: 1px solid #ccc;">
            <div class="column is-8">
                <label class="label">Proyecto ID {{ info.id }}</label>
                <div class="short-bar"></div>
                <br>
                <router-link to="/holdings">&lt; Volver</router-link>
            </div>
            <div class="column is-4">                
                <div class="control">                    
                    <div style="padding: 10px 0px;">Fecha de Publicación: {{info.publicdate}}</div>
                        <router-link to=""><a class="button is-outlined is-primary">Eliminar</a></router-link>
                        <router-link to=""><a class="button is-outlined is-primary">Pausar</a></router-link>
                    </div>
                </div>
            </div>
        </div>
        <div class="columns">
            <div class="column is-12">
                <div class="columns">
                    <div class="column is-4">
                        <span>Precio de venta</span>
                    </div>
                    <div class="column is-4">
                        <div class="control has-icons-left has-icons-right">                            
                            <input class="input" type="number" required v-model="info.rent">
                            <span class="icon is-small is-left">
                                $
                            </span>
                            <span class="icon is-small is-right">
                                UF
                            </span>
                        </div>
                    </div>                    
                    <div class="column is-3">
                        <div class="is-pulled-right" style="padding: 10px">
                            <img class="save_img":src="imagesDir+'/icono_guardar.png'"
                            @click="save_event(['projects',''])">
                        </div>
                    </div>
                </div>
            </div>
        </div>        
        <div class="columns is-mobile">
            <div class="column is-12">
                <div class="columns is-mobile">
                    <div class="column is-4">
                        <span>Nombre de tu proyecto</span>
                    </div>
                    <div class="column is-4">
                        <div class="control has-icons-left has-icons-right">
                            <input class="input" type="text" placeholder="Nombre" v-model="info.name">
                        </div>
                    </div>

                    <div class="column is-4">
                        <div class="control has-icons-left has-icons-right">
                            <h1>Carga tus fotos</h1>
                            <label style="font-size:12px">(Mínimo 1024 x 540 px en JPG/PNG)</label>

                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="columns is-mobile">
            <div class="column is-12">
                <div class="columns is-mobile">
                    <div class="column is-4">
                        <span>Tipo de proyecto</span>
                    </div>
                    <div class="column is-4">
                        <div class="control has-icons-left has-icons-right">
                            <select v-model="info.property_type_id">
                                <option v-for="item in filters.property_type.options" :key="item.id"
                                :selected="item.id==info.property_type_id">{{item.text}}</option>                                
                            </select>
                        </div>
                    </div>                    
                </div>
            </div>
        </div>
        <div class="columns is-mobile">
            <div class="column is-12">
                <div class="columns is-mobile">
                    <div class="column is-3">
                        <span>Estado del proyecto</span>
                    </div>
                    <div class="column is-5">
                        <div class="control has-icons-left has-icons-right">
                            <label class="radio">
                                <input type="radio" name="condition" value="1"
                                :checked="info.condition" v-model="info.condition" required>
                                Terminado
                            </label>                            
                            <label class="radio">
                                <input type="radio" name="condition" value="0"
                                :checked="info.condition" v-model="info.condition" required>
                                En obra
                            </label>
                            <label class="radio">
                                <input type="radio" name="condition" value="2"
                                :checked="info.condition" v-model="info.condition" required>
                                Otro
                            </label>                    
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="columns is-mobile">
            <div class="column is-12">
                <div class="columns is-mobile">
                    <div class="column is-8">
                        <span>Descripción de tu proyecto</span>
                        <textarea rows="6" style="width: 100%; padding: 6px;" v-model="info.description"></textarea>
                    </div>                    
                </div>
            </div>
        </div>
        <div class="columns is-mobile">
            <div class="column is-12">
                <div class="columns is-mobile">
                    <div class="column is-3">
                        <span>Ciudad</span>
                    </div>
                    <div class="column is-5">                        
                            <select v-model="info.city_id">
                                <option v-for="item in filters.cities.options" :key="item.id" :selected="item.id==info.city_id">{{item.text}}</option>
                            </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="columns is-mobile">
            <div class="column is-12">
                <div class="columns is-mobile">
                    <div class="column is-3">
                        <span>Dirección</span>
                    </div>
                    <div class="column is-5">
                        <div class="control">
                            <input class="input" type="text" placeholder="Dirección" v-model="info.address">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="columns">
            <div class="column is-12">                
                <div class="column is-8">                    
                    <panel-up-down title="Detalles" map_zone="details" @save_event="save_event($event)" style="border-top: 1px solid #000;">
                        <template slot-scope="data">
                            <holding-details :editing="data.editing" v-bind:filters="filters" v-bind:info="info"></holding-details>
                        </template>
                    </panel-up-down>
                </div>                
            </div>
        </div>
        <div class="columns">
            <div class="column is-12">                
                <div class="column is-8">                    
                    <panel-up-down title="Condiciones" map_zone="conditions" @save_event="save_event($event)" style="border-top: 1px solid #000;">
                        <template slot-scope="data">
                            <holding-conditions :editing="data.editing" v-bind:filters="filters" v-bind:info="info"></holding-conditions>
                        </template>
                    </panel-up-down>
                </div>                
            </div>
        </div>
        <!-- <div class="columns">
            <div class="column is-12">                
                <div class="column is-8">                    
                    <panel-up-down title="Servicios de la Unidad" map_zone="unit" @save_event="save_event([$event,'amenities', {id:info.id}])"
                        style="border-top: 1px solid #000;">
                        <template slot-scope="data">
                            <square-list :editing="data.editing" @change="changeAmenities($event)":items="filters.property_amenities" :values="info.amenities">
                        
                            </square-list>
                        </template>
                    </panel-up-down>
                </div>                
            </div>
        </div> -->

        

      <!-- 

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
      </panel-up-down> -->


        



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
  name: "CollateralProject",
  props: {
    prop: Object
  },
  computed: {
    newProject() {
      return !this.idProject || this.idProject === 0;
    },
    idProject() {
      return this.$route.params.idProject;
    },
    filters() {
        return this.$parent.filters;
    }
  },
  watch: {
    idProject(newId, oldId) {
      if (newId != null && newId !== oldId) {
        this.getData();
      }
    }
  },
  data() {
    return {
      info: null,
      loading: true,
      saveUrl: "project/save-prop",
      mapping: {
        projects: [
          "name",
          "rent",
          "description",
          "addres",
          "city",

        ],

        // details: [
        //   "id",
        //   "public_parking",
        //   "private_parking",
        //   "terrace",
        //   "garden",
        //   "pool",
        //   "meters",
        //   "bathrooms",
        //   "bedrooms"
        // ],

        // conditions: [],

        // tenant: [
        //   "id",
        //   "properties_for",
        //   "pet_preference",
        //   "smoking_allowed",
        //   "nationals_with_rut",
        //   "foreigners_with_rut",
        //   "foreigners_with_passport"
        // ]
      }
    };
  },
  methods: {
    changeAmenities: function(arr) {
      this.info.amenities = arr;
    },
    getData() {
      if (this.idProject == null) {
        return;
      }
      this.loading = true;
      const secret = sessionStorage.getItem("secret");
      const id = this.idProject;
      axios.defaults.headers.common = {
        Authorization: "Bearer " + secret
      };
      axios
        .get(`/projects/${id}`)
        .then(resp => {
          this.info = resp.data;
          this.loading = false;
        })
        .catch(() => {
            toastr['error']('Ha Ocurrido un error obteniendo el projecto');
        });
    }
  },
  mounted() {
    this.getData();
  }
};
</script>