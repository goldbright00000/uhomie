<template>
  <div class="columns service">
    <div class="column is-3 picture">
      <a :href="'/servicios/'+id">
        <img :src="imagePath">
      </a>
    </div>
    <div class="column is-9 detail">
      <div class="data">
        <star-rating v-model="starRating.value" v-bind="starRating.options"></star-rating>
        <h1 :class="['name', 'name-'+membership]">{{ name }}</h1>
        <h2 class="profession">{{ serviceName }}</h2>
        <p class="description">{{ serviceDescription }}</p> 
      </div>
      <div class="contacts">
        <a :href="'tel:'+phone ? phone : cell_phone">
          <img src="/images/services/ico_tel.jpg">
        </a>
        <a :href="'mailto:'+email">
          <img src="/images/services/ico_mensaje.jpg">
        </a>
      </div>
      <div>
        <a href="#" :class="['button', 'is-outlined', 'is-'+membership]">Contactar</a>
      </div>
    </div>
  </div>
</template>

<script>
import StarRating from 'vue-star-rating';

export default {
  name: 'Service',
  components: {
    StarRating
  },
  props: {
    id: Number,
    imagePath: String,
    rating: Number,
    name: String,
    profession: String,
    serviceName: String,
    description: String,
    membership: String,
    serviceDescription: String,
    phone: String,
    email: String
  },
  data () {
    return {
      starRating: {
        value: this.rating,
        options: {
          starSize: 15,
          showRating: false,
          borderWidth: 3,
          borderColor: 'hsl(51, 100%, 50%)',
          inactiveColor: '#fff',
          activeColor: 'hsl(51, 100%, 50%)'
        }
      }
    };
  },
  filters: {
    truncate: function(value, length ) {
      return (value.length > length) ? value.substr(0, length-1) + "..." : value;
    }
  }
}
</script>
