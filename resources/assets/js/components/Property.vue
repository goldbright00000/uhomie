<template>
  <div class="property-card">
    <div class="property-preview-image">
      <span 
        v-if="type_user"
        :class="['type-user', membership, { 'corredor': type_user == 5}]"
        v-text="type_user == 1 ? 'Dueño directo' : 'Corredor'"
        >
      </span>
      <a :href="['/explorar/'+id+'/'+slug]">
        <img class="main-image" :src="imagePath">
      </a>
      <h1 :class="['title', 'title-'+membership]">{{ name }}</h1>      
      <div class="scoring">
        <div class="points-wrapper">
          <img :src="imagesDir+'/explore/ico_logo_'+(membership != '' ? membership : 'basic')+'.png'">
          <span :class="['points', 'points-'+membership]">{{ scoring }}</span>
        </div>
        <div class="heart-wrapper">
          <img
            :src="imagesDir+'/explore/ico_corazon_'+(favorite ? 'full' : 'empty')+'_'+(membership != '' ? membership : 'basic')+'.jpg'"
          >
        </div>
      </div>
      <img v-if="verified" class="verified" :src="imagesDir+'/explore/ico_property_verified.jpg'">
    </div>
    <div class="property-info">
      <div class="property-description">
        <p>{{ description|truncate(70) }}</p>
        <div class="price">
          <img :src="imagesDir+'/explore/dolar.png'">
          {{ price|money }}
          <span>{{ type_stay == 'LONG_STAY' ? 'x mes' : 'x día'}}</span>
        </div>
      </div>
      <div class="property-features">
        <ul>
          <li>
            <img :src="imagesDir+'/explore/ico_habitaciones.png'">
            {{ roomNumber }}
          </li>
          <li>
            <img :src="imagesDir+'/explore/ico_bath.png'">
            {{ bathNumber }}
          </li>
          <li>
            <img :src="imagesDir+'/explore/ico_cochera.png'">
            {{ parkingNumber }}
          </li>
        </ul>
        <div class="demand">
          <!--Demanda Baja-->
            <span v-if="demand == 0" class="demand-low">Demanda baja</span>
            <img  v-if="demand == 0" :src="imagesDir+'/explore/ico_demanda_low.png'">
          <!--Demanda Media-->
            <span v-if="demand == 1" class="demand-medium">Demanda media</span>
            <img v-if="demand == 1" :src="imagesDir+'/explore/ico_demanda_medium.png'">
          <!--Demanda Alta-->
            <span v-if="demand == 2" class="demand-high">Demanda alta</span>
            <img v-if="demand == 2" :src="imagesDir+'/explore/ico_demanda_high.png'">
        </div>
      </div>
    </div>
    <div class="action">
      <ApplyPropertyButton
        v-if="available"
        :class="'button is-outlined is-'+(membership != '' ? membership : 'basic')+' ' + (applied ? 'is-active' : '') "
        :property_id="id"
        :type_stay="type_stay"
        @evento-abrir-modal-select-days="gatillar_evento_modal_select_days"
      >
        <slot>{{ applied ? '¡Postulado!' : ( type_stay == 'SHORT_STAY' ? 'Postular' : 'Arrendar') }}</slot>
      </ApplyPropertyButton>
      <a v-else :href="['/explorar/'+id+'/'+slug]" :class="'button is-outlined is-warning'">Arrendado</a>
    </div>
  </div>
</template>

<script>

import ApplyPropertyButton from "./pages/ApplyPropertyButton.vue";

export default {
  name: "Property",
  components: {
    ApplyPropertyButton
  },
  computed: {
    imagesDir: function() {
      return window.globals.asset("images");
    }
  },
  props: {
    id: Number,
    imagePath: String,
    name: String,
    slug: String,
    description: String,
    membership: String,
    demand: Number,
    scoring: Number,
    verified: Boolean,
    favorite: Boolean,
    price: Number,
    bathNumber: Number,
    roomNumber: Number,
    parkingNumber: Number,
    applied: Boolean,
    property_id: Number,
    available: Boolean,
    type_stay: String,
    type_user: String
  },
  updated: function() {
    console.log(window);

    console.log(this.$props);
  },
  methods: {
    gatillar_evento_modal_select_days: function(property_id){
      console.log('se quiere gatillar el evento desde el boton postular en propiedad '+property_id);
      this.$emit('evento_abrir_modal', property_id);
    },
  },
  filters: {
    truncate: function(value, length) {
      return value.length > length
        ? value.substr(0, length - 1) + "..."
        : value;
    },
    money: function(value) {
      return window.globals.filters.moneyFormat(value, 0);
    }
  },
  mounted: function() {
    /*
    this.id = Number(this.id);
    this.demand = Number(this.demand);
    this.scoring = Number(this.scoring);
    this.verified = Number(this.verified);
    this.price = Number(this.price);
    this.bathNumber = Number(this.bathNumber);
    this.roomNumber = Number(this.roomNumber);
    this.parkingNumber = Number(this.parkingNumber);
    this.property_id = Number(this.property_id);
    */
  },
};
</script>

<style scoped>
  .type-user {
    position: absolute;
    top: 0;
    left: 0;
    background-color: #ffd900dd;
    padding: 0.1rem 0.5rem 0.1rem 1rem;
    font-size: 0.5rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 1px;
  }
  .type-user.corredor {
    background-color: rgba(0,0,0,0.5);
    color: white;
  }
</style>