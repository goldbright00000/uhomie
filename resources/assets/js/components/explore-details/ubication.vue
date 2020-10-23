<template>
	<section id="ubicacion" class="section">
    <div class="container location">
      <h3 class="subtitle is-size-5 has-text-weight-bold" style="margin-bottom: 0;">
        Ubicación
      </h3>
      <p v-text="property.data.address"></p>

      <map-detail v-bind:property_location="propertyLocation"></map-detail>
      
    </div>
  </section>
</template>

<script>
	import MapDetail from "../MapDetail.vue"

export default {
  props: ['property'],
  components: {
  	MapDetail
  },
  data: function () {
    return {
    	
    }
  },
  mounted() {

  },
  methods: {
  	
  },
  computed: {  	
    propertyLocation: function() {
      let vm = this;
      return vm.property.data.longitude && vm.property.data.latitude
        ? {
            lat: vm.property.data.latitude,
            lng: vm.property.data.longitude
          }
        : {};
    },
  }
}
</script>

<style scoped>
	h3 {
		margin-bottom: 2rem;
	}
	.location {
		border-bottom: 0;
		margin-bottom: 0;
		padding-bottom: 0;
	}
</style>