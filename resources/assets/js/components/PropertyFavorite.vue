<template>
  <div class="property-card" style="height: 280px;">
    <div class="property-preview-image">
      <a :href="['/explorar/'+id+'/'+slug]">
        <img class="main-image" :src="imagePath.charAt(0) == '/'? imagePath : '/'+imagePath ">
      </a>
      <h1 :class="['title', 'title-'+membership]">{{ name }}</h1>
      <div class="scoring">
        <div class="points-wrapper">
          <img :src="imagesDir+'/explore/ico_logo_'+(membership.name ? membership.name.toLowerCase() : 'basic')+'.png'">
          <span :class="['points', 'points-'+membership.name]">{{ scoring }}</span>
        </div>
        <div class="heart-wrapper">
          <img
            :src="imagesDir+'/explore/ico_corazon_'+(favorite ? 'full' : 'empty')+'_'+(membership.name ? membership.name.toLowerCase() : 'basic')+'.jpg'"
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
          <span>x mes</span>
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
          <span
            :class="['demand-'+(demand > 0 ? 'high' : 'low')]"
          >Demanda {{ demand > 0 ? 'alta' : 'baja' }}</span>
          <img :src="imagesDir+'/explore/ico_demanda_'+(demand > 0 ? 'high' : 'low')+'.png'">
        </div>
      </div>
    </div>
    <div class="action">
      <ApplyPropertyButton
        :class="'button is-outlined is-primary ' + (applied ? 'is-active' : '') "
        :property_id="id"
      >
        <slot>{{ applied ? '¡Postulado!' : 'Postular' }}</slot>
      </ApplyPropertyButton>
    </div>
  </div>
</template>

<script>
import ApplyPropertyButton from "./pages/ApplyPropertyButton.vue";
export default {
  name: "PropertyFavorite",
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
    demand: Boolean,
    scoring: Number,
    verified: Boolean,
    favorite: Boolean,
    price: Number,
    bathNumber: Number,
    roomNumber: Number,
    parkingNumber: Number,
    applied: Boolean,
    property_id: Number,
  },
  updated: function() {
    console.log(window);

    console.log(this.$props);
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
    this.id = parseInt(this.id);
    this.demand = parseInt(this.demand);
    this.scoring = parseInt(this.scoring);
    this.verified = parseInt(this.verified);
    this.price = parseInt(this.price);
    this.bathNumber = parseInt(this.bathNumber);
    this.roomNumber = parseInt(this.roomNumber);
    this.parkingNumber = parseInt(this.parkingNumber);
    this.property_id = parseInt(this.property_id);

  },
};
</script>
