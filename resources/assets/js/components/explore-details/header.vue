<template>
	<div class="container-fluid">
    <div class="banner-container">
      <banner-gallery v-bind:images-dir="imagesDir" v-bind:slug="property.data.slug" v-bind:property_id="property.id()" v-bind:photos="propertyPhotos" v-bind:favorite="isFavouriteProperty" ref="banner_gallery">
      </banner-gallery>
    </div>
	</div>
</template>

<script>
	import Axios from 'axios';	
	import BannerGallery from '../pages/BannerGallery.vue';


const imagesDir = document.getElementById('images-dir').value;

export default {
  components: {  	
    BannerGallery
  },
  props: [ 'property', 'property-photos' ],
  data: function () {
    return {
      imagesDir: imagesDir      
    }
  },
  updated: function() {
    let vm = this;
    let favorite_button = vm.$refs["banner_gallery"].$el.querySelector(
      ".carousel-share .is-favorite"
    );

    if (typeof favorite_button != "undefined") {
      favorite_button.onclick = function() {
        vm.$emit('togglefavorite', this)
      };
    }
  },
  mounted() {
  	
  },
  methods: {

  },
  computed: {  	
    isFavouriteProperty: function() {
      let vm = this;

      return typeof vm.property.data.favourite != "undefined"
        ? vm.property.data.favourite
        : false;
    },
  }
}
</script>

<style lang="scss" scoped>

</style>
